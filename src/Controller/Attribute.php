<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\DataType\BoolFilter;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Attribute as AttributeDataType;
use OxidEsales\GraphQL\Catalogue\Exception\AttributeNotFound;
use OxidEsales\GraphQL\Catalogue\DataType\AttributeFilterList;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Attribute extends Base
{
    /**
     * @Query()
     *
     * @return AttributeDataType
     *
     * @throws AttributeNotFound
     */
    public function attribute(string $id): AttributeDataType
    {
        try {
            /** @var AttributeDataType $attribute */
            $attribute = $this->repository->getById(
                $id,
                AttributeDataType::class
            );
        } catch (NotFound $e) {
            throw AttributeNotFound::byId($id);
        }

        return $attribute;
    }

    /**
     * @Query()
     *
     * @return AttributeDataType[]
     */
    public function attributes(?AttributeFilterList $filter = null): array
    {
        $filter = $filter ?? new AttributeFilterList();

        if (!$this->isAuthorized('VIEW_INACTIVE_ATTRIBUTE')) {
            $filter = $filter->withActiveFilter(new BoolFilter(true));
        }

        return $this->repository->getByFilter(
            $filter,
            AttributeDataType::class
        );
    }
}
