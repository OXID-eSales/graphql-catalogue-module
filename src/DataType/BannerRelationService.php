<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Catalogue\Exception\ProductNotFound;
use OxidEsales\GraphQL\Catalogue\Service\Product as ProductService;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Banner::class)
 */
final class BannerRelationService
{
    /** @var ProductService */
    private $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    /**
     * @Field()
     */
    public function getProduct(Banner $banner): ?Product
    {
        /*
         * NOTE: getBannerArticle will load product but we need to make sure
         * customer have correct permission to see that product
         * by loading product thru product service
         * which can lead to performance issue due to double load of that product
         */
        /** @var \OxidEsales\Eshop\Application\Model\Article|null $product */
        $product = $banner->getEshopModel()->getBannerArticle();
        if ($product === null) {
            return null;
        }

        try {
            return $this->productService->product(
                $product->getId()
            );
        } catch (ProductNotFound | InvalidLogin $e) {
            return null;
        }
    }
}
