<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\GraphQL\Base\Tests\Integration\MultishopTestCase;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\Currency
 */
class CurrencyEnterpriseTest extends MultishopTestCase
{
    public function testGetSecondShopCurrency()
    {
        $this->setGETRequestParameter('shp', "2");

        $result = $this->query('
            query {
                currency {
                    id
                    name
                    rate
                    sign
                }
            }
        ');

        $expectedCurrency = [
            'id' => 0,
            'name' => 'EUR',
            'rate' => '1.00',
            'dec' => ',',
            'thousand' => '.',
            'sign' => '€',
            'decimal' => '2',
            'selected' => 0,
        ];

        $this->assertResponseStatus(200, $result);
        $this->assertSame($result['body']['data']['currency']['id'], $expectedCurrency['id']);
        $this->assertSame($result['body']['data']['currency']['name'], $expectedCurrency['name']);
        $this->assertSame($result['body']['data']['currency']['rate'], $expectedCurrency['rate']);
        $this->assertSame($result['body']['data']['currency']['sign'], $expectedCurrency['sign']);
    }
}
