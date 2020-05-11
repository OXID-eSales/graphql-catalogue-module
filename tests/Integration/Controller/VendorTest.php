<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase;
use TheCodingMachine\GraphQLite\Types\DateTimeType;

final class VendorTest extends TokenTestCase
{
    private const ACTIVE_VENDOR = "fe07958b49de225bd1dbc7594fb9a6b0";
    private const INACTIVE_VENDOR  = "05833e961f65616e55a2208c2ed7c6b8";

    public function testGetSingleActiveVendor()
    {
        $result = $this->query('query {
            vendor (id: "' . self::ACTIVE_VENDOR . '") {
                id
                active
                icon
                title
                shortdesc
                timestamp
                seo {
                  description
                  keywords
                  url
                }
                products {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $vendor = $result['body']['data']['vendor'];
        $this->assertSame(self::ACTIVE_VENDOR, $vendor['id']);
        $this->assertTrue($vendor['active']);
        $this->assertNull($vendor['icon']);
        $this->assertEquals('https://fashioncity.com/de', $vendor['title']);
        $this->assertSame('Fashion city', $vendor['shortdesc']);
        $this->assertRegExp('@https?://.*/Nach-Lieferant/https-fashioncity-com-de/$@', $vendor['seo']['url']);
        $this->assertEquals('german vendor seo description', $vendor['seo']['description']);
        $this->assertEquals('german vendor seo keywords', $vendor['seo']['keywords']);

        $dateTimeType = DateTimeType::getInstance();
        //Fixture timestamp can have few seconds difference
        $this->assertLessThanOrEqual(
            $dateTimeType->serialize(new \DateTimeImmutable('now')),
            $vendor['timestamp']
        );

        $this->assertEquals(
            [
                ['id' => '10049f9322cf8852f8d567e9662cb12c'],
                ['id' => '10067ab25bf275b7e68bc0431b204d24'],
                ['id' => '1008b12cef0476f5e941da460ba621e6']
            ],
            $vendor['products']
        );

        $this->assertEmpty(array_diff(array_keys($vendor), [
            'id',
            'active',
            'icon',
            'title',
            'shortdesc',
            'timestamp',
            'seo',
            'products'
        ]));
    }

    public function testGetSingleInactiveVendorWithoutToken()
    {
        $result = $this->query('query {
            vendor (id: "' . self::INACTIVE_VENDOR . '") {
                id
                active
                icon
                title
                shortdesc
                seo {
                  description
                  keywords
                  url
                }
            }
        }');

        $this->assertResponseStatus(
            401,
            $result
        );
    }

    public function testGetSingleInactiveVendorWithToken()
    {
        $this->prepareToken();

        $result = $this->query('query {
            vendor (id: "' . self::INACTIVE_VENDOR . '") {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            [
                'id' => self::INACTIVE_VENDOR,
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
        $this->assertSame(
            [
                [
                    "id"        => "a57c56e3ba710eafb2225e98f058d989"
                ],
                [
                    "id"        => "fe07958b49de225bd1dbc7594fb9a6b0"
                ],
            ],
            $result['body']['data']['vendors']
        );
    }

    public function testGetVendorListWithAdminToken()
    {
        $this->prepareToken();

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

    public function testVendorProductsWithOffsetAndLimit()
    {
        $result = $this->query('query {
            vendor (id: "' . self::ACTIVE_VENDOR . '") {
                products(pagination: {limit: 1, offset: 1}) {
                    title
                }
            }
        }');

        $this->assertEquals(200, $result['status']);

        $products = $result['body']['data']['vendor']['products'];

        $this->assertEquals([['title' => 'Kuyichi T-Shirt TIGER']], $products);
    }
}
