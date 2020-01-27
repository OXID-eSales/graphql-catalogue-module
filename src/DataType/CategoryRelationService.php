<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Catalogue\Exception\CategoryNotFound;
use OxidEsales\GraphQL\Catalogue\Exception\ShopNotFound;
use OxidEsales\GraphQL\Catalogue\Service\CategoryRepository;
use OxidEsales\GraphQL\Catalogue\Service\ShopRepository;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Category::class)
 */
class CategoryRelationService
{
    /** @var CategoryRepository */
    private $categoryRepository;

    /** @var ShopRepository */
    private $shopRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        ShopRepository $shopRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->shopRepository = $shopRepository;
    }

    /**
     * @Field()
     */
    public function getParent(Category $child): ?Category
    {
        try {
            return $this->categoryRepository->getById((string)$child->getParentId());
        } catch (CategoryNotFound $e) {
            return null;
        }
    }

    /**
     * @Field()
     */
    public function getRoot(Category $category): ?Category
    {
        try {
            return $this->categoryRepository->getById((string)$category->getRootId());
        } catch (CategoryNotFound $e) {
            return null;
        }
    }

    /**
     * @Field()
     */
    public function getShop(Category $category): ?Shop
    {
        try {
            return $this->shopRepository->getById((string)$category->getShopId());
        } catch (ShopNotFound $e) {
            return null;
        }
    }
}
