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
    private const ALL_CATEGORIES = [
        [
            'id' => "0f40c6a077b68c21f164767c4a903fd2",
            'position' => 202,
            'active' => true,
            'hidden' => false,
            'title' => "Bindungen",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "wakeboarding_bindings_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Wakeboarding/Bindungen",
        ],
        [
            'id' => "0f41a4463b227c437f6e6bf57b1697c4",
            'position' => 103,
            'active' => true,
            'hidden' => false,
            'title' => "Trapeze",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "trapeze_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Kiteboarding/Trapeze",
        ],
        [
            'id' => "0f4270b89fbef1481958381410a0dbca",
            'position' => 201,
            'active' => true,
            'hidden' => false,
            'title' => "Wakeboards",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "wakeboarding_boards_1_cico.jpg",
            'promotionIcon' => "cat_promo_wakeboards_pico.jpg",
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Wakeboarding/Wakeboards",
        ],
        [
            'id' => "0f4f08358666c54b4fde3d83d2b7ef04",
            'position' => 102,
            'active' => true,
            'hidden' => false,
            'title' => "Kiteboards",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "kiteboards_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Kiteboarding/Kiteboards",
        ],
        [
            'id' => "0f4fb00809cec9aa0910aa9c8fe36751",
            'position' => 101,
            'active' => true,
            'hidden' => false,
            'title' => "Kites",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "kites_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Kiteboarding/Kites",
        ],
        [
            'id' => "30e44ab83fdee7564.23264141",
            'position' => 3,
            'active' => true,
            'hidden' => false,
            'title' => "Bekleidung",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => "bekleidung_1_tc.jpg",
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => null,
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung",
        ],
        [
            'id' => "943173edecf6d6870a0f357b8ac84d32",
            'position' => 2,
            'active' => true,
            'hidden' => false,
            'title' => "Wakeboarding",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => "wakeboarding_1_tc.jpg",
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => null,
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Wakeboarding",
        ],
        [
            'id' => "943a9ba3050e78b443c16e043ae60ef3",
            'position' => 1,
            'active' => true,
            'hidden' => false,
            'title' => "Kiteboarding",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => "kiteboarding_1_tc.jpg",
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => null,
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Kiteboarding",
        ],
        [
            'id' => "d862abc1f98741797cf889eb4a9090ad",
            'position' => 3020202,
            'active' => true,
            'hidden' => false,
            'title' => "Shirts &amp; Co.",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "shirts_co_m_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Fashion/Fuer-Ihn/Shirts-Co",
        ],
        [
            'id' => "d863b76c6bb90a970a5577adf890e8cd",
            'position' => 3020101,
            'active' => true,
            'hidden' => false,
            'title' => "Jeans",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Fashion/Fuer-Sie/Jeans",
        ],
        [
            'id' => "d86779840626d3ab8263b62db85df3f0",
            'position' => 3020102,
            'active' => true,
            'hidden' => false,
            'title' => "Shirts &amp; Co.",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "shirts_co_w_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Fashion/Fuer-Sie/Shirts-Co",
        ],
        [
            'id' => "d86d90e4b441aa3f0004dcda5ba5bb38",
            'position' => 203,
            'active' => true,
            'hidden' => false,
            'title' => "Sets",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "wakeboarding_sets_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Wakeboarding/Sets",
        ],
        [
            'id' => "d86fdf0d67bf76dc427aabd2e53e0a97",
            'position' => 3010102,
            'active' => true,
            'hidden' => false,
            'title' => "Schuhe",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "shoes_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Sportswear/Neopren/Schuhe",
        ],
        [
            'id' => "fad181ad64642b955becd0759345161e",
            'position' => 302,
            'active' => true,
            'hidden' => false,
            'title' => "Fashion",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => "_tc.jpg",
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "fashion_1_cico.jpg",
            'promotionIcon' => "cat_promo_pico.jpg",
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Fashion",
        ],
        [
            'id' => "fad2a9b0037b71ff1107ae725aae8d1c",
            'position' => 30102,
            'active' => true,
            'hidden' => false,
            'title' => "Sonstiges",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "bekleidung_sonstiges_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Sportswear/Sonstiges",
        ],
        [
            'id' => "fad2d80baf7aca6ac54e819e066f24aa",
            'position' => 30101,
            'active' => true,
            'hidden' => false,
            'title' => "Neopren",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => "neopren_1_tc.jpg",
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "neopren_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Sportswear/Neopren",
        ],
        [
            'id' => "fad4d7e2b47d87bb6a2773d93d4ae9be",
            'position' => 30203,
            'active' => true,
            'hidden' => false,
            'title' => "Accessoires",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "access_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Fashion/Accessoires",
        ],
        [
            'id' => "fad569d6659caca39bc93e98d13dd58b",
            'position' => 301,
            'active' => true,
            'hidden' => false,
            'title' => "Sportswear",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => "sportswear_1_tc.jpg",
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Sportswear",
        ],
        [
            'id' => "fad7facadcb7d4297f033d242aa0d310",
            'position' => 30202,
            'active' => true,
            'hidden' => false,
            'title' => "Für Ihn",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "fuer_ihn_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Fashion/Fuer-Ihn",
        ],
        [
            'id' => "fada9485f003c731b7fad08b873214e0",
            'position' => 3010101,
            'active' => true,
            'hidden' => false,
            'title' => "Anzüge",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "wetsuits_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Sportswear/Neopren/Anzuege",
        ],
        [
            'id' => "fadbdb3145458afc5aa4dbf7eb906761",
            'position' => 30201,
            'active' => true,
            'hidden' => false,
            'title' => "Für Sie",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Bekleidung/Fashion/Fuer-Sie",
        ],
        [
            'id' => "fadcb6dd70b9f6248efa425bd159684e",
            'position' => 4,
            'active' => true,
            'hidden' => false,
            'title' => "Angebote",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => "angebote_1_tc.jpg",
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => null,
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Angebote",
        ],
        [
            'id' => "fc7e7bd8403448f00a363f60f44da8f2",
            'position' => 104,
            'active' => true,
            'hidden' => false,
            'title' => "Zubehör",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => "kiteboarding_accessoires_1_cico.jpg",
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Kiteboarding/Zubehoer",
        ],
        [
            'id' => "oia9ff5c96f1f29d527b61202ece0829",
            'position' => 5,
            'active' => true,
            'hidden' => false,
            'title' => "Downloads",
            'shortDescription' => "",
            'longDescription' => "",
            'thumbnail' => null,
            'externalLink' => "",
            'template' => "",
            'defaultSortField' => "",
            'defaultSortMode' => "ASC",
            'priceFrom' => 0,
            'priceTo' => 0,
            'icon' => null,
            'promotionIcon' => null,
            'vat' => null,
            'skipDiscount' => false,
            'showSuffix' => true,
            'url' => "Downloads",
        ],
    ];

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
        $this->assertEmpty(
            array_diff_assoc(self::ALL_CATEGORIES, $result['body']['data']['categories'])
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
        $this->assertCount(
            2,
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
                id
            }
        }');

        $this->assertEquals(
            200,
            $result['status']
        );
        $this->assertCount(
            1,
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
