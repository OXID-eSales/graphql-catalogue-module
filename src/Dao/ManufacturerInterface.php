<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Dao;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Catalogue\DataType\Manufacturer as ManufacturerDataType;
use OxidEsales\GraphQL\Catalogue\DataType\ManufacturerFilter;

interface ManufacturerInterface
{
    /**
     * @return ManufacturerDataType[]
     */
    public function getManufacturers(ManufacturerFilter $filter): array;

    public function getManufacturer(string $id): ManufacturerDataType;
}
