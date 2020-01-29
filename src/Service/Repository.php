<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Service;

use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\FilterList;
use OxidEsales\GraphQL\Catalogue\DataType\DataType;

class Repository
{
    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    private $queryBuilderFactory;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    /**
     * @template T
     * @template M
     * @param class-string<T> $type
     * @param class-string<M> $model
     * @return T
     */
    public function getById(
        string $id,
        string $type,
        string $model
    ) {
        /** @var BaseModel */
        $model = oxNew($model);
        if (!$model->load($id)) {
            throw new NotFound($id);
        }
        return new $type($model);
    }

    /**
     * @template T
     * @template M
     * @param class-string<T> $type
     * @param class-string<M> $model
     * @return T[]
     */
    public function getByFilter(
        FilterList $filter,
        string $type,
        string $model
    ): array {
        $types = [];
        /** @var BaseModel */
        $model = oxNew($model);
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('*')
                     ->from($model->getViewName())
                     ->orderBy('oxid');

        if (
            $filter->getActive() !== null &&
            $filter->getActive()->equals() === true
        ) {
            $queryBuilder->andWhere($model->getSqlActiveSnippet());
        }

        $filters = array_filter($filter->getFilters());
        foreach ($filters as $field => $fieldFilter) {
            $fieldFilter->addToQuery($queryBuilder, $field);
        }

        $result = $queryBuilder->execute();

        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            return $types;
        }
        foreach ($result as $row) {
            $newModel = clone $model;
            $newModel->assign($row);
            $types[] = new $type($newModel);
        }
        return $types;
    }

    public function save(DataType $type): DataType
    {
        $model = $type->getModel();
        if (!$model->save()) {
            throw new \Exception();
        }
        // reload model
        $model->load($model->getId());
        $class = get_class($type);
        return new $class($model);
    }
}
