<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Service;

use OxidEsales\Eshop\Application\Model\Vendor as VendorModel;
use OxidEsales\Eshop\Application\Model\VendorList as VendorListModel;
use OxidEsales\GraphQL\Catalogue\Exception\VendorNotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Vendor;
use OxidEsales\GraphQL\Catalogue\DataType\VendorFilter;

class VendorRepository
{

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
        /** @var VendorListModel */
        $vendorList = oxNew(VendorListModel::class);
        $vendorList->loadVendorList();
        $vendors = [];
        /** @var VendorModel $vendor */
        foreach ($vendorList as $vendor) {
            $vendors[] = new Vendor($vendor);
        }
        // as the VendorList model does not allow us to easily inject conditions
        // into the SQL where clause, we filter after the fact. This stinks, but
        // at the moment this is the easiest solution
        if ($filter !== null) {
            $titleFilter = $filter->getFilters()['oxtitle'];
            if ($titleFilter) {
                $vendors = array_filter(
                    $vendors,
                    function (Vendor $vendor) use ($titleFilter) {
                        if ($title = $titleFilter->equals()) {
                            if ($vendor->getTitle() !== $title) {
                                return false;
                            }
                        }
                        if ($title = $titleFilter->contains()) {
                            if (strpos($vendor->getTitle(), $title) === false) {
                                return false;
                            }
                        }
                        if ($title = $titleFilter->beginsWith()) {
                            if (strpos($vendor->getTitle(), $title) !== 0) {
                                return false;
                            }
                        }
                        return true;
                    }
                );
            }
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
