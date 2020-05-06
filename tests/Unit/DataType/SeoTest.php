<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\DataType;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\DataType\Seo;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\Seo
 */
final class SeoTest extends TestCase
{
    public function testNoSeoUrl(): void
    {
        $seo = new Seo(
            new NoEshopUrlContractModelStub()
        );

        $this->assertNull(
            $seo->getUrl()
        );
    }
}
