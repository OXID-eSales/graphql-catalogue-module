<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Controller;

use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use OxidEsales\GraphQL\Catalogue\DataType\Action as ActionDataType;
use OxidEsales\GraphQL\Catalogue\DataType\ActionFilterList;
use OxidEsales\GraphQL\Catalogue\Exception\ActionNotFound;
use TheCodingMachine\GraphQLite\Annotations\Query;

class Action extends Base
{
    /**
     * @Query()
     *
     * @return ActionDataType
     *
     * @throws ActionNotFound
     * @throws InvalidLogin
     */
    public function action(string $id): ActionDataType
    {
        try {
            /** @var ActionDataType $action */
            $action = $this->repository->getById(
                $id,
                ActionDataType::class
            );
        } catch (NotFound $e) {
            throw ActionNotFound::byId($id);
        }

        if ($action->isActive()) {
            return $action;
        }

        if (!$this->isAuthorized('VIEW_INACTIVE_ACTION')) {
            throw new InvalidLogin("Unauthorized");
        }

        return $action;
    }

    /**
     * @Query()
     *
     * @return ActionDataType[]
     */
    public function actions(?ActionFilterList $filter = null): array
    {
        $filter = $filter ?? new ActionFilterList();

        // In case user has VIEW_INACTIVE_ACTION permissions
        // return all actions including inactive ones
        if ($this->isAuthorized('VIEW_INACTIVE_ACTION')) {
            $filter = $filter->withActiveFilter(null);
        }

        $actions = $this->repository->getByFilter(
            $filter,
            ActionDataType::class
        );

        return $actions;
    }
}
