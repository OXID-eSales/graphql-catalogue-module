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
use OxidEsales\GraphQL\Catalogue\Service\Product as ProductService;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Base\Service\Authorization;

/**
 * @covers OxidEsales\GraphQL\Catalogue\DataType\ProductRelationService
 */
final class ProductRelationServiceTest extends TestCase
{

    private function productRelationService(): ProductRelationService
    {
        $repo = new Repository(
            $this->createMock(QueryBuilderFactoryInterface::class)
        );

        return new ProductRelationService(
            new ProductService(
                $repo,
                $this->createMock(Authorization::class)
            )
        );
    }

    public function testGetNoCategoryIfNotAssignedToProduct(): void
    {
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
            $this->productRelationService()->getCategory(
                new Product(
                    $noCategoryProductModelStub
                )
            )
        );
    }

    public function testGetNoCategoryIfEmptyCategoryAssignedToProduct(): void
    {
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
            $this->productRelationService()->getCategory(
                new Product(
                    $emptyCategoryProductModelStub
                )
            )
        );
    }
}
