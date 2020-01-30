<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Manufacturer as ManufacturerDataType;
use OxidEsales\GraphQL\Catalogue\DataType\ManufacturerFilterList;
use OxidEsales\GraphQL\Catalogue\Exception\ManufacturerNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Manufacturer extends Base
{
    /**
     * @Query()
     *
     * @return ManufacturerDataType
     *
     * @throws ManufacturerNotFound
     * @throws InvalidLogin
     */
    public function manufacturer(string $id): ManufacturerDataType
    {
        try {
            /** @var ManufacturerDataType $manufacturer */
            $manufacturer = $this->repository->getById(
                $id,
                ManufacturerDataType::class
            );
        } catch (NotFound $e) {
            throw ManufacturerNotFound::byId($id);
        }

        if ($manufacturer->getActive()) {
            return $manufacturer;
        }

        if (!$this->isAuthorized('VIEW_INACTIVE_MANUFACTURER')) {
            throw new InvalidLogin("Unauthorized");
        }

        return $manufacturer;
    }

    /**
     * @Query()
     *
     * @return ManufacturerDataType[]
     */
    public function manufacturers(?ManufacturerFilterList $filter = null): array
    {
        $filter = $filter ?? new ManufacturerFilterList();
        // In case of missing permissions
        // only return active vendors
        if (!$this->isAuthorized('VIEW_INACTIVE_MANUFACTURER')) {
            $filter = $filter->withActiveFilter(
                new \OxidEsales\GraphQL\Base\DataType\BoolFilter(true)
            );
        }

        try {
            $manufacturers = $this->repository->getByFilter(
                $filter,
                ManufacturerDataType::class
            );
        } catch (\Exception $e) {
            return [];
        }

        return $manufacturers;
    }
}
