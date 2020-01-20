<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Dao;

use OxidEsales\GraphQL\Catalogue\DataObject\Vendor as VendorModel;
use OxidEsales\GraphQL\Catalogue\DataObject\VendorFilter;

interface VendorInterface
{
    /**
     * @return VendorModel[]
     */
    public function getVendors(VendorFilter $filter): array;

    /**
     * @return VendorModel
     */
    public function getVendor(string $id): VendorModel;
}
