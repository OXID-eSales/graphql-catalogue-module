<?php
// phpcs:ignoreFile

$serviceCaller = oxNew(\OxidEsales\TestingLibrary\ServiceCaller::class);
$testConfig = oxNew(\OxidEsales\TestingLibrary\TestConfig::class);

$testDirectory = $testConfig->getEditionTestsPath($testConfig->getShopEdition());
$serviceCaller->setParameter('importSql', '@' . $testDirectory . '/Fixtures/testdemodata.sql');
$serviceCaller->callService('ShopPreparation', 1);

$serviceCaller->setParameter('importSql', '@' . __DIR__ . '/Fixtures/testdemodata.sql');
$serviceCaller->callService('ShopPreparation', 1);

define('oxADMIN_LOGIN', oxDb::getDb()->getOne("select OXUSERNAME from oxuser where oxid='oxdefaultadmin'"));
define('oxADMIN_PASSWD', getenv('oxADMIN_PASSWD') ? getenv('oxADMIN_PASSWD') : 'admin');
