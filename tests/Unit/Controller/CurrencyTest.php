<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Controller;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\GraphQL\Catalogue\Controller\Currency;
use OxidEsales\GraphQL\Catalogue\DataType\Currency as CurrencyDataType;
use OxidEsales\GraphQL\Catalogue\Exception\CurrencyNotFound;
use OxidEsales\GraphQL\Catalogue\Service\CurrencyRepository;
use stdClass;
use PHPUnit\Framework\TestCase;

/**
 * @covers OxidEsales\GraphQL\Catalogue\Controller\Currency
 * @covers OxidEsales\GraphQL\Catalogue\Service\CurrencyRepository
 * @covers OxidEsales\GraphQL\Catalogue\Exception\CurrencyNotFound
 */
class CurrencyTest extends TestCase
{
    public function testGetCurrencyFromController()
    {
        $currency = new Currency(
            new CurrencyRepository(
                new ValidCurrenciesConfig()
            )
        );
        $this->assertInstanceOf(
            CurrencyDataType::class,
            $currency->currency('EUR')
        );
        $this->assertInstanceOf(
            CurrencyDataType::class,
            $currency->currency()
        );
    }

    public function testExceptionFromControllerOnWrongCurrency()
    {
        $currency = new Currency(
            new CurrencyRepository(
                new InvalidCurrenciesConfig()
            )
        );
        $this->expectException(CurrencyNotFound::class);
        $currency->currency('FOOBAR');
    }

    public function testExceptionFromControllerOnNoActiveCurrency()
    {
        $currency = new Currency(
            new CurrencyRepository(
                new InvalidCurrenciesConfig()
            )
        );
        $this->expectException(CurrencyNotFound::class);
        $currency->currency();
    }

    public function testGetCurrencyList()
    {
        $currency = new Currency(
            new CurrencyRepository(
                new ValidCurrenciesConfig()
            )
        );
        $this->assertCount(
            1,
            $currency->currencies()
        );
        $this->assertInstanceOf(
            CurrencyDataType::class,
            $currency->currencies()[0]
        );
    }

    public function testGetEmptyCurrencyList()
    {
        $currency = new Currency(
            new CurrencyRepository(
                new InvalidCurrenciesConfig()
            )
        );
        $this->assertSame(
            [],
            $currency->currencies()
        );
    }
}


class ValidCurrenciesConfig extends Config // phpcs:ignore
{
    public function getCurrencyObject($name)
    {
        $cur = new stdClass();
        $cur->id = 0;
        $cur->name = $name;
        $cur->rate = '1.0';
        $cur->dec = ',';
        $cur->thousand = '.';
        $cur->sign = '€';
        $cur->decimal = '2';
        return $cur;
    }

    public function getActShopCurrencyObject()
    {
        $cur = new stdClass();
        $cur->id = 0;
        $cur->name = 'EUR';
        $cur->rate = '1.0';
        $cur->dec = ',';
        $cur->thousand = '.';
        $cur->sign = '€';
        $cur->decimal = '2';
        return $cur;
    }

    public function getCurrencyArray($currency = null)
    {
        return [
            $this->getActShopCurrencyObject()
        ];
    }
}

class InvalidCurrenciesConfig extends Config // phpcs:ignore
{
    public function getCurrencyObject($name)
    {
        return null;
    }

    public function getActShopCurrencyObject()
    {
        return null;
    }

    public function getCurrencyArray($currency = null)
    {
        return [];
    }
}
