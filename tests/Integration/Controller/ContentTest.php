<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase;
use TheCodingMachine\GraphQLite\Types\DateTimeType;

final class ContentTest extends TokenTestCase
{
    private const ACTIVE_CONTENT = "1074279e67a85f5b1.96907412"; // how to order
    private const INACTIVE_CONTENT  = "67c5bcf75ee346bd9566bce6c8"; // credits
    private const ACTIVE_CONTENT_AGB = "2eb4676806a3d2e87.06076523"; //agb

    public function testGetSingleActiveContent()
    {
        $result = $this->query('query {
            content (id: "' . self::ACTIVE_CONTENT . '") {
                id
                active
                title
                content
                folder
                version
                seo {
                  url
                }
                category {
                  id
                  title
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $content = $result['body']['data']['content'];
        $this->assertSame(self::ACTIVE_CONTENT, $content['id']);
        $this->assertTrue($content['active']);
        $this->assertEquals('Wie bestellen?', $content['title']);
        $this->assertEquals('CMSFOLDER_USERINFO', $content['folder']);
        $this->assertEmpty($content['version']);
        $this->assertEquals($content['category']['id'], '30e44ab83fdee7564.23264141');
        $this->assertEquals($content['category']['title'], 'Bekleidung');
        $this->assertRegExp('@https?://.*/Wie-bestellen/$@', $content['seo']['url']);
        $this->assertContains('Wenn Sie eine Bestellung aufgeben', $content['content']);

        $this->assertEmpty(array_diff(array_keys($content), [
            'id',
            'active',
            'title',
            'content',
            'folder',
            'version',
            'seo',
            'category'
        ]));
    }

    public function testGetSingleActiveContentWithVersion()
    {
        $result = $this->query('query {
            content (id: "' . self::ACTIVE_CONTENT_AGB . '") {
                id
                version
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $content = $result['body']['data']['content'];
        $this->assertSame(self::ACTIVE_CONTENT_AGB, $content['id']);
        $this->assertEquals(1, $content['version']);

        $this->assertEmpty(array_diff(array_keys($content), [
            'id',
            'version'
        ]));
    }

    public function testGetSingleInactiveContentWithoutToken()
    {
        $result = $this->query('query {
            content (id: "' . self::INACTIVE_CONTENT . '") {
                id
                active
                title
                content
                folder
                version
                seo {
                  url
                }
                category {
                  id
                  title
                }
            }
        }');

        $this->assertResponseStatus(
            401,
            $result
        );
    }

    public function testGetSingleInactiveContentWithToken()
    {
        $this->prepareToken();

        $result = $this->query('query {
            content (id: "' . self::INACTIVE_CONTENT . '") {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            [
                'id' => self::INACTIVE_CONTENT,
            ],
            $result['body']['data']['content']
        );
    }

    public function testGetSingleNonExistingContent()
    {
        $result = $this->query('query {
            content (id: "DOES-NOT-EXIST") {
                id
            }
        }');

        $this->assertEquals(404, $result['status']);
    }

    public function testGetContentListWithoutFilter()
    {
        $result = $this->query('query {
            contents {
                id
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );
        $this->assertCount(
            46,
            $result['body']['data']['contents']
        );
    }

    public function testGetContentListWithAdminToken()
    {
        $this->prepareToken();

        $result = $this->query('query {
            contents {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertCount(
            47,
            $result['body']['data']['contents']
        ); //for admin token we get the inactive one as well
    }

    public function testGetContentListWithExactFilter()
    {
        $result = $this->query('query {
            contents (filter: {
                folder: {
                    equals: "CMSFOLDER_EMAILS"
                }
            }) {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertCount(
            25,
            $result['body']['data']['contents']
        );
    }

    public function testGetContentListWithPartialFilter()
    {
        $result = $this->query('query {
            contents (filter: {
                folder: {
                    contains: "FOLDER"
                }
            }) {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertCount(
            40,
            $result['body']['data']['contents']
        );
    }

    public function testGetEmptyContentListWithFilter()
    {
        $result = $this->query('query {
            contents (filter: {
                folder: {
                    contains: "DOES-NOT-EXIST"
                }
            }) {
                id
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            0,
            count($result['body']['data']['contents'])
        );
    }
}
