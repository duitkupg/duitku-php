<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../Duitku.php';

class GetPaymentMethodApiTest extends TestCase
{
    public function testGetJson()
    {
        $duitkuConfig = new \Duitku\Config("e3b0c44298fc........", "DXXXX"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
        $duitkuConfig->setSandboxMode(true);

        $paymentAmount = "10000"; //"YOUR_AMOUNT";
        $paymentMethodList = \Duitku\Api::getPaymentMethod($paymentAmount, $duitkuConfig);

        $paymentMethod = json_decode($paymentMethodList);

        $this->assertEquals("00", $paymentMethod->responseCode);
        $this->assertEquals("SUCCESS", $paymentMethod->responseMessage);
        $this->assertNotEmpty($paymentMethod->paymentFee);
    }
}
