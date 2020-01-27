<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase;

final class VendorTest extends TokenTestCase
{
    private static $ACTIVE_VENDOR = "fe07958b49de225bd1dbc7594fb9a6b0";
    private static $INACTIVE_VENDOR  = "05833e961f65616e55a2208c2ed7c6b8";

    public function testGetSingleActiveVendor()
    {
        $result = $this->query('query {
            vendor (id: "' . self::$ACTIVE_VENDOR . '") {
                id
                active
                icon
                title
                shortdesc
                url
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $url = parse_url($result['body']['data']['vendor']['url']);
        $this->assertNotFalse(
            $url
        );

        $result['body']['data']['vendor']['url'] = $url['path'];

        $this->assertEquals(
            [
                'id' => self::$ACTIVE_VENDOR,
                'active' => true,
                'icon' => null,
                'title' => 'https://fashioncity.com/de',
                'shortdesc' => 'Fashion city',
                'url' => '/Nach-Lieferant/https-fashioncity-com-de/',
            ],
            $result['body']['data']['vendor']
        );
    }

    public function testGetSingleInactiveVendorWithoutToken()
    {
        $result = $this->query('query {
            vendor (id: "' . self::$INACTIVE_VENDOR . '") {
                id
                active
                icon
                title
                shortdesc
                url
            }
        }');

        $this->assertResponseStatus(
            401,
            $result
        );
    }

    public function testGetSingleInactiveVendorWithToken()
    {
        $this->prepareAdminToken();

        $result = $this->query('query {
            vendor (id: "' . self::$INACTIVE_VENDOR . '") {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            [
                'id' => self::$INACTIVE_VENDOR,
            ],
            $result['body']['data']['vendor']
        );
    }

    public function testGetSingleNonExistingVendor()
    {
        $result = $this->query('query {
            vendor (id: "DOES-NOT-EXIST") {
                id
            }
        }');

        $this->assertEquals(404, $result['status']);
    }

    public function testGetVendorListWithoutFilter()
    {
        $result = $this->query('query {
            vendors {
                id
                active
                icon
                title
                shortdesc
                url
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );
        $this->assertCount(
            2,
            $result['body']['data']['vendors']
        );
        for ($i = 0; $i <= 1; $i++) {
            $url = parse_url($result['body']['data']['vendors'][$i]['url']);
            $this->assertNotFalse(
                $url
            );
            $result['body']['data']['vendors'][$i]['url'] = $url['path'];
        }
        $this->assertEquals(
            [
                [
                    "id"        => "a57c56e3ba710eafb2225e98f058d989",
                    "active"    => true,
                    "icon"      => null,
                    "title"     => "www.true-fashion.com",
                    "shortdesc" => "Ethical style outlet",
                    "url"       => "/Nach-Lieferant/www-true-fashion-com/"
                ],
                [
                    "id"        => "fe07958b49de225bd1dbc7594fb9a6b0",
                    "active"    => true,
                    "icon"      => null,
                    "title"     => "https://fashioncity.com/de",
                    "shortdesc" => "Fashion city",
                    "url"       => "/Nach-Lieferant/https-fashioncity-com-de/"
                ],
            ],
            $result['body']['data']['vendors']
        );
    }

    public function testGetVendorListWithAdminToken()
    {
        $this->prepareAdminToken();

        $result = $this->query('query {
            vendors {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            [
                [
                    "id" => "05833e961f65616e55a2208c2ed7c6b8",
                ],
                [
                    "id" => "a57c56e3ba710eafb2225e98f058d989",
                ],
                [
                    "id" => "fe07958b49de225bd1dbc7594fb9a6b0",
                ],
            ],
            $result['body']['data']['vendors']
        );
    }

    public function testGetVendorListWithExactFilter()
    {
        $result = $this->query('query {
            vendors (filter: {
                title: {
                    equals: "www.true-fashion.com"
                }
            }) {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            [
                [
                    "id" => "a57c56e3ba710eafb2225e98f058d989"
                ]
            ],
            $result['body']['data']['vendors']
        );
    }

    public function testGetVendorListWithPartialFilter()
    {
        $result = $this->query('query {
            vendors (filter: {
                title: {
                    contains: "city"
                }
            }) {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            [
                [
                    "id" => "fe07958b49de225bd1dbc7594fb9a6b0"
                ]
            ],
            $result['body']['data']['vendors']
        );
    }

    public function testGetEmptyVendorListWithFilter()
    {
        $result = $this->query('query {
            vendors (filter: {
                title: {
                    contains: "DOES-NOT-EXIST"
                }
            }) {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            0,
            count($result['body']['data']['vendors'])
        );
    }
}
