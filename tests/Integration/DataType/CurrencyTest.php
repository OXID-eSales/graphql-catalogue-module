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
        $currencyArray = [
            'id' => 0,
            'name' => 'EUR',
            'rate' => '1.00',
            'dec' => ',',
            'thousand' => '.',
            'sign' => '€',
            'decimal' => '2',
            'selected' => 0,
        ];

        $config = $this->createPartialMock(Config::class, ['getCurrencyArray']);
        $config->method('getCurrencyArray')->willReturn($currencyArray);
        Registry::set(Config::class, $config);

        $currency = new Currency();

        $this->assertSame($currency->getId(), $currencyArray['id']);
        $this->assertSame($currency->getRate(), $currencyArray['rate']);
        $this->assertSame($currency->getName(), $currencyArray['name']);
        $this->assertSame($currency->getSign(), $currencyArray['sign']);
    }
}
