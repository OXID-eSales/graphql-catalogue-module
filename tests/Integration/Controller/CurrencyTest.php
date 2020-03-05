<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\Currency
 */
class CurrencyTest extends TestCase
{
    public function testGetCurrencyQuery()
    {
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

        $configCurrency = Registry::getConfig()->getActShopCurrencyObject();

        $this->assertResponseStatus(200, $result);
        $this->assertSame($result['body']['data']['currency']['id'], $configCurrency->id);
        $this->assertSame($result['body']['data']['currency']['name'], $configCurrency->name);
        $this->assertSame($result['body']['data']['currency']['rate'], $configCurrency->rate);
        $this->assertSame($result['body']['data']['currency']['sign'], $configCurrency->sign);
    }
}
