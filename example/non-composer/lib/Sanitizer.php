<?php

namespace Duitku;

/**
 * Request params filters.
 *
 * It truncate fields that have length limit, remove not allowed characters from other fields
 *
 * This feature is optional, you can control it with $isSanitizedMode (default: true)
 */
class Sanitizer
{

    private static $_rules = array(
        "paymentAmount"     => "int|maxLength:50",
        "merchantOrderId"   => "string|maxLength:50",
        "productDetails"    => "string|maxLength:255",
        "additionalParam"   => "string|maxLength:255",
        "merchantUserInfo"  => "string|maxLength:255",
        "customerVaName"    => "string|maxLength:20",
        "phoneNumber"       => "string|maxLength:20|phone",
        "expiryPeriod"      => "int",

        // itemDetails
        "name"              => "string|maxLength:50",
        "quantity"          => "int",
        "price"             => "int",

        //billingAddress and shippingAddress
        'firstName'         => "string|maxLength:50",
        'lastName'          => "string|maxLength:50",
        'address'           => "string|maxLength:255",
        'city'              => "string|maxLength:50",
        'postalCode'        => "string|maxLength:50",
        'phone'             => "string|maxLength:20|phone",
        'countryCode'       => "string|maxLength:50"
    );

    /**
     * 
     * Example:
     * 
     * ```php
     * 
     * $params = array(
     * 	"example1"  => "value1",
     * 	"example2"  => "value2",
     * 	"example3"  => "value3",
     * );
     * 
     * \Duitku\Sanitizer::request($params);
     *
     * ```
     * 
     * @param array &$parameterArray
     * @return void
     */
    public static function Request(&$parameterArray)
    {
        if (!is_array($parameterArray)) {
            return;
        }

        foreach ($parameterArray as $rulesLabel => &$parameterValue) {
            if (is_array($parameterValue)) {
                // parse itemDetails and customerDetail
                foreach ($parameterValue as $rulesCustomerDetail => &$parameterCustomerDetail) {
                    // parse billingAddress and shippingAddress
                    self::loopParamAddress($parameterCustomerDetail);

                    if (isset(self::$_rules[$rulesCustomerDetail])) {
                        self::sanitizeValue(self::$_rules[$rulesCustomerDetail], $parameterCustomerDetail);
                    }
                }
            }

            if (isset(self::$_rules[$rulesLabel])) {
                self::sanitizeValue(self::$_rules[$rulesLabel], $parameterValue);
            }
        }
    }

    /**
     * Filter field "int|string|maxLength|phone"
     *
     * @param string $fieldName
     * @param string &$parameterValue
     * @return void
     */
    private static function sanitizeValue($fieldName, &$parameterValue)
    {
        $attributeTags = explode('|', $fieldName);
        rsort($attributeTags);
        foreach ($attributeTags as $attributeTag) {
            $attributeTagValue = explode(':', $attributeTag);
            switch ($attributeTagValue[0]) {
                case "string":
                    $parameterValue = (string)$parameterValue;
                    break;
                case "int":
                    $parameterValue = (int)$parameterValue;
                    break;
                case "maxLength":
                    $parameterValue = substr($parameterValue, 0, $attributeTagValue[1]);
                    break;
                case "phone":
                    $parameterValue = preg_replace("/[^\\d\\-\\(\\)]/", '', $parameterValue);
                    break;
            }
        }
    }

    /**
     * Parse billingAddress and shippingAddress
     *
     * @param array &$parameterCustomerDetail
     * @return void
     */
    private static function loopParamAddress(&$parameterCustomerDetail)
    {
        if (!is_array($parameterCustomerDetail)) {
            return;
        }

        foreach ($parameterCustomerDetail as $rulesAddress => &$parameterAddress) {
            self::sanitizeValue(self::$_rules[$rulesAddress], $parameterAddress);
        }
    }
}
