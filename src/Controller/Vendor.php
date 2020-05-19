<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Vendor as VendorDataType;
use OxidEsales\GraphQL\Catalogue\DataType\VendorFilterList;
use OxidEsales\GraphQL\Catalogue\Exception\VendorNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Vendor extends Base
{
    /**
     * @Query()
     *
     * @throws VendorNotFound
     * @throws InvalidLogin
     */
    public function vendor(string $id): VendorDataType
    {
        try {
            $vendor = $this->repository->getById(
                $id,
                VendorDataType::class
            );
        } catch (NotFound $e) {
            throw VendorNotFound::byId($id);
        }

        return $vendor;
    }

    /**
     * @Query()
     * @return VendorDataType[]
     */
    public function vendors(?VendorFilterList $filter = null): array
    {
        $filter = $filter ?? new VendorFilterList();

        $vendors = $this->repository->getByFilter(
            $filter,
            VendorDataType::class
        );

        return $vendors;
    }
}
