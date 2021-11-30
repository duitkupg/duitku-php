<?php

use PHPUnit\Framework\TestCase;

require_once dirname(__FILE__) . '/../Duitku.php';

class CallbackTest extends TestCase
{
    public function testGetJson()
    {
        $duitkuConfig = new \Duitku\Config("732B39FC61796845775D2C4FB05332AF", "D0001"); // 'YOUR_MERCHANT_KEY' and 'YOUR_MERCHANT_CODE'
        $duitkuConfig->setSandboxMode(true);

        // $_POST dummy parameter callback
        $paramsCallback = file_get_contents(__DIR__ . '/params/ParamsCallback.json');
        $_POST = json_decode($paramsCallback, true);

        $callback = \Duitku\Pop::callback($duitkuConfig);
        $notif = json_decode($callback);

        $this->assertEquals($_POST["resultCode"], $notif->resultCode);
        $this->assertEquals($_POST["merchantOrderId"], $notif->merchantOrderId);
        $this->assertEquals($_POST["reference"], $notif->reference);
        $this->assertEquals($_POST["merchantCode"], $notif->merchantCode);
        $this->assertEquals($_POST["amount"], $notif->amount);
        $this->assertEquals($_POST["signature"], $notif->signature);
    }
}
