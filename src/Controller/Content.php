<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Content as ContentDataType;
use OxidEsales\GraphQL\Catalogue\DataType\ContentFilterList;
use OxidEsales\GraphQL\Catalogue\Exception\ContentNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;
use OxidEsales\GraphQL\Base\DataType\StringFilter;

class Content extends Base
{
    /**
     * @Query()
     *
     * @throws ContentNotFound
     * @throws InvalidLogin
     */
    public function content(string $id): ContentDataType
    {
        try {
            $content = $this->repository->getById(
                $id,
                ContentDataType::class,
                false
            );
        } catch (NotFound $e) {
            throw ContentNotFound::byId($id);
        }

        if ($content->isActive()) {
            return $content;
        }

        if (!$this->isAuthorized('VIEW_INACTIVE_CONTENT')) {
            throw new InvalidLogin("Unauthorized");
        }

        return $content;
    }

    /**
     * @Query()
     * @return ContentDataType[]
     */
    public function contents(?ContentFilterList $filter = null): array
    {
        $filter = $filter ?? new ContentFilterList();

        // In case user has VIEW_INACTIVE_CONTENT permissions
        // return all contents including inactive
        if ($this->isAuthorized('VIEW_INACTIVE_CONTENT')) {
            $filter = $filter->withActiveFilter(null);
        }

        $contents = $this->repository->getByFilter(
            $filter,
            ContentDataType::class
        );

        return $contents;
    }
}
