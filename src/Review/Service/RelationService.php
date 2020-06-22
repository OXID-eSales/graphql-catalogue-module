<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Review\Service;

use OxidEsales\GraphQL\Catalogue\Product\DataType\Product;
use OxidEsales\GraphQL\Catalogue\Product\Service\Product as ProductService;
use OxidEsales\GraphQL\Catalogue\Review\DataType\Review;
use OxidEsales\GraphQL\Catalogue\Review\DataType\Reviewer;
use OxidEsales\GraphQL\Catalogue\Review\Service\Reviewer as ReviewerService;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Review::class)
 */
final class RelationService
{
    /** @var ProductService */
    private $productService;

    /** @var ReviewerService */
    private $reviewerService;

    public function __construct(
        ProductService $productService,
        ReviewerService $reviewerService
    ) {
        $this->productService     = $productService;
        $this->reviewerService    = $reviewerService;
    }

    /**
     * @Field()
     */
    public function getReviewer(Review $review): ?Reviewer
    {
        $reviewerId = (string) $review->getReviewerId();

        return $this->reviewerService->reviewer($reviewerId);
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
