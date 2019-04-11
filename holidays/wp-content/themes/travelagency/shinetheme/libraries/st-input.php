<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 2/27/15
 * Time: 12:26 AM
 */

if (!class_exists('TravelAgency_Input')) {
    class TravelAgency_Input
    {

        static $_inst;

        function __construct()
        {

        }

        function ip_address()
        {

            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//check ip from share internet
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//to check ip is pass from proxy
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return apply_filters('stinput_ip_address', $ip);

        }

        function post($index = NULL, $default = false)
        {
            // Check if a field has been provided
            if ($index === NULL AND !empty($_POST)) {
                return $_POST;
            }

            if (isset($_POST[$index])) return $_POST[$index];

            return $default;

        }

        function get($index = NULL, $default = false)
        {
            // Check if a field has been provided
            if ($index === NULL AND !empty($_GET)) {
                return $_GET;
            }

            if (isset($_GET[$index])) return $_GET[$index];

            return $default;
        }

        function request($index = NULL, $default = false)
        {
            // Check if a field has been provided
            if ($index === NULL AND !empty($_REQUEST)) {
                return $_REQUEST;
            }

            if (isset($_REQUEST[$index])) return $_REQUEST[$index];

            return $default;
        }

        static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }

            return self::$_inst;
        }
    }

    TravelAgency_Input::inst();

    if (!function_exists('TravelAgency_Input')) {
        function TravelAgency_Input()
        {
            return TravelAgency_Input::inst();
        }
    }

}
