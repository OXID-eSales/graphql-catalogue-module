<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use OxidEsales\Eshop\Application\Model\User as EshopUserModel;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type()
 */
final class User implements DataType
{
    /** @var EshopUserModel */
    private $user;

    public function __construct(EshopUserModel $user)
    {
        $this->user = $user;
    }

    public static function getModelClass(): string
    {
        return EshopUserModel::class;
    }

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
    public function getFirstName(): string
    {
        return (string) $this->user->getFieldData('oxfname');
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
