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
                title
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
                'id' => 'oxstart',
                'title' => 'Startseite unten'
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
}
