<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Element2ShopRelations;
use OxidEsales\GraphQL\Base\Tests\Integration\MultishopTestCase;

/**
 * Class ManufacturerEnterpriseTest
 * @package OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller
 */
class ManufacturerEnterpriseTest extends MultishopTestCase
{
    private const MANUFACTURER_ID = "adc6df0977329923a6330cc8f3c0a906";
    private const MANUFACTURER_WITH_SINGLE_PRODUCT = "3a9fd0ec4b41d001e770b1d2d7af3e73";
    private const PRODUCT_RELATED_TO_MANUFACTURER = 'f4f73033cf5045525644042325355732';

    /**
     * Check if active manufacturer from shop 1 is not accessible for
     * shop 2 if its not yet related to shop 2
     */
    public function testGetSingleNotInShopActiveManufacturerWillFail()
    {
        $this->setGETRequestParameter('shp', "2");

        $result = $this->query('query {
            manufacturer (id: "' . self::MANUFACTURER_ID . '") {
                id
            }
        }');

        $this->assertEquals(
            404,
            $result['status']
        );
    }

    /**
     * Check if no manufacturers available while they are not related to the shop 2
     */
    public function testGetEmptyManufacturerListOfNotMainShop()
    {
        $this->setGETRequestParameter('shp', "2");

        $result = $this->query('query{
            manufacturers {
                id
            }
        }');
        $this->assertResponseStatus(
            200,
            $result
        );
        // fixtures have 11 active manufacturers
        $this->assertCount(
            0,
            $result['body']['data']['manufacturers']
        );
    }

    /**
     * Check if active manufacturer from shop 1 is accessible for
     * shop 2 if its related to shop 2
     */
    public function testGetSingleInShopActiveManufacturerWillWork()
    {
        $this->setGETRequestParameter('shp', "2");
        $this->addManufacturerToShops([2]);

        $result = $this->query('query {
            manufacturer (id: "' . self::MANUFACTURER_ID . '") {
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
                'id' => self::MANUFACTURER_ID,
                'title' => 'Liquid Force'
            ],
            $result['body']['data']['manufacturer']
        );
    }

    /**
     * Check if only one, related to the shop 2 manufacturer is available in list
     */
    public function testGetOneManufacturerInListOfNotMainShop()
    {
        $this->setGETRequestParameter('shp', "2");
        $this->addManufacturerToShops([2]);

        $result = $this->query('query{
            manufacturers {
                id
            }
        }');
        $this->assertResponseStatus(
            200,
            $result
        );
        // fixtures have 11 active manufacturers
        $this->assertCount(
            1,
            $result['body']['data']['manufacturers']
        );
    }

    /**
     * @return array
     */
    public function providerGetManufacturerMultilanguage()
    {
        return [
            'shop_1_de' => [
                'shopId' => '1',
                'languageId' => '0',
                'title' => 'Liquid Force'
            ],
            'shop_1_en' => [
                'shopId' => '1',
                'languageId' => '1',
                'title' => 'Liquid Force Kite'
            ],
            'shop_2_de' => [
                'shopId' => '2',
                'languageId' => '0',
                'title' => 'Liquid Force'
            ],
            'shop_2_en' => [
                'shopId' => '2',
                'languageId' => '1',
                'title' => 'Liquid Force Kite'
            ],
        ];
    }

    /**
     * Check multishop multilanguage data is accessible
     *
     * @dataProvider providerGetManufacturerMultilanguage
     */
    public function testGetSingleTranslatedSecondShopManufacturer($shopId, $languageId, $title)
    {
        $this->setGETRequestParameter('shp', $shopId);
        $this->setGETRequestParameter('lang', $languageId);
        $this->addManufacturerToShops([2]);

        $result = $this->query('query {
            manufacturer (id: "' . self::MANUFACTURER_ID . '") {
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
                'id' => self::MANUFACTURER_ID,
                'title' => $title
            ],
            $result['body']['data']['manufacturer']
        );
    }

    /**
     * Check multishop multilanguage data is accessible
     *
     * @dataProvider providerGetManufacturerMultilanguage
     */
    public function testGetListTranslatedSecondShopManufacturers($shopId, $languageId, $title)
    {
        $this->setGETRequestParameter('shp', $shopId);
        $this->setGETRequestParameter('lang', $languageId);
        $this->addManufacturerToShops([2]);

        $result = $this->query('query {
            manufacturers(filter: {
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
                'id' => self::MANUFACTURER_ID,
                'title' => $title
            ],
            $result['body']['data']['manufacturers'][0]
        );
    }

    /**
     * Check if products can be accessed within shop 1
     */
    public function testGetActiveProduct()
    {
        $this->setGETRequestParameter('shp', "1");

        $result = $this->query('query {
            manufacturer (id: "' . self::MANUFACTURER_ID . '") {
                 products(offset: null, limit: null)
                {
                  id
                }
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );
        //fixtures have 7 active products assigned to manufacturer in shop 1
        $this->assertEquals(7, sizeof($result['body']['data']['manufacturer']['products']));
    }

    public function testGetProductFromSecondShop()
    {
        $this->setGETRequestParameter('shp', "2");

        $this->addManufacturerToShops([2], self::MANUFACTURER_WITH_SINGLE_PRODUCT);
        $this->addProductToShops([2]);

        $result = $this->query('query {
            manufacturer (id: "' . self::MANUFACTURER_WITH_SINGLE_PRODUCT . '") {
                id
                 products(offset: null, limit: null)
                {
                  id
                }
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            self::PRODUCT_RELATED_TO_MANUFACTURER,
            $result['body']['data']['manufacturer']['products'][0]['id']
        );
    }

    public function testProductIsNotFetchedFromFirstShop()
    {
        $this->setGETRequestParameter('shp', "2");

        $this->addManufacturerToShops([2], self::MANUFACTURER_ID);
        $this->addProductToShops([1]);

        $result = $this->query('query {
            manufacturer (id: "' . self::MANUFACTURER_ID . '") {
                id
                 products(offset: null, limit: null)
                {
                  id
                }
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(0, sizeof($result['body']['data']['manufacturer']['products']));
    }

    private function addManufacturerToShops($shops, $manufacturer = null)
    {
        $manufacturerId = $manufacturer == null ? self::MANUFACTURER_ID : $manufacturer;
        $oElement2ShopRelations = oxNew(Element2ShopRelations::class, 'oxmanufacturers');
        $oElement2ShopRelations->setShopIds($shops);
        $oElement2ShopRelations->addToShop($manufacturerId);
    }

    private function addProductToShops($shops)
    {
        $oElement2ShopRelations = oxNew(Element2ShopRelations::class, 'oxarticles');
        $oElement2ShopRelations->setShopIds($shops);
        $oElement2ShopRelations->addToShop(self::PRODUCT_RELATED_TO_MANUFACTURER);
    }
}
