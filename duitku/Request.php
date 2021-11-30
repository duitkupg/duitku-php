<?php

namespace Duitku;

/**
 * Execute requests curl and writelogs
 */
class Request
{

    /**
     * Function to send HTTP POST Requests
     *
     * @param string $url
     * @param json $params
     * @param \Duitku\Config $config
     * @param array $headerParam
     * @return void
     */
    protected static function sendRequest($url, $params, $config, $setLogFunction, $headerParam = array())
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headerCurl = array(
            'Content-Type: application/json',
            'Content-Length:' . strlen($params)
        );

        // merger param $mergedHeaders
        $mergedHeaders = array_merge($headerCurl, $headerParam);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $mergedHeaders);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        //execute post
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        self::writeDuitkuLogs($setLogFunction, $url, "POST", $params, $response, $config);

        if ($httpCode >= 400) {
            throw new \Exception('Duitku Error: ' . $httpCode . ' response: ' . $response);
        } else {
            return $response;
        }
    }

    /**
     * Duitku write log parameter
     * 
     * Please set =>
     * $duitkuConfig = new \Duitku\Config();
     * $duitkuConfig->setDuitkuLogs(true); // default true
     *
     */
    protected static function writeDuitkuLogs($setLogFunction, $url, $method, $logRequest, $logResponse, $config)
    {
        if ($config->getDuitkuLogs()) {
            if (!empty($logRequest)) {
                self::writeLogs($config, "Date:" . date('Y-m-d H:i:s'));
                self::writeLogs($config, "METHOD:" . $method);
                self::writeLogs($config, "FUNCTION:" . $setLogFunction);
                self::writeLogs($config, "URL:" . $url);
                self::writeLogs($config, "REQUEST:", $logRequest);
                self::writeLogs($config, "RESPONSE:", $logResponse . "\r\n");
            }
        }
    }

    /**
     * Duitku write log parameter for callback only
     * 
     * Please set =>
     * $duitkuConfig = new \Duitku\Config();
     * $duitkuConfig->setDuitkuLogs(true); // default true
     *
     */
    protected static function writeDuitkuLogsCallback($url, $logRequest, $config)
    {
        if ($config->getDuitkuLogs()) {
            if (!empty($logRequest)) {
                self::writeLogs($config, "Date:" . date('Y-m-d H:i:s'));
                self::writeLogs($config, "URL:" . $url);
                self::writeLogs($config, "CALLBACK REQUEST:", $logRequest . "\r\n");
            }
        }
    }

    /**
     * Write Log parameter
     *
     */
    private static function writeLogs($config, $logTitle, $logMessage = '')
    {
        $rootDirLogs = __DIR__ . "/../logs/";

        // create dir logs
        if (!is_dir($rootDirLogs))
            mkdir($rootDirLogs);

        file_put_contents($rootDirLogs . $config->getLogFileName(), $logTitle . stripslashes($logMessage) . "\r\n", FILE_APPEND | LOCK_EX);
    }
}
