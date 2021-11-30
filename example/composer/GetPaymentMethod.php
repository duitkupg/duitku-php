<?php

require_once __DIR__ . '/vendor/autoload.php';

$duitkuConfig = new \Duitku\Config("732B39FC61796845775D2C4FB05332AF", "D0001"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
$duitkuConfig->setSandboxMode(true);
// $duitkuConfig->setDuitkuLogs(false);

try {
    $paymentAmount = "10000"; //"YOUR_AMOUNT";
    $paymentMethodList = \Duitku\Pop::getPaymentMethod($paymentAmount, $duitkuConfig);

    header('Content-Type: application/json');
    echo $paymentMethodList;
} catch (Exception $e) {
    echo $e->getMessage();
}
