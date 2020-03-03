<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\DataType;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;
use OxidEsales\Eshop\Application\Model\Article as EshopProduct;
use OxidEsales\Eshop\Application\Model\User as EshopUser;
use OxidEsales\GraphQL\Catalogue\DataType\Seo;
use OxidEsales\Eshop\Core\Registry as EshopRegistry;
use OxidEsales\Eshop\Core\Utils as EshopUtils;
use OxidEsales\Eshop\Core\Language as EshopLanguage;

/**
 * @covers \OxidEsales\GraphQL\Catalogue\DataType\Seo
 */
final class SeoTest extends TestCase
{
    private const PRODUCT_ID = '058de8224773a1d5fd54d523f0c823e0';

    protected function setUp(): void
    {
        parent::setUp();

        EshopRegistry::set(EshopLanguage::class, oxNew(EshopLanguage::class));
    }

    public function providerProductSeo()
    {
        return [
            'de_seo_active' => [
                'languageId'  => '0',
                'seoactive'   => true,
                'description' => 'german seo description',
                'keywords'    => 'german seo keywords',
                'url'         => 'Kiteboarding/Kiteboards/Kiteboard-CABRINHA-CALIBER-2011.html',
                'standardurl' => '?cl=details&anid=058de8224773a1d5fd54d523f0c823e0'
            ],
            'en_seo_active' => [
                'languageId'  => '1',
                'seoactive'   => true,
                'description' => 'english seo description',
                'keywords'    => 'english seo keywords',
                'url'         => 'Kiteboarding/Kiteboards/Kiteboard-CABRINHA-CALIBER-2011.html',
                'standardurl' => 'cl=details&anid=058de8224773a1d5fd54d523f0c823e0&lang=1'
            ],
            'de_seo_inactive' => [
                'languageId'  => '0',
                'seoactive'   => false,
                'description' => 'german seo description',
                'keywords'    => 'german seo keywords',
                'url'         => null,
                'standardurl' => '?cl=details&anid=058de8224773a1d5fd54d523f0c823e0'
            ],
            'en_seo_inactive' => [
                'languageId'  => '1',
                'seoactive'   => false,
                'description' => 'english seo description',
                'keywords'    => 'english seo keywords',
                'url'         => null,
                'standardurl' => 'cl=details&anid=058de8224773a1d5fd54d523f0c823e0&lang=1'
            ]
        ];
    }

    /**
     * @dataProvider providerProductSeo
     */
    public function testProductSeo($languageId, $seoactive, $description, $keywords, $url, $standardUrl)
    {
        $this->setSeoActive($seoactive);

        $this->setGETRequestParameter(
            'lang',
            $languageId
        );

        $product = oxNew(EshopProduct::class);
        $product->load(self::PRODUCT_ID);
        $seo = new Seo($product);

        $this->assertEquals($description, $seo->getMetaDescription());
        $this->assertEquals($keywords, $seo->getMetaKeywords());
        $this->assertContains($standardUrl, $seo->getStandardUrl());

        if (!is_null($url)) {
            $this->assertContains($url, $seo->getSeoUrl());
        } else {
            $this->assertNull($seo->getSeoUrl());
        }
    }

    public function testNonUrlContractObject()
    {
        $this->setSeoActive(true);

        $user = oxNew(EshopUser::class);
        $seo = new Seo($user);

        $this->assertSame('', $seo->getMetaDescription());
        $this->assertSame('', $seo->getMetaKeywords());
        $this->assertNull($seo->getStandardUrl());
        $this->assertNull($seo->getStandardUrl());
    }

    /**
     * Test helper
     *
     * @param bool $seoactive
     */
    private function setSeoActive($seoactive)
    {
        $utilsMock = $this->getMockBuilder(EshopUtils::class)
            ->setMethods(['seoIsActive'])
            ->getMock();

        $utilsMock->expects($this->any())
            ->method('seoIsActive')
            ->willReturn($seoactive);

        EshopRegistry::set(EshopUtils::class, $utilsMock);
    }
}
