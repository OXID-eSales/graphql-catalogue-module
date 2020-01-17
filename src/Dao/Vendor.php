<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Dao;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Catalogue\DataObject\Vendor as VendorModel;
use OxidEsales\GraphQL\Catalogue\DataObject\VendorFilter;

class Vendor implements VendorInterface
{
    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    private $queryBuilderFactory;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    /**
     * @param VendorFilter $filter
     *
     * @return VendorModel[]
     */
    public function getVendors(VendorFilter $filter): array
    {
        $vendors = [];

        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select(
            [
                'v.oxid',
                'v.oxactive',
                'v.oxicon',
                'v.oxtitle',
                'v.oxshortdesc',
                's.oxseourl',
                'v.oxtimestamp'
            ]
        )
            ->from('oxvendor', 'v')
            ->leftJoin('v', 'oxseo', 's', 'v.oxid = s.oxobjectid');

        $filters = array_filter($filter->getFilters());
        foreach ($filters as $field => $fieldFilter) {
            $fieldFilter->addToQuery($queryBuilder, $field);
        }

        $result = $queryBuilder->execute();
        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            return $vendors;
        }

        foreach ($result as $row) {
            $vendors[] = new VendorModel(
                $row['oxid'],
                (int) $row['oxactive'],
                $row['oxicon'],
                $row['oxtitle'],
                $row['oxshortdesc'],
                $row['oxseourl'],
                $row['oxtimestamp']
            );
        }

        return $vendors;
    }
}
