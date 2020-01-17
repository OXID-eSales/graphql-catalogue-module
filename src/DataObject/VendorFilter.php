<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataObject;

use OxidEsales\GraphQL\Base\DataObject\StringFilter;

class VendorFilter
{
    /** @var ?StringFilter */
    private $title;

    public function __construct(
        ?StringFilter $title = null
    ) {
        $this->title = $title;
    }

    /**
     * @return array{
     *  oxtitle: ?StringFilter
     * }
     */
    public function getFilters(): array
    {
        return [
            'oxtitle' => $this->title
        ];
    }
}
