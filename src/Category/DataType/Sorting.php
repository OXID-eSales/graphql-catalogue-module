<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Category\DataType;

use OxidEsales\GraphQL\Base\DataType\Sorting as BaseSorting;
use TheCodingMachine\GraphQLite\Annotations\Factory;

final class Sorting extends BaseSorting
{
    public function __construct(array $sorting)
    {
        $sorting = $sorting ?: ['oxsort' => self::SORTING_ASC];

        parent::__construct($sorting);
    }

    /**
     * @Factory(name="CategorySorting")
     */
    public static function fromUserInput(
        ?string $sort  = self::SORTING_ASC,
        ?string $title = null
    ): self {
        return new self([
            'oxsort'  => $sort,
            'oxtitle' => $title,
        ]);
    }
}
