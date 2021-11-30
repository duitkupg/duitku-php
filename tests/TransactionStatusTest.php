<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../Duitku.php';

class TransactionStatusTest extends TestCase
{
    public function testGetJson()
    {
        $duitkuConfig = new \Duitku\Config("732B39FC61796845775D2C4FB05332AF", "D0001"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
        $duitkuConfig->setSandboxMode(true);

        $merchantOrderId = "1"; //"YOUR_MERCHANTORDERID";
        $transactionList = \Duitku\Pop::transactionStatus($merchantOrderId, $duitkuConfig);

        $transaction = json_decode($transactionList);

        $this->assertEquals("00", $transaction->statusCode);
        $this->assertEquals("SUCCESS", $transaction->statusMessage);
        $this->assertEquals($merchantOrderId, $transaction->merchantOrderId);
        $this->assertNotEmpty($transaction->reference);
        $this->assertNotEmpty($transaction->amount);
        $this->assertNotEmpty($transaction->fee);
    }
}
