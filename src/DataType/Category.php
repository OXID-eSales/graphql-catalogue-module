<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use DateTimeImmutable;
use DateTimeInterface;
use OxidEsales\EshopCommunity\Application\Model\Category as CategoryModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type()
 */
final class Category
{
    /** @var CategoryModel */
    private $category;

    /**
     * Category constructor.
     *
     * @param CategoryModel $category
     */
    public function __construct(CategoryModel $category)
    {
        $this->category = $category;
    }

    /**
     * @return CategoryModel
     */
    public function getCategoryModel(): CategoryModel
    {
        return $this->category;
    }

    /**
     * @Field()
     *
     * @return ID
     */
    public function getId(): ID
    {
        return new ID(
            $this->category->getId()
        );
    }

    /**
     * @return ID
     */
    public function getParentId(): ID
    {
        return new ID(
            (string)$this->category->getFieldData('oxparentid')
        );
    }

    /**
     * @return ID
     */
    public function getRootId(): ID
    {
        return new ID(
            (string)$this->category->getFieldData('oxrootid')
        );
    }

    /**
     * Defines the order in which categories are displayed:
     * The category with the lowest number is displayed at the top,
     * and the category with the highest number at the bottom
     *
     * @Field()
     *
     * @return int
     */
    public function getPosition(): int
    {
        return (int)$this->category->getFieldData('oxsort');
    }

    /**
     * @Field()
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)$this->category->getFieldData('oxactive');
    }

    /**
     * Hidden categories are not visible in lists and menu,
     * but can be accessed by direct link
     *
     * @Field()
     *
     * @return bool
     */
    public function isHidden(): bool
    {
        return (bool)$this->category->getFieldData('oxhidden');
    }

    /**
     * @return ID
     */
    public function getShopId(): ID
    {
        return new ID(
            (int)$this->category->getFieldData('oxshopid')
        );
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getTitle(): string
    {
        return (string)$this->category->getFieldData('oxtitle');
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->category->getFieldData('oxdesc');
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getLongDescription(): string
    {
        return (string)$this->category->getFieldData('oxlongdesc');
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getThumbnail(): string
    {
        return (string)$this->category->getFieldData('oxthumb');
    }

    /**
     * If the external link is specified it will be opened instead of category content
     *
     * @Field()
     *
     * @return string
     */
    public function getExternalLink(): string
    {
        return (string)$this->category->getFieldData('oxextlink');
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return (string)$this->category->getFieldData('oxtemplate');
    }

    /**
     * Default field for sorting of products in this category
     * (most of oxarticles fields)
     *
     * @Field()
     *
     * @return string
     */
    public function getDefaultSortField(): string
    {
        return (string)$this->category->getFieldData('oxdefsort');
    }

    /**
     * With default field for sorting you specify the manner
     * in which the products in the category will be sorted
     * (ASC - false, DESC - true)
     *
     * @Field()
     *
     * @return bool
     */
    public function getDefaultSortMode(): bool
    {
        return (bool)$this->category->getFieldData('oxdefsortmode');
    }

    /**
     * If specified, all products, with price higher than specified,
     * will be shown in this category
     *
     * @Field()
     *
     * @return float
     */
    public function getPriceFrom(): float
    {
        return (float)$this->category->getFieldData('oxpricefrom');
    }

    /**
     * If specified, all products, with price lower than specified,
     * will be shown in this category
     *
     * @Field()
     *
     * @return float
     */
    public function getPriceTo(): float
    {
        return (float)$this->category->getFieldData('oxpriceto');
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getIcon(): string
    {
        return (string)$this->category->getFieldData('oxicon');
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getPromotionIcon(): string
    {
        return (string)$this->category->getFieldData('oxpromoicon');
    }

    /**
     * @Field()
     *
     * @return float|null
     */
    public function getVat(): ?float
    {
        $vat = $this->category->getFieldData('oxvat');
        return isset($vat) ? (float)$vat : $vat;
    }

    /**
     * Skip all negative discounts for products in this category
     * (Discounts, Vouchers, Delivery ...)
     *
     * @Field()
     *
     * @return bool
     */
    public function skipDiscount(): bool
    {
        return (bool)$this->category->getFieldData('oxskipdiscounts');
    }

    /**
     * @Field()
     *
     * @return bool
     */
    public function showSuffix(): bool
    {
        return (bool)$this->category->getFieldData('oxshowsuffix');
    }

    /**
     * @Field()
     *
     * @return DateTimeInterface
     * @throws \Exception
     */
    public function getTimestamp(): DateTimeInterface
    {
        return new DateTimeImmutable(
            $this->category->getFieldData('oxtimestamp')
        );
    }

    /**
     * @Field
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->category->getLink();
    }
}
