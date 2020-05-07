<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\DataType\IDFilter;
use OxidEsales\GraphQL\Base\DataType\PaginationFilter;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Vendor::class)
 */
class VendorRelationService
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
    public function getSeo(Vendor $vendor): Seo
    {
        $seo = new Seo($vendor->getEshopModel());

        return $seo;
    }

    /**
     * @Field()
     *
     * @return Product[]
     */
    public function getProducts(
        Vendor $vendor,
        ?PaginationFilter $pagination = null
    ): array {
        return $this->repository->getByFilter(
            new ProductFilterList(
                null,
                null,
                null,
                new IDFilter($vendor->getId())
            ),
            Product::class,
            $pagination
        );
    }
}
