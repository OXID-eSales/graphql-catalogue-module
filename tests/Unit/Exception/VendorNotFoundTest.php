<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\Exception\VendorNotFound;

/**
 * @covers OxidEsales\GraphQL\Catalogue\Exception\VendorNotFound
 */
class VendorNotFoundTest extends TestCase
{
    public function testExceptionById()
    {
        $this->expectException(VendorNotFound::class);
        $this->expectExceptionMessageRegExp('/.*VENDORID.*/');
        throw VendorNotFound::byId('VENDORID');
    }
}
