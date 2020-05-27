<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Service;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\GraphQL\Catalogue\DataType\Currency;
use OxidEsales\GraphQL\Catalogue\Exception\CurrencyNotFound;

class CurrencyRepository
{
    /** @var Config */
    private $config;

    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @throws CurrencyNotFound
     */
    public function getByName(string $name): Currency
    {
        /** @var \stdClass|null */
        $currency = $this->config->getCurrencyObject($name);
        if (!$currency instanceof \stdClass) {
            throw CurrencyNotFound::byName($name);
        }
        return new Currency($currency);
    }

    /**
     * @throws CurrencyNotFound
     */
    public function getActiveCurrency(): Currency
    {
        /** @var \stdClass|null */
        $currency = $this->config->getActShopCurrencyObject();
        if (!$currency instanceof \stdClass) {
            throw CurrencyNotFound::byActiveInShop();
        }
        return new Currency($currency);
    }

    /**
     * @return Currency[]
     */
    public function getAll(): array
    {
        $currencies = [];
        foreach ($this->config->getCurrencyArray() as $currencyObject) {
            $currencies[] = new Currency($currencyObject);
        }
        return $currencies;
    }
}
