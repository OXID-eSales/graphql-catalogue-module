<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Base\DataType\BoolFilter;
use OxidEsales\GraphQL\Catalogue\DataType\Category as CategoryDataType;
use OxidEsales\GraphQL\Catalogue\DataType\CategoryFilterList;
use OxidEsales\GraphQL\Catalogue\Exception\CategoryNotFound;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Category extends Base
{
    /** @var Repository*/
    protected $repository;

    /**
     * @Query()
     *
     * @throws CategoryNotFound
     */
    public function category(string $id): CategoryDataType
    {
        try {
            /** @var CategoryDataType $category */
            $category = $this->repository->getById($id, CategoryDataType::class);
        } catch (NotFound $e) {
            throw CategoryNotFound::byId($id);
        }

        if ($category->isActive()) {
            return $category;
        }

        if (!$this->isAuthorized('VIEW_INACTIVE_CATEGORY')) {
            throw new InvalidLogin("Unauthorized");
        }

        return $category;
    }

    /**
     * @Query()
     *
     * @return CategoryDataType[]
     */
    public function categories(?CategoryFilterList $filter = null): array
    {
        $filter = $filter ?? new CategoryFilterList();

        if (!$this->isAuthorized('VIEW_INACTIVE_CATEGORY')) {
            $filter = $filter->withActiveFilter(new BoolFilter(true));
        }

        try {
            $categories = $this->repository->getByFilter($filter, CategoryDataType::class);
        } catch (\Exception $e) {
            return [];
        }

        return $categories;
    }
}
