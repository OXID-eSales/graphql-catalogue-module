<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use Exception;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Catalogue\Struct\Currency as CurrencyStruct;
use stdClass;
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
        $currencyObject = Registry::getConfig()->getActShopCurrencyObject();

        if (!($currencyObject instanceof stdClass)) {
            throw new Exception();
        }

        $encodedObject = json_encode($currencyObject);

        if ($encodedObject === false) {
            throw new Exception();
        }

        $currencyArray = json_decode($encodedObject, true);

        $this->currency = new CurrencyStruct($currencyArray);
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
