<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

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
        $userId = (string)$review->getEshopModel()->getFieldData('oxuserid');

        if (!strlen($userId)) {
            return null;
        }

        try {
            $user = $this->repository->getById(
                $userId,
                User::class
            );
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

        $objectId = (string)$reviewModel->getFieldData('oxobjectid');

        if (!strlen($objectId)) {
            return null;
        }

        try {
            $product = $this->repository->getById(
                $objectId,
                Product::class
            );
        } catch (NotFound $e) {
            return null;
        }

        return $product;
    }
}
