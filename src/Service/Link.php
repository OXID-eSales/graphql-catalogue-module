<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Service;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Link as LinkDataType;
use OxidEsales\GraphQL\Catalogue\DataType\LinkFilterList;
use OxidEsales\GraphQL\Base\Service\Authorization;
use OxidEsales\GraphQL\Catalogue\Exception\LinkNotFound;

final class Link
{
    /** @var Repository */
    private $repository;

    /** @var Authorization */
    private $authorizationService;

    public function __construct(
        Repository $repository,
        Authorization $authorizationService
    ) {
        $this->repository = $repository;
        $this->authorizationService = $authorizationService;
    }

    /**
     * @return LinkDataType
     *
     * @throws LinkNotFound
     * @throws InvalidLogin
     */
    public function link(string $id): LinkDataType
    {
        try {
            /** @var LinkDataType $link */
            $link = $this->repository->getById(
                $id,
                LinkDataType::class
            );
        } catch (NotFound $e) {
            throw LinkNotFound::byId($id);
        }

        if ($link->isActive()) {
            return $link;
        }

        if (!$this->authorizationService->isAllowed('VIEW_INACTIVE_LINK')) {
            throw new InvalidLogin("Unauthorized");
        }

        return $link;
    }

    /**
     * @return LinkDataType[]
     */
    public function links(LinkFilterList $filter): array
    {
        // In case user has VIEW_INACTIVE_LINK permissions
        // return all links including inactive ones
        if ($this->authorizationService->isAllowed('VIEW_INACTIVE_LINK')) {
            $filter = $filter->withActiveFilter(null);
        }

        $links = $this->repository->getByFilter(
            $filter,
            LinkDataType::class
        );

        return $links;
    }
}
