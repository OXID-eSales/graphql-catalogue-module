<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\DataType;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\GraphQL\Catalogue\Service\CategoryRepository;
use PHPUnit\Framework\TestCase;
use TheCodingMachine\GraphQLite\Types\ID;

final class CategoryTest extends TestCase
{
    /**
     * @covers \OxidEsales\GraphQL\Catalogue\DataType\Category
     */
    public function testCategoryDataType()
    {
        $shopUrl = Registry::getConfig()->getShopUrl();
        $data = [
            'id' => '943a9ba3050e78b443c16e043ae60ef3',
            'parentid' => new ID('oxrootid'),
            'rootid' => new ID('943a9ba3050e78b443c16e043ae60ef3'),
            'position' => 1,
            'active' => true,
            'hidden' => true,
            'shopid' => new ID(1),
            'title' => 'Kiteboarding',
            'description' => '',
            'longdescription' => '',
            'thumbnail' => $shopUrl . 'out/pictures/generated/category/thumb/1140_250_75/kiteboarding_1_tc.jpg',
            'externallink' => '',
            'template' => '',
            'defaultsortfield' => '',
            'defaultsortmode' => 'ASC',
            'pricefrom' => 0.0,
            'priceto' => 0.0,
            'icon' => null,
            'promotionicon' => null,
            'vat' => null,
            'skipdiscounts' => false,
            'showsuffix' => true,
            'url' => $shopUrl . 'Kiteboarding/'
        ];

        $repository = new CategoryRepository();
        $category = $repository->getById($data['id']);

        $this->assertEquals(new ID($data['id']), $category->getId());
        $this->assertEquals($data['parentid'], $category->getParentId());
        $this->assertEquals($data['rootid'], $category->getRootId());
        $this->assertSame($data['position'], $category->getPosition());
        $this->assertSame($data['active'], $category->isActive());
        $this->assertSame($data['hidden'], $category->isHidden());
        $this->assertEquals($data['shopid'], $category->getShopId());
        $this->assertSame($data['title'], $category->getTitle());
        $this->assertSame($data['description'], $category->getShortDescription());
        $this->assertSame($data['longdescription'], $category->getLongDescription());
        $this->assertSame($data['thumbnail'], $category->getThumbnail());
        $this->assertSame($data['externallink'], $category->getExternalLink());
        $this->assertSame($data['template'], $category->getTemplate());
        $this->assertSame($data['defaultsortfield'], $category->getDefaultSortField());
        $this->assertSame($data['defaultsortmode'], $category->getDefaultSortMode());
        $this->assertSame($data['pricefrom'], $category->getPriceFrom());
        $this->assertSame($data['priceto'], $category->getPriceTo());
        $this->assertSame($data['icon'], $category->getIcon());
        $this->assertSame($data['promotionicon'], $category->getPromotionIcon());
        $this->assertSame($data['vat'], $category->getVat());
        $this->assertSame($data['skipdiscounts'], $category->skipDiscount());
        $this->assertSame($data['showsuffix'], $category->showSuffix());
        $this->assertSame($data['url'], $category->getUrl());
    }
}
