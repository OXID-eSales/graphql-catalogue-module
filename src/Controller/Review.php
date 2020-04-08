<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Base\Service\AuthenticationServiceInterface;
use OxidEsales\GraphQL\Base\Service\AuthorizationServiceInterface;
use OxidEsales\GraphQL\Catalogue\DataType\Review as ReviewDataType;
use OxidEsales\GraphQL\Catalogue\DataType\ReviewActivityService;
use OxidEsales\GraphQL\Catalogue\Exception\ReviewNotFound;
use OxidEsales\GraphQL\Catalogue\Service\Repository;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Review extends Base
{
    private $reviewActivityService;

    public function __construct(
        Repository $repository,
        AuthenticationServiceInterface $authenticationService,
        AuthorizationServiceInterface $authorizationService,
        ReviewActivityService $reviewActivityService
    ) {
        parent::__construct($repository, $authenticationService, $authorizationService);

        $this->reviewActivityService = $reviewActivityService;
    }

    /**
     * @Query()
     *
     * @throws ReviewNotFound
     */
    public function review(string $id): ReviewDataType
    {
        try {
            /** @var ReviewDataType $review */
            $review = $this->repository->getById($id, ReviewDataType::class);
        } catch (NotFound $e) {
            throw ReviewNotFound::byId($id);
        }

        if ($this->reviewActivityService->isActive($review)) {
            return $review;
        }

        if (!$this->isAuthorized('VIEW_INACTIVE_REVIEW')) {
            throw new InvalidLogin("Unauthorized");
        }

        return $review;
    }
}
