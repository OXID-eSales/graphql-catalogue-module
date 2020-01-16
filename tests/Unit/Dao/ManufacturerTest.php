<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Dao;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Connection;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Catalogue\Dao\Manufacturer;
use OxidEsales\GraphQL\Catalogue\DataObject\ManufacturerFilter;
use PHPUnit\Framework\TestCase;

class ManufacturerTest extends TestCase
{
    /**
     * @covers OxidEsales\GraphQL\Catalogue\Dao\Manufacturer
     */
    public function testExceptionOnErrorInSingleQuery()
    {
        $this->expectException(\Exception::class);
        $queryBuilderFactory = $this->getMockBuilder(QueryBuilderFactoryInterface::class)
                                    ->setMethods(['create'])
                                    ->getMock();
        $queryBuilderFactory->expects($this->once())
                            ->method('create')
                            ->willReturn(
                                new QueryBuilder($this->createMock(Connection::class))
                            );
        $manufacturer = new Manufacturer(
            $queryBuilderFactory
        );
        $manufacturer->getManufacturer("");
    }

    /**
     * @covers OxidEsales\GraphQL\Catalogue\Dao\Manufacturer
     */
    public function testExceptionOnErrorInCollectionQuery()
    {
        $this->expectException(\Exception::class);
        $queryBuilderFactory = $this->getMockBuilder(QueryBuilderFactoryInterface::class)
                                    ->setMethods(['create'])
                                    ->getMock();
        $queryBuilderFactory->expects($this->once())
                            ->method('create')
                            ->willReturn(
                                new QueryBuilder($this->createMock(Connection::class))
                            );
        $manufacturer = new Manufacturer(
            $queryBuilderFactory
        );
        $manufacturer->getManufacturers(
            new ManufacturerFilter()
        );
    }
}

namespace OxidEsales\GraphQL\Catalogue\Dao;

function getViewName($table, $languageId = null, $shopId = null)
{
    return $table;
}
