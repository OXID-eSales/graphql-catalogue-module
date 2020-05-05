<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

final class ContentMultiLanguageTest extends TestCase
{
    private const ACTIVE_CONTENT = '1074279e67a85f5b1.96907412';

    /**
     * @dataProvider providerGetContentMultiLanguage
     *
     * @param string $languageId
     * @param string $title
     * @param string $categoryTitle
     * @param string $seo
     */
    public function testGetContentMultiLanguage(string $languageId, string $title, string $categoryTitle, string $seo)
    {
        $query = 'query {
            content (id: "' . self::ACTIVE_CONTENT . '") {
                id
                title
                seo {
                    url
                }
                category {
                    title
                }
            }
        }';

        $this->setGETRequestParameter(
            'lang',
            $languageId
        );

        $result = $this->query($query);
        $this->assertResponseStatus(200, $result);

        $content = $result['body']['data']['content'];
        $this->assertEquals($content['id'], self::ACTIVE_CONTENT);
        $this->assertEquals($content['title'], $title);
        $this->assertEquals($content['category']['title'], $categoryTitle);
        $this->assertRegExp('@https?://.*/' . $seo . '/$@', $content['seo']['url']);
    }

    public function providerGetContentMultiLanguage()
    {
        return [
            'de' => [
                'languageId' => '0',
                'title'      => 'Wie bestellen?',
                'categoryTitle' => 'Bekleidung',
                'seo' => 'Wie-bestellen'
            ],
            'en' => [
                'languageId' => '1',
                'title'      => 'How to order?',
                'categoryTitle' => 'Gear',
                'seo' => 'How-to-order'
            ],
        ];
    }
}
