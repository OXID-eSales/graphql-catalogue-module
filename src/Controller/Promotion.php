<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\Eshop\Application\Model\ActionList;
use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Promotion as PromotionDataType;
use OxidEsales\GraphQL\Catalogue\Exception\PromotionNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Promotion extends Base
{
    /**
     * @Query()
     *
     * @return PromotionDataType
     *
     * @throws PromotionNotFound
     * @throws InvalidLogin
     */
    public function promotion(string $id): PromotionDataType
    {
        try {
            /** @var PromotionDataType $promotion */
            $promotion = $this->repository->getById(
                $id,
                PromotionDataType::class
            );
        } catch (NotFound $e) {
            throw PromotionNotFound::byId($id);
        }

        if ($promotion->isActive()) {
            return $promotion;
        }

        if (!$this->isAuthorized('VIEW_INACTIVE_PROMOTION')) {
            throw new InvalidLogin("Unauthorized");
        }

        return $promotion;
    }

    /**
     * @Query()
     *
     * @return PromotionDataType[]
     */
    public function promotions(): array
    {
        /** @var ActionList $actionList */
        $actionList = oxNew(ActionList::class);
        $actionList->loadCurrent();

        $result = [];
        if ($promotions = $actionList->getArray()) {
            foreach ($promotions as $promotion) {
                $result[] = new \OxidEsales\GraphQL\Catalogue\DataType\Promotion($promotion);
            }
        }
        return $result;
    }
}
