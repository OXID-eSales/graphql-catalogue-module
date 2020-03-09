<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\Eshop\Core\Registry;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Price::class)
 */
class PriceRelationService
{
    /**
     * @Field()
     */
    public function getCurrency(): Currency
    {

        /** @var \stdClass $currencyObject */
        $currencyObject = Registry::getConfig()->getActShopCurrencyObject();
        return new Currency($currencyObject);
    }
}
