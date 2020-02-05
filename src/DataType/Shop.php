<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\EshopCommunity\Application\Model\Shop as ShopModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type()
 */
final class Shop implements DataType
{
    /** @var ShopModel */
    private $shop;

    public function __construct(ShopModel $shop)
    {
        $this->shop = $shop;
    }

    /**
     * @return class-string
     */
    public static function getModelClass(): string
    {
        return ShopModel::class;
    }

    /**
     * @Field()
     */
    public function getId(): ID
    {
        return new ID(
            (int)$this->shop->getShopId()
        );
    }
}
