<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase;

final class BannerTest extends TokenTestCase
{
    private const ACTIVE_BANNER_WITH_PRODUCT = 'b5639c6431b26687321f6ce654878fa5';
    private const ACTIVE_BANNER_WITHOUT_PRODUCT = 'cb34f86f56162d0c95890b5985693710';
    private const INACTIVE_BANNER = 'b56a097dedf5db44e20ed56ac6defaa8';
    private const INACTIVE_BANNER_WITH_INTERVAL = '_test_active_interval';
    private const WRONG_TYPE_ACTION = 'd51545e80843be666a9326783a73e91d';

    /**
     * If product assigned, link is pointing to product
     */
    public function testGetSingleActiveBannerWithProduct()
    {
        $result = $this->query('query {
            banner(id: "' . self::ACTIVE_BANNER_WITH_PRODUCT . '") {
                id
                active
                title
                picture
                link
                sorting
                product{
                  id
                  title
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $banner = $result['body']['data']['banner'];

        $this->assertArraySubset([
            'id' => self::ACTIVE_BANNER_WITH_PRODUCT,
            'active' => true,
            'title' => 'Banner 1',
            'sorting' => 4,
            'product' => [
                'id' => 'f4fc98f99e3660bd2ecd7450f832c41a',
                'title' => 'Neoprenanzug NPX ASSASSIN'
            ]
        ], $banner);

        $this->assertRegExp(
            '@https?://.*/Bekleidung/Sportswear/Neopren/Anzuege/Neoprenanzug-NPX-ASSASSIN.html$@',
            $banner['link']
        );

        $this->assertRegExp(
            '@https?://.*/out/pictures/promo/surfer_wave_promo.jpg$@',
            $banner['picture']
        );
    }

    /**
     * This case will checks different link generation process, its not a link to product anymore
     */
    public function testGetSingleActiveBannerWithoutProduct()
    {
        $result = $this->query('query {
            banner(id: "' . self::ACTIVE_BANNER_WITHOUT_PRODUCT . '") {
                id
                active
                title
                picture
                link
                sorting
                product{
                  id
                  title
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $banner = $result['body']['data']['banner'];

        $this->assertArraySubset([
            'id' => self::ACTIVE_BANNER_WITHOUT_PRODUCT,
            'active' => true,
            'title' => 'Banner 4',
            'sorting' => 1,
            'product' => null
        ], $banner);

        $this->assertRegExp(
            '@https?://.*/Wakeboarding/Wakeboards/.*?$@',
            $banner['link']
        );

        $this->assertRegExp(
            '@https?://.*/out/pictures/promo/banner4de\(1\)_promo.jpg$@',
            $banner['picture']
        );
    }

    public function testInactive()
    {
        $result = $this->query('query {
            banner (id: "' . self::INACTIVE_BANNER . '") {
                id
                active
            }
        }');

        $this->assertResponseStatus(
            401,
            $result
        );
    }

    public function testNotExisting()
    {
        $result = $this->query('query {
            banner (id: "wrong_id") {
                id
                active
            }
        }');

        $this->assertResponseStatus(
            404,
            $result
        );
    }

    public function testWrongType()
    {
        $result = $this->query('query {
            banner (id: "' . self::WRONG_TYPE_ACTION . '") {
                id
                active
            }
        }');

        $this->assertResponseStatus(
            404,
            $result
        );
    }

    public function testInactiveButActiveInterval()
    {
        $result = $this->query('query {
            banner (id: "' . self::INACTIVE_BANNER_WITH_INTERVAL . '") {
                id
                active
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertEquals(
            [
                'id' => self::INACTIVE_BANNER_WITH_INTERVAL,
                'active' => true
            ],
            $result['body']['data']['banner']
        );
    }

    public function testGetBannersList()
    {
        $result = $this->query('query {
            banners {
                id,
                sorting
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame([
            [
                'id' => 'cb34f86f56162d0c95890b5985693710',
                'sorting' => 1
            ],
            [
                'id' => 'b56efaf6c93664b6dca5b1cee1f87057',
                'sorting' => 2
            ],
            [
                'id' => 'b5639c6431b26687321f6ce654878fa5',
                'sorting' => 4
            ],
            [
                'id' => '_test_active_interval',
                'sorting' => 5
            ],
        ], $result['body']['data']['banners']);
    }

    public function testInactiveWithToken()
    {
        $this->prepareToken();

        $result = $this->query('query {
            banner (id: "' . self::INACTIVE_BANNER . '") {
                id
                active
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertEquals(
            [
                'id' => self::INACTIVE_BANNER,
                'active' => false
            ],
            $result['body']['data']['banner']
        );
    }

    public function testGetBannersListForAdminGroupUser()
    {
        $this->prepareToken();

        $result = $this->query('query {
            banners {
                id,
                sorting
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame([
            [
                'id' => 'cb34f86f56162d0c95890b5985693710',
                'sorting' => 1
            ],
            [
                'id' => 'b56efaf6c93664b6dca5b1cee1f87057',
                'sorting' => 2
            ],
            [
                'id' => 'b5639c6431b26687321f6ce654878fa5',
                'sorting' => 4
            ],
            [
                'id' => '_test_active_interval',
                'sorting' => 5
            ],
            [
                'id' => '_test_group_banner',
                'sorting' => 6
            ]
        ], $result['body']['data']['banners']);
    }
}
