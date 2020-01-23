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
class Shop
{
    /** @var ShopModel */
    private $shop;

    public function __construct(ShopModel $shop)
    {
        $this->shop = $shop;
    }

    /**
     * @Field()
     *
     * @return ID
     */
    public function getId(): ID
    {
        return new ID(
            (int)$this->shop->getShopId()
        );
    }
}
