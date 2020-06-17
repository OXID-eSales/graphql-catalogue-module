<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\User\Service;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Base\Service\Authentication;
use OxidEsales\GraphQL\Base\Service\Authorization;
use OxidEsales\GraphQL\Catalogue\Shared\Infrastructure\Repository;
use OxidEsales\GraphQL\Catalogue\User\DataType\User as UserDataType;
use OxidEsales\GraphQL\Catalogue\User\Exception\UserNotFound;

final class User
{
    /** @var Repository */
    private $repository;

    /** @var Authorization */
    private $authorizationService;

    /** @var Authentication */
    private $authenticationService;

    public function __construct(
        Repository $repository,
        Authorization $authorizationService,
        Authentication $authenticationService
    ) {
        $this->repository            = $repository;
        $this->authorizationService  = $authorizationService;
        $this->authenticationService = $authenticationService;
    }

    /**
     * @throws UserNotFound
     * @throws InvalidLogin
     */
    public function user(string $id): UserDataType
    {
        if (
            $this->authenticationService->getUserId() !== $id &&
            !$this->authorizationService->isAllowed('VIEW_USER')
        ) {
            throw new InvalidLogin('Unauthorized');
        }

        try {
            /** @var UserDataType $user */
            $user = $this->repository->getById($id, UserDataType::class);
        } catch (NotFound $e) {
            throw UserNotFound::byId($id);
        }

        return $user;
    }

    /**
     * @throws UserNotFound
     * @throws InvalidLogin
     */
    public function userFirstName(string $id): string
    {
        try {
            /** @var UserDataType $user */
            $user = $this->repository->getById($id, UserDataType::class);
        } catch (NotFound $e) {
            //TODO: maybe better throw new InvalidLogin("Unauthorized"); ?
            throw UserNotFound::byId($id);
        }

        return $user->getFirstName();
    }
}
