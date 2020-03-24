<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\Exception\AttributeNotFound;

/**
 * @covers OxidEsales\GraphQL\Catalogue\Exception\AttributeNotFound
 */
class AttributeNotFoundTest extends TestCase
{
    public function testExceptionById()
    {
        $this->expectException(AttributeNotFound::class);
        $this->expectExceptionMessage('ATTRID');
        throw AttributeNotFound::byId('ATTRID');
    }
}
