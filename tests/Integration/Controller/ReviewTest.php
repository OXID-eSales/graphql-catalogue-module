<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase;

final class ReviewTest extends TokenTestCase
{
    private const ACTIVE_REVIEW = '94415306f824dc1aa2fce0dc4f12783d';
    private const INACTIVE_REVIEW = 'bcb341381858129f7412beb11c827a25';
    private const WRONG_USER = '_test_wrong_user';
    private const WRONG_PRODUCT = '_test_wrong_product';
    private const WRONG_OBJECT_TYPE = '_test_wrong_object_type';

    public function testGetSingleActiveReview()
    {
        $result = $this->query('query {
            review(id: "' . self::ACTIVE_REVIEW . '") {
                id
                active
                text
                rating
                createAt
                user {
                    id
                    firstName
                    lastName
                }
                product {
                    id
                    title
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $product = $result['body']['data']['review'];

        $this->assertSame([
            'id' => self::ACTIVE_REVIEW,
            'active' => true,
            'text' => 'Fantastic kite with great performance!',
            'rating' => 5,
            'createAt' => '2011-03-25T16:51:05+01:00',
            'user' => [
                'id' => 'e7af1c3b786fd02906ccd75698f4e6b9',
                'firstName' => 'Marc',
                'lastName' => 'Muster'
            ],
            'product' => [
                'id' => 'b56597806428de2f58b1c6c7d3e0e093',
                'title' => 'Kite NBK EVO 2010'
            ]
        ], $product);
    }

    public function testGetSingleInactiveReviewWithoutToken()
    {
        $result = $this->query('query {
            review (id: "' . self::INACTIVE_REVIEW . '") {
                id
                active
            }
        }');

        $this->assertResponseStatus(
            401,
            $result
        );
    }

    public function testGetSingleInactiveReviewWithToken()
    {
        $this->prepareToken();

        $result = $this->query('query {
            review (id: "' . self::INACTIVE_REVIEW . '") {
                id
                active
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            [
                'id' => self::INACTIVE_REVIEW,
                'active' => false
            ],
            $result['body']['data']['review']
        );
    }

    public function testGetSingleNonExistingReview()
    {
        $result = $this->query('query {
            review (id: "DOES-NOT-EXIST") {
                id
            }
        }');

        $this->assertEquals(404, $result['status']);
    }

    public function testGetWrongUserCase()
    {
        $result = $this->query('query {
            review(id: "' . self::WRONG_USER . '") {
                id
                user {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $product = $result['body']['data']['review'];

        $this->assertSame([
            'id' => self::WRONG_USER,
            'user' => null
        ], $product);
    }

    /**
     * @dataProvider nullProductIdsDataProvider
     */
    public function testGetWrongProductCase($id)
    {
        $result = $this->query('query {
            review(id: "' . $id . '") {
                id
                product {
                    id
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $product = $result['body']['data']['review'];

        $this->assertSame([
            'id' => $id,
            'product' => null
        ], $product);
    }

    public function nullProductIdsDataProvider()
    {
        return [
            [self::WRONG_PRODUCT],
            [self::WRONG_OBJECT_TYPE]
        ];
    }
}
