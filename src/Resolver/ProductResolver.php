<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Resolver;

use OxidEsales\GraphQL\Catalogue\DataType\DataType;
use OxidEsales\GraphQL\Catalogue\DataType\Product;

class ProductResolver
{
    public function getAction(): string
    {
        return 'VIEW_INACTIVE_PRODUCT';
    }

    public function support(DataType $type): bool
    {
        return $type instanceof Product;
    }
}
