<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\DataType\PaginationFilter;
use OxidEsales\GraphQL\Base\DataType\StringFilter;
use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Catalogue\Exception\CategoryNotFound;
use OxidEsales\GraphQL\Catalogue\Service\Product as ProductService;
use OxidEsales\GraphQL\Catalogue\Service\Category as CategoryService;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Category::class)
 */
class CategoryRelationService
{
    /** @var ProductService */
    private $productService;

    /** @var CategoryService */
    private $categoryService;

    public function __construct(
        ProductService $productService,
        CategoryService $categoryService
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    /**
     * @Field()
     */
    public function getParent(Category $category): ?Category
    {
        try {
            return $this->categoryService->category(
                (string)$category->getParentId()
            );
        } catch (InvalidLogin | CategoryNotFound $e) {
        }
        return null;
    }

    /**
     * @Field()
     */
    public function getRoot(Category $category): ?Category
    {
        try {
            return $this->categoryService->category(
                (string)$category->getRootId()
            );
        } catch (InvalidLogin | CategoryNotFound $e) {
        }
        return null;
    }

    /**
     * @Field()
     *
     * @return Category[]
     */
    public function getChildren(Category $category): array
    {
        return $this->categoryService->categories(
            new CategoryFilterList(
                null,
                new StringFilter((string)$category->getId())
            )
        );
    }

    /**
     * @Field()
     */
    public function getSeo(Category $category): Seo
    {
        $seo = new Seo($category->getEshopModel());

        return $seo;
    }

    /**
     * @Field()
     *
     * @return Product[]
     */
    public function getProducts(
        Category $category,
        ?PaginationFilter $pagination
    ): array {
        return $this->productService->products(
            new ProductFilterList(
                null,
                new CategoryIDFilter($category->getId())
            ),
            $pagination
        );
    }
}
