<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\Eshop\Application\Model\Attribute as EshopAttributeModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
final class ProductAttribute implements DataType
{
    /** @var EshopAttributeModel */
    private $attribute;

    public function __construct(EshopAttributeModel $attribute)
    {
        $this->attribute = $attribute;
    }

    public static function getModelClass(): string
    {
        return EshopAttributeModel::class;
    }

    /**
     * @Field()
     */
    public function getAttribute(): Attribute
    {
        return new Attribute($this->attribute);
    }

    /**
     * @Field()
     */
    public function getValue(): string
    {
        return (string) $this->attribute->getFieldData('oxvalue');
    }
}
