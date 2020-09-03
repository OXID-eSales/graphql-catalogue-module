<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

final class CategoryMultiLanguageTest extends TestCase
{
    private const ACTIVE_CATEGORY = 'd86fdf0d67bf76dc427aabd2e53e0a97';

    private const CATEGORY_WITH_PRODUCTS = 'fad4d7e2b47d87bb6a2773d93d4ae9be';

    /**
     * @dataProvider providerGetCategoryMultiLanguage
     */
    public function testGetCategoryMultiLanguage(string $languageId, string $title): void
    {
        $query = 'query {
            category (id: "' . self::ACTIVE_CATEGORY . '") {
                id
                title
            }
        }';

        $this->setGETRequestParameter(
            'lang',
            $languageId
        );

        $result = $this->query($query);

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertEquals(
            [
                'id'    => self::ACTIVE_CATEGORY,
                'title' => $title,
            ],
            $result['body']['data']['category']
        );
    }

    public function providerGetCategoryMultiLanguage()
    {
        return [
            'de' => [
                'languageId' => '0',
                'title'      => 'Schuhe',
            ],
            'en' => [
                'languageId' => '1',
                'title'      => 'Shoes',
            ],
        ];
    }

    /**
     * @dataProvider providerGetCategoryProductsMultiLanguage
     */
    public function testGetCategoryProductsMultiLanguage(string $languageId, array $products): void
    {
        $this->setGETRequestParameter(
            'lang',
            $languageId
        );

        $result = $this->query('query {
            category (id: "' . self::CATEGORY_WITH_PRODUCTS . '") {
                title
                products {
                    title
                }
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertEquals(
            $products,
            $result['body']['data']['category']['products']
        );
    }

    public function providerGetCategoryProductsMultiLanguage()
    {
        return [
            'de' => [
                'languageId' => '0',
                'products'   => [
                    [
                        'title' => 'Kuyichi Gürtel JUNO',
                    ],
                    [
                        'title' => 'Kuyichi Ledergürtel JEVER',
                    ],
                    [
                        'title' => 'Sonnenbrille TRIGGERNAUT AGENT ORANGE',
                    ],
                ],
            ],
            'en' => [
                'languageId' => '1',
                'products'   => [
                    [
                        'title' => 'Kuyichi belt JUNO',
                    ],
                    [
                        'title' => 'Kuyichi leather belt JEVER',
                    ],
                    [
                        'title' => 'Sun glasses TRIGGERNAUT AGENT ORANGE',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider providerGetCategoryListWithFilterMultiLanguage
     */
    public function testGetCategoryListWithFilterMultiLanguage(
        string $languageId,
        string $contains,
        int $count
    ): void {
        $query = 'query{
            categories(filter: {
                title: {
                    contains: "' . $contains . '"
                }
            }){
                id
            }
        }';

        $this->setGETRequestParameter('lang', $languageId);

        $result = $this->query($query);

        $this->assertResponseStatus(
            200,
            $result
        );

        $this->assertCount(
            $count,
            $result['body']['data']['categories']
        );
    }

    public function providerGetCategoryListWithFilterMultiLanguage(): array
    {
        return [
            'de' => [
                'languageId' => '0',
                'contains'   => 'Sch',
                'count'      => 1,
            ],
            'en' => [
                'languageId' => '1',
                'contains'   => 'Sho',
                'count'      => 1,
            ],
        ];
    }

    /**
     * @dataProvider providerGetCategoryListWithFilterMultiLanguage
     */
    public function testSortedCategoriesListByTitle(string $languageId): void
    {
        $this->setGETRequestParameter('lang', $languageId);

        $result = $this->query('query {
            categories(
                sort: {
                    position: ""
                    title: "ASC"
                }
            ) {
                id
                title
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $titles = [];

        foreach ($result['body']['data']['categories'] as $category) {
            $titles[$category['id']] = $category['title'];
        }

        $expected = $titles;
        asort($expected, SORT_STRING);
        $this->assertSame($expected, $titles);
    }
}
