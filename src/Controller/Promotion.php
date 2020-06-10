<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Catalogue\DataType\Promotion as PromotionDataType;
use OxidEsales\GraphQL\Catalogue\Service\Promotion as PromotionService;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Promotion
{
    /** @var PromotionService */
    private $promotionService = null;

    public function __construct(
        PromotionService $promotionService
    ) {
        $this->promotionService = $promotionService;
    }

    /**
     * @Query()
     *
     * @return PromotionDataType
     */
    public function promotion(string $id): PromotionDataType
    {
        return $this->promotionService->promotion($id);
    }

    /**
     * @Query()
     *
     * @return PromotionDataType[]
     */
    public function promotions(): array
    {
        return $this->promotionService->promotions();
    }
}
