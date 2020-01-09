<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Framework;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\Framework\NamespaceMapper;

class NamespaceMapperTest extends TestCase
{
    /**
     * @covers OxidEsales\GraphQL\Catalogue\Framework\NamespaceMapper
     */
    public function testNamespaceCounts()
    {
        $namespaceMapper = new NamespaceMapper();
        $this->assertCount(
            1,
            $namespaceMapper->getControllerNamespaceMapping()
        );
        $this->assertCount(
            1,
            $namespaceMapper->getTypeNamespaceMapping()
        );
    }
}
