<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Product\Service;

use OxidEsales\GraphQL\Catalogue\Currency\DataType\Currency;
use OxidEsales\GraphQL\Catalogue\Currency\Infrastructure\Repository;
use OxidEsales\GraphQL\Catalogue\Product\DataType\Price;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Price::class)
 */
final class PriceRelationService
{
    /** @var Repository */
    private $currencyRepository;

    public function __construct(
        Repository $currencyRepository
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