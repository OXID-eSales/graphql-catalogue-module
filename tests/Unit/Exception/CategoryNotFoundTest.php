<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\Exception\CategoryNotFound;

/**
 * @covers OxidEsales\GraphQL\Catalogue\Exception\CategoryNotFound
 */
class CategoryNotFoundTest extends TestCase
{
    public function testExceptionById()
    {
        $this->expectException(CategoryNotFound::class);
        $this->expectExceptionMessageRegExp('/.*CATID.*/');
        throw CategoryNotFound::byId('CATID');
    }
}
