<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

final class VendorMultiLanguageTest extends TestCase
{
    private static $ACTIVE_VENDOR = 'fe07958b49de225bd1dbc7594fb9a6b0';

    /**
     * @dataProvider providerGetVendorListWithFilterMultiLanguage
     *
     * @param string $languageId
     * @param string $contains
     * @param int    $count
     * @param array  $vendors
     */
    public function testGetVendorListWithFilterMultiLanguage(
        string $languageId,
        string $contains,
        int $count,
        array $vendors
    ) {
        $query = 'query{
            vendors(filter: {
                title: {
                    contains: "' . $contains . '"
                }
            }){
                title
                url
            }
        }';

        $this->setGETRequestParameter('lang', $languageId);

        $result = $this->query($query);
        $this->assertResponseStatus(200, $result);

        $this->assertEquals(
            $count,
            count($result['body']['data']['vendors'])
        );

        $this->assertEquals(
            $vendors,
            $result['body']['data']['vendors']
        );
    }

    public function providerGetVendorListWithFilterMultiLanguage(): array
    {
        return [
            'de' => [
                'languageId' => '0',
                'contains' => 'de',
                'count' => 1,
                'result' => [
                    [
                        'title' => 'https://fashioncity.com/de',
                        'url' => 'Nach-Lieferant/https-fashioncity-com-de/'
                    ]
                ]
            ],
            'en' => [
                'languageId' => '1',
                'contains' => 'en',
                'count' => 1,
                'result' => [
                    [
                        'title' => 'https://fashioncity.com/en',
                        'url' => 'en/By-distributor/https-fashioncity-com-en/'
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider providerGetVendorMultiLanguage
     *
     * @param string $languageId
     * @param string $title
     */
    public function testGetVendorMultiLanguage(string $languageId, string $title)
    {
        $query = 'query {
            vendor (id: "' . self::$ACTIVE_VENDOR . '") {
                id
                title
            }
        }';

        $this->setGETRequestParameter(
            'lang',
            $languageId
        );

        $result = $this->query($query);
        $this->assertResponseStatus(200, $result);

        $this->assertEquals(
            [
                'id' => self::$ACTIVE_VENDOR,
                'title' => $title
            ],
            $result['body']['data']['vendor']
        );
    }

    public function providerGetVendorMultiLanguage()
    {
        return [
            'de' => [
                'languageId' => '0',
                'title'      => 'https://fashioncity.com/de'
            ],
            'en' => [
                'languageId' => '1',
                'title'      => 'https://fashioncity.com/en'
            ],
        ];
    }
}
