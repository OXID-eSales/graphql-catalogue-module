<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\EshopCommunity\Application\Model\Actions as EshopActionsModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type()
 */
class Promotion implements DataType
{
    public const PROMOTION_ACTION_TYPE = '2';

    /** @var EshopActionsModel */
    private $actionsModel;

    public function __construct(EshopActionsModel $actionsModel)
    {
        $this->actionsModel = $actionsModel;

        if ($actionsModel->getFieldData('oxtype') !== self::PROMOTION_ACTION_TYPE) {
            throw new \OxidEsales\GraphQL\Catalogue\Exception\WrongType();
        }
    }

    /**
     * @return class-string
     */
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
     */
    public function isActive(): bool
    {
        return (bool)$this->actionsModel->getFieldData('oxactive') && $this->isActiveNow();
    }

    private function isActiveNow(): bool
    {
        $activeNow = false;

        $from = new \DateTimeImmutable(
            (string)$this->actionsModel->getFieldData('oxactivefrom')
        );
        $to = new \DateTimeImmutable(
            (string)$this->actionsModel->getFieldData('oxactiveto')
        );
        $now = $now ?? new \DateTimeImmutable("now");

        if ($from <= $now && $to >= $now) {
            $activeNow = true;
        }

        return $activeNow;
    }

    /**
     * @Field()
     */
    public function getTitle(): string
    {
        return $this->actionsModel->getFieldData('oxtitle');
    }

    /**
     * @Field()
     */
    public function getText(): string
    {
        return $this->actionsModel->getFieldData('oxlongdesc');
    }
}
