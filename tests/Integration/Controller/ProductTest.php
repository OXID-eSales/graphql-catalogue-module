<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase;

final class ProductTest extends TokenTestCase
{
    private const ACTIVE_PRODUCT = '058e613db53d782adfc9f2ccb43c45fe';
    private const INACTIVE_PRODUCT  = '09602cddb5af0aba745293d08ae6bcf6';
    private const ACTIVE_PRODUCT_WITH_ACCESSORIES = '05848170643ab0deb9914566391c0c63';
    private const ACTIVE_PRODUCT_WITH_VARIANTS = '531b537118f5f4d7a427cdb825440922';
    private const ACTIVE_PRODUCT_MANUFACTURER = 'oiaf6ab7e12e86291e86dd3ff891fe40';
    private const VENDOR_OF_ACTIVE_PRODUCT = 'a57c56e3ba710eafb2225e98f058d989';

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
                wishedPriceEnabled
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

        $this->assertSame(['id' => self::ACTIVE_PRODUCT_MANUFACTURER], $product['manufacturer']);
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
        $this->assertTrue($product['wishedPriceEnabled']);
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
                    variantLabels
                    variants {
                        id
                        active
                        variantValues
                    }
                }
            }
        ');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame(
            $result['body']['data']['product']['variantLabels'],
            [
                'Größe',
                'Farbe'
            ]
        );

        $actualVariants = $result['body']['data']['product']['variants'];

        $expectedVariants = [
            [
              "id" => "6b6efaa522be53c3e86fdb41f0542a8a",
              "active" => true,
              "variantValues" => [
                  "W 30/L 30",
                  "Blau",
              ],
            ],
            [
              "id" => "6b65c82bfe8fa19865d560f8c1a905b4",
              "active" => true,
              "variantValues" => [
                  "W 30/L 30",
                  "Smoke Gray",
              ],
            ],
            [
              "id" => "6b6ee4ad0a02a725a136ca139e226dd5",
              "active" => true,
              "variantValues" => [
                  "W 30/L 30",
                  "Super Blue",
              ],
            ],
            [
              "id" => "6b628e6a8ffa98fea6f2ee9d708b1b23",
              "active" => true,
              "variantValues" => [
                  "W 31/L 34",
                  "Blau",
              ],
            ],
            [
              "id" => "6b6e2c7af07fd2b9d82223ff35f4e08f",
              "active" => true,
              "variantValues" => [
                  "W 31/L 34",
                  "Smoke Gray",
              ],
            ],
            [
              "id" => "6b6d187d3f648ab5d7875ce863244095",
              "active" => true,
              "variantValues" => [
                  "W 31/L 34",
                  "Super Blue",
              ],
            ],
            [
              "id" => "6b65295a7fe5fa6faaa2f0ac3f9b0f80",
              "active" => true,
              "variantValues" => [
                  "W 34/L 34",
                  "Blau",
              ],
            ],
            [
              "id" => "6b6e0bb9f2b8b5f070f91593073b4555",
              "active" => true,
              "variantValues" => [
                  "W 34/L 34",
                  "Smoke Gray",
              ],
            ],
            [
              "id" => "6b6cf1ed0c0b3e784b05b1c9c207d352",
              "active" => true,
              "variantValues" => [
                  "W 34/L 34",
                  "Super Blue",
              ],
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

    public function testProducts()
    {
        $result = $this->query('query {
            products {
                id
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(
            53,
            $result['body']['data']['products']
        );
    }

    /**
     * @dataProvider productsOffsetAndLimitDataProvider
     *
     * @param int $offset
     * @param int $limit
     * @param array $expectedProducts
     */
    public function testProductsOffsetAndLimit(int $offset, int $limit, array $expectedProducts)
    {
        $result = $this->query('query {
            products(pagination: {offset: ' . $offset . ', limit: ' . $limit . '}) {
                id
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertEquals($expectedProducts, $result['body']['data']['products']);
    }

    /**
     * @return array[]
     */
    public function productsOffsetAndLimitDataProvider()
    {
        return [
            [
                0,
                1,
                [
                    ['id' => '05848170643ab0deb9914566391c0c63']
                ]
            ],
            [
                0,
                4,
                [
                    ['id' => '05848170643ab0deb9914566391c0c63'],
                    ['id' => '0584e8b766a4de2177f9ed11d1587f55'],
                    ['id' => '058de8224773a1d5fd54d523f0c823e0'],
                    ['id' => '058e613db53d782adfc9f2ccb43c45fe'],
                ]
            ],
            [
                2,
                4,
                [
                    ['id' => '058de8224773a1d5fd54d523f0c823e0'],
                    ['id' => '058e613db53d782adfc9f2ccb43c45fe'],
                    ['id' => '531b537118f5f4d7a427cdb825440922'],
                    ['id' => '531f91d4ab8bfb24c4d04e473d246d0b'],
                ]
            ],
        ];
    }

    /**
     * @dataProvider productsByManufacturerProvider
     *
     * @param string $manufacturerId
     * @param int $expectedCount
     */
    public function testProductsByManufacturer(string $manufacturerId, int $expectedCount)
    {
        $result = $this->query('query {
            products(filter: { manufacturer: { equals: "' . $manufacturerId . '" } }) {
                manufacturer {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(
            $expectedCount,
            $result['body']['data']['products']
        );

        $filterByManufacturerFunction = function (array $product) use ($manufacturerId) {
            return $product['manufacturer']['id'] == $manufacturerId;
        };

        $this->assertEquals(
            $result['body']['data']['products'],
            array_filter($result['body']['data']['products'], $filterByManufacturerFunction)
        );
    }

    /**
     * @return array[]
     */
    public function productsByManufacturerProvider()
    {
        return [
            ['9434afb379a46d6c141de9c9e5b94fcf', 10],
            ['adc6df0977329923a6330cc8f3c0a906', 7],
            ['90a0b84564cde2394491df1c673b6aa0', 3]
        ];
    }

    /**
     * @dataProvider productsByVendorProvider
     *
     * @param string $vendorId
     * @param int $expectedCount
     */
    public function testProductsByVendor(string $vendorId, int $expectedCount)
    {
        $result = $this->query('query {
            products(filter: { vendor: { equals: "' . $vendorId . '" } }) {
                vendor {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(
            $expectedCount,
            $result['body']['data']['products']
        );

        $filterByVendorFunction = function (array $product) use ($vendorId) {
            return $product['vendor']['id'] == $vendorId;
        };

        $this->assertEquals(
            $result['body']['data']['products'],
            array_filter($result['body']['data']['products'], $filterByVendorFunction)
        );
    }

    /**
     * @return array[]
     */
    public function productsByVendorProvider()
    {
        return [
            ['a57c56e3ba710eafb2225e98f058d989', 13],
            ['fe07958b49de225bd1dbc7594fb9a6b0', 0],
        ];
    }

    /**
     * @dataProvider productsByCategoryDataProvider
     *
     * @param string $categoryId
     * @param int $expectedCount
     */
    public function testProductsByCategory(string $categoryId, int $expectedCount)
    {
        $result = $this->query('query {
            products(filter: { category: { equals: "' . $categoryId . '" } }) {
                id
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(
            $expectedCount,
            $result['body']['data']['products']
        );
    }

    public function productsByCategoryDataProvider()
    {
        return [
            ['0f41a4463b227c437f6e6bf57b1697c4', 2],
            ['0f4fb00809cec9aa0910aa9c8fe36751', 12],
            ['0f4f08358666c54b4fde3d83d2b7ef04', 4],
        ];
    }

    public function testDeliveryStatusHandling()
    {
        $noStockProduct = 'fadc492a5807c56eb80b0507accd756b';
        $queryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class);
        $queryBuilder = $queryBuilderFactory->create();

        // set stock flag to: offline if out of stock
        $query = $queryBuilder
            ->update('oxarticles')
            ->set('oxstockflag', ':STOCKFLAG')
            ->where('OXID = :OXID')
            ->setParameter(':OXID', $noStockProduct)
            ->setParameter(':STOCKFLAG', '2');
        $query->execute();

        $result = $this->query('query {
            products (filter: {
                title: {
                    contains: "SPLEENE"
                }
            }) {
                id
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame(
            [],
            $result['body']['data']['products']
        );

        // reset stock flag to: default
        $query->setParameter(':STOCKFLAG', '1')
              ->execute();

        $result = $this->query('query {
            products (filter: {
                title: {
                    contains: "SPLEENE"
                }
            }) {
                id
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame(
            [
                ['id' => $noStockProduct]
            ],
            $result['body']['data']['products']
        );
    }

    public function productVendorWithTokenProvider()
    {
        return [
            [
                'isVendorActive' => false,
                'withToken' => false,
                'expectedVendor' => null,
            ],
            [
                'isVendorActive' => false,
                'withToken' => true,
                'expectedVendor' => [
                    'id' => self::VENDOR_OF_ACTIVE_PRODUCT,
                    'active' => false,
                ],
            ],
            [
                'isVendorActive' => true,
                'withToken' => false,
                'expectedVendor' => [
                    'id' => self::VENDOR_OF_ACTIVE_PRODUCT,
                    'active' => true,
                ],
            ],
            [
                'isVendorActive' => true,
                'withToken' => true,
                'expectedVendor' => [
                    'id' => self::VENDOR_OF_ACTIVE_PRODUCT,
                    'active' => true,
                ],
            ],
        ];
    }

    /**
     * @dataProvider productVendorWithTokenProvider
     */
    public function testGetProductVendorWithToken($isVendorActive, $withToken, $expectedVendor)
    {
        $queryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class);
        $queryBuilder = $queryBuilderFactory->create();

        $oxactive = $isVendorActive ? 1 : 0;
        $queryBuilder
            ->update('oxvendor')
            ->set('oxactive', $oxactive)
            ->where('OXID = :OXID')
            ->setParameter(':OXID', self::VENDOR_OF_ACTIVE_PRODUCT)
            ->execute();

        if ($withToken) {
            $this->prepareToken();
        }

        $result = $this->query('query {
            product(id: "' . self::ACTIVE_PRODUCT_WITH_VARIANTS . '") {
                vendor {
                    id
                    active
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $productVendor = $result['body']['data']['product']['vendor'];
        $this->assertSame($expectedVendor, $productVendor);
    }

    public function productManufacturerWithTokenProvider()
    {
        return [
            [
                'isManufacturerActive' => false,
                'withToken' => false,
                'expectedManufacturer' => null,
            ],
            [
                'isManufacturerActive' => false,
                'withToken' => true,
                'expectedManufacturer' => [
                    'id' => self::ACTIVE_PRODUCT_MANUFACTURER,
                    'active' => false,
                ],
            ],
            [
                'isManufacturerActive' => true,
                'withToken' => false,
                'expectedManufacturer' => [
                    'id' => self::ACTIVE_PRODUCT_MANUFACTURER,
                    'active' => true,
                ],
            ],
            [
                'isManufacturerActive' => true,
                'withToken' => true,
                'expectedManufacturer' => [
                    'id' => self::ACTIVE_PRODUCT_MANUFACTURER,
                    'active' => true,
                ],
            ],
        ];
    }

    /**
     * @dataProvider productManufacturerWithTokenProvider
     */
    public function testGetProductManufacturerWithToken($isManufacturerActive, $withToken, $expectedManufacturer)
    {
        $queryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class);
        $queryBuilder = $queryBuilderFactory->create();

        $oxactive = $isManufacturerActive ? 1 : 0;
        $queryBuilder
            ->update('oxmanufacturers')
            ->set('oxactive', $oxactive)
            ->where('OXID = :OXID')
            ->setParameter(':OXID', self::ACTIVE_PRODUCT_MANUFACTURER)
            ->execute();

        if ($withToken) {
            $this->prepareToken();
        }

        $result = $this->query('query {
            product(id: "' . self::ACTIVE_PRODUCT . '") {
                manufacturer {
                    id
                    active
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $productManufacturer = $result['body']['data']['product']['manufacturer'];
        $this->assertSame($expectedManufacturer, $productManufacturer);
    }
}
