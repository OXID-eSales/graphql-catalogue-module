<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Catalogue\Service\Product as ProductService;
use OxidEsales\GraphQL\Catalogue\Service\User as UserService;
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

    /** @var UserService */
    private $userService;

    public function __construct(
        Repository $repository,
        ProductService $productService,
        UserService $userService
    ) {
        $this->repository = $repository;
        $this->productService = $productService;
        $this->userService = $userService;
    }

    /**
     * @Field()
     */
    public function getUser(Review $review): ?User
    {
        $userId = (string)$review->getUserId();
        return $this->userService->user($userId);
    }

    /**
     * @Field()
     */
    public function getProduct(Review $review): ?Product
    {
        if (!$review->isArticleType()) {
            return null;
        }

        return $this->productService->product($review->getObjectId());
    }
}
