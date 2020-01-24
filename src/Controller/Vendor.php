<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Service\AuthenticationServiceInterface;
use OxidEsales\GraphQL\Base\Service\AuthorizationServiceInterface;
use OxidEsales\GraphQL\Catalogue\DataType\Vendor as VendorModel;
use OxidEsales\GraphQL\Catalogue\DataType\VendorFilter;
use OxidEsales\GraphQL\Catalogue\Exception\VendorNotFound;
use OxidEsales\GraphQL\Catalogue\Service\VendorRepository;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Vendor
{
    /** @var VendorRepository */
    protected $repository;

    /** @var AuthenticationServiceInterface */
    protected $authenticationService;

    /** @var AuthorizationServiceInterface */
    protected $authorizationService;

    public function __construct(
        VendorRepository $repository,
        AuthenticationServiceInterface $authenticationService,
        AuthorizationServiceInterface $authorizationService
    ) {
        $this->repository = $repository;
        $this->authenticationService = $authenticationService;
        $this->authorizationService = $authorizationService;
    }

    /**
     * @Query()
     *
     * @throws VendorNotFound
     */
    public function vendor(string $id): VendorModel
    {
        $vendor = $this->repository->getVendor($id);

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

    /**
     * @Query()
     * @return VendorModel[]
     */
    public function vendors(?VendorFilter $filter = null): array
    {
        try {
            $vendors = $this->repository->getVendors(
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

}
