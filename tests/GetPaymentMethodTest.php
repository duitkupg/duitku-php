<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../Duitku.php';

class GetPaymentMethodApiTest extends TestCase
{
    public function testGetJson()
    {
        $duitkuConfig = new \Duitku\Config("732B39FC61796845775D2C4FB05332AF", "D0001"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
        $duitkuConfig->setSandboxMode(true);

        $paymentAmount = "10000"; //"YOUR_AMOUNT";
        $paymentMethodList = \Duitku\Pop::getPaymentMethod($paymentAmount, $duitkuConfig);

        $paymentMethod = json_decode($paymentMethodList);

        $this->assertEquals("00", $paymentMethod->responseCode);
        $this->assertEquals("SUCCESS", $paymentMethod->responseMessage);
        $this->assertNotEmpty($paymentMethod->paymentFee);
    }
}
