<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Resolver;

use Doctrine\DBAL\Query\QueryBuilder;
use OxidEsales\EshopCommunity\Core\Model\BaseModel;
use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Service\Authentication;
use OxidEsales\GraphQL\Base\Service\Authorization;
use OxidEsales\GraphQL\Catalogue\DataType\DataType;

class BaseResolver
{
    /** @var Authentication */
    protected $authenticationService;

    /** @var Authorization */
    protected $authorizationService;

    public function __construct(
        Authentication $authenticationService,
        Authorization $authorizationService
    ) {
        $this->authenticationService = $authenticationService;
        $this->authorizationService = $authorizationService;
    }

    public function isAuthorized(string $action): bool
    {
        return (
            $this->authenticationService->isLogged() &&
            $this->authorizationService->isAllowed($action)
        );
    }

    public function resolveList(
        BaseModel $model,
        QueryBuilder $queryBuilder
    ): void {
        if (!$this->resolve()) {
            if ($activeSnippet = $model->getSqlActiveSnippet()) {
                $queryBuilder->andWhere($activeSnippet);
            }
        }
    }

    /**
     * @param DataType $type
     *
     * @throws InvalidLogin
     */
    public function resolveById(DataType $type): void
    {
        $resolvers = $this->getResolvers();
        foreach ($resolvers as $resolver) {
            if ($resolver->support($type)) {
                if (method_exists($type, 'isActive') && $type->isActive()) {
                    continue;
                }

                if ($this->isAuthorized($resolver->getAction())) {
                    continue;
                }

                throw new InvalidLogin('Unauthorized');
            }
        }
    }

    protected function resolve(): bool
    {
        $resolvers = $this->getResolvers();
        foreach ($resolvers as $resolver) {
            if ($this->isAuthorized($resolver->getAction())) {
                return true;
            }
        }

        return false;
    }

    protected function getResolvers(): iterable
    {
        return [
            new ProductResolver(),
            //todo: add more resolvers
        ];
    }
}
