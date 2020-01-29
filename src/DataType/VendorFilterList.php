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

class VendorFilterList implements FilterList
{
    /** @var ?StringFilter */
    private $title = null;

    /** @var ?BoolFilter */
    private $active = null;

    public function __construct(
        ?StringFilter $title = null,
        ?BoolFilter $active = null
    ) {
        $this->title = $title;
        $this->active = $active;
    }

    /**
     * @Factory(name="VendorFilterList")
     */
    public static function createVendorFilterList(
        ?StringFilter $title = null,
        ?BoolFilter $active = null
    ): self {
        return new self(
            $title,
            $active
        );
    }

    /**
     * @return array{
     *  oxtitle: ?StringFilter,
     *  oxactive: ?BoolFilter
     * }
     */
    public function getFilters(): array
    {
        return [
            'oxtitle' => $this->title,
            'oxactive' => $this->active
        ];
    }
}
