<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Element2ShopRelations;
use OxidEsales\GraphQL\Base\Tests\Integration\MultishopTestCase;

/**
 * Class ProductEnterpriseTest
 * @package OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller
 */
class ProductEnterpriseTest extends MultishopTestCase
{
    private const PRODUCT_ID = "058e613db53d782adfc9f2ccb43c45fe";

    /**
     * Check if active product from shop 1 is not accessible for
     * shop 2 if its not yet related to shop 2
     */
    public function testGetSingleNotInShopActiveProductWillFail()
    {
        $this->setGETRequestParameter('shp', "2");

        $result = $this->query('query {
            product (id: "' . self::PRODUCT_ID . '") {
                id
            }
        }');

        $this->assertEquals(
            404,
            $result['status']
        );
    }

    /**
     * Check if active product from shop 1 is accessible for
     * shop 2 if its related to shop 2
     */
    public function testGetSingleInShopActiveProductWillWork()
    {
        $this->setGETRequestParameter('shp', "2");
        $this->setGETRequestParameter('lang', '0');
        $this->addProductToShops([2]);

        $result = $this->query('query {
            product (id: "' . self::PRODUCT_ID . '") {
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
                'id' => self::PRODUCT_ID,
                'title' => 'Bindung O&#039;BRIEN DECADE CT 2010'
            ],
            $result['body']['data']['product']
        );
    }

    /**
     * @return array
     */
    public function providerGetProductMultilanguage()
    {
        return [
            'shop_1_de' => [
                'shopId' => '1',
                'languageId' => '0',
                'title' => 'Bindung O&#039;BRIEN DECADE CT 2010'
            ],
            'shop_1_en' => [
                'shopId' => '1',
                'languageId' => '1',
                'title' => 'Binding O&#039;BRIEN DECADE CT 2010'
            ],
            'shop_2_de' => [
                'shopId' => '2',
                'languageId' => '0',
                'title' => 'Bindung O&#039;BRIEN DECADE CT 2010'
            ],
            'shop_2_en' => [
                'shopId' => '2',
                'languageId' => '1',
                'title' => 'Binding O&#039;BRIEN DECADE CT 2010'
            ],
        ];
    }

    /**
     * Check multishop multilanguage data is accessible
     *
     * @dataProvider providerGetProductMultilanguage
     */
    public function testGetSingleTranslatedSecondShopProduct($shopId, $languageId, $title)
    {
        $this->setGETRequestParameter('shp', $shopId);
        $this->setGETRequestParameter('lang', $languageId);
        $this->addProductToShops([2]);

        $result = $this->query('query {
            product (id: "' . self::PRODUCT_ID . '") {
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
                'id' => self::PRODUCT_ID,
                'title' => $title
            ],
            $result['body']['data']['product']
        );
    }

    /**
     * @param int|array $shops
     */
    private function addProductToShops($shops)
    {
        $oElement2ShopRelations = oxNew(Element2ShopRelations::class, 'oxarticles');
        $oElement2ShopRelations->setShopIds($shops);
        $oElement2ShopRelations->addToShop(self::PRODUCT_ID);
    }
}
