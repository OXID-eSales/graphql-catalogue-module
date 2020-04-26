<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\DataType\BoolFilter;
use OxidEsales\GraphQL\Base\DataType\IntegerFilter;
use OxidEsales\GraphQL\Base\DataType\StringFilter;
use TheCodingMachine\GraphQLite\Annotations\Factory;

class ActionFilterList extends FilterList
{
    /** @var null|StringFilter */
    protected $actionId;

    public function __construct(
        ?StringFilter $actionId = null,
        ?BoolFilter $active = null
    ) {
        $this->actionId = $actionId;
        $this->active = $active;
        parent::__construct();
    }

    /**
     * @Factory(name="ActionFilterList")
     */
    public static function createActionFilterList(?StringFilter $actionId = null): self
    {
        return new self(
            $actionId
        );
    }

    /**
     * @return array{
     *  oxactionid: null|StringFilter
     * }
     */
    public function getFilters(): array
    {
        return [
            'oxactionid' => $this->actionId,
            'oxtype' => new IntegerFilter(null, 2)
        ];
    }
}
