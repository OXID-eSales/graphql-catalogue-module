<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\DataType\PaginationFilter;
use OxidEsales\GraphQL\Catalogue\DataType\Product as ProductDataType;
use OxidEsales\GraphQL\Catalogue\DataType\ProductFilterList;
use TheCodingMachine\GraphQLite\Annotations\Query;
use OxidEsales\GraphQL\Catalogue\Service\Product as ProductService;

final class Product
{
    /** @var ProductService */
    private $productService = null;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    /**
     * @Query()
     */
    public function product(string $id): ProductDataType
    {
        return $this->productService->product($id);
    }

    /**
     * @Query()
     *
     * @return ProductDataType[]
     */
    public function products(?ProductFilterList $filter = null, ?PaginationFilter $pagination = null): array
    {
        return $this->productService->products(
            $filter ?? new ProductFilterList(),
            $pagination
        );
    }
}
