<?php

namespace Duitku;

/**
 * Send request and processing response for Duitku-API
 */
class Api extends Request
{

    /**
     * Create Invoice Duitku API
     *
     * Example:
     *
     * ```php
     * 
     * $paymentAmount      = 10000; // Amount
     * $paymentMethod      = "BT"; // Permata Bank Virtual Account
     * $email              = "customer@gmail.com"; // your customer email
     * $phoneNumber        = "081234567890"; // your customer phone number (optional)
     * $productDetails     = "Test Payment";
     * $merchantOrderId    = time(); // from merchant, unique   
     * $additionalParam    = ''; // optional
     * $merchantUserInfo   = ''; // optional
     * $customerVaName     = 'John Doe'; // display name on bank confirmation display
     * $callbackUrl        = 'http://YOUR_SERVER/callback'; // url for callback
     * $returnUrl          = 'http://YOUR_SERVER/return'; // url for redirect
     * $expiryPeriod       = 60; // set the expired time in minutes
     * 
     * // Customer Detail
     * $firstName          = "John";
     * $lastName           = "Doe";
     * 
     * // Address
     * $alamat             = "Jl. Kembangan Raya";
     * $city               = "Jakarta";
     * $postalCode         = "11530";
     * $countryCode        = "ID";
     * 
     * $address = array(
     * 	'firstName'     => $firstName,
     * 	'lastName'      => $lastName,
     * 	'address'       => $alamat,
     * 	'city'          => $city,
     * 	'postalCode'    => $postalCode,
     * 	'phone'         => $phoneNumber,
     * 	'countryCode'   => $countryCode
     * );
     * 
     * $customerDetail = array(
     * 	'firstName'         => $firstName,
     * 	'lastName'          => $lastName,
     * 	'email'             => $email,
     * 	'phoneNumber'       => $phoneNumber,
     * 	'billingAddress'    => $address,
     * 	'shippingAddress'   => $address
     * );
     * 
     * // Item Details
     * $item1 = array(
     * 	'name'      => $productDetails,
     * 	'price'     => $paymentAmount,
     * 	'quantity'  => 1
     * );
     * 
     * $itemDetails = array(
     * 	$item1
     * );
     * 
     * $params = array(
     * 	'paymentAmount'     => $paymentAmount,
     *  'paymentMethod'     => $paymentMethod,
     * 	'merchantOrderId'   => $merchantOrderId,
     * 	'productDetails'    => $productDetails,
     * 	'additionalParam'   => $additionalParam,
     * 	'merchantUserInfo'  => $merchantUserInfo,
     * 	'customerVaName'    => $customerVaName,
     * 	'email'             => $email,
     * 	'phoneNumber'       => $phoneNumber,
     * 	'itemDetails'       => $itemDetails,
     * 	'customerDetail'    => $customerDetail,
     * 	'callbackUrl'       => $callbackUrl,
     * 	'returnUrl'         => $returnUrl,
     * 	'expiryPeriod'      => $expiryPeriod
     * );
     * 
     * try {
     * 	// createInvoice Request
     * 	$responseDuitkuApi = \Duitku\Api::createInvoice($params, $config);
     * 
     * 	header('Content-Type: application/json');
     * 	echo $responseDuitkuApi;
     * } catch (Exception $e) {
     * 	echo $e->getMessage();
     * }
     *
     * ```
     * @param array $payload
     * @param \Duitku\Config $config
     * @return string response duitku API. 
     * @throws Exception
     */
    public static function createInvoice($payload, $config)
    {
        if ($config->getSanitizedMode()) {
            \Duitku\Sanitizer::request($payload);
        }

        $timestamp = round(microtime(true) * 1000);
        $payload["merchantCode"]    = $config->getMerchantCode();
        $payload["signature"]       = md5($config->getMerchantCode() . $payload["merchantOrderId"] . $payload["paymentAmount"] . $config->getApiKey());

        $params         = json_encode($payload);
        $setLogFunction = __CLASS__ . "->" . __FUNCTION__;
        $url            = $config->getApiUrl() . '/webapi/api/merchant/v2/inquiry';
        return self::sendRequest($url, $params, $config, $setLogFunction);
    }

    /**
     * Cek Transaction Duitku API
     *
     * Example:
     *
     * ```php
     *
     * try {
     *	$merchantOrderId = "YOUR_ORDER_ID";
     *	$transactionList = \Duitku\Api::transactionStatus($merchantOrderId, $config);
     *
     *	header('Content-Type: application/json');
     *	echo json_encode($transactionList);
     *	
     *	if ($transaction->statusCode == "00") {
     *		// Action Success
     *	} else if ($transaction->statusCode == "01") {
     *		// Action Pending
     *	} else {
     *		// Action Failed Or Expired
     *	}
     * }
     * catch (Exception $e) {
     *	echo $e->getMessage();
     * }
     * ```
     *
     * @param string $merchantOrderId
     * @param \Duitku\Config $config
     * @return string response cek status duitku API.
     * @throws Exception
     */
    public static function transactionStatus($merchantOrderId, $config)
    {
        $signature = md5($config->getMerchantCode() . $merchantOrderId . $config->getApiKey());

        $payload = array(
            'merchantCode'        => $config->getMerchantCode(),
            'merchantOrderId'     => $merchantOrderId,
            'signature'           => $signature
        );

        $params         = json_encode($payload);
        $setLogFunction = __CLASS__ . "->" . __FUNCTION__;
        $url            = $config->getApiUrl() . '/webapi/api/merchant/transactionStatus';
        return self::sendRequest($url, $params, $config, $setLogFunction);
    }

    /**
     * Get List Payment Method Duitku API
     *
     * Example:
     *
     * ```php
     *
     * try {
     *  $paymentAmount = 10000;
     *	$paymentMethodList = \Duitku\Api::getPaymentMethod($paymentAmount, $config);
     *
     *	var_dump(paymentMethodList)
     *
     * }
     * catch (Exception $e) {
     *	echo $e->getMessage();
     * }
     * ```
     *
     * @param \Duitku\Config $config
     * @return string response cek status duitku API.
     * @throws Exception
     */
    public static function getPaymentMethod($paymentAmount, $config)
    {
        $datetime = date('Y-m-d H:i:s');
        $signature = hash("sha256", $config->getMerchantCode() . $paymentAmount . $datetime . $config->getApiKey());

        $payload = array(
            'merchantCode'        => $config->getMerchantCode(),
            'amount'              => $paymentAmount,
            'datetime'            => $datetime,
            'signature'           => $signature
        );

        $params         = json_encode($payload);
        $setLogFunction = __CLASS__ . "->" . __FUNCTION__;
        $url            = $config->getApiUrl() . '/webapi/api/merchant/paymentmethod/getpaymentmethod';
        return self::sendRequest($url, $params, $config, $setLogFunction);
    }

    /**
     * Callback Duitku API
     * Handle Method HTTP POST => Type x-www-form-urlencoded
     * 
     * Example
     * 
     * try {
     *	$callback = \Duitku\Api::callback($config);
     * 
     *	header('Content-Type: application/json');
     *	$notif = json_decode($callback);
     * 
     *	if ($notif->resultCode == "00") {
     *		// Action Success
     *	} else if ($notif->resultCode == "01") {
     *		// Action Pending
     *	} else {
     *		// Ignore
     *	}
     * 
     * } catch (Exception $e) {
     *	http_response_code(400);
     *	echo $e->getMessage();
     * }
     * 
     * @param \Duitku\Config $config
     * @return json response
     * @throws Exception
     */
    public static function callback($config)
    {
        $notification = $_POST;
        if (empty($notification)) {
            throw new \Exception('Access denied');
        }

        self::writeDuitkuLogsCallback($_SERVER['PHP_SELF'], json_encode($notification), $config);

        foreach ($config->callbackParams as $callbackParam) {
            if (!array_key_exists($callbackParam, $notification)) {
                $notification[$callbackParam] = null;
            }
        }

        if (!self::isSignatureValid($notification, $config)) {
            throw new \Exception('Signature Invalid');
        }

        return json_encode($notification);
    }

    /**
     * Validation signature callback duitku API
     *
     */
    private static function isSignatureValid($notification, $config)
    {
        $signature = $notification['signature'];
        $signGenerate = md5($notification['merchantCode'] . $notification['amount'] . $notification['merchantOrderId'] . $config->getApiKey());
        return $signature == $signGenerate;
    }
}
