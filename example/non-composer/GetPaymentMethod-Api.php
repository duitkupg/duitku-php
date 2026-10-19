<?php

require_once dirname(__FILE__) . '/Duitku.php';

$duitkuConfig = new \Duitku\Config("e3b0c44298fc........", "DXXXX"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
$duitkuConfig->setSandboxMode(true);
// $duitkuConfig->setDuitkuLogs(false);

try {
    $paymentAmount = "10000"; //"YOUR_AMOUNT";
    $paymentMethodList = \Duitku\Api::getPaymentMethod($paymentAmount, $duitkuConfig);

    header('Content-Type: application/json');
    echo $paymentMethodList;
} catch (Exception $e) {
    echo $e->getMessage();
}
