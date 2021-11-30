# Duitku PHP Library
Welcome to Duitku PHP Example Project Implementation Page, Integrate this Duitku PHP to start transaction using Duitku in your Web or Application.


![flow_duitku_payment](https://user-images.githubusercontent.com/13087322/138187049-1a28ed5b-e9e8-48c9-aada-fa6f978c6e64.gif)



<h3>Demo Project</h3>
Go To  <a target="_blank" href="https://api-sandbox.duitku.com/demoduitku/">Demo Duitku-Pop</a>

Go To  <a target="_blank" href="https://sandbox.duitku.com/payment/demopage.aspx">Demo Duitku-Api</a>


<h3>Full Step Docs</h3>
Go To  <a target="_blank" href="https://docs.duitku.com/pop/id">Duitku Docs Duitku-Pop</a>

Go To  <a target="_blank" href="https://docs.duitku.com/api/id">Duitku Docs Duitku-Api</a>

## Installation

Install duitku-php with composer by following command:

```bash
composer require duitkupg/duitku-php:dev-master
```

or add it manually in your `composer.json` file.

```bash
"duitkupg/duitku-php": "dev-master"
```
## Configuration Settings

```php
$duitkuConfig = new \Duitku\Config("YOUR_MERCHANT_KEY", "YOUR_MERCHANT_CODE");
// false for production mode
// true for sandbox mode
$duitkuConfig->setSandboxMode(false);
// set sanitizer (default : true)
$duitkuConfig->setSanitizedMode(false);
// set log parameter (default : true)
$duitkuConfig->setDuitkuLogs(false);
```

## Duitku POP
### Create Invoice (Duitku-Pop)

Parameter paymentMethod is optional,

You can put payment method('paymentMethod') on parameter createInvoice, as a step to set direct payment to specific payment. Customers will be directed to wanted payment without necessary to pick a payment.

```php
// $paymentMethod      = ""; // PaymentMethod list => https://docs.duitku.com/pop/id/#payment-method
$paymentAmount      = 10000; // Amount
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
    $responseDuitkuPop = \Duitku\Pop::createInvoice($params, $duitkuConfig);

    header('Content-Type: application/json');
    echo $responseDuitkuPop;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

### Check Transaction Status (Duitku-Pop)
```php
try {
    $merchantOrderId = "YOUR_MERCHANTORDERID";
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
```

### Callback (Duitku-Pop)
```php
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
```

### Get Payment Method (Duitku-Pop)
```php
try {
    $paymentAmount = "10000"; //"YOUR_AMOUNT";
    $paymentMethodList = \Duitku\Pop::getPaymentMethod($paymentAmount, $duitkuConfig);

    header('Content-Type: application/json');
    echo $paymentMethodList;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

### Frontend Integration (Duitku-Pop)
```bash
$.ajax({
    type: "POST",
    data:{
      // paymentMethod: '',
	  paymentAmount: amount,
	  productDetail: productDetail,
	  email: email,
	  phoneNumber: phoneNumber
    },
    url: 'http://domain.com/createInvoice.php',
    dataType: "json",
    cache: false,
    success: function (result) {                                
            console.log(result.reference);
            console.log(result);
            checkout.process(result.reference, {
                successEvent: function(result){
                // Add Your Action
                    console.log('success');
                    console.log(result);
                    alert('Payment Success');
                },
                pendingEvent: function(result){
                // Add Your Action
                    console.log('pending');
                    console.log(result);
                    alert('Payment Pending');
                },
                errorEvent: function(result){
                // Add Your Action
                    console.log('error');
                    console.log(result);
                    alert('Payment Error');
                },
                closeEvent: function(result){
                // Add Your Action
                    console.log('customer closed the popup without finishing the payment');
                    console.log(result);
                    alert('customer closed the popup without finishing the payment');
                }
            });     
    }
});
```

## Duitku API
### Create Invoice (Duitku-Api)
```php
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
    echo $responseDuitkuApi;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

### Check Transaction Status (Duitku-Api)
```php
try {
    $merchantOrderId = "YOUR_MERCHANTORDERID";
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
```

### Callback (Duitku-Api)
```php
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
```

### Get Payment Method (Duitku-Api)
```php
try {
    $paymentAmount = "10000"; //"YOUR_AMOUNT";
    $paymentMethodList = \Duitku\Api::getPaymentMethod($paymentAmount, $duitkuConfig);

    header('Content-Type: application/json');
    echo $paymentMethodList;
} catch (Exception $e) {
    echo $e->getMessage();
}
```

## Tests

### Tests Duitku-Pop

#### Create Invoice Test
```bash
php vendor\bin\phpunit tests\CreateInvoiceTest.php
```

#### Transaction Status Test
```bash
php vendor\bin\phpunit tests\TransactionStatusTest.php
```

#### Callback Test
```bash
php vendor\bin\phpunit tests\CallbackTest.php
```

### Tests Duitku-Api

#### Create Invoice Api Test
```bash
php vendor\bin\phpunit tests\CreateInvoiceApiTest.php
```

#### Transaction Status Api Test
```bash
php vendor\bin\phpunit tests\TransactionStatusApiTest.php
```

#### Callback Api Test
```bash
php vendor\bin\phpunit tests\CallbackApiTest.php
```