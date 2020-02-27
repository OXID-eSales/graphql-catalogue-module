<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Struct;

use OxidEsales\GraphQL\Catalogue\Struct\Currency;
use PHPUnit\Framework\TestCase;

/**
 * @covers OxidEsales\GraphQL\Catalogue\Struct\Currency
 */
class CurrencyTest extends TestCase
{
    /**
     * @dataProvider currencyData
     */
    public function testCreateFromArray(array $currencyData): void
    {
        $currencyStruct = new Currency($currencyData);

        foreach ($currencyData as $key => $value) {
            $this->assertEquals($value, $currencyStruct->$key);
        }
    }

    public function currencyData(): array
    {
        return [
            [
                [
                    'id' => 1,
                    'name' => 'EUR',
                    'rate' => '1.00',
                    'dec' => ',',
                    'thousand' => '.',
                    'sign' => '€',
                    'decimal' => '2',
                    'selected' => 0,
                ],
                [
                    'id' => 999999,
                    'name' => 'USD',
                    'rate' => '0.91',
                    'dec' => '.',
                    'thousand' => ' ',
                    'sign' => '$',
                    'decimal' => '10',
                    'selected' => 1,
                ],
            ]
        ];
    }

    /**
     * @dataProvider currencyDataWithFieldsThatShouldNotExist
     */
    public function testCreateWithIncorrectData(array $currencyData): void
    {
        $currencyStruct = new Currency($currencyData);

        $this->assertSame($currencyStruct->id, null);
        $this->assertSame($currencyStruct->name, null);
        $this->assertSame($currencyStruct->rate, null);
        $this->assertSame($currencyStruct->dec, null);
        $this->assertSame($currencyStruct->thousand, null);
        $this->assertSame($currencyStruct->sign, null);
        $this->assertSame($currencyStruct->decimal, null);
        $this->assertSame($currencyStruct->selected, null);

        foreach ($currencyData as $key => $value) {
            $this->assertFalse(property_exists($currencyStruct, $key));
        }
    }

    public function currencyDataWithFieldsThatShouldNotExist(): array
    {
        return [
            [
                [
                    'doesNotExistInTheStruct1' => 'someString',
                    'doesNotExistInTheStruct2' => 1,
                    'doesNotExistInTheStruct3' => false,
                    'doesNotExistInTheStruct4' => null,
                    'doesNotExistInTheStruct5' => [],
                ],
            ]
        ];
    }
}
