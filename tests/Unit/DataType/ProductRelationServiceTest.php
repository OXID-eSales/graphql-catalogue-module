<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\DataType;

use PHPUnit\Framework\TestCase;
use OxidEsales\GraphQL\Catalogue\DataType\Category;
use OxidEsales\GraphQL\Catalogue\DataType\Product;
use OxidEsales\Eshop\Application\Model\Article as EshopArticleModel;
use OxidEsales\Eshop\Application\Model\Category as EshopCategoryModel;
use OxidEsales\GraphQL\Catalogue\DataType\ProductRelationService;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductRelationService
 */
final class ProductRelationServiceTest extends TestCase
{
    public function testGetNoCategoryIfNotAssignedToProduct()
    {
        $productRelationService = new ProductRelationService(
            new Repository(
                $this->createMock(QueryBuilderFactoryInterface::class)
            )
        );
        $this->assertNull(
            $productRelationService->getCategory(
                new Product(
                    new NoCategoryProductModelStub()
                )
            )
        );
    }

    public function testGetNoCategoryIfEmptyCategoryAssignedToProduct()
    {
        $productRelationService = new ProductRelationService(
            new Repository(
                $this->createMock(QueryBuilderFactoryInterface::class)
            )
        );
        $this->assertNull(
            $productRelationService->getCategory(
                new Product(
                    new EmptyCategoryProductModelStub()
                )
            )
        );
    }
}

// phpcs:disable

class NoCategoryProductModelStub extends EshopArticleModel
{
    public function __construct()
    {
    }

    public function getCategory()
    {
        return null;
    }
}

class EmptyCategoryProductModelStub extends EshopArticleModel
{
    public function __construct()
    {
    }

    public function getCategory()
    {
        return new EmptyCategoryModelStub();
    }
}

class EmptyCategoryModelStub extends EshopCategoryModel
{
    public function __construct()
    {
    }

    public function getId()
    {
        return '';
    }
}
