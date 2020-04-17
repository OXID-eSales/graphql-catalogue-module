<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\DataType\StringFilter;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=ProductRating::class)
 */
class ProductRatingRelationService
{
    /** @var Repository */
    private $repository;

    public function __construct(
        Repository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @Field
     *
     * @return Rating[]
     */
    public function getRatings(ProductRating $rating): array
    {
        return $this->repository->getByFilter(
            new ProductRatingFilterList(
                new StringFilter((string)$rating->getEshopModel()->getId())
            ),
            Rating::class
        );
    }
}
