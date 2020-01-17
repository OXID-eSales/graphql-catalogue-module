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

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            [
                [
                    "id"        => "a57c56e3ba710eafb2225e98f058d989",
                    "active"    => true,
                    "icon"      => "",
                    "title"     => "www.true-fashion.com",
                    "shortdesc" => "Ethical style outlet",
                    "url"       => "Nach-Lieferant/www-true-fashion-com/"
                ],
                [
                    "id"        => "fe07958b49de225bd1dbc7594fb9a6b0",
                    "active"    => true,
                    "icon"      => "",
                    "title"     => "https://fashioncity.com",
                    "shortdesc" => "Fashion city",
                    "url"       => "Nach-Lieferant/https-fashioncity-com/"
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
