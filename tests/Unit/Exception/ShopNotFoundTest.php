<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\Exception\ShopNotFound;

/**
 * @covers OxidEsales\GraphQL\Catalogue\Exception\ShopNotFound
 */
class ShopNotFoundTest extends TestCase
{
    public function testExceptionById()
    {
        $this->expectException(ShopNotFound::class);
        $this->expectExceptionMessage('SHOPID');
        throw ShopNotFound::byId('SHOPID');
    }
}
