<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Review::class)
 */
class ReviewRelationService
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
        $product = null;

        if ($reviewModel->getFieldData('oxtype') !== 'oxarticle') {
            return null;
        }

        try {
            if ($objectId = (string)$reviewModel->getFieldData('oxobjectid')) {
                $product = $this->repository->getById(
                    $objectId,
                    Product::class
                );
            }
        } catch (NotFound $e) {
            return null;
        } catch (InvalidLogin $e) {
            return null;
        }

        return $product;
    }
}
