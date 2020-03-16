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
    private const ACTIVE_PRODUCT = "058e613db53d782adfc9f2ccb43c45fe";
    private const INACTIVE_PRODUCT  = "09602cddb5af0aba745293d08ae6bcf6";

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
                crossSellingProducts {
                    id
                }
                id
                active
                sKU
                eAN
                manufacturerEAN
                mPN
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
        $this->assertSame($dimensions['length'], 0.0);
        $this->assertSame($dimensions['width'], 0.0);
        $this->assertSame($dimensions['height'], 0.0);
        $this->assertSame($dimensions['weight'], 0.0);

        $price = $product['price'];
        $this->assertSame($price['price'], 359.0);
        $this->assertSame($price['vat'], 19.0);
        $this->assertSame($price['vatValue'], 57.32);
        $this->assertSame($price['nettoPriceMode'], false);

        $currency = $price['currency'];
        $expectedCurrency = Registry::getConfig()->getActShopCurrencyObject();
        $this->assertSame($expectedCurrency->id, $currency['id']);
        $this->assertSame($expectedCurrency->name, $currency['name']);
        $this->assertSame($expectedCurrency->rate, $currency['rate']);
        $this->assertSame($expectedCurrency->sign, $currency['sign']);

        $listPrice = $product['listPrice'];
        $this->assertSame($listPrice['price'], 399.0);
        $this->assertSame($listPrice['vat'], 19.0);
        $this->assertSame($listPrice['vatValue'], 63.71);
        $this->assertSame($listPrice['nettoPriceMode'], false);

        $stock = $product['stock'];
        $this->assertSame($stock['stock'], 16.0);
        $this->assertSame($stock['stockStatus'], 0);
        $this->assertSame($stock['restockDate'], null);

        $this->assertCount(
            3,
            $product['crossSellingProducts']
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
        $this->assertSame($rating['rating'], 0.0);
        $this->assertSame($rating['count'], 0);

        $scalePrices = $product['scalePrices'];
        $this->assertSame($scalePrices, []);

        $unit = $product['unit'];
        $this->assertSame($unit, null);

        $active = $product['active'];
        $this->assertSame($active, true);

        $sKU = $product['sKU'];
        $this->assertSame($sKU, '2401');

        $eAN = $product['eAN'];
        $this->assertSame($eAN, '');

        $manufacturerEAN = $product['manufacturerEAN'];
        $this->assertSame($manufacturerEAN, '');

        $mPN = $product['mPN'];
        $this->assertSame($mPN, '');

        $title = $product['title'];
        $this->assertSame($title, 'Bindung O&#039;BRIEN DECADE CT 2010');

        $shortDescription = $product['shortDescription'];
        $this->assertSame($shortDescription, 'Geringes Gewicht, beste Performance!');

        $longDescription = $product['longDescription'];
        $this->assertSame(
            $longDescription,
            "<p>\r\n<div class=\"product_title_big\">\r\n<h2>O'Brien Decade CT Boot 2010</h2></div>\r\n" .
            "    Die Decade Pro Bindung ist nicht nur\r\n Close-Toe Boots mit hammer Style," .
            " sondern es war das Ziel den Komfort\r\nvon normalen Boots in eine Wakeboard-Bindung einzubringen," .
            " dabei\r\nleichtgewichtig zu bleiben, und damit perfekte Performance auf dem\r\nWasser zu ermöglichen." .
            "<br />\r\n<br />\r\n  Die Wakeboard-Bindung ist in der Größe gleich bleibend, gibt also im\r\n" .
            "Laufe der Zeit nicht nach und sitzt somit wie ein perfekter Schuh. Ein\r\n" .
            "ergonomisches und stoßabsorbierendes Fussbett sorgt für weiche\r\nLandungen.</p>\r\n<p> </p>"
        );

        $vat = $product['vat'];
        $this->assertSame($vat, 19.0);

        $insert = $product['insert'];
        $this->assertSame($insert, '2010-12-06T00:00:00+01:00');

        $freeShipping = $product['freeShipping'];
        $this->assertSame($freeShipping, false);

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
}
