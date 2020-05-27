<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Catalogue\DataType\Manufacturer as ManufacturerDataType;
use OxidEsales\GraphQL\Catalogue\DataType\ManufacturerFilterList;
use OxidEsales\GraphQL\Catalogue\Service\Manufacturer as ManufacturerService;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class Manufacturer
{
    /** @var ManufacturerService */
    private $manufacturerService = null;

    public function __construct(
        ManufacturerService $manufacturerService
    ) {
        $this->manufacturerService = $manufacturerService;
    }

    /**
     * @Query()
     */
    public function manufacturer(string $id): ManufacturerDataType
    {
        return $this->manufacturerService->manufacturer($id);
    }

    /**
     * @Query()
     *
     * @return ManufacturerDataType[]
     */
    public function manufacturers(?ManufacturerFilterList $filter = null): array
    {
        return $this->manufacturerService->manufacturers(
            $filter ?? new ManufacturerFilterList()
        );
    }
}
