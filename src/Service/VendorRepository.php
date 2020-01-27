<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Service;

use OxidEsales\Eshop\Application\Model\Vendor as VendorModel;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Catalogue\Exception\VendorNotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Vendor;
use OxidEsales\GraphQL\Catalogue\DataType\VendorFilter;

class VendorRepository
{

    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    private $queryBuilderFactory;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function getById(string $id): Vendor
    {
        /** @var VendorModel */
        $vendor = oxNew(VendorModel::class);
        // @TODO refactor when return type annotation in VendorModel is fixed
        // should be: if (!$vendor->load($id))
        /** @var bool $loaded */
        $loaded = $vendor->load($id);
        if (!$loaded) {
            throw VendorNotFound::byId($id);
        }
        return new Vendor($vendor);
    }

    /**
     * @return Vendor[]
     */
    public function getByFilter(?VendorFilter $filter = null): array
    {
        $vendors = [];
        /** @var VendorModel */
        $vendor = oxNew(VendorModel::class);
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('*')
                     ->from($vendor->getViewName())
                     ->orderBy('oxid');
        if ($filter) {
            $filters = array_filter($filter->getFilters());
            foreach ($filters as $field => $fieldFilter) {
                $fieldFilter->addToQuery($queryBuilder, $field);
            }
        }
        $result = $queryBuilder->execute();
        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            return $vendors;
        }
        foreach ($result as $row) {
            $newVendor = clone $vendor;
            $newVendor->assign($row);
            $vendors[] = new Vendor($newVendor);
        }
        return $vendors;
    }

    public function save(Vendor $vendor): Vendor
    {
        $vendorModel = $vendor->getVendorModel();
        if (!$vendorModel->save()) {
            throw new \Exception();
        }
        // reload model
        $vendorModel->load($vendorModel->getId());
        return new Vendor($vendorModel);
    }
}
