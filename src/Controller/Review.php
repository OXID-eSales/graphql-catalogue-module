<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Catalogue\DataType\Review as ReviewDataType;
use OxidEsales\GraphQL\Catalogue\Service\Review as ReviewService;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Review
{
    /** @var ReviewService */
    private $reviewService = null;

    public function __construct(
        ReviewService $reviewService
    ) {
        $this->reviewService = $reviewService;
    }

    /**
     * @Query()
     */
    public function review(string $id): ReviewDataType
    {
        return $this->reviewService->review($id);
    }
}
