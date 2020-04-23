<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\Eshop\Application\Model\Article as EshopProductModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
class ProductDimensions
{
    /** @var EshopProductModel */
    private $product;

    public function __construct(
        EshopProductModel $product
    ) {
        $this->product = $product;
    }

    /**
     * @Field
     */
    public function getLength(): float
    {
        return (float) $this->product->getFieldData('oxlength');
    }

    /**
     * @Field
     */
    public function getWidth(): float
    {
        return (float) $this->product->getFieldData('oxwidth');
    }

    /**
     * @Field
     */
    public function getHeight(): float
    {
        return (float) $this->product->getFieldData('oxheight');
    }

    /**
     * @Field
     */
    public function getWeight(): float
    {
        return (float) $this->product->getFieldData('oxweight');
    }
}
