<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

final class ManufacturerWithTokenTest extends TestCase
{
    //Kuyichi
    private static $ACTIVE_MANUFACTURER = "9434afb379a46d6c141de9c9e5b94fcf";

    //RRD
    private static $INACTIVE_MANUFACTURER  = "adca51c88a3caa1c7b939fd6a229ae3a";

    protected function setUp(): void
    {
        parent::setUp();

        $result = $this->query('query {
            token (
                username: "admin",
                password: "admin"
            )
        }');

        $this->setAuthToken($result['body']['data']['token']);
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

        $this->assertEquals(200, $result['status']);

        unset($result['body']['data']['manufacturer']['timestamp']);

        $this->assertEquals(
            [
                'id'        => self::$ACTIVE_MANUFACTURER,
                'active'    => true,
                'icon'      => 'logo3_ico.png',
                'title'     => 'Kuyichi',
                'shortdesc' => 'Eine stilbewusste Marke',
                'url'       => 'Nach-Hersteller/Kuyichi/',
            ],
            $result['body']['data']['manufacturer']
        );
    }

    public function testGetSingleInactiveManufacturer()
    {
        $this->setAuthToken(self::$token);
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

        $this->assertEquals(200, $result['status']);

        unset($result['body']['data']['manufacturer']['timestamp']);

        $this->assertEquals(
            [
                'id'        => self::$INACTIVE_MANUFACTURER,
                'active'    => 0,
                'icon'      => '',
                'title'     => 'RRD',
                'shortdesc' => '',
                'url'       => '',
            ],
            $result['body']['data']['manufacturer']
        );
    }

    public function testGetSingleNonExistingManufacturer()
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

        $this->assertEquals(404, $result['status']);
    }

    public function testGetManufacturerListWithoutFilter()
    {
        $result = $this->query('query {
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

        $this->assertEquals(200, $result['status']);

        // fixtures have total 15 manufacturers, 4 inactive and 11 active
        $this->assertEquals(
            15,
            count($result['body']['data']['manufacturers'])
        );
    }

    public function testGetManufacturerListWithPartialFilter()
    {
        $result = $this->query('query {
            manufacturers(filter: {
                title: {
                    beginsWith: "Fly"
                }
            }) {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            2,
            count($result['body']['data']['manufacturers'])
        );
    }

    public function testGetEmptyManufacturerListWithExactMatchFilter()
    {
        $result = $this->query('query {
            manufacturers(filter: {
                title: {
                    equals: "Flysurfer"
                }
            }) {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            1,
            count($result['body']['data']['manufacturers'])
        );
    }
}
