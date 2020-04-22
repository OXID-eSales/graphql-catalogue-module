<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\DataType\BoolFilter;
use OxidEsales\GraphQL\Base\DataType\IDFilter;
use OxidEsales\GraphQL\Base\DataType\StringFilter;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class ProductFilterList extends FilterList
{
    /** @var ?StringFilter */
    private $title = null;

    /** @var ?CategoryIDFilter */
    private $category = null;

    /** @var ?IDFilter */
    private $manufacturer = null;

    /** @var ?IDFilter */
    private $vendor = null;

    /**
     * ProductFilterList constructor.
     *
     * @param StringFilter|null $title
     * @param IDFilter|null $manufacturer
     * @param IDFilter|null $vendor
     * @param BoolFilter|null $active
     */
    public function __construct(
        ?StringFilter $title = null,
        ?CategoryIDFilter $category = null,
        ?IDFilter $manufacturer = null,
        ?IDFilter $vendor = null,
        ?BoolFilter $active = null
    ) {
        $this->title = $title;
        $this->category = $category;
        $this->manufacturer = $manufacturer;
        $this->vendor = $vendor;
        $this->active = $active;
        parent::__construct();
    }

    /**
     * @Factory(name="ProductFilterList")
     */
    public static function createProductFilterList(
        ?StringFilter $title = null,
        ?CategoryIDFilter $category = null,
        ?IDFilter $manufacturer = null,
        ?IDFilter $vendor = null
    ): self {
        return new self($title, $category, $manufacturer, $vendor);
    }

    /**
     * @return array{
     *  oxtitle: ?StringFilter,
     *  oxmanufacturerid: ?IDFilter,
     *  oxvendorid: ?IDFilter
     * }
     */
    public function getFilters(): array
    {
        return [
            'oxtitle' => $this->title,
            'oxcatnid' => $this->category,
            'oxmanufacturerid' => $this->manufacturer,
            'oxvendorid' => $this->vendor
        ];
    }
}
