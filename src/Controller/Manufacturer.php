<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Catalogue\Dao\ManufacturerInterface as ManufacturerDao;
use OxidEsales\GraphQL\Catalogue\DataObject\Manufacturer as ManufacturerModel;
use OxidEsales\GraphQL\Catalogue\DataObject\ManufacturerFilter;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Manufacturer
{
    /** @var ManufacturerDao */
    protected $manufacturerDao;

    public function __construct(
        ManufacturerDao $manufacturerDao
    ) {
        $this->manufacturerDao = $manufacturerDao;
    }

    /**
     * List of order amounts per day
     *
     * @Query()
     *
     * @return ManufacturerModel[]
     */
    public function manufacturer(ManufacturerFilter $filter): array
    {
        return $this->manufacturerDao->getManufacturer(
            $filter
        );
    }
}
