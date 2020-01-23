<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Service;

use OxidEsales\Eshop\Application\Model\Shop as ShopModel;
use OxidEsales\GraphQL\Catalogue\DataType\Shop;
use OxidEsales\GraphQL\Catalogue\Exception\ShopNotFound;

class ShopRepository
{
    /**
     * @param string $id
     *
     * @return Shop
     * @throws ShopNotFound
     */
    public function getById(string $id): Shop
    {
        /** @var ShopModel */
        $shop = oxNew(ShopModel::class);
        if (!$shop->load($id)) {
            throw ShopNotFound::byId($id);
        }

        return new Shop($shop);
    }
}
