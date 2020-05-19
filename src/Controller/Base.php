<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Service\Authentication;
use OxidEsales\GraphQL\Base\Service\Authorization;
use OxidEsales\GraphQL\Catalogue\Service\Repository;

abstract class Base
{
    /** @var Repository */
    protected $repository;

    /** @var Authentication */
    protected $authenticationService;

    /** @var Authorization */
    protected $authorizationService;

    public function __construct(
        Repository $repository,
        Authentication $authenticationService,
        Authorization $authorizationService
    ) {
        $this->repository = $repository;
        $this->authenticationService = $authenticationService;
        $this->authorizationService = $authorizationService;
    }

    /**
     * @param String $action
     *
     * @return bool
     */
    public function isAuthorized(string $action)
    {
        if (
            $this->authenticationService->isLogged() &&
            $this->authorizationService->isAllowed($action)
        ) {
            return true;
        }

        return false;
    }
}
