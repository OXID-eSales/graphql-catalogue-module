<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\DataType;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\DataType\Product;
use OxidEsales\Eshop\Core\Model\BaseModel as EshopBaseModel;
use OxidEsales\GraphQL\Catalogue\DataType\ProductScalePrice;
use OxidEsales\Eshop\Core\Field;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductScalePrice
 */
final class ProductScalePriceTest extends TestCase
{
    public function testAbsoluteScalePrice()
    {
        $productScalePrice = new ProductScalePrice(
            new ScalePriceEshopModelStub(
                "10.5",
                "",
                "10",
                "19"
            )
        );

        $this->assertTrue(
            $productScalePrice->isAbsoluteScalePrice()
        );
        $this->assertSame(
            10.5,
            $productScalePrice->getAbsolutePrice()
        );
        $this->assertNull(
            $productScalePrice->getDiscount()
        );
        $this->assertSame(
            10,
            $productScalePrice->getAmountFrom()
        );
        $this->assertSame(
            19,
            $productScalePrice->getAmountTo()
        );
    }

    public function testDiscountedScalePrice()
    {
        $productScalePrice = new ProductScalePrice(
            new ScalePriceEshopModelStub(
                "",
                "10.5",
                "10",
                "19"
            )
        );

        $this->assertFalse(
            $productScalePrice->isAbsoluteScalePrice()
        );
        $this->assertNull(
            $productScalePrice->getAbsolutePrice()
        );
        $this->assertSame(
            10.5,
            $productScalePrice->getDiscount()
        );
        $this->assertSame(
            10,
            $productScalePrice->getAmountFrom()
        );
        $this->assertSame(
            19,
            $productScalePrice->getAmountTo()
        );
    }
}

// phpcs:disable

class ScalePriceEshopModelStub extends EshopBaseModel
{
    public function __construct(
        string $addAbsolute,
        string $addPercentage,
        string $amountFrom,
        string $amountTo
    ) {
        $this->_sCoreTable = 'oxprice2article';

        $this->oxprice2article__oxaddabs = new Field(
            $addAbsolute,
            Field::T_RAW
        );
        $this->oxprice2article__oxaddperc = new Field(
            $addPercentage,
            Field::T_RAW
        );
        $this->oxprice2article__oxamount = new Field(
            $amountFrom,
            Field::T_RAW
        );
        $this->oxprice2article__oxamountto = new Field(
            $amountTo,
            Field::T_RAW
        );
    }
}
