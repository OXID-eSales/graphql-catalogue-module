<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Application\Model\Content as EshopContent;
use OxidEsales\GraphQL\Base\Tests\Integration\MultishopTestCase;

/**
 * Class ContentEnterpriseTest
 *
 * @package OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller
 */
class ContentEnterpriseTest extends MultishopTestCase
{
    private const CONTENT_ID = '1074279e67a85f5b1.96907412';

    /**
     * Active content from shop 1 is not accessible for shop 2
     */
    public function testGetActiveContentFromOtherShopWillFail()
    {
        $this->setGETRequestParameter('shp', '2');

        $result = $this->query('query {
            content (id: "' . self::CONTENT_ID . '") {
                id
            }
        }');

        $this->assertEquals(
            404,
            $result['status']
        );
    }

    /**
     * Check if no contents available while they are not related to the shop 2
     */
    public function _testGetEmptyContentListOfNotMainShop()
    {
        $this->setGETRequestParameter('shp', '2');

        $result = $this->query('query{
            contents {
                id
            }
        }');
        $this->assertResponseStatus(
            200,
            $result
        );
        $this->assertCount(
            0,
            $result['body']['data']['contents']
        );
    }

    /**
     * Check if active content from shop 1 is accessible for
     * shop 2 if its related to shop 2
     */
    public function _testGetSingleInShopActiveContentWillWork()
    {
        $this->setGETRequestParameter('shp', '2');
        $this->setGETRequestParameter('lang', '0');
        $this->addContentToShops([2]);

        $result = $this->query('query {
            content (id: "' . self::CONTENT_ID . '") {
                id,
                title
                products {
                    id
                }
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );

        $this->assertEquals(
            [
                'id' => self::CONTENT_ID,
                'title' => 'https://fashioncity.com/de',
                'products' => [
                    ['id' => '10067ab25bf275b7e68bc0431b204d24']
                ]
            ],
            $result['body']['data']['content']
        );
    }

    /**
     * Check if only one, related to the shop 2 content is available in list
     */
    public function _testGetOneContentInListOfNotMainShop()
    {
        $this->setGETRequestParameter('shp', '2');
        $this->addContentToShops([2]);

        $result = $this->query('query{
            contents {
                id
            }
        }');
        $this->assertResponseStatus(
            200,
            $result
        );
        // fixtures have 2 active contents
        $this->assertCount(
            1,
            $result['body']['data']['contents']
        );
    }

    /**
     * @return array
     */
    public function providerGetContentMultilanguage()
    {
        return [
            'shop_1_de' => [
                'shopId' => '1',
                'languageId' => '0',
                'title' => 'https://fashioncity.com/de'
            ],
            'shop_1_en' => [
                'shopId' => '1',
                'languageId' => '1',
                'title' => 'https://fashioncity.com/en'
            ],
            'shop_2_de' => [
                'shopId' => '2',
                'languageId' => '0',
                'title' => 'https://fashioncity.com/de'
            ],
            'shop_2_en' => [
                'shopId' => '2',
                'languageId' => '1',
                'title' => 'https://fashioncity.com/en'
            ],
        ];
    }

    /**
     * Check multishop multilanguage data is accessible
     *
     * @dataProvider providerGetContentMultilanguage
     */
    public function _testGetSingleTranslatedSecondShopContent($shopId, $languageId, $title)
    {
        $this->setGETRequestParameter('shp', $shopId);
        $this->setGETRequestParameter('lang', $languageId);
        $this->addContentToShops([2]);

        $result = $this->query('query {
            content (id: "' . self::CONTENT_ID . '") {
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
                'id' => self::CONTENT_ID,
                'title' => $title
            ],
            $result['body']['data']['content']
        );
    }

    /**
     * Check multishop multilanguage data is accessible
     *
     * @dataProvider providerGetContentMultilanguage
     */
    public function _testGetListTranslatedSecondShopContents($shopId, $languageId, $title)
    {
        $this->setGETRequestParameter('shp', $shopId);
        $this->setGETRequestParameter('lang', $languageId);
        $this->addContentToShops([2]);

        $result = $this->query('query {
            contents(filter: {
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
                'id' => self::CONTENT_ID,
                'title' => $title
            ],
            $result['body']['data']['contents'][0]
        );
    }

    private function addContentToShops($shops)
    {
        $content = oxNew(EshopContent::class);
        $content->load(self::CONTENT_ID);
        foreach ($shops as $shopId) {
            $content->setId('_subshop_' . $shopId);
            $content->assign(['oxshopid' => $shopId]);
            $content->save();
        }
    }
}
