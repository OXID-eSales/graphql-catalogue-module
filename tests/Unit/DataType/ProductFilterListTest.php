<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\DataType;

use OxidEsales\GraphQL\Base\DataType\IDFilter;
use OxidEsales\GraphQL\Catalogue\DataType\ProductFilterList;
use PHPUnit\Framework\TestCase;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @covers \OxidEsales\GraphQL\Catalogue\DataType\ProductFilterList
 */
final class ProductFilterListTest extends TestCase
{
    public function testInputFilterDefaults(): void
    {
        $filter = new ProductFilterList();
        $this->assertEquals(
            [
                'oxtitle' => null,
                'oxcatnid' => null,
                'oxmanufacturerid' => null,
                'oxvendorid' => null,
                'oxparentid' => new IDFilter(new ID(''))
            ],
            $filter->getFilters()
        );
    }
}