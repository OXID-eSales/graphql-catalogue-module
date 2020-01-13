<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLoginException;
use OxidEsales\GraphQL\Base\Exception\NotFoundException;
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
     * @Query()
     */
    public function manufacturer(string $id): ManufacturerModel
    {
        try {
            $manufacturer = $this->manufacturerDao->getManufacturer(
                $id
            );
        } catch (\OutOfBoundsException $e) {
            throw new NotFoundException('Manufacturer could not be found');
        }
        if ($manufacturer->getActive()) {
            return $manufacturer;
        }
        throw new InvalidLoginException("Unauthorized");
    }
}
