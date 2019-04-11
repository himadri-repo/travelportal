<?php
/**
 * Created by ShineTheme.
 * Developer: sejinichi
 * Date: 9/26/2017
 * Version: 1.0
 */

if (!defined('ABSPATH')) return;

if (!class_exists('TravelAgency_Session')) {
    class TravelAgency_Session
    {

        static $_inst;

        function __construct()
        {
            //Hook action init: init session
            add_action('init', array($this, '_action_init'));
        }

        function _action_init()
        {
            // Use session for flash message
            if (!session_id()) {
                session_start();
            }
        }

        function get($key = false, $default = NULL)
        {
            if ($key and isset($_SESSION[$key])) return $_SESSION[$key];

            return $default;
        }

        function set($key = false, $value)
        {
            $_SESSION[$key] = $value;
        }

        function destroy($key)
        {
            if (isset($_SESSION[$key])) unset($_SESSION[$key]);
        }

        static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }

            return self::$_inst;
        }
    }

    TravelAgency_Session::inst();

    if (!function_exists('TravelAgency_Session')) {
        function TravelAgency_Session()
        {
            return TravelAgency_Session::inst();
        }
    }
}