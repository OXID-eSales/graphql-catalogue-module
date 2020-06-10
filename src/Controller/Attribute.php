<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Catalogue\DataType\Attribute as AttributeDataType;
use OxidEsales\GraphQL\Catalogue\DataType\AttributeFilterList;
use OxidEsales\GraphQL\Catalogue\Service\Attribute as AttributeService;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Attribute
{
    /** @var AttributeService */
    private $attributeService = null;

    public function __construct(
        AttributeService $attributeService
    ) {
        $this->attributeService = $attributeService;
    }

    /**
     * @Query()
     *
     * @return AttributeDataType
     */
    public function attribute(string $id): AttributeDataType
    {
        return $this->attributeService->attribute($id);
    }

    /**
     * @Query()
     *
     * @return AttributeDataType[]
     */
    public function attributes(?AttributeFilterList $filter = null): array
    {
        return $this->attributeService->attributes(
            $filter ?? new AttributeFilterList()
        );
    }
}
