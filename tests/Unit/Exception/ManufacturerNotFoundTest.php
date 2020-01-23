<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\Exception\ManufacturerNotFound;

/**
 * @covers OxidEsales\GraphQL\Catalogue\Exception\ManufacturerNotFound
 */
class ManufacturerNotFoundTest extends TestCase
{
    public function testExceptionById()
    {
        $this->expectException(ManufacturerNotFound::class);
        $this->expectExceptionMessageRegExp('/.*MANUID.*/');
        throw ManufacturerNotFound::byId('MANUID');
    }
}
