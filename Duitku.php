<?php

/** 
 * Check PHP version.
 */
if (version_compare(PHP_VERSION, '5.6', '<')) {
    throw new Exception('PHP version >= 5.6 required');
}

// Check PHP Curl & 
if (!function_exists('curl_init') || !function_exists('curl_exec')) {
    throw new Exception('Duitku::cURL library is required');
}

// Json decode capabilities.
if (!function_exists('json_decode')) {
    throw new Exception('Duitku::JSON PHP extension is required');
}

// Configuration Duitku Config
require_once 'Duitku/Config.php';
// Duitku Sanitizer Parameter
require_once 'Duitku/Sanitizer.php';
// Duitku Request Curl
require_once 'Duitku/Request.php';
// General Duitku-Pop Request
require_once 'Duitku/Pop.php';
// General Duitku-API Request
require_once 'Duitku/Api.php';
