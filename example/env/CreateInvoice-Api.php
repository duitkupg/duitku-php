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

$paymentAmount      = 10000; // Amount
$paymentMethod      = "BT"; // Permata Bank Virtual Account
$email              = "customer@gmail.com"; // your customer email
$phoneNumber        = "081234567890"; // your customer phone number (optional)
$productDetails     = "Test Payment";
$merchantOrderId    = time(); // from merchant, unique   
$additionalParam    = ''; // optional
$merchantUserInfo   = ''; // optional
$customerVaName     = 'John Doe'; // display name on bank confirmation display
$callbackUrl        = 'http://YOUR_SERVER/callback'; // url for callback
$returnUrl          = 'http://YOUR_SERVER/return'; // url for redirect
$expiryPeriod       = 60; // set the expired time in minutes

// Customer Detail
$firstName          = "John";
$lastName           = "Doe";

// Address
$alamat             = "Jl. Kembangan Raya";
$city               = "Jakarta";
$postalCode         = "11530";
$countryCode        = "ID";

$address = array(
    'firstName'     => $firstName,
    'lastName'      => $lastName,
    'address'       => $alamat,
    'city'          => $city,
    'postalCode'    => $postalCode,
    'phone'         => $phoneNumber,
    'countryCode'   => $countryCode
);

$customerDetail = array(
    'firstName'         => $firstName,
    'lastName'          => $lastName,
    'email'             => $email,
    'phoneNumber'       => $phoneNumber,
    'billingAddress'    => $address,
    'shippingAddress'   => $address
);


// Item Details
$item1 = array(
    'name'      => $productDetails,
    'price'     => $paymentAmount,
    'quantity'  => 1
);


$itemDetails = array(
    $item1
);

$params = array(
    'paymentAmount'     => $paymentAmount,
    'paymentMethod'     => $paymentMethod,
    'merchantOrderId'   => $merchantOrderId,
    'productDetails'    => $productDetails,
    'additionalParam'   => $additionalParam,
    'merchantUserInfo'  => $merchantUserInfo,
    'customerVaName'    => $customerVaName,
    'email'             => $email,
    'phoneNumber'       => $phoneNumber,
    'itemDetails'       => $itemDetails,
    'customerDetail'    => $customerDetail,
    'callbackUrl'       => $callbackUrl,
    'returnUrl'         => $returnUrl,
    'expiryPeriod'      => $expiryPeriod
);

try {
    // createInvoice Request
    $responseDuitkuApi = \Duitku\Api::createInvoice($params, $duitkuConfig);

    header('Content-Type: application/json');

    $responseDuitku = json_decode($responseDuitkuApi);

    // var_dump($$responseDuitku);

    if ($responseDuitku->statusCode == "00") {
        header('location: ' . $responseDuitku->paymentUrl);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
