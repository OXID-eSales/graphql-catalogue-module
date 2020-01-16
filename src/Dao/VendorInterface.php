<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Dao;

use OxidEsales\GraphQL\Catalogue\DataObject\Vendor as VendorModel;

interface VendorInterface
{
    /**
     * @return VendorModel[]
     */
    public function getVendors(): array;
}
