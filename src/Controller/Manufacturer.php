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
use OxidEsales\GraphQL\Catalogue\Dao\ManufacturerInterface as ManufacturerDao;
use OxidEsales\GraphQL\Catalogue\DataType\Manufacturer as ManufacturerDataType;
use OxidEsales\GraphQL\Catalogue\DataType\ManufacturerFilter;
use OxidEsales\GraphQL\Catalogue\Exception\ManufacturerNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Manufacturer
{
    /** @var ManufacturerDao */
    protected $manufacturerDao;

    /** @var AuthorizationServiceInterface */
    protected $authorizationService;

    /** @var AuthenticationServiceInterface */
    protected $authenticationService;

    public function __construct(
        ManufacturerDao $manufacturerDao,
        AuthorizationServiceInterface $authorizationService,
        AuthenticationServiceInterface $authenticationService
    ) {
        $this->manufacturerDao = $manufacturerDao;
        $this->authorizationService = $authorizationService;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @Query()
     */
    public function manufacturer(string $id): ManufacturerDataType
    {
        $manufacturer = $this->manufacturerDao->getManufacturer($id);

        if (
            $manufacturer->getActive() ||
            (
                $this->authenticationService->isLogged() &&
                $this->authorizationService->isAllowed('VIEW_INACTIVE_MANUFACTURER')
            )
        ) {
            return $manufacturer;
        }

        throw new InvalidLogin("Unauthorized");
    }

    /**
     * @Query()
     * @return ManufacturerDataType[]
     */
    public function manufacturers(?ManufacturerFilter $filter = null): array
    {
        try {
            $manufacturers = $this->manufacturerDao->getManufacturers(
                $filter ?? new ManufacturerFilter()
            );
        } catch (\Exception $e) {
            return [];
        }

        // In case of missing permissions
        // to see inactive manufacturers
        // only return active ones
        if (
            !$this->authenticationService->isLogged() ||
            !$this->authorizationService->isAllowed('VIEW_INACTIVE_MANUFACTURER')
        ) {
            $manufacturers = array_filter(
                $manufacturers,
                function (ManufacturerDataType $manufacturer) {
                    return $manufacturer->getActive();
                }
            );
        }

        return $manufacturers;
    }
}
