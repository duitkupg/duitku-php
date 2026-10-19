<?php

require_once dirname(__FILE__) . '/Duitku.php';

$duitkuConfig = new \Duitku\Config("e3b0c44298fc........", "DXXXX"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
$duitkuConfig->setSandboxMode(true);
// $duitkuConfig->setDuitkuLogs(false);

try {
    $callback = \Duitku\Api::callback($duitkuConfig);

    header('Content-Type: application/json');
    $notif = json_decode($callback);

    // var_dump($callback);

    if ($notif->resultCode == "00") {
        // Action Success
    } else if ($notif->resultCode == "01") {
        // Action Failed
    }
} catch (Exception $e) {
    http_response_code(400);
    echo $e->getMessage();
}
