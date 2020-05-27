<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\DataType\IDFilter;
use OxidEsales\GraphQL\Base\DataType\PaginationFilter;
use OxidEsales\GraphQL\Catalogue\Service\Product as ProductService;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;
use OxidEsales\GraphQL\Catalogue\DataType\Product as ProductDataType;

/**
 * @ExtendType(class=Manufacturer::class)
 */
class ManufacturerRelationService
{
    /** @var ProductService */
    private $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    /**
     * @Field()
     */
    public function getSeo(Manufacturer $manufacturer): Seo
    {
        $seo = new Seo($manufacturer->getEshopModel());

        return $seo;
    }

    /**
     * @Field()
     *
     * @return ProductDataType[]
     */
    public function getProducts(
        Manufacturer $manufacturer,
        ?PaginationFilter $pagination = null
    ): array {
        return $this->productService->products(
            new ProductFilterList(
                null,
                null,
                new IDFilter(
                    $manufacturer->getId()
                )
            ),
            $pagination
        );
    }
}
