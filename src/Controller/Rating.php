<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Rating as RatingDataType;
use OxidEsales\GraphQL\Catalogue\Exception\RatingNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Rating extends Base
{
    /**
     * @Query()
     *
     * @throws RatingNotFound
     */
    public function rating(string $id): RatingDataType
    {
        try {
            /** @var RatingDataType $rating */
            $rating = $this->repository->getById(
                $id,
                RatingDataType::class
            );
        } catch (NotFound $e) {
            throw RatingNotFound::byId($id);
        }

        return $rating;
    }
}
