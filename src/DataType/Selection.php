<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\Eshop\Application\Model\Selection as EshopSelectionModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
final class Selection implements DataType
{
    /** @var EshopSelectionModel */
    private $selection;

    /**
     * Selection constructor.
     *
     * @param EshopSelectionModel $selection
     */
    public function __construct(EshopSelectionModel $selection)
    {
        $this->selection = $selection;
    }

    /**
     * @return string
     */
    public static function getModelClass(): string
    {
        return EshopSelectionModel::class;
    }

    /**
     * @Field()
     */
    public function getName(): string
    {
        return (string)$this->selection->getName();
    }

    /**
     * @Field()
     */
    public function getValue(): int
    {
        return (int)$this->selection->getValue();
    }

    /**
     * @Field()
     */
    public function isActive(): bool
    {
        return (bool)$this->selection->isActive();
    }

    /**
     * @Field()
     */
    public function isDisabled(): bool
    {
        return (bool)$this->selection->isDisabled();
    }
}
