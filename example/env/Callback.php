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
