<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\DataType;

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

        $currency = new Currency($currencyObject);

        $this->assertSame($currencyObject->id, $currency->getId());
        $this->assertSame($currencyObject->rate, $currency->getRate());
        $this->assertSame($currencyObject->name, $currency->getName());
        $this->assertSame($currencyObject->sign, $currency->getSign());
    }
}
