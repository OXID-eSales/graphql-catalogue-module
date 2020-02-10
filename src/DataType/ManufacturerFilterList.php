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

class ManufacturerFilterList extends FilterList
{
    /** @var ?StringFilter */
    private $title = null;

    public function __construct(
        ?StringFilter $title = null,
        ?BoolFilter $active = null
    ) {
        $this->title = $title;
        $this->active = $active;
    }

    /**
     * @Factory(name="ManufacturerFilterList")
     */
    public static function createManufacturerFilterList(
        ?StringFilter $title = null
    ): self {
        return new self(
            $title
        );
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
