<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\DataType;

use OxidEsales\GraphQL\Catalogue\DataType\Banner;
use PHPUnit\Framework\TestCase;
use OxidEsales\Eshop\Application\Model\Actions as EshopActionsModel;
use OxidEsales\Eshop\Core\Field;

/**
 * @covers \OxidEsales\GraphQL\Catalogue\DataType\Banner
 */
class BannerTest extends TestCase
{
    /**
     * @dataProvider testActiveDataProvider
     */
    public function testActive($active, $from, $to, $now, $expected)
    {
        $banner = new Banner($this->getModelStub(
            $active,
            $from,
            $to
        ));
        $this->assertSame($expected, $banner->isActive($now));
    }

    public function testActiveDataProvider()
    {
        return [
            [
                'active' => '1',
                'from' => '',
                'to' => '',
                'now' => null,
                'result' => true
            ],
            [
                'active' => '0',
                'from' => '',
                'to' => '',
                'now' => null,
                'result' => false
            ],
            [
                'active' => '1',
                'from' => '2018-01-01 12:00:00',
                'to' => '2018-01-01 19:00:00',
                'now' => null,
                'result' => true
            ],
            [
                'active' => '0',
                'from' => '2018-01-01 12:00:00',
                'to' => '2018-01-01 19:00:00',
                'now' => null,
                'result' => false
            ],
            [
                'active' => '0',
                'from' => '2018-01-01 12:00:00',
                'to' => '2018-01-01 19:00:00',
                'now' => new \DateTimeImmutable('2018-01-01 16:00:00'),
                'result' => true
            ],
        ];
    }

    private function getModelStub(
        string $active = '1',
        string $activefrom = '0000-00-00 00:00:00',
        string $activeto = '0000-00-00 00:00:00'
    ) {
        $model = $this->createPartialMock(
            \OxidEsales\Eshop\Application\Model\Actions::class,
            ['getFieldData']
        );
        $model->method('getFieldData')->willReturnMap([
            ['oxtype', Banner::ACTION_TYPE],
            ['oxactive', $active],
            ['oxactivefrom', $activefrom],
            ['oxactiveto', $activeto]
        ]);

        return $model;
    }
}
