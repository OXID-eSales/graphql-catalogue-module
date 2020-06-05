<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\DataType\StringFilter;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @deprecated Not used for catalogue, we keep the code and later move it to admin
 * module.
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
     * @return array
     */
    public function getRatings(ProductRating $rating): array
    {
        /*
        return $this->repository->getByFilter(
            new ProductRatingFilterList(
                new StringFilter((string)$rating->getEshopModel()->getId())
            ),
            Rating::class
        ); */
        return [];
    }
}
