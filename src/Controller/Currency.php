<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

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
            /** @var CurrencyDataType $currency */
            $currency = new CurrencyDataType();
        } catch (\Exception $e) {
            throw CurrencyNotFound::inShop();
        }

        return $currency;
    }
}
