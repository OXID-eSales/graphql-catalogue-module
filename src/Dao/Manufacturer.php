<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Dao;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Catalogue\DataObject\Manufacturer as ManufacturerModel;
use OxidEsales\GraphQL\Catalogue\DataObject\ManufacturerFilter;

class Manufacturer implements ManufacturerInterface
{

    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    private $queryBuilderFactory;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function getManufacturer(string $id): ManufacturerModel
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select([
                        'm.oxid',
                        'm.oxactive',
                        'm.oxicon',
                        'm.oxtitle',
                        'm.oxshortdesc',
                        's.oxseourl',
                        'm.oxtimestamp'
                     ])
                     ->from('oxmanufacturers', 'm')
                     ->leftJoin('m', 'oxseo', 's', 'm.oxid = s.oxobjectid')
                     ->where('OXID = :oxid')
                     ->setParameter('oxid', $id);
        $result = $queryBuilder->execute();
        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            throw new \Exception();
        }
        $row = $result->fetch();
        if (!$row) {
            throw new \Exception();
        }
        return new ManufacturerModel(
            $row['oxid'],
            intval($row['oxactive']),
            $row['oxicon'],
            $row['oxtitle'],
            $row['oxshortdesc'],
            $row['oxseourl'],
            $row['oxtimestamp']
        );
    }

    /**
     * @return ManufacturerModel[]
     */
    public function getManufacturers(ManufacturerFilter $filter): array
    {
        $manufacturers = [];
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select([
                        'oxid',
                        'oxactive',
                        'oxicon',
                        'oxtitle',
                        'oxshortdesc',
                        'oxseourl',
                        'oxtimestamp'
                     ])
                     ->from('oxmanufacturers');

        $filters = array_filter($filter->getFilters());
        foreach ($filters as $field => $fieldFilter) {
            $fieldFilter->addToQuery($queryBuilder, $field);
        }

        $result = $queryBuilder->execute();

        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            return $manufacturers;
        }

        foreach ($result as $row) {
            $manufacturers[] = new ManufacturerModel(
                $row['OXID'],
                $row['OXACTIVE'],
                $row['OXICON'],
                $row['OXTITLE'],
                $row['OXSHORTDESC'],
                $row['OXSEOURL'],
                $row['OXTIMESTAMP']
            );
        }
        return $manufacturers;
    }
}
