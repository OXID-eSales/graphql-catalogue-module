<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Catalogue\Tests\Integration\TokenTestCase;

final class CategoryTest extends TokenTestCase
{
    private const ACTIVE_CATEGORY = "d86fdf0d67bf76dc427aabd2e53e0a97";
    private const INACTIVE_CATEGORY  = "d8665fef35f4d528e92c3d664f4a00c0";
    private const CATEGORY_WITHOUT_CHILDREN  = "0f4270b89fbef1481958381410a0dbca";
    private const CATEGORY_WITH_CHILDREN  = "943173edecf6d6870a0f357b8ac84d32";

    public function testGetSingleActiveCategory()
    {
        $result = $this->query('query {
            category (id: "' . self::ACTIVE_CATEGORY . '") {
                id
                position
                active
                hidden
                title
                shortDescription
                longDescription
                thumbnail
                externalLink
                template
                defaultSortField
                defaultSortMode
                priceFrom
                priceTo
                icon
                promotionIcon
                vat
                skipDiscount
                showSuffix
                url
                timestamp
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $category = $result['body']['data']['category'];

        $this->assertSame(self::ACTIVE_CATEGORY, $category['id']);
        $this->assertSame(3010102, $category['position']);
        $this->assertTrue($category['active']);
        $this->assertFalse($category['hidden']);
        $this->assertSame('Schuhe', $category['title']);
        $this->assertEmpty($category['shortDescription']);
        $this->assertEmpty($category['longDescription']);
        $this->assertNull($category['thumbnail']);
        $this->assertEmpty($category['externalLink']);
        $this->assertEmpty($category['template']);
        $this->assertEmpty($category['defaultSortField']);
        $this->assertSame('ASC', $category['defaultSortMode']);
        $this->assertSame(0.0, $category['priceFrom']);
        $this->assertSame(0.0, $category['priceTo']);
        $this->assertRegExp(
            '@https?://.*/out/pictures/generated/category/icon/.*/shoes_1_cico.jpg@',
            $category['icon']
        );
        $this->assertNull($category['promotionIcon']);
        $this->assertNull($category['vat']);
        $this->assertFalse($category['skipDiscount']);
        $this->assertTrue($category['showSuffix']);
        $this->assertRegExp('@https?://.*/Bekleidung/Sportswear/Neopren/Schuhe/@', $category['url']);
        $this->assertInstanceOf(
            \DateTimeInterface::class,
            new \DateTimeImmutable($category['timestamp'])
        );

        $this->assertNotFalse(parse_url($result['body']['data']['category']['url']));
        $this->assertNotFalse(parse_url($result['body']['data']['category']['icon']));
    }

    public function testGetSingleInactiveCategoryWithoutToken()
    {
        $result = $this->query('query {
            category (id: "' . self::INACTIVE_CATEGORY . '") {
                id
                active
            }
        }');

        $this->assertResponseStatus(
            401,
            $result
        );
    }

    public function testGetSingleInactiveCategoryWithToken()
    {
        $this->prepareAdminToken();

        $result = $this->query('query {
            category (id: "' . self::INACTIVE_CATEGORY . '") {
                id
                active
            }
        }');

        $this->assertEquals(200, $result['status']);
        $this->assertEquals(
            [
                'id' => self::INACTIVE_CATEGORY,
                'active' => false
            ],
            $result['body']['data']['category']
        );
    }

    public function testGetSingleNonExistingCategory()
    {
        $result = $this->query('query {
            category (id: "DOES-NOT-EXIST") {
                id
            }
        }');

        $this->assertEquals(404, $result['status']);
    }

    public function testGetCategoryRelations()
    {
        $result = $this->query('query {
            category (id: "' . self::ACTIVE_CATEGORY . '") {
                id
                shop {
                    id
                }
                parent {
                    id
                }
                root {
                    id
                    parent {
                        id
                    }
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $category = $result['body']['data']['category'];

        $this->assertSame(
            "1",
            $category['shop']['id']
        );

        $this->assertSame(
            "fad2d80baf7aca6ac54e819e066f24aa",
            $category['parent']['id']
        );

        $this->assertSame(
            "30e44ab83fdee7564.23264141",
            $category['root']['id']
        );

        $this->assertNull(
            $category['root']['parent']
        );
    }

    public function testGetChildrenWhenThereAreNoChildren()
    {
        $result = $this->query('query{
            category(id: "' . self::CATEGORY_WITHOUT_CHILDREN . '"){
                id
                children{id}
            }
        }');

        $children = $result['body']['data']['category']['children'];

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertSame(
            [],
            $children
        );
    }

    public function testGetChildren()
    {
        $result = $this->query('query{
            category(id: "' . self::CATEGORY_WITH_CHILDREN . '"){
                id
                children{id}
            }
         }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $children = $result['body']['data']['category']['children'];

        $this->assertSame($children[0]['id'], '0f40c6a077b68c21f164767c4a903fd2');
        $this->assertSame($children[1]['id'], '0f4270b89fbef1481958381410a0dbca');
        $this->assertSame($children[2]['id'], 'd86d90e4b441aa3f0004dcda5ba5bb38');
    }

    public function testGetAllFieldsOfSingleActiveChildCategory()
    {
        $result = $this->query('query {
            category(id: "' . self::CATEGORY_WITH_CHILDREN . '") {
                children {
                    id
                    position
                    active
                    hidden
                    title
                    shortDescription
                    longDescription
                    thumbnail
                    externalLink
                    template
                    defaultSortField
                    defaultSortMode
                    priceFrom
                    priceTo
                    icon
                    promotionIcon
                    vat
                    skipDiscount
                    showSuffix
                    url
                    timestamp
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $child = $result['body']['data']['category']['children'][0];

        $this->assertSame('0f40c6a077b68c21f164767c4a903fd2', $child['id']);
        $this->assertSame(202, $child['position']);
        $this->assertTrue($child['active']);
        $this->assertFalse($child['hidden']);
        $this->assertSame('Bindungen', $child['title']);
        $this->assertEmpty($child['shortDescription']);
        $this->assertEmpty($child['longDescription']);
        $this->assertNull($child['thumbnail']);
        $this->assertEmpty($child['externalLink']);
        $this->assertEmpty($child['template']);
        $this->assertEmpty($child['defaultSortField']);
        $this->assertSame('ASC', $child['defaultSortMode']);
        $this->assertSame(0.0, $child['priceFrom']);
        $this->assertSame(0.0, $child['priceTo']);
        $this->assertRegExp(
            '@https?://.*/out/pictures/generated/category/icon/.*/wakeboarding_bindings_1_cico.jpg@',
            $child['icon']
        );
        $this->assertNull($child['promotionIcon']);
        $this->assertNull($child['vat']);
        $this->assertFalse($child['skipDiscount']);
        $this->assertTrue($child['showSuffix']);
        $this->assertRegExp('@https?://.*/Wakeboarding/Bindungen/@', $child['url']);
        $this->assertInstanceOf(
            \DateTimeInterface::class,
            new \DateTimeImmutable($child['timestamp'])
        );
    }

    public function testGetCategoryListWithoutFilter()
    {
        $result = $this->query('query {
            categories {
                id
                position
                active
                hidden
                title
                shortDescription
                longDescription
                thumbnail
                externalLink
                template
                defaultSortField
                defaultSortMode
                priceFrom
                priceTo
                icon
                promotionIcon
                vat
                skipDiscount
                showSuffix
                url
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );
        $this->assertCount(
            24,
            $result['body']['data']['categories']
        );
    }

    public function testGetCategoryListWithPartialFilter()
    {
        $result = $this->query('query {
            categories(filter: {
                title: {
                    contains: "l"
                }
            }) {
                id
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );
        $this->assertEquals(
            [
                ["id" => "30e44ab83fdee7564.23264141"],
                ["id" => "oia9ff5c96f1f29d527b61202ece0829"]
            ],
            $result['body']['data']['categories']
        );
    }

    public function testGetCategoryListWithExactFilter()
    {
        $result = $this->query('query {
            categories(filter: {
                title: {
                    equals: "Jeans"
                }
            }) {
                id,
                title
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );
        $this->assertSame(
            [
                [
                    'id' => 'd863b76c6bb90a970a5577adf890e8cd',
                    'title' => 'Jeans'
                ]
            ],
            $result['body']['data']['categories']
        );
    }

    public function testGetEmptyCategoryListWithFilter()
    {
        $result = $this->query('query {
            categories(filter: {
                title: {
                    contains: "DOES-NOT-EXIST"
                }
            }) {
                id
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );
        $this->assertEquals(
            0,
            count($result['body']['data']['categories'])
        );
    }
}
