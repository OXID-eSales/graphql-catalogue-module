<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Base\Service\AuthenticationServiceInterface;
use OxidEsales\GraphQL\Base\Service\AuthorizationServiceInterface;
use OxidEsales\GraphQL\Catalogue\DataType\VendorFilter;
use TheCodingMachine\GraphQLite\Annotations\Query;
use OxidEsales\GraphQL\Catalogue\Dao\VendorInterface as VendorDao;
use OxidEsales\GraphQL\Catalogue\DataType\Vendor as VendorModel;

class Vendor
{
    /** @var VendorDao */
    protected $vendorDao;

    /** @var AuthenticationServiceInterface */
    protected $authenticationService;

    /** @var AuthorizationServiceInterface */
    protected $authorizationService;

    public function __construct(
        VendorDao $vendorDao,
        AuthenticationServiceInterface $authenticationService,
        AuthorizationServiceInterface $authorizationService
    ) {
        $this->vendorDao = $vendorDao;
        $this->authenticationService = $authenticationService;
        $this->authorizationService = $authorizationService;
    }

    /**
     * @Query()
     * @return VendorModel[]
     */
    public function vendors(?VendorFilter $filter = null): array
    {
        try {
            $vendors = $this->vendorDao->getVendors(
                $filter ?? new VendorFilter()
            );
        } catch (\Exception $e) {
            return [];
        }

        // In case of missing permissions
        // only return active vendors
        if (
            !$this->authenticationService->isLogged() ||
            !$this->authorizationService->isAllowed('VIEW_INACTIVE_VENDOR')
        ) {
            $vendors = array_filter(
                $vendors,
                function (VendorModel $vendor) {
                    return $vendor->getActive();
                }
            );
        }

        return $vendors;
    }

    /**
     * @Query()
     * @param string $id
     *
     * @return null|VendorModel
     *
     * @throws NotFound
     * @throws InvalidLogin
     */
    public function vendor(string $id): ?VendorModel
    {
        try {
            $vendor = $this->vendorDao->getVendor($id);
        } catch (\Exception $e) {
            throw new NotFound();
        }

        if (!$vendor instanceof VendorModel) {
            throw new NotFound();
        }

        if ($vendor->getActive()) {
            return $vendor;
        }

        if (
            !$this->authenticationService->isLogged() ||
            !$this->authorizationService->isAllowed('VIEW_INACTIVE_VENDOR')
        ) {
            throw new InvalidLogin("Unauthorized");
        }

        return $vendor;
    }
}
