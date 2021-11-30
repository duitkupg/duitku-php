<?php

require_once __DIR__ . '/vendor/autoload.php';

$duitkuConfig = new \Duitku\Config("732B39FC61796845775D2C4FB05332AF", "D0001"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
$duitkuConfig->setSandboxMode(true);
// $duitkuConfig->setDuitkuLogs(false);

try {
    $merchantOrderId = "1"; //"YOUR_MERCHANTORDERID";
    $transactionList = \Duitku\Api::transactionStatus($merchantOrderId, $duitkuConfig);

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
