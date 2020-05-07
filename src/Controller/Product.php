<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Base\DataType\PaginationFilter;
use OxidEsales\GraphQL\Catalogue\DataType\Product as ProductDataType;
use OxidEsales\GraphQL\Catalogue\DataType\ProductFilterList;
use OxidEsales\GraphQL\Catalogue\Exception\ProductNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Product extends Base
{
    /**
     * @Query()
     *
     * @throws ProductNotFound
     */
    public function product(string $id): ProductDataType
    {
        try {
            /** @var ProductDataType $product */
            $product = $this->repository->getById($id, ProductDataType::class);
        } catch (NotFound $e) {
            throw ProductNotFound::byId($id);
        }

        if ($product->isActive()) {
            return $product;
        }

        if (!$this->isAuthorized('VIEW_INACTIVE_PRODUCT')) {
            throw new InvalidLogin("Unauthorized");
        }

        return $product;
    }

    /**
     * @Query()
     *
     * @return ProductDataType[]
     */
    public function products(?ProductFilterList $filter = null, ?PaginationFilter $pagination = null): array
    {
        $filter = $filter ?? new ProductFilterList();

        // In case user has VIEW_INACTIVE_PRODUCT permissions
        // return all products including inactive ones
        if ($this->isAuthorized('VIEW_INACTIVE_PRODUCT')) {
            $filter = $filter->withActiveFilter(null);
        }

        $products = $this->repository->getByFilter(
            $filter,
            ProductDataType::class,
            $pagination
        );

        return $products;
    }
}
