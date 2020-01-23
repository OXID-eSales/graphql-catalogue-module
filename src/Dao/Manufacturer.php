<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Dao;

use Doctrine\DBAL\Query\QueryBuilder;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Catalogue\DataType\Manufacturer as ManufacturerDataType;
use OxidEsales\GraphQL\Catalogue\DataType\ManufacturerFilter;
use OxidEsales\GraphQL\Catalogue\Exception\ManufacturerNotFound;

class Manufacturer implements ManufacturerInterface
{

    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    private $queryBuilderFactory;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    private function prepareQueryBuilder(QueryBuilder $queryBuilder): void
    {
        $queryBuilder
            ->select([
                'm.oxid',
                'm.oxactive',
                'm.oxicon',
                'm.oxtitle',
                'm.oxshortdesc',
                's.oxseourl',
                'm.oxtimestamp'
            ])
            ->from(getViewName('oxmanufacturers'), 'm')
            ->leftJoin('m', 'oxseo', 's', 'm.oxid = s.oxobjectid');
    }

    /**
     * @throws \Exception if database dies
     * @throws ManufacturerNotFound if id not found in database
     */
    public function getManufacturer(string $id): ManufacturerDataType
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $this->prepareQueryBuilder($queryBuilder);
        $queryBuilder->where('OXID = :oxid')
                     ->setParameter('oxid', $id);
        $result = $queryBuilder->execute();
        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            throw new \Exception();
        }
        $row = $result->fetch();
        if (!$row) {
            throw ManufacturerNotFound::byId($id);
        }
        return ManufacturerDataType::fromDatabaseResult($row);
    }

    /**
     * @return ManufacturerDataType[]
     * @throws \Exception if the database dies
     */
    public function getManufacturers(ManufacturerFilter $filter): array
    {
        $manufacturers = [];

        $queryBuilder = $this->queryBuilderFactory->create();
        $this->prepareQueryBuilder($queryBuilder);

        $filters = array_filter($filter->getFilters());
        foreach ($filters as $field => $fieldFilter) {
            $fieldFilter->addToQuery($queryBuilder, $field);
        }

        $result = $queryBuilder->execute();

        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            throw new \Exception();
        }

        foreach ($result as $row) {
            $manufacturers[] = ManufacturerDataType::fromDatabaseResult($row);
        }
        return $manufacturers;
    }
}
