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

class CategoryFilterList extends FilterList
{
    /** @var null|StringFilter */
    protected $title;

    /** @var null|StringFilter */
    protected $parentId;

    public function __construct(
        ?StringFilter $title = null,
        ?BoolFilter $active = null,
        ?StringFilter $parentId = null
    ) {
        $this->title = $title;
        $this->active = $active;
        $this->parentId = $parentId;
    }

    /**
     * @Factory(name="CategoryFilterList")
     */
    public static function createCategoryFilterList(
        ?StringFilter $title = null,
        ?BoolFilter $active = null,
        ?StringFilter $parentId = null
    ): self {
        return new self(
            $title,
            $active,
            $parentId
        );
    }

    /**
     * @return array{
     *  oxtitle: null|StringFilter
     * }
     */
    public function getFilters(): array
    {
        return [
            'oxtitle' => $this->title,
            'oxparentid' => $this->parentId
        ];
    }
}
