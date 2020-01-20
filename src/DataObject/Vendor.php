<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataObject;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * @Type()
 */
class Vendor
{
    /** @var ID */
    private $id;

    /** @var bool */
    private $active;

    /** @var string  */
    private $icon;

    /** @var string */
    private $title;

    /** @var string */
    private $shortdesc;

    /** @var string|null */
    private $url;

    /** @var DateTimeInterface */
    private $timestamp;

    public function __construct(
        string $id,
        int $active,
        string $icon,
        string $title,
        string $shortdesc,
        ?string $url,
        string $timestamp
    ) {
        $this->id = new ID($id);
        $this->active = (bool)$active;
        $this->icon = $icon;
        $this->title = $title;
        $this->shortdesc = $shortdesc;
        $this->url = $url;
        $this->timestamp = new DateTimeImmutable($timestamp);
    }

    /**
     * @param string[] $result
     *
     * @return Vendor
     */
    public static function fromDatabaseResult(array $result): self
    {
        return new self(
            $result['oxid'],
            (int) $result['oxactive'],
            $result['oxicon'],
            $result['oxtitle'],
            $result['oxshortdesc'],
            $result['oxseourl'],
            $result['oxtimestamp']
        );
    }

    /**
     * @Field()
     */
    public function getId(): ID
    {
        return $this->id;
    }

    /**
     * @Field()
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @Field()
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @Field()
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @Field()
     */
    public function getShortdesc(): string
    {
        return $this->shortdesc;
    }

    /**
     * @Field()
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @Field()
     */
    public function getTimestamp(): DateTimeInterface
    {
        return $this->timestamp;
    }
}
