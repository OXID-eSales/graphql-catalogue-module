<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataObject;

use OxidEsales\GraphQL\Base\DataObject\StringFilter;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class ManufacturerFilterFactory
{
    /**
     * @Factory(name="ManufacturerFilter")
     */
    public static function createManufacturerFilter(
        StringFilter $title
    ): ManufacturerFilter {
        return new ManufacturerFilter(
            $title
        );
    }
}
