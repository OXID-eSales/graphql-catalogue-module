<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\Eshop\Application\Model\SelectList as EshopSelectionListModel;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 */
final class SelectionList implements DataType
{
    /** @var EshopSelectionListModel */
    private $selectionList;

    /**
     * SelectionList constructor.
     *
     * @param EshopSelectionListModel $selectionList
     */
    public function __construct(EshopSelectionListModel $selectionList)
    {
        $this->selectionList = $selectionList;
    }

    /**
     * @return string
     */
    public static function getModelClass(): string
    {
        return EshopSelectionListModel::class;
    }

    /**
     * @Field()
     */
    public function getTitle(): string
    {
        return (string) $this->selectionList->getFieldData('oxtitle');
    }

    /**
     * @Field()
     *
     * @return Selection[]
     */
    public function getFields(): array
    {
        $fields = [];
        foreach ($this->selectionList->getSelections() as $field) {
            $fields[] = new Selection($field);
        }

        return $fields;
    }
}
