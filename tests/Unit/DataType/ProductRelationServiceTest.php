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
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductRelationService
 */
final class ProductRelationServiceTest extends TestCase
{
    public function testGetNoCategoryIfNotAssignedToProduct(): void
    {
        $productRelationService = new ProductRelationService(
            new Repository(
                $this->createMock(QueryBuilderFactoryInterface::class)
            )
        );
        $noCategoryProductModelStub = new class extends EshopArticleModel {
            public function __construct()
            {
            }

            public function getCategory()
            {
                return null;
            }
        };

        $this->assertNull(
            $productRelationService->getCategory(
                new Product(
                    $noCategoryProductModelStub
                )
            )
        );
    }

    public function testGetNoCategoryIfEmptyCategoryAssignedToProduct(): void
    {
        $productRelationService = new ProductRelationService(
            new Repository(
                $this->createMock(QueryBuilderFactoryInterface::class)
            )
        );
        $emptyCategoryProductModelStub = new class extends EshopArticleModel {
            public function __construct()
            {
            }

            public function getCategory()
            {
                return new class extends EshopCategoryModel
                    {
                    public function __construct()
                    {
                    }

                    public function getId()
                    {
                        return '';
                    }
                };
            }
        };

        $this->assertNull(
            $productRelationService->getCategory(
                new Product(
                    $emptyCategoryProductModelStub
                )
            )
        );
    }
}
