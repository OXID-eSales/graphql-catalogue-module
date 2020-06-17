<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\User\DataType;

use OxidEsales\Eshop\Application\Model\User as EshopUserModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
class Reviewer
{
    /** @var EshopUserModel */
    protected $user;

    public function __construct(EshopUserModel $user)
    {
        $this->user = $user;
    }

    /**
     * @Field()
     */
    public function getFirstName(): string
    {
        return (string) $this->user->getFieldData('oxfname');
    }

    public static function getModelClass(): string
    {
        return EshopUserModel::class;
    }
}
