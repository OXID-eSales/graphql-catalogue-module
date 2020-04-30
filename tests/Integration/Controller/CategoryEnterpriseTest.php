<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Element2ShopRelations;
use OxidEsales\GraphQL\Base\Tests\Integration\MultishopTestCase;

/**
 * Class CategoryEnterpriseTest
 *
 * @package OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller
 */
class CategoryEnterpriseTest extends MultishopTestCase
{
    private const CATEGORY_IDS = [
        'shoes-active' => 'd86fdf0d67bf76dc427aabd2e53e0a97',
        'jeans-active' => 'd863b76c6bb90a970a5577adf890e8cd',
        'jeans-inactive' => 'd8665fef35f4d528e92c3d664f4a00c0',
        'supplies-active' => 'fc7e7bd8403448f00a363f60f44da8f2',
    ];
    private const PRODUCT_ID = 'd86236918e1533cccb679208628eda32';
    private const CATEGORY_PRODUCT_RELATION = 'd8677dff861fb6b83f29f3558e7394c4';

    /**
     * Check if active category from shop 1 is not accessible for
     * shop 2 if its not yet related to shop 2
     */
    public function testGetSingleNotInShopActiveCategoryWillFail()
    {
        $this->setGETRequestParameter('shp', '2');

        $result = $this->query('query {
            category (id: "' . self::CATEGORY_IDS['shoes-active'] . '") {
                id
            }
        }');

        $this->assertEquals(
            404,
            $result['status']
        );
    }

    /**
     * Check if active category from shop 1 is accessible for
     * shop 2 if its related to shop 2
     */
    public function testGetSingleInShopActiveCategoryWillWork()
    {
        $this->setGETRequestParameter('shp', '2');
        $this->setGETRequestParameter('lang', '0');
        $this->addCategoryToShops(self::CATEGORY_IDS['shoes-active'], [2]);

        $result = $this->query('query {
            category (id: "' . self::CATEGORY_IDS['shoes-active'] . '") {
                id,
                title
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );

        $this->assertEquals(
            [
                'id' => self::CATEGORY_IDS['shoes-active'],
                'title' => 'Schuhe'
            ],
            $result['body']['data']['category']
        );
    }

    /**
     * @return array
     */
    public function providerGetCategoryMultilanguage()
    {
        return [
            'shop_1_de' => [
                'shopId' => '1',
                'languageId' => '0',
                'title' => 'Schuhe'
            ],
            'shop_1_en' => [
                'shopId' => '1',
                'languageId' => '1',
                'title' => 'Shoes'
            ],
            'shop_2_de' => [
                'shopId' => '2',
                'languageId' => '0',
                'title' => 'Schuhe'
            ],
            'shop_2_en' => [
                'shopId' => '2',
                'languageId' => '1',
                'title' => 'Shoes'
            ],
        ];
    }

    /**
     * Check multishop multilanguage data is accessible
     *
     * @dataProvider providerGetCategoryMultilanguage
     */
    public function testGetSingleTranslatedSecondShopCategory($shopId, $languageId, $title)
    {
        $this->setGETRequestParameter('shp', $shopId);
        $this->setGETRequestParameter('lang', $languageId);

        $result = $this->query('query {
            category (id: "' . self::CATEGORY_IDS['shoes-active'] . '") {
                id
                title
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );

        $this->assertEquals(
            [
                'id' => self::CATEGORY_IDS['shoes-active'],
                'title' => $title
            ],
            $result['body']['data']['category']
        );
    }

    /**
     * Check if only one, related to the shop 2 category is available in list
     */
    public function testGetOneCategoryInListOfNotMainShop()
    {
        $this->setGETRequestParameter('shp', "2");

        $result = $this->query('query{
            categories {
                id
            }
        }');
        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertEquals(
            1,
            count($result['body']['data']['categories'])
        );
    }

    /**
     * Check multishop multilanguage data is accessible
     *
     * @dataProvider providerGetCategoryMultilanguage
     */
    public function testGetListTranslatedSecondShopCategories($shopId, $languageId, $title)
    {
        $this->setGETRequestParameter('shp', $shopId);
        $this->setGETRequestParameter('lang', $languageId);

        $result = $this->query('query {
            categories(filter: {
                title: {
                    equals: "' . $title . '"
                }
            }) {
                id,
                title
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );

        $this->assertEquals(
            [
                'id' => self::CATEGORY_IDS['shoes-active'],
                'title' => $title
            ],
            $result['body']['data']['categories'][0]
        );
    }

    public function testGetListByFilterNotInShopActiveCategoryWillReturnEmpty()
    {
        $this->setGETRequestParameter('shp', '2');

        $result = $this->query('query {
            categories (filter: {
                title: {
                    equals: "Jeans"
                }
            }) {
                id
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );

        $this->assertEmpty($result['body']['data']['categories']);
    }

    public function testGetListAllActiveInSecondShop()
    {
        $this->setGETRequestParameter('shp', '2');
        $this->setGETRequestParameter('lang', '0');
        $this->addCategoryToShops(self::CATEGORY_IDS['jeans-active'], [2]);
        $this->addCategoryToShops(self::CATEGORY_IDS['jeans-inactive'], [2]);
        $this->addCategoryToShops(self::CATEGORY_IDS['supplies-active'], [2]);

        $result = $this->query('query {
            categories {
                id
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );

        $this->assertEquals(
            [
                ['id' => self::CATEGORY_IDS['jeans-active']],
                ['id' => self::CATEGORY_IDS['shoes-active']],
                ['id' => self::CATEGORY_IDS['supplies-active']],
            ],
            $result['body']['data']['categories']
        );
    }

    public function testSingleCategoryProductsInSecondShop()
    {
        $this->setGETRequestParameter('shp', '2');
        $this->setGETRequestParameter('lang', '0');

        $this->addCategoryToShops(self::CATEGORY_IDS['supplies-active'], [2]);
        $this->addProductToShops(self::PRODUCT_ID, [2]);
        $this->addProductToCategory(self::CATEGORY_PRODUCT_RELATION, [2]);

        $result = $this->query('query {
            category (id: "' . self::CATEGORY_IDS['supplies-active'] . '") {
                title
                products {
                    title
                }
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );

        $this->assertEquals(
            [
                [
                    'title' => 'Smart Loop NAISH'
                ]
            ],
            $result['body']['data']['category']['products']
        );
    }

    public function testCategoryListProductsInSecondShop()
    {
        $this->addCategoryToShops(self::CATEGORY_IDS['shoes-active'], [2]);
        $this->addCategoryToShops(self::CATEGORY_IDS['jeans-active'], [2]);

        $this->addCategoryToShops(self::CATEGORY_IDS['supplies-active'], [2]);
        $this->addProductToShops(self::PRODUCT_ID, [2]);
        $this->addProductToCategory(self::CATEGORY_PRODUCT_RELATION, [2]);

        $this->setGETRequestParameter('shp', '2');
        $this->setGETRequestParameter('lang', '0');

        $result = $this->query('query {
            categories {
                title
                products {
                    title
                }
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );

        $this->assertCount(
            3,
            $result['body']['data']['categories']
        );

        $this->assertCount(
            0,
            $result['body']['data']['categories'][1]['products']
        );

        $this->assertEquals(
            [
                [
                    'title' => 'Smart Loop NAISH'
                ]
            ],
            $result['body']['data']['categories'][2]['products']
        );
    }

    /**
     * @param string $categoryId
     * @param array $shops
     */
    private function addCategoryToShops(string $categoryId, array $shops)
    {
        $oElement2ShopRelations = oxNew(Element2ShopRelations::class, 'oxcategories');
        $oElement2ShopRelations->setShopIds($shops);
        $oElement2ShopRelations->addToShop($categoryId);
    }

    /**
     * @param string $productId
     * @param array  $shops
     */
    private function addProductToShops(string $productId, array $shops)
    {
        $oElement2ShopRelations = oxNew(Element2ShopRelations::class, 'oxarticles');
        $oElement2ShopRelations->setShopIds($shops);
        $oElement2ShopRelations->addToShop($productId);
    }

    private function addProductToCategory(string $relationId, array $shops)
    {
        $element2ShopRelations = oxNew(Element2ShopRelations::class, 'oxobject2category');
        $element2ShopRelations->setShopIds($shops);
        $element2ShopRelations->addToShop($relationId);
    }
}
