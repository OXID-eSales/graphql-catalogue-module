<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use stdClass;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
final class Currency
{
    /** @var stdClass */
    private $currency;

    public function __construct(stdClass $currencyObject)
    {
        $this->currency = $currencyObject;
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
