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
use OxidEsales\GraphQL\Catalogue\DataType\Banner as BannerDataType;
use OxidEsales\GraphQL\Catalogue\Exception\BannerNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Banner extends Base
{
    /**
     * @Query()
     *
     * @throws BannerNotFound
     *
     * @throws InvalidLogin
     */
    public function banner(string $id): BannerDataType
    {
        try {
            /** @var BannerDataType $banner */
            $banner = $this->repository->getById($id, BannerDataType::class);
        } catch (NotFound $e) {
            throw BannerNotFound::byId($id);
        }

        if ($banner->isActive()) {
            return $banner;
        }

        if (!$this->isAuthorized('VIEW_INACTIVE_BANNER')) {
            throw new InvalidLogin("Unauthorized");
        }

        return $banner;
    }

    /**
     * @Query()
     *
     * @return BannerDataType[]
     */
    public function banners(): array
    {
        /** @var ActionList $actionList */
        $actionList = oxNew(ActionList::class);
        $actionList->loadBanners();

        $result = [];
        if ($banners = $actionList->getArray()) {
            foreach ($banners as $oneBannerModelItem) {
                $result[] = new \OxidEsales\GraphQL\Catalogue\DataType\Banner($oneBannerModelItem);
            }
        }

        return $result;
    }
}
