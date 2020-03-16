<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Attribute as AttributeDataType;
use OxidEsales\GraphQL\Catalogue\Exception\AttributeNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;
use OxidEsales\GraphQL\Catalogue\DataType\AttributeFilterList;

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
    public function attributes(): array
    {
        $filter = new AttributeFilterList();

        try {
            $attributes = $this->repository->getByFilter(
                $filter,
                AttributeDataType::class
            );

            return $attributes;
        } catch (\Exception $e) {
            return [];
        }
    }
}
