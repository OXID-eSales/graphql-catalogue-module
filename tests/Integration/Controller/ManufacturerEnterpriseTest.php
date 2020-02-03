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

    private function addManufacturerToShops($shops)
    {
        $oElement2ShopRelations = oxNew(Element2ShopRelations::class, 'oxmanufacturers');
        $oElement2ShopRelations->setShopIds($shops);
        $oElement2ShopRelations->addToShop(self::MANUFACTURER_ID);
    }
}
