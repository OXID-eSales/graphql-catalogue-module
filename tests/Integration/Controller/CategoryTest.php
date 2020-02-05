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
}
