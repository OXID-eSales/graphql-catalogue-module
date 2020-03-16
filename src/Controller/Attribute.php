<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Attribute as AttributeDataType;
use OxidEsales\GraphQL\Catalogue\Exception\AttributeNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Attribute extends Base
{
    /**
     * @Query()
     *
     * @return AttributeDataType
     *
     * @throws AttributeNotFound
     * @throws InvalidLogin
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
}
