<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Resolver;

use OxidEsales\GraphQL\Catalogue\DataType\DataType;
use OxidEsales\GraphQL\Catalogue\DataType\Vendor;

class VendorResolver
{
    /**
     * @return string
     */
    public function getAction()
    {
        return 'VIEW_INACTIVE_VENDOR';
    }

    public function support(DataType $type): bool
    {
        return $type instanceof Vendor;
    }
}
