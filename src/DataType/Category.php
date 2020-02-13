<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use DateTimeImmutable;
use DateTimeInterface;
use OxidEsales\Eshop\Application\Model\Category as CategoryModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type()
 */
final class Category implements DataType
{
    /** @var CategoryModel */
    private $category;

    public function __construct(CategoryModel $category)
    {
        $this->category = $category;
    }

    /**
     * @return class-string
     */
    public static function getModelClass(): string
    {
        return CategoryModel::class;
    }

    /**
     * @Field()
     */
    public function getId(): ID
    {
        return new ID(
            $this->category->getId()
        );
    }

    public function getParentId(): ID
    {
        return new ID(
            (string)$this->category->getFieldData('oxparentid')
        );
    }

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
     */
    public function getPosition(): int
    {
        return (int)$this->category->getFieldData('oxsort');
    }

    /**
     * @Field()
     */
    public function isActive(?\DateTimeInterface $now = null): bool
    {
        $active = (bool)$this->category->getFieldData('oxactive');
        if ($active) {
            return true;
        }

        $from = new \DateTimeImmutable(
            (string)$this->category->getFieldData('oxactivefrom')
        );
        $to = new \DateTimeImmutable(
            (string)$this->category->getFieldData('oxactiveto')
        );
        $now = $now ?? new \DateTimeImmutable("now");
        if ($from <= $now && $to >= $now) {
            return true;
        }

        return false;
    }

    /**
     * Hidden categories are not visible in lists and menu,
     * but can be accessed by direct link
     *
     * @Field()
     */
    public function isHidden(): bool
    {
        return !$this->category->getIsVisible();
    }

    /**
     * @Field()
     */
    public function getTitle(): string
    {
        return $this->category->getTitle();
    }

    /**
     * @Field()
     */
    public function getShortDescription(): string
    {
        return $this->category->getShortDescription();
    }

    /**
     * @Field()
     */
    public function getLongDescription(): string
    {
        return $this->category->getLongDesc();
    }

    /**
     * @Field()
     */
    public function getThumbnail(): ?string
    {
        return $this->category->getThumbUrl();
    }

    /**
     * If the external link is specified it will be opened instead of category content
     *
     * @Field()
     */
    public function getExternalLink(): string
    {
        return (string)$this->category->getFieldData('oxextlink');
    }

    /**
     * @Field()
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
     */
    public function getDefaultSortField(): string
    {
        return $this->category->getDefaultSorting();
    }

    /**
     * With default field for sorting you specify the manner
     * in which the products in the category will be sorted
     * (ASC or DESC)
     *
     * @Field()
     */
    public function getDefaultSortMode(): string
    {
        return $this->category->getDefaultSortingMode() ? 'DESC' : 'ASC';
    }

    /**
     * If specified, all products, with price higher than specified,
     * will be shown in this category
     *
     * @Field()
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
     */
    public function getPriceTo(): float
    {
        return (float)$this->category->getFieldData('oxpriceto');
    }

    /**
     * @Field()
     */
    public function getIcon(): ?string
    {
        return $this->category->getIconUrl();
    }

    /**
     * @Field()
     */
    public function getPromotionIcon(): ?string
    {
        return $this->category->getPromotionIconUrl();
    }

    /**
     * @Field()
     */
    public function getVat(): ?float
    {
        $vat = $this->category->getFieldData('oxvat');
        return is_null($vat) ? $vat : (float)$vat;
    }

    /**
     * Skip all negative discounts for products in this category
     * (Discounts, Vouchers, Delivery ...)
     *
     * @Field()
     */
    public function skipDiscount(): bool
    {
        return (bool)$this->category->getFieldData('oxskipdiscounts');
    }

    /**
     * @Field()
     */
    public function showSuffix(): bool
    {
        return (bool)$this->category->getFieldData('oxshowsuffix');
    }

    /**
     * @Field()
     *
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
     */
    public function getUrl(): string
    {
        return $this->category->getLink();
    }
}
