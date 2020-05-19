<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Resolver;

use OxidEsales\GraphQL\Catalogue\DataType\DataType;
use OxidEsales\GraphQL\Catalogue\DataType\Category;

class CategoryResolver
{
    public function getAction(): string
    {
        return 'VIEW_INACTIVE_CATEGORY';
    }

    public function support(DataType $type): bool
    {
        return $type instanceof Category;
    }
}
