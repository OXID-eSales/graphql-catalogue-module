<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Base\Tests\Integration\MultishopTestCase;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\Currency
 */
class CurrencyEnterpriseTest extends MultishopTestCase
{
    public function testGetSecondShopCurrency()
    {
        $this->setGETRequestParameter('shp', '2');

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
        $resultCurrency = $result['body']['data']['currency'];

        $this->assertResponseStatus(200, $result);
        $this->assertSame($configCurrency->id, $resultCurrency['id']);
        $this->assertSame($configCurrency->name, $resultCurrency['name']);
        $this->assertSame($configCurrency->rate, $resultCurrency['rate']);
        $this->assertSame($configCurrency->sign, $resultCurrency['sign']);
    }

    /**
     * @dataProvider currencyNames
     */
    public function testGetCurrencyByName(string $name): void
    {
        $result = $this->query(sprintf('
            query {
                currency (name: "%s") {
                    id
                    name
                    rate
                    sign
                }
            }
        ', $name));

        $configCurrency = Registry::getConfig()->getCurrencyObject($name);
        $resultCurrency = $result['body']['data']['currency'];

        $this->assertResponseStatus(200, $result);
        $this->assertSame($resultCurrency['id'], $configCurrency->id);
        $this->assertSame($resultCurrency['name'], $configCurrency->name);
        $this->assertSame($resultCurrency['rate'], $configCurrency->rate);
        $this->assertSame($resultCurrency['sign'], $configCurrency->sign);
    }

    public function currencyNames(): array
    {
        return [
            ['EUR'],
            ['GBP'],
            ['USD'],
            ['CHF'],
        ];
    }

    /**
     * @dataProvider incorrectCurrencyNames
     */
    public function testGetCurrencyByNameShouldFail(string $name): void
    {
        $result = $this->query(sprintf('
            query {
                currency (name: "%s") {
                    id
                    name
                    rate
                    sign
                }
            }
        ', $name));

        $this->assertResponseStatus(400, $result);
    }

    public function incorrectCurrencyNames(): array
    {
        return [
            ['US'],
            ['EU'],
            ['ABC'],
            ['notACurrencyNameAtAll'],
            ['null'],
            [17],
        ];
    }

    public function testGetSecondShopCurrencyList()
    {
        $this->setGETRequestParameter('shp', '2');

        $result = $this->query('
            query {
                currencies{
                    id
                    name
                    rate
                    sign
                }
            }
        ');

        $configCurrencies = Registry::getConfig()->getCurrencyArray();
        $resultCurrencies = $result['body']['data']['currencies'];

        $this->assertResponseStatus(200, $result);

        foreach ($configCurrencies as $key => $expectedCurrency) {
            $this->assertSame($expectedCurrency->id, $resultCurrencies[$key]['id']);
            $this->assertSame($expectedCurrency->name, $resultCurrencies[$key]['name']);
            $this->assertSame($expectedCurrency->rate, $resultCurrencies[$key]['rate']);
            $this->assertSame($expectedCurrency->sign, $resultCurrencies[$key]['sign']);
        }
    }
}
