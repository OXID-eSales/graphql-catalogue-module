<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

class ProductMultilanguageTest extends TestCase
{

    private const ACTIVE_MULTILANGUAGE_PRODUCT = '058e613db53d782adfc9f2ccb43c45fe';

    public function providerGetProductMultilanguage()
    {
        return [
            'de' => [
                'languageId' => '0',
                'title'      => 'Bindung O&#039;BRIEN DECADE CT 2010',
                'url'        => 'Wakeboarding/Bindungen/'
            ],
            'en' => [
                'languageId' => '1',
                'title'      => 'Binding O&#039;BRIEN DECADE CT 2010',
                'url'        => 'en/Wakeboarding/Bindings'
            ],
        ];
    }

    /**
     * @dataProvider providerGetProductMultilanguage
     */
    public function testGetProductMultilanguage(string $languageId, string $title, string $seoUrl)
    {
        $query = 'query {
            product (id: "' . self::ACTIVE_MULTILANGUAGE_PRODUCT . '") {
                id
                title
                seo {
                   url
                }
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

        $product = $result['body']['data']['product'];

        $this->assertSame(self::ACTIVE_MULTILANGUAGE_PRODUCT, $product['id']);
        $this->assertEquals($title, $product['title']);
        $this->assertRegExp('@https?://.*' . $seoUrl . '.*@', $product['seo']['url']);
    }

    public function providerGetProductListWithFilterMultilanguage()
    {
        return [
            'de' => [
                'languageId' => '0',
                'count'      => 0
            ],
            'en' => [
                'languageId' => '1',
                'count'      => 1
            ]
        ];
    }
}
