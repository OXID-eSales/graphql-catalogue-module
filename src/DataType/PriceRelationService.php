<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Catalogue\Service\CurrencyRepository;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Price::class)
 */
class PriceRelationService
{
    /** @var CurrencyRepository */
    private $currencyRepository;

    public function __construct(
        CurrencyRepository $currencyRepository
    ) {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @Field()
     */
    public function getCurrency(): Currency
    {
        return $this->currencyRepository->getActiveCurrency();
    }
}
