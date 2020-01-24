<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Service;

use OxidEsales\Eshop\Application\Model\Vendor as VendorModel;
use OxidEsales\Eshop\Application\Model\VendorList as VendorListModel;
use OxidEsales\GraphQL\Example\Exception\VendorNotFound;
use OxidEsales\GraphQL\Example\DataType\Vendor;
use OxidEsales\GraphQL\Example\DataType\VendorFilter;

class VendorRepository
{

    public function getById(string $id): Vendor
    {
        /** @var VendorModel */
        $vendor = oxNew(VendorModel::class);
        if (!$vendor->load($id)) {
            throw VendorNotFound::byId($id);
        }
        return new Vendor($vendor);
    }

    /**
     * @return Vendor[]
     */
    public function getByFilter(?VendorFilter $filter = null): array
    {
        /** @var VendorListModel */
        $vendorList = oxNew(VendorListModel::class);
        $vendorList->loadList();
        $categories = [];
        /** @var VendorModel $vendor */
        foreach ($vendorList as $vendor) {
            $categories[] = new Vendor($vendor);
        }
        // as the VendorList model does not allow us to easily inject conditions
        // into the SQL where clause, we filter after the fact. This stinks, but
        // at the moment this is the easiest solution
        if ($filter !== null) {
            $parentIdFilter = $filter->getFilters()['oxparentid'];
            $categories = array_filter(
                $categories,
                function (Vendor $vendor) use ($parentIdFilter) {
                    return $parentIdFilter->equals() == $vendor->getParentId();
                }
            );
        }
        return $categories;
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
