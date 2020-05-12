<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\GraphQL\Catalogue\DataType\Category as CategoryDataType;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Content::class)
 */
class ContentRelationService
{
    /** @var Repository */
    private $repository;

    public function __construct(
        Repository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @Field()
     */
    public function getSeo(Content $content): Seo
    {
        $seo = new Seo($content->getEshopModel());

        return $seo;
    }

    /**
     * @Field()
     */
    public function getCategory(Content $content): ?Category
    {
        /** @var \OxidEsales\Eshop\Application\Model\Category|null */
        $id = $content->getEshopModel()->getCategoryId();

        if (!$id) {
            return null;
        }

        return $this->repository->getById($id, CategoryDataType::class);
    }
}
