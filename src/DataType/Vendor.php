<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\Eshop\Application\Model\Vendor as VendorModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * @Type()
 */
class Vendor implements DataType
{
    /** @var VendorModel */
    private $vendor;

    public function __construct(
        VendorModel $vendor
    ) {
        $this->vendor = $vendor;
    }

    /**
     * @return class-string
     */
    public static function getModelClass(): string
    {
        return VendorModel::class;
    }

    /**
     * @Field()
     */
    public function getId(): ID
    {
        return new ID($this->vendor->getId());
    }

    /**
     * @Field()
     */
    public function isActive(): bool
    {
        return (bool)$this->vendor->getFieldData('oxactive');
    }

    /**
     * @Field()
     */
    public function getIcon(): ?string
    {
        return $this->vendor->getIconUrl();
    }

    /**
     * @Field()
     */
    public function getTitle(): string
    {
        return $this->vendor->getTitle();
    }

    /**
     * @Field()
     */
    public function getShortdesc(): string
    {
        return $this->vendor->getShortDescription();
    }

    /**
     * @Field()
     */
    public function getUrl(): string
    {
        return $this->vendor->getLink();
    }

    /**
     * @Field()
     */
    public function getTimestamp(): DateTimeInterface
    {
        return new DateTimeImmutable((string)$this->vendor->getFieldData('oxtimestamp'));
    }
}
