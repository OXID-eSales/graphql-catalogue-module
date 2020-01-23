<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Catalogue\DataType\Category as CategoryDataType;
use OxidEsales\GraphQL\Catalogue\Exception\CategoryNotFound;
use OxidEsales\GraphQL\Catalogue\Service\CategoryRepository;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Category
{
    /** @var CategoryRepository */
    private $repository;

    /**
     * @param CategoryRepository $repository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Query()
     *
     * @param string $id
     *
     * @return CategoryDataType
     * @throws CategoryNotFound
     */
    public function category(string $id): CategoryDataType
    {
        return $this->repository->getById($id);
    }
}
