<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\Eshop\Application\Model\Actions as EshopActionsModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type()
 */
final class Banner implements DataType
{
    public const ACTION_TYPE = '3';

    /** @var EshopActionsModel */
    private $actionsModel;

    public function __construct(EshopActionsModel $actionsModel)
    {
        $this->actionsModel = $actionsModel;

        if ($actionsModel->getFieldData('oxtype') !== self::ACTION_TYPE) {
            throw new \OxidEsales\GraphQL\Catalogue\Exception\WrongType();
        }
    }

    public static function getModelClass(): string
    {
        return EshopActionsModel::class;
    }

    public function getEshopModel(): EshopActionsModel
    {
        return $this->actionsModel;
    }

    /**
     * @Field()
     */
    public function getId(): ID
    {
        return new ID($this->actionsModel->getId());
    }

    /**
     * @Field()
     *
     * @todo: remove $now param as its needed for tests only
     * @todo: extract this method as its duplicated
     */
    public function isActive(?\DateTimeInterface $now = null): bool
    {
        $active = (bool)$this->actionsModel->getFieldData('oxactive');
        if ($active) {
            return true;
        }

        $from = new \DateTimeImmutable(
            (string)$this->actionsModel->getFieldData('oxactivefrom')
        );
        $to = new \DateTimeImmutable(
            (string)$this->actionsModel->getFieldData('oxactiveto')
        );
        $now = $now ?? new \DateTimeImmutable("now");
        if ($from <= $now && $to >= $now) {
            return true;
        }

        return false;
    }

    /**
     * @Field()
     */
    public function getTitle(): string
    {
        return (string)$this->actionsModel->getFieldData('oxtitle');
    }

    /**
     * @Field()
     */
    public function getPicture(): string
    {
        return (string)$this->actionsModel->getBannerPictureUrl();
    }

    /**
     * @Field()
     */
    public function getLink(): string
    {
        return (string)$this->actionsModel->getBannerLink();
    }

    /**
     * @Field()
     */
    public function getSorting(): int
    {
        return (int)$this->actionsModel->getFieldData('oxsort');
    }
}
