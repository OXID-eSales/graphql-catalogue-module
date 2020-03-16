<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Element2ShopRelations;
use OxidEsales\GraphQL\Base\Tests\Integration\MultishopTestCase;

/**
 * Class AttributeEnterpriseTest
 * @package OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller
 */
class AttributeEnterpriseTest extends MultishopTestCase
{
    private const ATTRIBUTE_ID = "6cf89d2d73e666457d167cebfc3eb492";

    /**
     * Check if attribute from shop 1 is not accessible for
     * shop 2 if its not yet related to shop 2
     */
    public function testGetSingleNotInShopAttributeWillFail()
    {
        $this->setGETRequestParameter('shp', "2");

        $result = $this->query('query {
            attribute (id: "' . self::ATTRIBUTE_ID . '") {
                title
            }
        }');

        $this->assertEquals(
            404,
            $result['status']
        );
    }

    /**
     * Check if attribute from shop 1 is accessible for
     * shop 2 if its related to shop 2
     */
    public function testGetSingleInShopAttributeWillWork()
    {
        $this->setGETRequestParameter('shp', "2");
        $this->addAttributeToShops([2]);

        $result = $this->query('query {
            attribute (id: "' . self::ATTRIBUTE_ID . '") {
                title
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );

        $this->assertEquals(
            [
                'title' => 'Lieferumfang'
            ],
            $result['body']['data']['attribute']
        );
    }

    /**
     * @return array
     */
    public function providerGetAttributeMultilanguage(): array
    {
        return [
            'shop_1_de' => [
                'shopId' => '1',
                'languageId' => '0',
                'title' => 'Lieferumfang'
            ],
            'shop_1_en' => [
                'shopId' => '1',
                'languageId' => '1',
                'title' => 'Included in delivery'
            ],
            'shop_2_de' => [
                'shopId' => '2',
                'languageId' => '0',
                'title' => 'Lieferumfang'
            ],
            'shop_2_en' => [
                'shopId' => '2',
                'languageId' => '1',
                'title' => 'Included in delivery'
            ],
        ];
    }

    /**
     * Check multishop multilanguage data is accessible
     *
     * @dataProvider providerGetAttributeMultilanguage
     *
     * @param string $shopId
     * @param string $languageId
     * @param string $title
     */
    public function testGetSingleTranslatedSecondShopAttribute(string $shopId, string $languageId, string $title)
    {
        $this->setGETRequestParameter('shp', $shopId);
        $this->setGETRequestParameter('lang', $languageId);
        $this->addAttributeToShops([2]);

        $result = $this->query('query {
            attribute (id: "' . self::ATTRIBUTE_ID . '") {
                title
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );

        $this->assertEquals(
            [
                'title' => $title
            ],
            $result['body']['data']['attribute']
        );
    }

    /**
     * @param int[] $shops
     */
    private function addAttributeToShops(array $shops)
    {
        $oElement2ShopRelations = oxNew(Element2ShopRelations::class, 'oxattribute');
        $oElement2ShopRelations->setShopIds($shops);
        $oElement2ShopRelations->addToShop(self::ATTRIBUTE_ID);
    }
}
