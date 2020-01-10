<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

class ManufacturerTest extends TestCase
{

    private static $ACTIVE_MANUFACTURER = "oiaf6ab7e12e86291e86dd3ff891fe40";
    private static $INACTIVE_MANUFACTURER  = "dc50589ad69b6ec71721b25bdd403171";

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
        $this->assertEquals(
            200,
            $result['status']
        );
        $timestamp = $result['body']['data']['manufacturer']['timestamp'];
        unset($result['body']['data']['manufacturer']['timestamp']);
        $this->assertEquals(
            [
                'id' => self::OREILLY_ID,
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
        $this->assertEquals(
            401,
            $result['status']
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
        $this->assertEquals(
            404,
            $result['status']
        );
    }
}
