<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Tests;

use OxidEsales\GraphQL\Base\Tests\Integration\TestCase;

abstract class TokenTestCase extends TestCase
{
    /** @var string */
    protected const ADMIN_USER = 'admin';

    /** @var string */
    protected const ADMIN_PASS = 'admin';

    public function prepareAdminToken(string $username = null, string $password = null): void
    {
        $result = $this->query('query {
            token (
                username: "' . (($username) ?: self::ADMIN_USER) . '",
                password: "' . (($password) ?: self::ADMIN_PASS) . '"
            )
        }');
        $this->setAuthToken($result['body']['data']['token']);
    }
}
