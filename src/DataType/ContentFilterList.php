<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\DataType\StringFilter;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class ContentFilterList extends FilterList
{
    /** @var ?StringFilter */
    private $folder = null;

    public function __construct(
        ?StringFilter $folder = null
    ) {
        $this->folder = $folder;
        parent::__construct();
    }

    /**
     * @Factory(name="ContentFilterList")
     */
    public static function createContentFilterList(
        ?StringFilter $folder = null
    ): self {
        return new self(
            $folder
        );
    }

    /**
     * @return array{
     *  oxfolder: ?StringFilter
     * }
     */
    public function getFilters(): array
    {
        return [
            'oxfolder' => $this->folder
        ];
    }
}
