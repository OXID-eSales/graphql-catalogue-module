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
    public function currency(?string $name): CurrencyDataType
    {
        try {
            $config = Registry::getConfig();

            /** @var \stdClass $currencyObject */
            $currencyObject = $name ? $config->getCurrencyObject($name) : $config->getActShopCurrencyObject();

            return new CurrencyDataType($currencyObject);
        } catch (\Exception $e) {
            throw CurrencyNotFound::inShop();
        }
    }

    /**
     * @Query()
     *
     * @return CurrencyDataType[]
     */
    public function currencies(): array
    {
        try {
            $currencies = [];

            /** @var \stdClass[] $currencyArray */
            $currencyArray = Registry::getConfig()->getCurrencyArray();

            foreach ($currencyArray as $currencyObject) {
                $currencies[] = new CurrencyDataType($currencyObject);
            }

            return $currencies;
        } catch (\Exception $e) {
            return [];
        }
    }
}
