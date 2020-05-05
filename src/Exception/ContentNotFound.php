<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Exception;

use OxidEsales\GraphQL\Base\Exception\NotFound;

class ContentNotFound extends NotFound
{
    /**
     * @param string $id
     * @return self
     */
    public static function byId(string $id): self
    {
        return new self(sprintf('Content was not found by id: %s', $id));
    }
}
