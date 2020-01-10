<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Dao;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Catalogue\DataObject\Manufacturer as ManufacturerModel;
use OxidEsales\GraphQL\Catalogue\DataObject\ManufacturerFilter;

interface ManufacturerInterface
{
    /**
     * @return ManufacturerModel[]
     */
    public function getManufacturers(ManufacturerFilter $filter): array;

    public function getManufacturer(string $id): ManufacturerModel;
}
