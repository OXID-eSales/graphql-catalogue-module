<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Struct;

/**
 * Class Currency
 *
 * Wrapper for the currency information in Oxid Eshop
 */
class Currency extends Struct
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $rate;

    /** @var string */
    public $dec;

    /** @var string */
    public $thousand;

    /** @var string */
    public $sign;

    /** @var string */
    public $decimal;

    /** @var int */
    public $selected;
}
