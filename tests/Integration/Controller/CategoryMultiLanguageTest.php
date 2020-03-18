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

    /**
     * @dataProvider providerGetCategoryMultiLanguage
     *
     * @param string $languageId
     * @param string $title
     */
    public function testGetCategoryMultiLanguage(string $languageId, string $title)
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
                'id' => self::ACTIVE_CATEGORY,
                'title' => $title
            ],
            $result['body']['data']['category']
        );
    }

    public function providerGetCategoryMultiLanguage()
    {
        return [
            'de' => [
                'languageId' => '0',
                'title'      => 'Schuhe'
            ],
            'en' => [
                'languageId' => '1',
                'title'      => 'Shoes'
            ],
        ];
    }

    /**
     * @dataProvider providerGetCategoryListWithFilterMultiLanguage
     *
     * @param string $languageId
     * @param string $contains
     * @param int    $count
     */
    public function testGetCategoryListWithFilterMultiLanguage(
        string $languageId,
        string $contains,
        int $count
    ) {
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
                'contains' => 'Sch',
                'count' => 1
            ],
            'en' => [
                'languageId' => '1',
                'contains' => 'Sho',
                'count' => 1
            ]
        ];
    }
}
