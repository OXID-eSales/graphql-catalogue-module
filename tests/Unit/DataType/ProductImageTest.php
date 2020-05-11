<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\DataType;

use OxidEsales\GraphQL\Catalogue\DataType\ProductImage;
use PHPUnit\Framework\TestCase;

/**
 * @covers \OxidEsales\GraphQL\Catalogue\DataType\ProductImage
 */
final class ProductImageTest extends TestCase
{
    public function testProductImage(): void
    {
        $imageValue = "image value";
        $iconValue = "icon value";
        $zoomValue = "zoom value";

        $productImage = new ProductImage($imageValue, $iconValue, $zoomValue);

        $this->assertSame(
            $imageValue,
            $productImage->getImage()
        );
        $this->assertSame(
            $iconValue,
            $productImage->getIcon()
        );
        $this->assertSame(
            $zoomValue,
            $productImage->getZoom()
        );
    }
}
