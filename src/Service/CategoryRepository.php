<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Service;

use OxidEsales\Eshop\Application\Model\Category as CategoryModel;
use OxidEsales\GraphQL\Catalogue\DataType\Category;
use OxidEsales\GraphQL\Catalogue\Exception\CategoryNotFound;

class CategoryRepository
{
    /**
     * @param string $id
     *
     * @return Category
     * @throws CategoryNotFound
     */
    public function getById(string $id): Category
    {
        /** @var CategoryModel */
        $category = oxNew(CategoryModel::class);
        if (!$category->load($id)) {
            throw CategoryNotFound::byId($id);
        }

        return new Category($category);
    }
}
