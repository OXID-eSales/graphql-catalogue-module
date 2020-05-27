<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Catalogue\DataType\Vendor as VendorDataType;
use OxidEsales\GraphQL\Catalogue\DataType\VendorFilterList;
use OxidEsales\GraphQL\Catalogue\Exception\VendorNotFound;
use OxidEsales\GraphQL\Catalogue\Service\Vendor as VendorService;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class Vendor
{
    /** @var VendorService */
    private $vendorService = null;

    public function __construct(
        VendorService $vendorService
    ) {
        $this->vendorService = $vendorService;
    }

    /**
     * @Query()
     *
     * @throws VendorNotFound
     * @throws InvalidLogin
     */
    public function vendor(string $id): VendorDataType
    {
        return $this->vendorService->vendor($id);
    }

    /**
     * @Query()
     * @return VendorDataType[]
     */
    public function vendors(?VendorFilterList $filter = null): array
    {
        return $this->vendorService->vendors(
            $filter ?? new VendorFilterList()
        );
    }
}
