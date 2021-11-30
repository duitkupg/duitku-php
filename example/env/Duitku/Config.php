<?php

namespace Duitku;

/**
 * Managing Duitku-pop configurations
 */
class Config
{
    /**
     * Constructor
     *
     * @param string $apiKey
     * @param string $merchantCode
     * @param boolean $isSandboxMode
     * @param boolean $isSanitizedMode
     * @param boolean $duitkuLogs
     */
    public function __construct($apiKey, $merchantCode, $isSandboxMode = true, $isSanitizedMode = true, $duitkuLogs = true)
    {
        $this->_apiKey          = $apiKey;
        $this->_merchantCode    = $merchantCode;
        $this->_isSandboxMode   = $isSandboxMode;
        $this->_isSanitizedMode = $isSanitizedMode;
        $this->_duitkuLogs      = $duitkuLogs;
    }

    /**
     * Your merchant's api key
     * 
     */
    private $_apiKey;
    /**
     * Your merchant's merchant code
     * 
     */
    private $_merchantCode;
    /**
     * false for production
     * true for sandbox
     * 
     */
    private $_isSandboxMode;
    /**
     * Enable request params sanitized mode / default true
     * 
     */
    private $_isSanitizedMode;
    /**
     * Set it true to enable log file
     * 
     */
    private $_duitkuLogs;

    /**
     * Set Callback Parameter
     *
     */
    public $callbackParams = array(
        "merchantCode",
        "amount",
        "merchantOrderId",
        "productDetail",
        "additionalParam",
        "paymentCode",
        "resultCode",
        "merchantUserId",
        "reference",
        "signature",
        "spUserHash"
    );

    const SANDBOX_URL = 'https://api-sandbox.duitku.com';
    const PASSPORT_URL = 'https://api-prod.duitku.com';

    const SANDBOX_API_URL = 'https://sandbox.duitku.com';
    const PASSPORT_API_URL = 'https://passport.duitku.com';

    /**
     * Set apiKey config
     * 
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    /**
     * Get apiKey config
     * 
     */
    public function getApiKey()
    {
        return $this->_apiKey;
    }

    /**
     * Set merchantCode config
     * 
     */
    public function setMerchantCode($merchantCode)
    {
        $this->_merchantCode = $merchantCode;
    }

    /**
     * Get merchantCode config
     * 
     */
    public function getMerchantCode()
    {
        return $this->_merchantCode;
    }

    /**
     * Set sandboxMode config
     * 
     */
    public function setSandboxMode($isSandboxMode)
    {
        $this->_isSandboxMode = $isSandboxMode;
    }

    /**
     * Get sandboxMode config
     * 
     */
    public function getSandboxMode()
    {
        return $this->_isSandboxMode;
    }

    /**
     * Set sanitizedMode config
     * 
     */
    public function setSanitizedMode($isSanitizedMode)
    {
        $this->_isSanitizedMode = $isSanitizedMode;
    }

    /**
     * Get sanitizedMode config
     * 
     */
    public function getSanitizedMode()
    {
        return $this->_isSanitizedMode;
    }

    /**
     * Set duitkuLogs config
     * 
     */
    public function setDuitkuLogs($duitkuLogs)
    {
        $this->_duitkuLogs = $duitkuLogs;
    }

    /**
     * Get duitkuLogs config
     * 
     */
    public function getDuitkuLogs()
    {
        return $this->_duitkuLogs;
    }

    /**
     * Get apiUrl
     * 
     * @return Duitku API URL, depends on $_isSandboxMode
     */
    public function getApiUrl()
    {
        if ($this->getSandboxMode()) {
            return self::SANDBOX_API_URL;
        } else {
            return self::PASSPORT_API_URL;
        }
    }

    /**
     * Get baseUrl
     * 
     * @return Duitku POP URL, depends on $_isSandboxMode
     */
    public function getBaseUrl()
    {
        if ($this->getSandboxMode()) {
            return self::SANDBOX_URL;
        } else {
            return self::PASSPORT_URL;
        }
    }

    /**
     * Generate string log file name
     * 
     * @return string
     */
    public function getLogFileName()
    {
        $logFileName = "duitku_" . date('Ymd') . ".log";
        return $logFileName;
    }
}
