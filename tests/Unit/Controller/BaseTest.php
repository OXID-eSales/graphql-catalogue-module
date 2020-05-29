<?php

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests\Unit\Controller;

use OxidEsales\GraphQL\Base\Service\Authentication;
use OxidEsales\GraphQL\Base\Service\Authorization;
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
        $base = $this->getBase($allowed, $logged);

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

    public function isAuthenticatedDataProvider()
    {
        return [
            'logged_in' => [
                true
            ],
            'not_logged_in' => [
                false
            ]
        ];
    }

    /**
     * @dataProvider isAuthenticatedDataProvider
     *
     * @covers \OxidEsales\GraphQL\Catalogue\Controller\Base
     */
    public function testIsAuthenticated(bool $logged)
    {
        $base = $this->getBase(true, $logged);

        $this->assertSame($logged, $base->isAuthenticated());
    }

    public function testWhoIsAuthenticated()
    {
        $username = 'user@oxid-esales.com';
        $base = $this->getBase(true, true);

        $this->assertSame($username, $base->whoIsAuthenticated());
    }

    private function getBase(bool $allowed, bool $logged, string $userName = 'user@oxid-esales.com'): Base
    {
        $repository = $this->getMockBuilder(Repository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $authorizationService = $this->getMockBuilder(Authorization::class)
            ->disableOriginalConstructor()
            ->setMethods(['isAllowed'])
            ->getMock();
        $authorizationService
            ->method("isAllowed")
            ->willReturn($allowed);

        $authenticationService = $this->getMockBuilder(Authentication::class)
            ->disableOriginalConstructor()
            ->setMethods(['isLogged', 'whoIsLogged'])
            ->getMock();
        $authenticationService
            ->method("isLogged")
            ->willReturn($logged);
        $authenticationService
            ->method("whoIsLogged")
            ->willReturn($userName);

        return new class($repository, $authenticationService, $authorizationService) extends Base {
        };
    }
}
