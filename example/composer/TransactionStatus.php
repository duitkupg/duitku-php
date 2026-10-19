<?php

require_once __DIR__ . '/vendor/autoload.php';

$duitkuConfig = new \Duitku\Config("e3b0c44298fc........", "DXXXX"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
$duitkuConfig->setSandboxMode(true);
// $duitkuConfig->setDuitkuLogs(false);

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
