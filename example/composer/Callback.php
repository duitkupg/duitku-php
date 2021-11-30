<?php

require_once __DIR__ . '/vendor/autoload.php';

$duitkuConfig = new \Duitku\Config("732B39FC61796845775D2C4FB05332AF", "D0001"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
$duitkuConfig->setSandboxMode(true);
// $duitkuConfig->setDuitkuLogs(false);

try {
    $callback = \Duitku\Pop::callback($duitkuConfig);

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
