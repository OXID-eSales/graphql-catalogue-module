<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Catalogue\Struct\Currency as CurrencyStruct;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
final class Currency
{
    /** @var CurrencyStruct */
    private $currency;

    public function __construct()
    {
        $this->currency = new CurrencyStruct(Registry::getConfig()->getCurrencyArray());
    }

    /**
     * @Field()
     * @return int
     */
    public function getId(): int
    {
        return $this->currency->id;
    }

    /**
     * @Field()
     * @return string
     */
    public function getName(): string
    {
        return $this->currency->name;
    }

    /**
     * @Field()
     * @return string
     */
    public function getRate(): string
    {
        return $this->currency->rate;
    }

    /**
     * @Field()
     * @return string
     */
    public function getSign(): string
    {
        return $this->currency->sign;
    }
}
