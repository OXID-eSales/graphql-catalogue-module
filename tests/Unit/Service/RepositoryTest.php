<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

/**
 * @covers OxidEsales\GraphQL\Catalogue\Service\Repository
 */
class RepositoryTest extends TestCase
{
    public function testFatalErrorOnWrongClass()
    {
        $this->expectException(\Error::class);
        $repository = new Repository(
            $this->createMock(QueryBuilderFactoryInterface::class)
        );
        $repository->getById(
            'foo',
            \stdClass::class
        );
    }

    public function testExceptionOnWrongModel()
    {
        $this->expectException(\InvalidArgumentException::class);
        $repository = new Repository(
            $this->createMock(QueryBuilderFactoryInterface::class)
        );
        $repository->getById(
            'foo',
            WrongType::class
        );
    }

    public function testExceptionOnWrongType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $repository = new Repository(
            $this->createMock(QueryBuilderFactoryInterface::class)
        );
        $repository->getById(
            'foo',
            AlsoWrongType::class
        );
    }
}

// phpcs:disable

class WrongModel
{
}

class WrongType
{
    public function getModelClass(): string
    {
        return WrongModel::class;
    }
}

class CorrectModel extends \OxidEsales\Eshop\Core\Model\BaseModel
{
    public function __construct()
    {
    }

    public function load($oxid)
    {
        return true;
    }
}

class AlsoWrongType
{
    public function getModelClass(): string
    {
        return CorrectModel::class;
    }
}

namespace OxidEsales\GraphQL\Catalogue\Service;

if (!function_exists("\oxNew")) {
    function oxNew(string $class)
    {
        return new $class();
    }
}
