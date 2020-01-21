<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Exception;

use OxidEsales\GraphQL\Base\Exception\NotFound;

class ManufacturerNotFound extends NotFound
{

    /** @var string */
    private $id;

    /**
     * @param string $id
     * @return static
     */
    public static function byId(string $id): self
    {
        $ex = new self(sprintf('Manufacturer was not found by id: %s', $id));
        $ex->id = $id;

        return $ex;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }
}
