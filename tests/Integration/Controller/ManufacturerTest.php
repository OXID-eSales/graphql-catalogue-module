<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

class ManufacturerTest extends TestCase
{

    private static $ACTIVE_MANUFACTURER = "oiaf6ab7e12e86291e86dd3ff891fe40";
    private static $INACTIVE_MANUFACTURER  = "dc50589ad69b6ec71721b25bdd403171";
    private static $ACTIVE_MULTILANGUAGE_MANUFACTURER = 'adc6df0977329923a6330cc8f3c0a906';

    protected function setUp(): void
    {
        parent::setUp();

        $language = \OxidEsales\Eshop\Core\Registry::getLang();
        $language->resetBaseLanguage();
    }

    public function testGetSingleActiveManufacturer()
    {
        $result = $this->query('query {
            manufacturer (id: "' . self::$ACTIVE_MANUFACTURER . '") {
                id
                active
                icon
                title
                shortdesc
                url
                timestamp
            }
        }');
        $this->assertResponseStatus(
            200,
            $result
        );
        $timestamp = $result['body']['data']['manufacturer']['timestamp'];
        unset($result['body']['data']['manufacturer']['timestamp']);
        $this->assertEquals(
            [
                'id' => self::$ACTIVE_MANUFACTURER,
                'active' => 1,
                'icon' => 'oreilly_1_mico.png',
                'title' => 'O\'Reilly',
                'shortdesc' => '',
                'url' => 'Nach-Hersteller/O-Reilly/',
            ],
            $result['body']['data']['manufacturer']
        );
    }

    public function testGet401ForSingleInactiveManufacturer()
    {
        $result = $this->query('query {
            manufacturer (id: "' . self::$INACTIVE_MANUFACTURER . '") {
                id
                active
                icon
                title
                shortdesc
                url
                timestamp
            }
        }');
        $this->assertResponseStatus(
            401,
            $result
        );
    }

    public function testGet404ForSingleNonExistingManufacturer()
    {
        $result = $this->query('query {
            manufacturer (id: "DOES-NOT-EXIST") {
                id
                active
                icon
                title
                shortdesc
                url
                timestamp
            }
        }');
        $this->assertResponseStatus(
            404,
            $result
        );
    }

    public function testGetManufacturerListWithoutFilter()
    {
        $result = $this->query('query{
            manufacturers {
                id
                active
                icon
                title
                shortdesc
                url
                timestamp
            }
        }');
        $this->assertResponseStatus(
            200,
            $result
        );
        // fixtures have 11 active manufacturers
        $this->assertEquals(
            11,
            count($result['body']['data']['manufacturers'])
        );
    }

    public function testGetManufacturerListWithFilter()
    {
        $result = $this->query('query{
            manufacturers(filter: {
                title: {
                    contains: "l"
                }
            }){
                id
            }
        }');
        $this->assertResponseStatus(
            200,
            $result
        );
        // fixtures have 3 active manufacturers with lowercase l and 3 inactive
        $this->assertEquals(
            3,
            count($result['body']['data']['manufacturers'])
        );
    }

    public function testGetEmptyManufacturerListWithFilter()
    {
        $result = $this->query('query{
            manufacturers(filter: {
                title: {
                    beginsWith: "Fly"
                }
            }){
                id
            }
        }');
        $this->assertResponseStatus(
            200,
            $result
        );
        // fixtures have 2 inactive manufacturers starting with Fly
        $this->assertEquals(
            0,
            count($result['body']['data']['manufacturers'])
        );
    }

    public function testGetEmptyManufacturerListWithExactMatchFilter()
    {
        $result = $this->query('query{
            manufacturers(filter: {
                title: {
                    equals: "DOES-NOT-EXIST"
                }
            }){
                id
            }
        }');
        $this->assertResponseStatus(
            200,
            $result
        );
        // fixtures have 0 manufacturers matching title DOES-NOT-EXIST
        $this->assertEquals(
            0,
            count($result['body']['data']['manufacturers'])
        );
    }

    public function providerGetManufacturerMultilanguage()
    {
        return [
            'de' => [
                'languageId' => '0',
                'title'      => 'Liquid Force'
            ],
            'en' => [
                'languageId' => '1',
                'title'      => 'Liquid Force Kite'
            ],
        ];
    }

    /**
     * @dataProvider providerGetManufacturerMultilanguage
     */
    public function testGetManufacturerMultilanguage(string $languageId, string $title)
    {
        $query = 'query {
            manufacturer (id: "' . self::$ACTIVE_MULTILANGUAGE_MANUFACTURER . '") {
                id
                title
            }
        }';

        $this->setGETRequestParameter(
            'lang',
            $languageId
        );

        $result = $this->query($query);
        $this->assertResponseStatus($result, 200);

        $this->assertEquals(
            [
                'id' => self::$ACTIVE_MULTILANGUAGE_MANUFACTURER,
                'title' => $title
            ],
            $result['body']['data']['manufacturer']
        );
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
        $this->assertResponseStatus($result, 200);

        $this->assertEquals(
            $count,
            count($result['body']['data']['manufacturers'])
        );
    }
}
