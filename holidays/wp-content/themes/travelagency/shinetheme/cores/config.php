<?php
/**
 * Created by ShineTheme.
 * User: Sejinichi
 * Date: 9/26/2017
 * Since: 1.0
 */

if (!defined('ABSPATH')) return;

if (!class_exists('TravelAgency_Config')) {

    class TravelAgency_Config
    {

        static $_inst;
        public $_all;

        function __construct()
        {

            $this->load('autoload');

        }

        function load($file)
        {
            $file_path = get_template_directory() . '/shinetheme/configs/' . $file . '.php';

            $config = array();

            if (file_exists($file_path)) {
                require_once($file_path);
            }

            if (!is_array($this->_all)) $this->_all = array();

            $this->_all = array_merge($this->_all, $config);
        }

        function get($key = false, $default = NULL)
        {

            if (isset($this->_all[$key])) {
                $return = $this->_all[$key];
            } else {
                $return = $default;
            }

            return apply_filters('st_config_get_' . $key, $return, $key, $default);
        }

        static function inst()
        {

            if (!self::$_inst) {
                self::$_inst = new self();
            }

            return self::$_inst;
        }
    }

    TravelAgency_Config::inst();

    if (!function_exists('TravelAgency_Config')) {
        function TravelAgency_Config()
        {
            return TravelAgency_Config::inst();
        }
    }
}
