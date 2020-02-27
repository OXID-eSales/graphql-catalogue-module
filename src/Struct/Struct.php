<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Struct;

abstract class Struct
{
    /**
     * Struct constructor. Assigns the values in the $values array to the object properties based on the array keys.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        //This is called only if the property we try to set does not exist in the current object
        //For now we just skip unknown fields without throwing exception or showing error
    }
}
