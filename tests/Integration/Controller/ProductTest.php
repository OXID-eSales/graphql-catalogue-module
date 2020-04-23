<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase;

final class ProductTest extends TokenTestCase
{
    private const ACTIVE_PRODUCT = '058e613db53d782adfc9f2ccb43c45fe';
    private const INACTIVE_PRODUCT  = '09602cddb5af0aba745293d08ae6bcf6';
    private const ACTIVE_PRODUCT_WITH_ACCESSORIES = '05848170643ab0deb9914566391c0c63';
    private const ACTIVE_PRODUCT_WITH_VARIANTS = '531b537118f5f4d7a427cdb825440922';

    public function testGetSingleActiveProduct()
    {
        $result = $this->query('query {
            product(id: "' . self::ACTIVE_PRODUCT . '") {
                dimensions {
                    length
                    width
                    height
                    weight
                }
                price {
                    price
                    vat
                    vatValue
                    nettoPriceMode
                    currency {
                        id
                        name
                        rate
                        sign
                    }
                }
                listPrice {
                    price
                    vat
                    vatValue
                    nettoPriceMode
                }
                stock {
                    stock
                    stockStatus
                    restockDate
                }
                imageGallery {
                    images {
                        image
                        icon
                        zoom
                    }
                    icon
                    thumb
                }
                rating {
                    rating
                    count
                    ratings {
                        rating
                    }
                }
                scalePrices {
                    absoluteScalePrice
                    absolutePrice
                    discount
                    amountFrom
                    amountTo
                }
                unit {
                    price {
                        price
                        vat
                        vatValue
                        nettoPriceMode
                    }
                    name
                }
                seo {
                    description
                    keywords
                    url
                }
                accessories {
                    id
                }
                deliveryTime {
                    minDeliveryTime
                    maxDeliveryTime
                    deliveryTimeUnit
                }
                attributes {
                    attribute {
                        title
                    }
                    value
                }
                selectionLists {
                    title
                    fields {
                        value
                    }
                }
                variants {
                    id
                    active
                }
                id
                active
                sku
                ean
                manufacturerEan
                manufacturer {
                    id
                }
                vendor {
                    id
                }
                bundleProduct {
                    id
                }
                mpn
                title
                shortDescription
                longDescription
                vat
                insert
                freeShipping
                timestamp
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $product = $result['body']['data']['product'];

        $this->assertSame(self::ACTIVE_PRODUCT, $product['id']);

        $dimensions = $product['dimensions'];
        $this->assertSame(0.0, $dimensions['length']);
        $this->assertSame(0.0, $dimensions['width']);
        $this->assertSame(0.0, $dimensions['height']);
        $this->assertSame(0.0, $dimensions['weight']);

        $deliveryTime = $product['deliveryTime'];
        if (\version_compare(\PHPUnit\Runner\Version::id(), '7.0.0') >= 0) {
            $this->assertIsInt($deliveryTime['minDeliveryTime']);
            $this->assertIsInt($deliveryTime['maxDeliveryTime']);
        } else {
            $this->assertTrue(is_int($deliveryTime['minDeliveryTime']), 'minDeliveryTime must be of type integer');
            $this->assertTrue(is_int($deliveryTime['maxDeliveryTime']), 'maxDeliveryTime must be of type integer');
        }
        $this->assertGreaterThan(0, $deliveryTime['minDeliveryTime']);
        $this->assertGreaterThan(0, $deliveryTime['maxDeliveryTime']);
        $this->assertContains(
            $deliveryTime['deliveryTimeUnit'],
            ['DAY','WEEK'],
            'deliveryTimeUnit must be one of DAY, WEEK, but is not'
        );

        $price = $product['price'];
        $this->assertSame(359.0, $price['price']);
        $this->assertSame(19.0, $price['vat']);
        $this->assertSame(57.32, $price['vatValue']);
        $this->assertFalse($price['nettoPriceMode']);

        $this->assertNull($product['manufacturer']);
        $this->assertNull($product['vendor']);
        $this->assertNull($product['bundle']);

        $currency = $price['currency'];
        $expectedCurrency = Registry::getConfig()->getActShopCurrencyObject();
        $this->assertSame($expectedCurrency->id, $currency['id']);
        $this->assertSame($expectedCurrency->name, $currency['name']);
        $this->assertSame($expectedCurrency->rate, $currency['rate']);
        $this->assertSame($expectedCurrency->sign, $currency['sign']);

        $listPrice = $product['listPrice'];
        $this->assertSame(399.0, $listPrice['price']);
        $this->assertSame(19.0, $listPrice['vat']);
        $this->assertSame(63.71, $listPrice['vatValue']);
        $this->assertFalse($listPrice['nettoPriceMode']);

        $stock = $product['stock'];
        $this->assertSame(16.0, $stock['stock']);
        $this->assertSame(0, $stock['stockStatus']);
        $this->assertNull($stock['restockDate']);

        $this->assertCount(
            0,
            $product['accessories']
        );

        $imageGallery = $product['imageGallery'];
        $images = $imageGallery['images'][0];
        $this->assertRegExp(
            '@https?://.*/out/pictures/generated/product/1/540_340_75/obrien_decade_ct_boot_2010_1.jpg@',
            $images['image']
        );
        $this->assertRegExp(
            '@https?://.*/out/pictures/generated/product/1/87_87_75/obrien_decade_ct_boot_2010_1.jpg@',
            $images['icon']
        );
        $this->assertRegExp(
            '@https?://.*/out/pictures/generated/product/1/665_665_75/obrien_decade_ct_boot_2010_1.jpg@',
            $images['zoom']
        );
        $this->assertRegExp(
            '@https?://.*/out/pictures/generated/product/1/87_87_75/obrien_decade_ct_boot_2010_1.jpg@',
            $imageGallery['icon']
        );
        $this->assertRegExp(
            '@https?://.*/out/pictures/generated/product/1/390_245_75/obrien_decade_ct_boot_2010_1.jpg@',
            $imageGallery['thumb']
        );

        $rating = $product['rating'];
        $this->assertSame(0.0, $rating['rating']);
        $this->assertSame(0, $rating['count']);
        $this->assertCount(3, $rating['ratings']);

        $this->assertSame(
            [],
            $product['scalePrices']
        );

        $this->assertNull($product['unit']);
        $this->assertTrue($product['active']);
        $this->assertSame('2401', $product['sku']);
        $this->assertSame('', $product['ean']);
        $this->assertSame('', $product['manufacturerEan']);
        $this->assertSame([], $product['attributes']);
        $this->assertSame([], $product['selectionLists']);
        $this->assertSame([], $product['variants']);
        $this->assertSame('', $product['mpn']);
        $this->assertSame('Bindung O&#039;BRIEN DECADE CT 2010', $product['title']);
        $this->assertSame('Geringes Gewicht, beste Performance!', $product['shortDescription']);
        $this->assertSame(
            "<p>\r\n<div class=\"product_title_big\">\r\n<h2>O'Brien Decade CT Boot 2010</h2></div>\r\n" .
            "    Die Decade Pro Bindung ist nicht nur\r\n Close-Toe Boots mit hammer Style," .
            " sondern es war das Ziel den Komfort\r\nvon normalen Boots in eine Wakeboard-Bindung einzubringen," .
            " dabei\r\nleichtgewichtig zu bleiben, und damit perfekte Performance auf dem\r\nWasser zu ermöglichen." .
            "<br />\r\n<br />\r\n  Die Wakeboard-Bindung ist in der Größe gleich bleibend, gibt also im\r\n" .
            "Laufe der Zeit nicht nach und sitzt somit wie ein perfekter Schuh. Ein\r\n" .
            "ergonomisches und stoßabsorbierendes Fussbett sorgt für weiche\r\nLandungen.</p>\r\n<p> </p>",
            $product['longDescription']
        );
        $this->assertSame(19.0, $product['vat']);
        $this->assertSame('2010-12-06T00:00:00+01:00', $product['insert']);
        $this->assertFalse($product['freeShipping']);
        $this->assertRegExp(
            '@https?://.*/Wakeboarding/Bindungen/Bindung-O-BRIEN-DECADE-CT-2010.html@',
            $product['seo']['url']
        );
        $this->assertEquals('german product seo description', $product['seo']['description']);
        $this->assertEquals('german product seo keywords', $product['seo']['keywords']);
    }

    public function testGetSingleInactiveProductWithoutToken()
    {
        $result = $this->query('query {
            product (id: "' . self::INACTIVE_PRODUCT . '") {
                id
                active
            }
        }');

        $this->assertResponseStatus(
            401,
            $result
        );
    }

    public function testGetSingleInactiveProductWithToken()
    {
        $this->prepareToken();

        $result = $this->query('query {
            product (id: "' . self::INACTIVE_PRODUCT . '") {
                id
                active
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            [
                'id' => self::INACTIVE_PRODUCT,
                'active' => false
            ],
            $result['body']['data']['product']
        );
    }

    public function testGetSingleNonExistingProduct()
    {
        $result = $this->query('query {
            product (id: "DOES-NOT-EXIST") {
                id
            }
        }');

        $this->assertEquals(404, $result['status']);
    }

    private function assertArraySameNonAssociative(array $expected, array $actual): void
    {
        $this->assertSame(sort($expected), sort($actual));
    }

    public function testGetProductVariants(): void
    {
        $result = $this->query('
            query{
                product(id: "' . self::ACTIVE_PRODUCT_WITH_VARIANTS . '" ){
                    variants {
                        id
                        active
                        sku
                        ean
                        manufacturerEan
                        mpn
                        title
                        shortDescription
                        longDescription
                        vat
                        insert
                        freeShipping
                        timestamp
                    }
                }
            }
        ');

        $this->assertResponseStatus(
            200,
            $result
        );

        $actualVariants = $result['body']['data']['product']['variants'];

        $expectedVariants = [
            [
              "id" => "6b6efaa522be53c3e86fdb41f0542a8a",
              "active" => true,
            ],
            [
              "id" => "6b65c82bfe8fa19865d560f8c1a905b4",
              "active" => true,
            ],
            [
              "id" => "6b6ee4ad0a02a725a136ca139e226dd5",
              "active" => true,
            ],
            [
              "id" => "6b628e6a8ffa98fea6f2ee9d708b1b23",
              "active" => true,
            ],
            [
              "id" => "6b6e2c7af07fd2b9d82223ff35f4e08f",
              "active" => true,
            ],
            [
              "id" => "6b6d187d3f648ab5d7875ce863244095",
              "active" => true,
            ],
            [
              "id" => "6b65295a7fe5fa6faaa2f0ac3f9b0f80",
              "active" => true,
            ],
            [
              "id" => "6b6e0bb9f2b8b5f070f91593073b4555",
              "active" => true,
            ],
            [
              "id" => "6b6cf1ed0c0b3e784b05b1c9c207d352",
              "active" => true,
            ],
        ];

        $this->assertArraySameNonAssociative($expectedVariants, $actualVariants);
    }

    public function testGetNoProductVariants(): void
    {
        $result = $this->query('
            query{
                product(id: "' . self::ACTIVE_PRODUCT_WITH_ACCESSORIES . '"){
                    variants {
                        id
                    }
                }
            }
        ');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(
            0,
            $result['body']['data']['product']['variants']
        );
    }
}
