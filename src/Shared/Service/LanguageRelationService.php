<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\Shared\Service;

use OxidEsales\Eshop\Core\Language as LanguageService;
use OxidEsales\GraphQL\Catalogue\Shared\DataType\Language;
use TheCodingMachine\GraphQLite\Annotations\ExtendType;
use TheCodingMachine\GraphQLite\Annotations\Field;

/**
 * @ExtendType(class=Language::class)
 */
final class LanguageRelationService
{
    /** @var LanguageService */
    private $languageService;

    public function __construct(
        LanguageService $languageService
    ) {
        $this->languageService = $languageService;
    }

    /**
     * @Field()
     */
    public function getCode(Language $language): string
    {
        $languageId = $language->getLanguageId();

        return $this->languageService->getLanguageAbbr($languageId);
    }

    /**
     * @Field()
     */
    public function getLanguage(Language $language): string
    {
        $languageId = $language->getLanguageId();
        $languageNames = $this->languageService->getLanguageNames();

        return $languageNames[$languageId];
    }
}
