<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use TheCodingMachine\GraphQLite\Annotations\Query;
use OxidEsales\GraphQL\Catalogue\Dao\VendorInterface as VendorDao;
use OxidEsales\GraphQL\Catalogue\DataObject\Vendor as VendorModel;

class Vendor
{
    /** @var VendorDao */
    protected $vendorDao;

    public function __construct(
        VendorDao $vendorDao
    ) {
        $this->vendorDao = $vendorDao;
    }

    /**
     * @Query()
     * @return VendorModel[]
     */
    public function vendors(): array
    {
        try {
            $vendors = $this->vendorDao->getVendors();
        } catch (\Exception $e) {
            return [$e->getMessage()];
        }

        $vendors = array_filter(
            $vendors,
            function (VendorModel $vendor) {
                return $vendor->getActive();
            }
        );

        return $vendors;
    }
}
