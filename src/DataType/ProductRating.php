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
 * @TODO the individual ratings should be named votes then
 */
class ProductRating implements DataType
{
    /** @var EshopProductModel */
    private $product;

    public function __construct(
        EshopProductModel $product
    ) {
        $this->product = $product;
    }

    public function getEshopModel(): EshopProductModel
    {
        return $this->product;
    }

    /**
     * @return class-string
     */
    public static function getModelClass(): string
    {
        return EshopProductModel::class;
    }

    /**
     * @Field
     */
    public function getRating(): float
    {
        return $this->product->getArticleRatingAverage(false);
    }

    /**
     * @Field
     */
    public function getCount(): int
    {
        /**
         * the upstream typehint is wrongly stated as double
         * @var int
         */
        return (int)$this->product->getArticleRatingCount(false);
    }
}
