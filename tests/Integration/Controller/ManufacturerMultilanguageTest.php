<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

class ManufacturerMultilanguageTest extends TestCase
{

    private const ACTIVE_MULTILANGUAGE_MANUFACTURER = 'adc6df0977329923a6330cc8f3c0a906';

    public function providerGetManufacturerMultilanguage()
    {
        return [
            'de' => [
                'languageId'         => '0',
                'title'              => 'Liquid Force',
                'url'                => 'Nach-Hersteller/Liquid-Force/',
                'productDescription' => 'Bewährte Qualität in neuem Design'
            ],
            'en' => [
                'languageId'         => '1',
                'title'              => 'Liquid Force Kite',
                'url'                => 'en/By-manufacturer/Liquid-Force-Kite/',
                'productDescription' => 'Proven quality in a new design'
            ],
        ];
    }

    /**
     * @dataProvider providerGetManufacturerMultilanguage
     */
    public function testGetManufacturerMultilanguage(
        string $languageId,
        string $title,
        string $seoUrl,
        string $productDescription
    ) {
        $query = 'query {
            manufacturer (id: "' . self::ACTIVE_MULTILANGUAGE_MANUFACTURER . '") {
                id
                title
                seo {
                    url
                },
                 products(offset: null, limit: 1)
                {
                  shortDescription
                }
            }
        }';

        $this->setGETRequestParameter(
            'lang',
            $languageId
        );

        $result = $this->query($query);
        $this->assertResponseStatus(
            200,
            $result
        );

        $manufacturer = $result['body']['data']['manufacturer'];

        $this->assertSame(self::ACTIVE_MULTILANGUAGE_MANUFACTURER, $manufacturer['id']);
        $this->assertEquals($title, $manufacturer['title']);
        $this->assertRegExp('@https?://.*' . $seoUrl . '$@', $manufacturer['seo']['url']);
        $this->assertSame($productDescription, $manufacturer['products'][0]['shortDescription']);
    }

    public function providerGetManufacturerListWithFilterMultilanguage()
    {
        return [
            'de' => [
                'languageId' => '0',
                'count'      => 0
            ],
            'en' => [
                'languageId' => '1',
                'count'      => 1
            ]
        ];
    }

    /**
     * @dataProvider providerGetManufacturerListWithFilterMultilanguage
     */
    public function testGetManufacturerListWithFilterMultilanguage(string $languageId, int $count)
    {
        $query = 'query{
            manufacturers(filter: {
                title: {
                    contains: "Force Kite"
                }
            }){
                id
            }
        }';

        $this->setGETRequestParameter(
            'lang',
            $languageId
        );

        $result = $this->query($query);
        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(
            $count,
            $result['body']['data']['manufacturers']
        );
    }
}
