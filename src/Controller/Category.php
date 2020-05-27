<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Catalogue\DataType\Category as CategoryDataType;
use OxidEsales\GraphQL\Catalogue\DataType\CategoryFilterList;
use OxidEsales\GraphQL\Catalogue\Service\Category as CategoryService;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class Category
{
    /** @var CategoryService */
    private $categoryService = null;

    public function __construct(
        CategoryService $categoryService
    ) {
        $this->categoryService = $categoryService;
    }

    /**
     * @Query()
     */
    public function category(string $id): CategoryDataType
    {
        return $this->categoryService->category($id);
    }

    /**
     * @Query()
     *
     * @return CategoryDataType[]
     */
    public function categories(?CategoryFilterList $filter = null): array
    {
        return $this->categoryService->categories(
            $filter ?? new CategoryFilterList()
        );
    }
}
