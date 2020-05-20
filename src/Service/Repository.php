<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Service;

use InvalidArgumentException;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Base\DataType\FilterInterface;
use OxidEsales\GraphQL\Base\DataType\PaginationFilter;
use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\FilterList;
use OxidEsales\GraphQL\Catalogue\DataType\DataType;
use OxidEsales\GraphQL\Catalogue\Resolver\BaseResolver;
use PDO;

class Repository
{
    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    private $queryBuilderFactory;

    /** @var BaseResolver */
    private $resolver;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory,
        BaseResolver $resolver
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->resolver = $resolver;
    }

    /**
     * @template T
     * @param class-string<T> $type
     * @return T
     * @throws InvalidArgumentException if $type is not instance of DataType
     * @throws NotFound if BaseModel can not be loaded
     * @throws InvalidLogin on invalid permissions
     */
    public function getById(
        string $id,
        string $type,
        bool $disableSubShop = true
    ) {
        $model = $this->getModel($type::getModelClass(), $disableSubShop);

        if (!$model->load($id) || (method_exists($model, 'canView') && !$model->canView())) {
            throw new NotFound($id);
        }
        $type = new $type($model);
        if (!($type instanceof DataType)) {
            throw new InvalidArgumentException();
        }

        $this->resolver->resolveById($type);

        return $type;
    }

    /**
     * @template T
     * @param class-string<T> $type
     * @return T[]
     * @throws InvalidArgumentException if model in $type is not instance of BaseModel
     */
    public function getByFilter(
        FilterList $filter,
        string $type,
        ?PaginationFilter $pagination = null,
        bool $disableSubShop = true
    ): array {
        $types = [];
        $model = $this->getModel($type::getModelClass(), $disableSubShop);

        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('*')
                     ->from($model->getViewName())
                     ->orderBy($model->getViewName() . '.oxid');

        $this->resolver->resolveList($model, $queryBuilder);

        /** @var FilterInterface[] $filters */
        $filters = array_filter($filter->getFilters());
        foreach ($filters as $field => $fieldFilter) {
            $fieldFilter->addToQuery($queryBuilder, $field);
        }

        if ($pagination !== null) {
            $pagination->addPaginationToQuery($queryBuilder);
        }

        $queryBuilder->getConnection()->setFetchMode(PDO::FETCH_ASSOC);
        /** @var \Doctrine\DBAL\Statement $result */
        $result = $queryBuilder->execute();
        foreach ($result as $row) {
            $newModel = clone $model;
            $newModel->assign($row);
            $types[] = new $type($newModel);
        }

        return $types;
    }

    /**
     * @throws InvalidArgumentException if model in $type is not instance of BaseModel
     */
    private function getModel(string $modelClass, bool $disableSubShop): BaseModel
    {
        $model = oxNew($modelClass);

        if (!($model instanceof BaseModel)) {
            throw new InvalidArgumentException();
        }
        if (method_exists($model, 'setDisableShopCheck')) {
            $model->setDisableShopCheck($disableSubShop);
        }
        return $model;
    }
}
