<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Base\Service\Legacy;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Review::class)
 */
class ReviewActivityService
{
    /** @var Legacy */
    private $legacyService;

    public function __construct(Legacy $legacyService)
    {
        $this->legacyService = $legacyService;
    }

    /**
     * @Field()
     */
    public function isActive(Review $review): bool
    {
        $reviewModel = $review->getEshopModel();
        $moderationIsActive = (bool)$this->legacyService->getConfigParam('blGBModerate');
        $reviewActiveFieldValue = (bool)$reviewModel->getFieldData('oxactive');
        return  $reviewActiveFieldValue || !$moderationIsActive;
    }
}
