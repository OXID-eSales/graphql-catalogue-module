<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\DataType;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\DataType\Category;
use OxidEsales\EshopCommunity\Application\Model\Category as CategoryModel;
use OxidEsales\Eshop\Core\Field;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\Category
 */
class CategoryTest extends TestCase
{
    public function testIsActive()
    {
        $category = new Category(
            new CategoryModelStub()
        );
        $this->assertTrue(
            $category->isActive()
        );

        $category = new Category(
            new CategoryModelStub('0')
        );
        $this->assertFalse(
            $category->isActive()
        );

        $category = new Category(
            new CategoryModelStub(
                '1',
                '2018-01-01 12:00:00',
                '2018-01-01 19:00:00'
            )
        );
        $this->assertTrue(
            $category->isActive()
        );

        $category = new Category(
            new CategoryModelStub(
                '0',
                '2018-01-01 12:00:00',
                '2018-01-01 19:00:00'
            )
        );
        $this->assertFalse(
            $category->isActive()
        );

        $category = new Category(
            new CategoryModelStub(
                '0',
                '2018-01-01 12:00:00',
                '2018-01-01 19:00:00'
            )
        );
        $this->assertTrue(
            $category->isActive(new \DateTimeImmutable('2018-01-01 16:00:00'))
        );
    }
}

// phpcs:disable

class CategoryModelStub extends CategoryModel
{
    public function __construct(
        string $active = '1',
        string $activefrom = '0000-00-00 00:00:00',
        string $activeto = '0000-00-00 00:00:00'
    ) {
        $this->_sCoreTable = 'oxcategories';
        $this->oxcategories__oxactive = new Field(
            $active,
            Field::T_RAW
        );
        $this->oxcategories__oxactivefrom = new Field(
            $activefrom,
            Field::T_RAW
        );
        $this->oxcategories__oxactiveto = new Field(
            $activeto,
            Field::T_RAW
        );
    }
}
