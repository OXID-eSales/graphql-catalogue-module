<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\DataType;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductRelationService
 */
final class ProductRelationServiceTest extends TokenTestCase
{
    private const ACTIVE_PRODUCT = '058e613db53d782adfc9f2ccb43c45fe';
    private const ACTIVE_PRODUCT_WITH_ACCESSORIES = '05848170643ab0deb9914566391c0c63';
    private const ACTIVE_PRODUCT_WITH_UNITNAME = 'f33d5bcc7135908fd36fc736c643aa1c';
    private const ACTIVE_PRODUCT_WITHOUT_CROSSSELLING = 'f33d5bcc7135908fd36fc736c643aa1c';
    private const INACTIVE_CROSSSELLING_PRODUCT = 'b5685a5230f5050475f214b4bb0e239b';
    private const ACTIVE_PRODUCT_WITH_SELECTION_LISTS = '058de8224773a1d5fd54d523f0c823e0';
    private const ACTIVE_PRODUCT_WITH_RESTOCK_DATE = 'f4fe754e1692b9f79f2a7b1a01bb8dee';
    private const ACTIVE_PRODUCT_WITH_SCALE_PRICES = 'dc53d3c0ca2ae7c38bf51f3410da0bf8';
    private const ACTIVE_PRODUCT_WITH_BUNDLE_ITEM = 'dc53d3c0ca2ae7c38bf51f3410da0bf8';
    private const ACTIVE_PRODUCT_WITHOUT_MANUFACTURER = 'f33d5bcc7135908fd36fc736c643aa1c';
    private const INACTIVE_PRODUCT  = '09602cddb5af0aba745293d08ae6bcf6';
    private const ACTIVE_MAIN_BUNDLE_PRODUCT = '_test_active_main_bundle';

    public function testGetAccessoriesRelation()
    {
        $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITH_ACCESSORIES . '") {
                id
                accessories {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $product = $result['body']['data']['product'];

        $this->assertCount(
            2,
            $product['accessories']
        );

        $this->assertSame(
            [
                [
                    'id' => 'adcb9deae73557006a8ac748f45288b4'
                ], [
                    'id' => 'd86236918e1533cccb679208628eda32'
                ]
            ],
            $product['accessories']
        );
    }

    /**
     * @dataProvider productWithATtributesProvider
     */
    public function testGetProductAttributesRelation(string $productId, array $expected): void
    {
        $result = $this->query('
            query{
                product(id: "' . $productId . '" ){
                    attributes {
                        value
                        attribute {
                          title
                        }
                    }
                }
            }
        ');

        $this->assertEquals(200, $result['status']);

        $this->assertSame(
            sort($expected),
            sort($result['body']['data']['product']['attributes'])
        );
    }

    public function productWithAttributesProvider(): array
    {
        return [
            [
                'product' => 'b56369b1fc9d7b97f9c5fc343b349ece',
                'expected' => [
                    [
                        'value' => 'Kite, Backpack, Reparaturset',
                        'attribute' => ['title' => 'Lieferumfang'],
                    ],
                    [
                        'value' => 'Allround',
                        'attribute' => ['title' => 'Einsatzbereich'],
                    ],
                ],
            ],
            [
                'product' => 'f4f0cb3606e231c3fdb34fcaee2d6d04',
                'expected' => [
                    [
                        'value' => 'Allround',
                        'attribute' => ['title' => 'Einsatzbereich'],
                    ],
                    [
                        'value' => 'Kite, Tasche, CPR Control System, Pumpe',
                        'attribute' => ['title' => 'Lieferumfang'],
                    ],
                ],
            ],
            [
                'product' => '058de8224773a1d5fd54d523f0c823e0',
                'expected' => [],
            ],
        ];
    }

    /**
     * @covers OxidEsales\GraphQL\Catalogue\DataType\Selection
     * @covers OxidEsales\GraphQL\Catalogue\DataType\SelectionList
     * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductRelationService
     */
    public function testGetSelectionListsRelation()
    {
        $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITH_SELECTION_LISTS . '") {
                id
                selectionLists {
                    title
                    fields {
                        value
                    }
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $product = $result['body']['data']['product'];

        $this->assertCount(
            1,
            $product['selectionLists']
        );

        $this->assertSame(
            [
                'title' => 'test selection list [DE] šÄßüл',
                'fields' => [
                    [
                        'value' => 'selvar1 [DE]',
                    ],
                    [
                        'value' => 'selvar2 [DE]',
                    ],
                    [
                        'value' => 'selvar3 [DE]',
                    ],
                    [
                        'value' => 'selvar4 [DE]',
                    ],
                ],
            ],
            $product['selectionLists'][0]
        );
    }

    /**
     * @dataProvider getReviewsConfigDataProvider
     */
    public function testGetReviewsRelation($configValue, $expectedIds)
    {
        $this->getConfig()->setConfigParam('blGBModerate', $configValue);

        $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT . '") {
                id
                reviews {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $product = $result['body']['data']['product'];

        $this->assertSame(
            $expectedIds,
            $product['reviews']
        );
    }

    public function getReviewsConfigDataProvider()
    {
        return [
            [
                true,
                [
                    ['id' => '_test_real_product_1'],
                    ['id' => '_test_real_product_2']
                ]
            ],
            [
                false,
                [
                    ['id' => '_test_real_product_1'],
                    ['id' => '_test_real_product_2'],
                    ['id' => '_test_real_product_inactive']
                ]
            ]
        ];
    }

    public function testGetNoReviewsRelation()
    {
        $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITH_ACCESSORIES . '") {
                id
                reviews {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(
            0,
            $result['body']['data']['product']['reviews']
        );
    }

    /**
     * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductUnit
     * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductRelationService
     */
    public function testGetUnitNameAndPriceRelation()
    {
         $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITH_UNITNAME . '") {
                id
                unit {
                    name
                    price {
                        price
                    }
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame('g', $result['body']['data']['product']['unit']['name']);
        $this->assertSame(0.42, $result['body']['data']['product']['unit']['price']['price']);
    }

    /**
     * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductStock
     * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductRelationService
     */
    public function testGetRestockDateRelation()
    {
         $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITH_RESTOCK_DATE . '") {
                id
                stock {
                    restockDate
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame('2999-12-31T00:00:00+01:00', $result['body']['data']['product']['stock']['restockDate']);
    }

    public function testGetProductVendorRelation()
    {
         $result = $this->query('query {
            product (id: "6b63456b3abeeeccd9b085a76ffba1a3") {
                id
                vendor {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame(
            'a57c56e3ba710eafb2225e98f058d989',
            $result['body']['data']['product']['vendor']['id']
        );
    }

    public function testGetCrossSellingRelation()
    {
         $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT . '") {
                id
                crossSelling {
                    id
                    active
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(
            3,
            $result['body']['data']['product']['crossSelling']
        );

        $this->assertSame(
            [
                'id' => self::INACTIVE_CROSSSELLING_PRODUCT,
                'active' => true
            ],
            $result['body']['data']['product']['crossSelling'][0]
        );
    }

    public function testGetNoCrossSellingRelation()
    {
         $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITHOUT_CROSSSELLING . '") {
                id
                crossSelling {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame(
            [],
            $result['body']['data']['product']['crossSelling']
        );
    }

    public function testGetProductManufacturerRelation()
    {
         $result = $this->query('query {
            product (id: "6b63456b3abeeeccd9b085a76ffba1a3") {
                id
                manufacturer {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame(
            '9434afb379a46d6c141de9c9e5b94fcf',
            $result['body']['data']['product']['manufacturer']['id']
        );
    }

    public function testGetProductWithoutManufacturerRelation()
    {
        $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITHOUT_MANUFACTURER . '") {
                id
                manufacturer {
                    id
                }
            }
        }');

        $this->assertResponseStatus(200, $result);
        $this->assertNull($result['body']['data']['product']['manufacturer']);
    }

    public function testGetNoProductBundleItemRelation()
    {
        $config = Registry::getConfig();
        $oldParam = $config->getConfigParam('bl_perfLoadAccessoires');
        $config->setConfigParam('bl_perfLoadAccessoires', false);

        $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM . '") {
                id
                bundleProduct {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertNull($result['body']['data']['product']['bundleProduct']);

        $config->setConfigParam('bl_perfLoadAccessoires', $oldParam);
    }

    public function testGetNoNonExistingProductBundleItemRelation()
    {
        $queryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class);

        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder->update('oxarticles')
                     ->set('oxbundleid', ':BUNDLEID')
                     ->where('OXID = :OXID')
                     ->setParameter(':OXID', self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM)
                     ->setParameter(':BUNDLEID', 'THIS-IS-INVALID')
                     ->execute();


        $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM . '") {
                id
                bundleProduct {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertNull($result['body']['data']['product']['bundleProduct']);

        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder->update('oxarticles')
                     ->set('oxbundleid', ':BUNDLEID')
                     ->where('OXID = :OXID')
                     ->setParameter(':OXID', self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM)
                     ->setParameter(':BUNDLEID', '')
                     ->execute();
    }

    public function testGetNoInvisibleProductBundleItemRelation()
    {
        $queryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class);

        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder->update('oxarticles')
                     ->set('oxbundleid', ':BUNDLEID')
                     ->where('OXID = :OXID')
                     ->setParameter(':OXID', self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM)
                     ->setParameter(':BUNDLEID', self::INACTIVE_PRODUCT)
                     ->execute();


        $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM . '") {
                id
                bundleProduct {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertNull($result['body']['data']['product']['bundleProduct']);

        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder->update('oxarticles')
                     ->set('oxbundleid', ':BUNDLEID')
                     ->where('OXID = :OXID')
                     ->setParameter(':OXID', self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM)
                     ->setParameter(':BUNDLEID', '')
                     ->execute();
    }

    public function testGetInvisibleProductBundleItemRelation()
    {
        $this->prepareToken();

        $result = $this->query('query {
            product (id: "' . self::ACTIVE_MAIN_BUNDLE_PRODUCT . '") {
                id
                bundleProduct {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame(
            '_test_inactive_bundle',
            $result['body']['data']['product']['bundleProduct']['id']
        );
    }

    public function testGetExistingProductBundleItemRelation()
    {
        $queryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class);

        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder->update('oxarticles')
                     ->set('oxbundleid', ':BUNDLEID')
                     ->where('OXID = :OXID')
                     ->setParameter(':OXID', self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM)
                     ->setParameter(':BUNDLEID', self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM)
                     ->execute();

        $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM . '") {
                id
                bundleProduct {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame(
            self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM,
            $result['body']['data']['product']['bundleProduct']['id']
        );

        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder->update('oxarticles')
                     ->set('oxbundleid', ':BUNDLEID')
                     ->where('OXID = :OXID')
                     ->setParameter(':OXID', self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM)
                     ->setParameter(':BUNDLEID', '')
                     ->execute();
    }

    public function testInactiveBundleProductsWithToken()
    {
        $this->prepareToken();

        $queryBuilderFactory = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class);

        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder->update('oxarticles')
            ->set('oxactive', 0)
            ->set('oxbundleid', ':BUNDLEID')
            ->where('OXID = :OXID')
            ->setParameter(':OXID', self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM)
            ->setParameter(':BUNDLEID', self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM)
            ->execute();

        $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM . '") {
                id
                bundleProduct {
                    id
                    active
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame(
            [
                'id'     => self::ACTIVE_PRODUCT_WITH_BUNDLE_ITEM,
                'active' => false
            ],
            $result['body']['data']['product']['bundleProduct']
        );
    }

    /**
     * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductScalePrice
     * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductRelationService
     */
    public function testGetScalePricesRelation()
    {
         $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT_WITH_SCALE_PRICES . '") {
                id
                scalePrices {
                    absoluteScalePrice
                    absolutePrice
                    discount
                    amountFrom
                    amountTo
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(
            3,
            $result['body']['data']['product']['scalePrices']
        );
        $this->assertSame(
            [
                'absoluteScalePrice' => true,
                'absolutePrice' => 27.9,
                'discount' => null,
                'amountFrom' => 5,
                'amountTo' => 9,
            ],
            $result['body']['data']['product']['scalePrices'][0]
        );
        $this->assertSame(
            [
                'absoluteScalePrice' => true,
                'absolutePrice' => 25.9,
                'discount' => null,
                'amountFrom' => 10,
                'amountTo' => 19,
            ],
            $result['body']['data']['product']['scalePrices'][1]
        );
        $this->assertSame(
            [
                'absoluteScalePrice' => true,
                'absolutePrice' => 21.9,
                'discount' => null,
                'amountFrom' => 20,
                'amountTo' => 99,
            ],
            $result['body']['data']['product']['scalePrices'][2]
        );
    }

    public function testGetProductCategoryRelation()
    {
        $result = $this->query('query {
            product (id: "' . self::ACTIVE_PRODUCT . '") {
                id
                category {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame(
            '0f40c6a077b68c21f164767c4a903fd2',
            $result['body']['data']['product']['category']['id']
        );
    }
}
