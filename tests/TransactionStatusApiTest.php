<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../Duitku.php';

class TransactionStatusApiTest extends TestCase
{
    public function testGetJson()
    {
        $duitkuConfig = new \Duitku\Config("e3b0c44298fc........", "DXXXX"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
        $duitkuConfig->setSandboxMode(true);

        $merchantOrderId = "1"; //"YOUR_MERCHANTORDERID";
        $transactionList = \Duitku\Api::transactionStatus($merchantOrderId, $duitkuConfig);

        $transaction = json_decode($transactionList);

        $this->assertEquals("00", $transaction->statusCode);
        $this->assertEquals("SUCCESS", $transaction->statusMessage);
        $this->assertEquals($merchantOrderId, $transaction->merchantOrderId);
        $this->assertNotEmpty($transaction->reference);
        $this->assertNotEmpty($transaction->amount);
        $this->assertNotEmpty($transaction->fee);
    }
}
