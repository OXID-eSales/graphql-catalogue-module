<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

use function array_map;
use function count;
use function is_iterable;
use function strlen;

/**
 * @ExtendType(class=Product::class)
 */
class ProductRelationService
{
    /** @var Repository */
    private $repository;

    public function __construct(
        Repository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @Field()
     */
    public function getDimensions(Product $product): ProductDimensions
    {
        return new ProductDimensions(
            $product->getEshopModel()
        );
    }

    /**
     * @Field()
     */
    public function getPrice(Product $product): Price
    {
        return new Price(
            $product->getEshopModel()->getPrice()
        );
    }

    /**
     * @Field()
     */
    public function getListPrice(Product $product): Price
    {
        return new Price(
            $product->getEshopModel()->getTPrice()
        );
    }

    /**
     * @Field()
     */
    public function getStock(Product $product): ProductStock
    {
        return new ProductStock(
            $product->getEshopModel()
        );
    }

    /**
     * @Field()
     */
    public function getImageGallery(Product $product): ProductImageGallery
    {
        return new ProductImageGallery(
            $product->getEshopModel()
        );
    }

    /**
     * @Field()
     */
    public function getRating(Product $product): ProductRating
    {
        return new ProductRating(
            $product->getEshopModel()
        );
    }

    /**
     * @Field()
     */
    public function getDeliveryTime(Product $product): ProductDeliveryTime
    {
        return new ProductDeliveryTime(
            $product->getEshopModel()
        );
    }

    /**
     * @Field()
     * @return ProductScalePrice[]
     */
    public function getScalePrices(Product $product): array
    {
        $amountPrices = $product->getEshopModel()->loadAmountPriceInfo();
        return array_map(
            function ($amountPrice) {
                return new ProductScalePrice($amountPrice);
            },
            $amountPrices
        );
    }

    /**
     * @Field()
     */
    public function getBundleProduct(Product $product): ?Product
    {
        $bundleProductId = (string)$product->getEshopModel()->getFieldData('oxbundleid');
        if (!strlen($bundleProductId)) {
            return null;
        }
        try {
            $bundleProduct = $this->repository->getById(
                $bundleProductId,
                Product::class
            );
        } catch (NotFound $e) {
            return null;
        }
        if (!$bundleProduct->getEshopModel()->isVisible()) {
            return null;
        }
        return $bundleProduct;
    }

    /**
     * @Field()
     */
    public function getManufacturer(Product $product): ?Manufacturer
    {
        $manufacturer = $product->getEshopModel()->getManufacturer();
        if ($manufacturer === null) {
            return null;
        }
        return new Manufacturer(
            $manufacturer
        );
    }

    /**
     * @Field()
     */
    public function getVendor(Product $product): ?Vendor
    {
        /** @var \OxidEsales\Eshop\Application\Model\Vendor|null */
        $vendor = $product->getEshopModel()->getVendor();
        if ($vendor === null) {
            return null;
        }
        return new Vendor(
            $vendor
        );
    }

    /**
     * @Field()
     */
    public function getCategory(Product $product): ?Category
    {
        /** @var \OxidEsales\Eshop\Application\Model\Category $category */
        $category = $product->getEshopModel()->getCategory();
        if (!$category->isLoaded()) {
            return null;
        }
        return new Category(
            $category
        );
    }

    /**
     * @Field()
     */
    public function getUnit(Product $product): ?ProductUnit
    {
        if (!$product->getEshopModel()->getUnitPrice()) {
            return null;
        }
        return new ProductUnit(
            $product->getEshopModel()
        );
    }

    /**
     * @Field()
     *
     * @param Product $product
     *
     * @return Seo
     */
    public function getSeo(Product $product): Seo
    {
        $seo = new Seo($product->getEshopModel());

        return $seo;
    }

    /**
     * @Field()
     *
     * @return Product[]
     */
    public function getCrossSellingProducts(Product $product): array
    {
        $products = $product->getEshopModel()->getCrossSelling();
        if (!is_iterable($products) || count($products) === 0) {
            return [];
        }
        $crossSellings = [];
        foreach ($products as $product) {
            $crossSellings[] = new Product($product);
        }
        return $crossSellings;
    }
}
