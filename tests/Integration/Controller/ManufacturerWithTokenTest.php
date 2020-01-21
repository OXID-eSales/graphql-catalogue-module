<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase;
use TheCodingMachine\GraphQLite\Types\DateTimeType;

final class ManufacturerWithTokenTest extends TokenTestCase
{
    //Kuyichi
    private const ACTIVE_MANUFACTURER = "9434afb379a46d6c141de9c9e5b94fcf";

    //RRD
    private const INACTIVE_MANUFACTURER  = "adca51c88a3caa1c7b939fd6a229ae3a";

    protected function setUp(): void
    {
        parent::setUp();

        $this->prepareAdminToken();
    }

    public function testGetSingleActiveManufacturer()
    {
        $result = $this->query('query {
            manufacturer (id: "' . self::ACTIVE_MANUFACTURER . '") {
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

        $timestamp = $result['body']['data']['manufacturer']['timestamp'];
        unset($result['body']['data']['manufacturer']['timestamp']);

        $this->assertEquals(
            [
                'id'        => self::ACTIVE_MANUFACTURER,
                'active'    => true,
                'icon'      => 'logo3_ico.png',
                'title'     => 'Kuyichi',
                'shortdesc' => 'Eine stilbewusste Marke',
                'url'       => 'Nach-Hersteller/Kuyichi/',
            ],
            $result['body']['data']['manufacturer']
        );

        $dateTimeType = DateTimeType::getInstance();

        //Fixture timestamp can have few seconds difference
        $this->assertLessThanOrEqual(
            $dateTimeType->serialize(new \DateTimeImmutable('now')),
            $timestamp
        );
    }

    public function testGetSingleInactiveManufacturer()
    {
        $result = $this->query('query {
            manufacturer (id: "' . self::INACTIVE_MANUFACTURER . '") {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);

        $this->assertEquals(
            [
                'id' => self::INACTIVE_MANUFACTURER,
            ],
            $result['body']['data']['manufacturer']
        );
    }

    public function testGetSingleNonExistingManufacturer()
    {
        $result = $this->query('query {
            manufacturer (id: "DOES-NOT-EXIST") {
                id
            }
        }');

        $this->assertEquals(404, $result['status']);
    }

    public function testGetManufacturerListWithoutFilter()
    {
        $result = $this->query('query {
            manufacturers {
                id
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
            [
                [
                    "id" => "dc50589ad69b6ec71721b25bdd403171"
                ],
                [
                    "id" => "dc59459d4d67189182c53ed0e4e777bc"
                ]
            ],
            $result['body']['data']['manufacturers']
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
            [
                [
                    "id" => "dc50589ad69b6ec71721b25bdd403171"
                ]
            ],
            $result['body']['data']['manufacturers']
        );
    }
}
