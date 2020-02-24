<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use DateTimeImmutable;
use DateTimeInterface;
use OxidEsales\Eshop\Application\Model\Manufacturer as ManufacturerModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type()
 */
class Manufacturer implements DataType
{
    /** @var ManufacturerModel */
    private $manufacturer;

    public function __construct(
        ManufacturerModel $manufacturer
    ) {
        $this->manufacturer = $manufacturer;
    }

    /**
     * @return class-string
     */
    public static function getModelClass(): string
    {
        return ManufacturerModel::class;
    }

    /**
     * @Field()
     */
    public function getId(): ID
    {
        return new ID($this->manufacturer->getId());
    }

    /**
     * @Field()
     */
    public function isActive(): bool
    {
        return (bool)$this->manufacturer->getFieldData('oxactive');
    }

    /**
     * @Field()
     */
    public function getIcon(): ?string
    {
        return $this->manufacturer->getIconUrl();
    }

    /**
     * @Field()
     */
    public function getTitle(): string
    {
        return $this->manufacturer->getTitle();
    }

    /**
     * @Field()
     */
    public function getShortdesc(): string
    {
        return $this->manufacturer->getShortDescription();
    }

    /**
     * @Field()
     */
    public function getUrl(): string
    {
        return $this->manufacturer->getLink();
    }

    /**
     * @Field()
     */
    public function getTimestamp(): DateTimeInterface
    {
        return new DateTimeImmutable((string)$this->manufacturer->getFieldData('oxtimestamp'));
    }
}
