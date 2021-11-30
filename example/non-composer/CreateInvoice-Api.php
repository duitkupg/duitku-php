<?php

require_once dirname(__FILE__) . '/Duitku.php';

$duitkuConfig = new \Duitku\Config("732B39FC61796845775D2C4FB05332AF", "D0001"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
$duitkuConfig->setSandboxMode(true);
// $duitkuConfig->setDuitkuLogs(false);

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
