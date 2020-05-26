<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase;

final class ActionTest extends TokenTestCase
{
    private const ACTIVE_ACTION_WITH_PRODUCTS = 'oxtop5';
    private const ACTIVE_ACTION_WITHOUT_PRODUCTS = 'oxnewsletter';
    private const INACTIVE_ACTION = 'oxstart';
    private const WRONG_TYPE_ACTION = 'b5639c6431b26687321f6ce654878fa5';

    public function testGetSingleActiveActionWithoutProducts()
    {
        $result = $this->query('query {
            action(id: "' . self::ACTIVE_ACTION_WITHOUT_PRODUCTS . '") {
                id
                active
                title
                products {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $action = $result['body']['data']['action'];

        $this->assertEquals([
            'id' => self::ACTIVE_ACTION_WITHOUT_PRODUCTS,
            'active' => true,
            'title' => 'Newsletter',
            'products' => []
        ], $action);
    }

    public function testGetSingleActiveActionWithProducts()
    {
        $result = $this->query('query {
            action(id: "' . self::ACTIVE_ACTION_WITH_PRODUCTS . '") {
                id
                products{
                  id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $products = $result['body']['data']['action']['products'];

        $this->assertCount(4, $products);

        $this->assertEquals([
            [
                'id' => 'fadc492a5807c56eb80b0507accd756b'
            ],
            [
                'id' => 'f4fc98f99e3660bd2ecd7450f832c41a'
            ],
            [
                'id' => 'f4f73033cf5045525644042325355732'
            ],
            [
                'id' => '058de8224773a1d5fd54d523f0c823e0'
            ]
        ], $products);
    }

    public function testGetSingleInactiveAction()
    {
        $result = $this->query('query {
            action (id: "' . self::INACTIVE_ACTION . '") {
                id
                active
            }
        }');

        $this->assertResponseStatus(
            401,
            $result
        );
    }

    public function testGetSingleNonExistingAction()
    {
        $result = $this->query('query {
            action (id: "non_existing_id") {
                id
                active
            }
        }');

        $this->assertResponseStatus(
            404,
            $result
        );
    }

    public function testGetSingleWrongTypeAction()
    {
        $result = $this->query('query {
            action (id: "' . self::WRONG_TYPE_ACTION . '") {
                id
                active
            }
        }');

        $this->assertResponseStatus(
            404,
            $result
        );
    }

    public function testGetSingleInactiveActionForAdminGroupUser()
    {
        $this->prepareToken();

        $result = $this->query('query {
            action (id: "' . self::INACTIVE_ACTION . '") {
                id
                title
                active
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertEquals([
            'id' => self::INACTIVE_ACTION,
            'title' => 'Startseite unten',
            'active' => false
        ], $result['body']['data']['action']);
    }

    public function testGetActionsList()
    {
        $result = $this->query('query {
            actions {
                id,
                title
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(6, $result['body']['data']['actions']);

        $this->assertSame([
            [
                'id' => 'oxbargain',
                'title' => 'Angebot der Woche'
            ],
            [
                'id' => 'oxcatoffer',
                'title' => 'Kategorien-Topangebot'
            ],
            [
                'id' => 'oxnewest',
                'title' => 'Frisch eingetroffen'
            ],
            [
                'id' => 'oxnewsletter',
                'title' => 'Newsletter'
            ],
            [
                'id' => 'oxtop5',
                'title' => 'Topseller'
            ],
            [
                'id' => 'oxtopstart',
                'title' => 'Topangebot Startseite'
            ],
        ], $result['body']['data']['actions']);
    }

    public function testGetActionsListForAdminGroupUser()
    {
        $this->prepareToken();

        $result = $this->query('query {
            actions {
                id,
                title,
                active
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(7, $result['body']['data']['actions']);

        $this->assertEquals([
            [
                'id' => 'oxbargain',
                'title' => 'Angebot der Woche',
                'active' => true,
            ],
            [
                'id' => 'oxcatoffer',
                'title' => 'Kategorien-Topangebot',
                'active' => true,
            ],
            [
                'id' => 'oxnewest',
                'title' => 'Frisch eingetroffen',
                'active' => true,
            ],
            [
                'id' => 'oxnewsletter',
                'title' => 'Newsletter',
                'active' => true,
            ],
            [
                'id' => 'oxstart',
                'title' => 'Startseite unten',
                'active' => false,
            ],
            [
                'id' => 'oxtop5',
                'title' => 'Topseller',
                'active' => true,
            ],
            [
                'id' => 'oxtopstart',
                'title' => 'Topangebot Startseite',
                'active' => true,
            ],
        ], $result['body']['data']['actions']);
    }

    /**
     * @dataProvider actionsListFilterProvider
     */
    public function testGetActionsListWithFilter(string $contains, array $expected): void
    {
        $result = $this->query('query {
            actions(filter: {actionId: {contains: "' . $contains . '"}}) {
                id
                products{
                    id
                }
            }
        }');

        $this->assertResponseStatus(200, $result);

        $this->assertEquals($expected, $result['body']['data']['actions']);
    }

    public function actionsListFilterProvider(): array
    {
        return [
            [
                'new',
                [
                    [
                        'id' => 'oxnewest',
                        'products' => [
                            [
                                'id' => 'f4f73033cf5045525644042325355732',
                            ],
                            [
                                'id' => 'f4f2d8eee51b0fd5eb60a46dff1166d8',
                            ],
                            [
                                'id' => 'dc581d8a115035cbfb0223c9c736f513',
                            ],
                            [
                                'id' => 'b56369b1fc9d7b97f9c5fc343b349ece',
                            ],
                            [
                                'id' => 'ed6573c0259d6a6fb641d106dcb2faec',
                            ],
                            [
                                'id' => '531b537118f5f4d7a427cdb825440922',
                            ],
                            [
                                'id' => 'b56597806428de2f58b1c6c7d3e0e093',
                            ],
                            [
                                'id' => 'b563ab240dc19b89fc0349866b2be9c0',
                            ]
                        ],
                    ],
                    [
                        'id' => 'oxnewsletter',
                        'products' => [],
                    ],
                ],
            ],
            [
                'bar',
                [
                    [
                        'id' => 'oxbargain',
                        'products' => [
                            [
                                'id' => 'dc5ffdf380e15674b56dd562a7cb6aec',
                            ]
                        ],
                    ]
                ],
            ],
            [
                'somethingThatDoesNotExist',
                [],
            ],
        ];
    }
}
