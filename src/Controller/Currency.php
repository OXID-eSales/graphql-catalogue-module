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
use OxidEsales\GraphQL\Catalogue\Service\CurrencyRepository;

class Currency extends Base
{
    /** @var CurrencyRepository */
    private $currencyRepository;

    public function __construct(
        CurrencyRepository $currencyRepository
    ) {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * If `name` is ommited, gives you the currently active currency
     *
     * @Query()
     *
     * @throws CurrencyNotFound
     */
    public function currency(?string $name = null): CurrencyDataType
    {
        return $name ? $this->currencyRepository->getByName($name) : $this->currencyRepository->getActiveCurrency();
    }

    /**
     * @Query()
     *
     * @return CurrencyDataType[]
     */
    public function currencies(): array
    {
        return $this->currencyRepository->getAll();
    }
}
