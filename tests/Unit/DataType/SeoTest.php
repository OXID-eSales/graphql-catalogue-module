<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\DataType;

use PHPUnit\Framework\TestCase;
use OxidEsales\Eshop\Core\Model\BaseModel as EshopBaseModel;
use OxidEsales\GraphQL\Catalogue\DataType\Seo;
use OxidEsales\Eshop\Core\Field;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\Seo
 */
final class SeoTest extends TestCase
{
    public function testNoSeoUrl()
    {
        $seo = new Seo(
            new NoEshopContractStub()
        );

        $this->assertNull(
            $seo->getUrl()
        );
    }
}

// phpcs:disable

class NoEshopContractStub extends EshopBaseModel
{
    public function __construct()
    {
    }
}
