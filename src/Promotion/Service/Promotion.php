<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Promotion\Service;

use OxidEsales\Eshop\Application\Model\ActionList;
use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Base\Service\Authorization;
use OxidEsales\GraphQL\Catalogue\Promotion\DataType\Promotion as PromotionDataType;
use OxidEsales\GraphQL\Catalogue\Promotion\Exception\PromotionNotFound;
use OxidEsales\GraphQL\Catalogue\Shared\Infrastructure\Repository;

final class Promotion
{
    /** @var Repository */
    private $repository;

    /** @var Authorization */
    private $authorizationService;

    public function __construct(
        Repository $repository,
        Authorization $authorizationService
    ) {
        $this->repository           = $repository;
        $this->authorizationService = $authorizationService;
    }

    /**
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

        if (!$this->authorizationService->isAllowed('VIEW_INACTIVE_PROMOTION')) {
            throw new InvalidLogin('Unauthorized');
        }

        return $promotion;
    }

    /**
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
                $result[] = new PromotionDataType($promotion);
            }
        }

        return $result;
    }
}
