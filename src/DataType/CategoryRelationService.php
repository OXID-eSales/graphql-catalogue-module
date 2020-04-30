<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\DataType\PaginationFilter;
use OxidEsales\GraphQL\Base\DataType\StringFilter;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Category::class)
 */
class CategoryRelationService
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
    public function getParent(Category $child): ?Category
    {
        try {
            return $this->repository->getById(
                (string)$child->getParentId(),
                Category::class
            );
        } catch (NotFound $e) {
            return null;
        }
    }

    /**
     * @Field()
     */
    public function getRoot(Category $category): Category
    {
        return $this->repository->getById(
            (string)$category->getRootId(),
            Category::class
        );
    }

    /**
     * @Field()
     *
     * @return Category[]
     */
    public function getChildren(Category $category): array
    {
        $filter = $filter ?? new CategoryFilterList(
            null,
            new \OxidEsales\GraphQL\Base\DataType\StringFilter((string)$category->getId())
        );

        return $this->repository->getByFilter(
            $filter,
            Category::class
        );
    }

    /**
     * @Field()
     *
     * @param Category $category
     *
     * @return Seo
     */
    public function getSeo(Category $category): Seo
    {
        $seo = new Seo($category->getEshopModel());

        return $seo;
    }

    /**
     * @Field()
     *
     * @param Category         $category
     * @param PaginationFilter $pagination
     *
     * @return Product[]
     */
    public function getProducts(
        Category $category,
        ?PaginationFilter $pagination
    ): array {
        $filters = new ProductFilterList(
            null,
            new CategoryIDFilter($category->getId())
        );

        return $this->repository->getByFilter(
            $filters,
            Product::class,
            $pagination
        );
    }
}
