<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\DataType\BoolFilter;
use OxidEsales\GraphQL\Base\DataType\StringFilter;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class VendorFilterFactory
{
    /**
     * @Factory(name="VendorFilterList")
     */
    public static function createVendorFilter(
        ?StringFilter $title = null,
        ?BoolFilter $active = null
    ): VendorFilterList {
        return new VendorFilterList(
            $title,
            $active
        );
    }
}
