<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\DataType;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\DataType\Price;
use OxidEsales\Eshop\Application\Model\Article as EshopArticle;
use OxidEsales\Eshop\Core\Price as EshopPrice;
use OxidEsales\Eshop\Core\Field;

/**
 * The EshopPrice object sadly is tightly coupled to the registry, session and config
 * so we would need to mock to much to make this working as a unit test.
 *
 * @covers OxidEsales\GraphQL\Catalogue\DataType\Price
 */
class PriceTest extends TestCase
{
    public function testGetPrice()
    {
        $price = new Price(
            new EshopPrice(
                12.99
            )
        );
        $this->assertSame(
            12.99,
            $price->getPrice()
        );
        $this->assertSame(
            0.0,
            $price->getVat()
        );
        $this->assertSame(
            0.0,
            $price->getVatValue()
        );
        $this->assertFalse(
            $price->isNettoPriceMode()
        );
    }

    public function testWithArticle()
    {
        $article = oxNew(EshopArticle::class);
        $article->load('058de8224773a1d5fd54d523f0c823e0');
        $price = new Price(
            $article->getPrice()
        );
        $this->assertSame(
            479.0,
            $price->getPrice()
        );
        $this->assertSame(
            19.0,
            $price->getVat()
        );
        $this->assertSame(
            76.48,
            $price->getVatValue()
        );
        $this->assertFalse(
            $price->isNettoPriceMode()
        );
    }
}
