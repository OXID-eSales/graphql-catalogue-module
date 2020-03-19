<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Controller;

use OxidEsales\GraphQL\Base\Service\AuthenticationService;
use OxidEsales\GraphQL\Base\Service\AuthorizationService;
use OxidEsales\GraphQL\Catalogue\Controller\Base;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use PHPUnit\Framework\TestCase;

class BaseTest extends TestCase
{
    /**
     * @dataProvider isAuthorizedDataProvider
     *
     * @covers \OxidEsales\GraphQL\Catalogue\Controller\Base
     */
    public function testIsAuthorized(bool $allowed, bool $logged, bool $expected)
    {
        $repository = $this->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $authorizationService = $this->getMockBuilder(AuthorizationService::class)
            ->disableOriginalConstructor()
            ->setMethods(['isAllowed'])
            ->getMock();
        $authorizationService
            ->method("isAllowed")
            ->willReturn($allowed);

        $authenticationService = $this->getMockBuilder(AuthenticationService::class)
            ->disableOriginalConstructor()
            ->setMethods(['isLogged'])
            ->getMock();
        $authenticationService
            ->method("isLogged")
            ->willReturn($logged);

        $base = new class (
            $repository,
            $authenticationService,
            $authorizationService
        ) extends Base {
        };

        $this->assertSame(
            $expected,
            $base->isAuthorized("right_key")
        );
    }

    public function isAuthorizedDataProvider()
    {
        return [
            [true, true, true],
            [true, false, false],
            [false, false, false],
            [false, true, false]
        ];
    }
}
