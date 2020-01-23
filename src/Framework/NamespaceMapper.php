<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Framework;

use OxidEsales\GraphQL\Base\Framework\NamespaceMapperInterface;

class NamespaceMapper implements NamespaceMapperInterface
{
    public function getControllerNamespaceMapping(): array
    {
        return [
            '\\OxidEsales\\GraphQL\\Catalogue\\Controller' => __DIR__ . '/../Controller/'
        ];
    }

    public function getTypeNamespaceMapping(): array
    {
        return [
            '\\OxidEsales\\GraphQL\\Catalogue\\DataType' => __DIR__ . '/../DataType/'
        ];
    }
}
