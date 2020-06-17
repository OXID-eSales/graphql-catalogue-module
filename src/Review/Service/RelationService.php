<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Review\Service;

use OxidEsales\GraphQL\Base\Service\Authentication;
use OxidEsales\GraphQL\Base\Service\Authorization;
use OxidEsales\GraphQL\Catalogue\Product\DataType\Product;
use OxidEsales\GraphQL\Catalogue\Product\Service\Product as ProductService;
use OxidEsales\GraphQL\Catalogue\Review\DataType\Review;
use OxidEsales\GraphQL\Catalogue\Shared\Infrastructure\Repository;
use OxidEsales\GraphQL\Catalogue\User\DataType\Reviewer;
use OxidEsales\GraphQL\Catalogue\User\DataType\User;
use OxidEsales\GraphQL\Catalogue\User\Service\User as UserService;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Review::class)
 */
final class RelationService
{
    /** @var ProductService */
    private $productService;

    /** @var Repository */
    private $repository;

    /** @var UserService */
    private $userService;

    /** @var Authorization */
    private $authorizationService;

    /** @var Authentication */
    private $authenticationService;

    public function __construct(
        Repository $repository,
        ProductService $productService,
        UserService $userService,
        Authorization $authorizationService,
        Authentication $authenticationService
    ) {
        $this->repository     = $repository;
        $this->productService = $productService;
        $this->userService    = $userService;
        $this->authorizationService = $authorizationService;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @Field()
     * @return User|Reviewer
     */
    public function getUser(Review $review)
    {
        $userId = (string) $review->getUserId();

        $isAllowed = $this->authorizationService->isAllowed('VIEW_USER');

        $user = $isAllowed ? $this->userService->user($userId) : $this->userService->reviewer($userId);

        return $user;
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
