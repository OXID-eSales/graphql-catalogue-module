<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\DataType;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\DataType\Currency;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\Currency
 */
class CurrencyTest extends TestCase
{
    public function testGetCurrency()
    {
        $currencyObject = new \stdClass();
        $currencyObject->id = 0;
        $currencyObject->name = 'EUR';
        $currencyObject->rate = '1.00';
        $currencyObject->dec = ',';
        $currencyObject->thousand = '.';
        $currencyObject->sign = '€';
        $currencyObject->decimal = '2';
        $currencyObject->selected = 0;

        $config = $this->createPartialMock(Config::class, ['getActShopCurrencyObject']);
        $config->method('getActShopCurrencyObject')->willReturn($currencyObject);
        Registry::set(Config::class, $config);

        $currency = new Currency();

        $this->assertSame($currency->getId(), $currencyObject->id);
        $this->assertSame($currency->getRate(), $currencyObject->rate);
        $this->assertSame($currency->getName(), $currencyObject->name);
        $this->assertSame($currency->getSign(), $currencyObject->sign);
    }
}
