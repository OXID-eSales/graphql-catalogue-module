<?php
// phpcs:ignoreFile

use OxidEsales\Facts\Facts;

$serviceCaller = oxNew(\OxidEsales\TestingLibrary\ServiceCaller::class);
$testConfig = oxNew(\OxidEsales\TestingLibrary\TestConfig::class);

$testDirectory = $testConfig->getEditionTestsPath($testConfig->getShopEdition());
$serviceCaller->setParameter('importSql', '@' . $testDirectory . '/Fixtures/testdemodata.sql');
$serviceCaller->callService('ShopPreparation', 1);

$serviceCaller->setParameter('importSql', '@' . __DIR__ . '/Fixtures/testdemodata.sql');
$serviceCaller->callService('ShopPreparation', 1);

if ((new Facts())->isEnterprise()) {
    $serviceCaller->setParameter('importSql', '@' . __DIR__ . '/Fixtures/testdemodata_ee.sql');
    $serviceCaller->callService('ShopPreparation', 1);
}