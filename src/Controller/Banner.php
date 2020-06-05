<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Banner as BannerDataType;
use OxidEsales\GraphQL\Catalogue\Exception\BannerNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;
use OxidEsales\GraphQL\Catalogue\Service\Banner as BannerService;

class Banner
{
    /** @var BannerService */
    private $bannerService = null;

    public function __construct(
        BannerService $bannerService
    ) {
        $this->bannerService = $bannerService;
    }

    /**
     * @Query()
     *
     * @throws BannerNotFound
     *
     * @throws InvalidLogin
     */
    public function banner(string $id): BannerDataType
    {
        return $this->bannerService->banner($id);
    }

    /**
     * @Query()
     *
     * @return BannerDataType[]
     */
    public function banners(): array
    {
        return $this->bannerService->banners();
    }
}
