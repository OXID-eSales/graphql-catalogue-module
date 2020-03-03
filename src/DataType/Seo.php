<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Catalogue\DataType;

use OxidEsales\Eshop\Core\Contract\IUrl  as EshopContractUrl;
use OxidEsales\Eshop\Core\Model\BaseModel as EshopModel;
use OxidEsales\Eshop\Core\Registry as EshopRegistry;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;

/**
 * @Type()
 */
final class Seo
{

    /** @var EshopModel */
    private $eshopModel;

    public function __construct(
        EshopModel $eshopModel
    ) {
        $this->eshopModel = $eshopModel;
    }

    /**
     * @Field()
     */
    public function getMetadescription(): string
    {
        $dataType = 'oxdescription';
        return (string) EshopRegistry::getSeoEncoder()->getMetaData($this->eshopModel->getId(), $dataType);
    }

    /**
     * @Field()
     */
    public function getMetakeywords(): string
    {
        $dataType = 'oxkeywords';
        return (string) EshopRegistry::getSeoEncoder()->getMetaData($this->eshopModel->getId(), $dataType);
    }

    /**
     * @Field()
     */
    public function getSeourl(): ?string
    {
        $seoUrl = null;
        if (
            is_a($this->eshopModel, EshopContractUrl::class)
            && EshopRegistry::getUtils()->seoIsActive()
        ) {
            $seoUrl = $this->eshopModel->getLink();
        }

        return $seoUrl;
    }

    /**
     * @Field()
     */
    public function getStandardurl(): ?string
    {
        $seoUrl = null;
        if (is_a($this->eshopModel, EshopContractUrl::class)) {
            $seoUrl = html_entity_decode($this->eshopModel->getStdLink());
        }

        return $seoUrl;
    }
}
