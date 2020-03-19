<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

/**
 * Class AttributeTest
 *
 * @package OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller
 */
final class AttributeTest extends TestCase
{

    private const ATTRIBUTE_ID = "6cf89d2d73e666457d167cebfc3eb492";

    protected function setUp(): void
    {
        parent::setUp();

        $this->setGETRequestParameter(
            'lang',
            '0'
        );
    }

    public function testGetSingleAttribute()
    {
        $result = $this->query('query {
            attribute (id: "' . self::ATTRIBUTE_ID . '") {
                title
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );

        $attribute = $result['body']['data']['attribute'];

        $this->assertEquals(
            'Lieferumfang',
            $attribute['title']
        );
    }

    public function testGet404ForSingleNonExistingAttribute()
    {
        $result = $this->query('query {
            attribute (id: "DOES-NOT-EXIST") {
                title
            }
        }');

        $this->assertResponseStatus(
            404,
            $result
        );
    }

    /**
     * @return array
     */
    public function providerGetAttributeMultilanguage(): array
    {
        return [
            'de' => [
                'languageId' => '0',
                'title'      => 'Lieferumfang'
            ],
            'en' => [
                'languageId' => '1',
                'title'      => 'Included in delivery'
            ],
        ];
    }

    /**
     * @dataProvider providerGetAttributeMultilanguage
     *
     * @param string $languageId
     * @param string $title
     */
    public function testGetAttributeMultilanguage(string $languageId, string $title)
    {
        $query = 'query {
            attribute (id: "' . self::ATTRIBUTE_ID . '") {
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
                'title' => $title
            ],
            $result['body']['data']['attribute']
        );
    }

    public function testAttributeList()
    {
        $result = $this->query('query {
            attributes {
                title
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );
        $this->assertCount(
            12,
            $result['body']['data']['attributes']
        );
    }

    /**
     * @dataProvider providerGetAttributesMultilanguage
     *
     * @param string $languageId
     * @param array $attributes
     */
    public function testAttributeListMultilanguage($languageId, $attributes)
    {
        $this->setGETRequestParameter('lang', $languageId);

        $result = $this->query('query {
            attributes {
                title
            }
        }');

        $this->assertResponseStatus(
            200,
            $result
        );
        foreach ($attributes as $key => $attribute) {
            $this->assertSame(
                $attribute,
                $result['body']['data']['attributes'][$key]['title']
            );
        }
    }

    /**
     * @return array
     */
    public function providerGetAttributesMultilanguage(): array
    {
        return [
            'de' => [
                'languageId' => '0',
                'attributes' => [
                    'EU-Größe',
                    'Washing',
                    'Lieferumfang'
                ]
            ],
            'en' => [
                'languageId' => '1',
                'attributes' => [
                    'EU-Size',
                    'Washing',
                    'Included in delivery'
                ]
            ],
        ];
    }
}
