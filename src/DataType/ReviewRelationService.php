<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\Exception\ProductNotFound;
use OxidEsales\GraphQL\Catalogue\Service\Product as ProductService;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Review::class)
 */
final class ReviewRelationService
{
    /** @var ProductService */
    private $productService;

    /** @var Repository */
    private $repository;

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
    public function getUser(Review $review): ?User
    {
        $user = null;

        try {
            if ($userId = (string)$review->getEshopModel()->getFieldData('oxuserid')) {
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
    public function getProduct(Review $review): ?Product
    {
        $reviewModel = $review->getEshopModel();

        if ($reviewModel->getFieldData('oxtype') !== 'oxarticle') {
            return null;
        }

        try {
            return $this->productService->product(
                (string)$reviewModel->getFieldData('oxobjectid')
            );
        } catch (ProductNotFound | InvalidLogin $e) {
        }
        return null;
    }
}
