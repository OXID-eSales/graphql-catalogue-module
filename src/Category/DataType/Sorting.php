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
    /**
     * @param array<string, string> $sorting
     */
    public function __construct(
        array $sorting
    ) {
        $sorting['oxorder'] = self::SORTING_ASC;
        parent::__construct($sorting);
    }

    /**
     * @Factory()
     */
    public static function fromUserInput(
        ?string $title = BaseSorting::SORTING_DESC
    ): self {
        return new self([
            'oxtitle' => $title,
        ]);
    }
}
