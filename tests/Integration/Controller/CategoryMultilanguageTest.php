<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

final class CategoryMultiLanguageTest extends TestCase
{
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
        $this->assertResponseStatus(200, $result);

        $this->assertCount(
            $count,
            count($result['body']['data']['categories'])
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
