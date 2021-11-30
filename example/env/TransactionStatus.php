<?php

use PhpDotEnv\DotEnv;

require_once dirname(__FILE__) . '/Duitku.php';
require_once dirname(__FILE__) . '/PhpDotEnv/DotEnv.php';
$env = new DotEnv(__DIR__ . '/.env');
$env->load();

$duitkuConfig = new \Duitku\Config(getenv("SANDBOX_KEY"), getenv("SANDBOX_MERCHANTCODE"));

if (getenv("SANDBOX_MODE") == "true") {
	$duitkuConfig->setApiKey(getenv("SANDBOX_KEY")); //'YOUR_MERCHANT_KEY';
	$duitkuConfig->setMerchantCode(getenv("SANDBOX_MERCHANTCODE")); //'YOUR_MERCHANT_CODE';
	$duitkuConfig->setSandboxMode(true);
} else {
	$duitkuConfig->setApiKey(getenv("PRODUCTION_KEY")); //'YOUR_MERCHANT_KEY';
	$duitkuConfig->setMerchantCode(getenv("PRODUCTION_MERCHANTCODE")); //'YOUR_MERCHANT_CODE';
	$duitkuConfig->setSandboxMode(false);
}

try {
	$merchantOrderId = "1"; //"YOUR_MERCHANTORDERID";
	$transactionList = \Duitku\Pop::transactionStatus($merchantOrderId, $duitkuConfig);

	header('Content-Type: application/json');
	$transaction = json_decode($transactionList);

	// var_dump($transactionList);

	if ($transaction->statusCode == "00") {
		// Action Success
	} else if ($transaction->statusCode == "01") {
		// Action Pending
	} else {
		// Action Failed Or Expired
	}
} catch (Exception $e) {
	echo $e->getMessage();
}
