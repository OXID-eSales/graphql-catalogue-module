<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\DataType;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\DataType\ProductScalePrice;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductScalePrice
 */
final class ProductScalePriceTest extends TestCase
{
    public function testAbsoluteScalePrice(): void
    {
        $productScalePrice = new ProductScalePrice(
            new ProductScalePriceModelStub(
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

    public function testDiscountedScalePrice(): void
    {
        $productScalePrice = new ProductScalePrice(
            new ProductScalePriceModelStub(
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
