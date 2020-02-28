<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Integration\DataType;

use OxidEsales\Eshop\Application\Model\Article as EshopArticle;
use OxidEsales\GraphQL\Catalogue\DataType\Product;
use OxidEsales\GraphQL\Catalogue\DataType\ProductImage;
use OxidEsales\GraphQL\Catalogue\DataType\ProductRelationService;
use PHPUnit\Framework\TestCase;

/**
 * @covers \OxidEsales\GraphQL\Catalogue\DataType\ProductImageGallery
 * @covers \OxidEsales\GraphQL\Catalogue\DataType\ProductRelationService
 */
class ProductImageGalleryTest extends TestCase
{
    public function testGetImageGalleryIconAndThumb()
    {
        $article = oxNew(EshopArticle::class);
        $article->load('058de8224773a1d5fd54d523f0c823e0');
        $product = new Product(
            $article
        );

        $productRelation = new ProductRelationService(
            $this->createPartialMock(\OxidEsales\GraphQL\Catalogue\Service\Repository::class, [])
        );
        $imageGallery = $productRelation->getImageGallery($product);

        $this->assertRegExp(
            "@^http.*?/out/pictures/generated/product/1/390_245_75/cabrinha_caliber_2011.jpg$@msi",
            $imageGallery->getThumb()
        );

        $this->assertRegExp(
            "@^http.*?/out/pictures/generated/product/1/87_87_75/cabrinha_caliber_2011.jpg$@msi",
            $imageGallery->getIcon()
        );
    }

    public function testGetImageGalleryImagesTypeAndCount()
    {
        $article = oxNew(EshopArticle::class);
        $article->load('058de8224773a1d5fd54d523f0c823e0');
        $product = new Product(
            $article
        );

        $productRelation = new ProductRelationService(
            $this->createPartialMock(\OxidEsales\GraphQL\Catalogue\Service\Repository::class, [])
        );
        $imageGallery = $productRelation->getImageGallery($product);

        $images = $imageGallery->getImages();
        $this->assertCount(3, $images);

        foreach ($images as $oneImage) {
            $this->assertInstanceOf(ProductImage::class, $oneImage);
        }
    }

    /**
     * @param $key
     * @param $image
     * @param $icon
     * @param $zoom
     *
     * @dataProvider getImageGalleryImagesContentDataProvider
     */
    public function testGetImageGalleryImagesContent($key, $image, $icon, $zoom)
    {
        $article = oxNew(EshopArticle::class);
        $article->load('058de8224773a1d5fd54d523f0c823e0');
        $product = new Product(
            $article
        );

        $productRelation = new ProductRelationService(
            $this->createPartialMock(\OxidEsales\GraphQL\Catalogue\Service\Repository::class, [])
        );
        $imageGallery = $productRelation->getImageGallery($product);

        /** @var ProductImage[] $images */
        $images = $imageGallery->getImages();

        $this->assertRegExp($image, $images[$key]->getImage());
        $this->assertRegExp($icon, $images[$key]->getIcon());
        $this->assertRegExp($zoom, $images[$key]->getZoom());
    }

    public function getImageGalleryImagesContentDataProvider()
    {
        return [
            [
                1,
                '@^http.*?/out/pictures/generated/product/1/540_340_75/cabrinha_caliber_2011.jpg$@msi',
                '@^http.*?/out/pictures/generated/product/1/87_87_75/cabrinha_caliber_2011.jpg$@msi',
                '@^http.*?/out/pictures/generated/product/1/665_665_75/cabrinha_caliber_2011.jpg$@msi'
            ],
            [
                2,
                '@^http.*?/out/pictures/generated/product/2/540_340_75/cabrinha_caliber_2011_deck.jpg$@msi',
                '@^http.*?/out/pictures/generated/product/2/87_87_75/cabrinha_caliber_2011_deck.jpg$@msi',
                '@^http.*?/out/pictures/generated/product/2/665_665_75/cabrinha_caliber_2011_deck.jpg$@msi'
            ],
            [
                3,
                '@^http.*?/out/pictures/generated/product/3/540_340_75/cabrinha_caliber_2011_bottom.jpg$@msi',
                '@^http.*?/out/pictures/generated/product/3/87_87_75/cabrinha_caliber_2011_bottom.jpg$@msi',
                '@^http.*?/out/pictures/generated/product/3/665_665_75/cabrinha_caliber_2011_bottom.jpg$@msi'
            ]
        ];
    }
}
