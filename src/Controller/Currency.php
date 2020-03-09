<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Catalogue\DataType\Currency as CurrencyDataType;
use OxidEsales\GraphQL\Catalogue\Exception\CurrencyNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Currency extends Base
{
    /**
     * @Query()
     *
     * @throws CurrencyNotFound
     */
    public function currency(): CurrencyDataType
    {
        try {
            /** @var \stdClass $currencyObject */
            $currencyObject = Registry::getConfig()->getActShopCurrencyObject();
            $currency = new CurrencyDataType($currencyObject);
        } catch (\Exception $e) {
            throw CurrencyNotFound::inShop();
        }

        return $currency;
    }
}
