<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use Doctrine\DBAL\Query\QueryBuilder;
use InvalidArgumentException;
use OxidEsales\Eshop\Application\Model\Object2Category;
use OxidEsales\GraphQL\Base\DataType\FilterInterface;
use TheCodingMachine\GraphQLite\Annotations\Factory;
use TheCodingMachine\GraphQLite\Types\ID;

use function strtoupper;

class CategoryIDFilter implements FilterInterface
{
    /** @var ID */
    private $equals;

    /**
     * CategoryIDFilter constructor.
     * @param ID $equals
     */
    public function __construct(ID $equals)
    {
        $this->equals = $equals;
    }

    /**
     * @return ID
     */
    public function equals(): ID
    {
        return $this->equals;
    }

    /**
     * @param QueryBuilder $builder
     * @param string $field
     */
    public function addToQuery(QueryBuilder $builder, string $field): void
    {
        $from = $builder->getQueryPart('from');
        if ($from === []) {
            throw new InvalidArgumentException('QueryBuilder is missing "from" SQL part');
        }
        $table = $from[0]['alias'] ?? $from[0]['table'];

        /** @var Object2Category $model */
        $model = oxNew(Object2Category::class);
        $alias = $model->getViewName();

        $builder
            ->join(
                $table,
                $model->getViewName(),
                $alias,
                $builder->expr()->eq("$table.OXID", "$alias.OXOBJECTID")
            )
            ->andWhere($builder->expr()->eq($alias . '.' . strtoupper($field), ":$field"))
            ->setParameter(":$field", $this->equals());
    }

    /**
     * @Factory(name="CategoryIDFilterInput")
     *
     * @param ID $equals
     *
     * @return CategoryIDFilter
     */
    public static function fromUserInput(ID $equals): self
    {
        return new self($equals);
    }
}
