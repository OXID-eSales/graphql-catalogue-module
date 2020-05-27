<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Catalogue\Exception\ProductNotFound;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use OxidEsales\GraphQL\Catalogue\Service\Product as ProductService;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Rating::class)
 */
class RatingRelationService
{
    /** @var Repository */
    private $repository;

    /** @var ProductService */
    private $productService = null;

    public function __construct(
        Repository $repository,
        ProductService $productService
    ) {
        $this->repository = $repository;
        $this->productService = $productService;
    }

    /**
     * @Field()
     */
    public function getUser(Rating $rating): ?User
    {
        $user = null;

        try {
            if ($userId = (string)$rating->getEshopModel()->getFieldData('oxuserid')) {
                $user = $this->repository->getById(
                    $userId,
                    User::class
                );
            }
        } catch (NotFound $e) {
            return null;
        }

        return $user;
    }

    /**
     * @Field()
     */
    public function getProduct(Rating $rating): ?Product
    {
        $ratingModel = $rating->getEshopModel();

        if ($ratingModel->getFieldData('oxtype') !== 'oxarticle') {
            return null;
        }

        try {
            return $this->productService->product(
                (string)$ratingModel->getFieldData('oxobjectid')
            );
        } catch (ProductNotFound | InvalidLogin $e) {
        }
        return null;
    }
}
