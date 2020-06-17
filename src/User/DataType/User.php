<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\User\DataType;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type()
 */
class User extends Reviewer
{
    /**
     * @Field()
     */
    public function getId(): ID
    {
        return new ID($this->user->getId());
    }

    /**
     * @Field()
     */
    public function getLastName(): string
    {
        return (string) $this->user->getFieldData('oxlname');
    }

    /**
     * @Field()
     */
    public function getUserName(): string
    {
        return (string) $this->user->getFieldData('oxusername');
    }
}
