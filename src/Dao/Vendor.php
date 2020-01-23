<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Dao;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Vendor as VendorModel;
use OxidEsales\GraphQL\Catalogue\DataType\VendorFilter;

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
            ->from(getViewName('oxvendor'), 'v')
            ->leftJoin('v', 'oxseo', 's', 'v.oxid = s.oxobjectid AND s.oxshopid = :shopid AND s.oxlang = :lang')
            ->setParameters([
                ':lang' => Registry::getLang()->getBaseLanguage(),
                ':shopid' => Registry::getConfig()->getShopId()
            ]);

        $filters = array_filter($filter->getFilters());
        foreach ($filters as $field => $fieldFilter) {
            $fieldFilter->addToQuery($queryBuilder, $field);
        }

        $result = $queryBuilder->execute();
        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            return $vendors;
        }

        foreach ($result as $row) {
            $vendors[] = VendorModel::fromDatabaseResult($row);
        }

        return $vendors;
    }

    /**
     * @param string $id
     *
     * @return VendorModel
     *
     * @throws NotFound
     */
    public function getVendor(string $id): VendorModel
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select(
            [
                'v.oxid',
                'v.oxactive',
                'v.oxicon',
                'v.oxtitle',
                'v.oxshortdesc',
                's.oxseourl',
                'v.oxtimestamp',
            ]
        )
            ->from(getViewName('oxvendor'), 'v')
            ->leftJoin('v', 'oxseo', 's', 'v.oxid = s.oxobjectid AND s.oxlang = :lang AND s.oxshopid = :shopid')
            ->where($queryBuilder->expr()->eq('v.oxid', ':oxid'))
            ->setParameters([
                ':oxid' => $id,
                ':lang' => Registry::getLang()->getBaseLanguage(),
                ':shopid' => Registry::getConfig()->getShopId()
            ]);

        $result = $queryBuilder->execute();

        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            throw new NotFound();
        }

        $row = $result->fetch();

        if (!$row) {
            throw new NotFound();
        }

        return VendorModel::fromDatabaseResult($row);
    }
}
