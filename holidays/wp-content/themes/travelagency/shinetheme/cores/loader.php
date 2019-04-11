<?php
/**
 * Created by ShineTheme.
 * User: Sejinichi
 * Date: 9/26/2017
 * Since: 1.0
 */

if (!defined('ABSPATH')) return;

if (!class_exists('TravelAgency_Loader')) {

    class TravelAgency_Loader
    {

        static $_inst;

        function __construct()
        {
            $this->_autoload();
        }

        function _autoload()
        {

            $autoloads = TravelAgency_Config::inst()->get('autoload');

            // Load Helpers
            $helpers = isset($autoloads['helpers']) ? $autoloads['helpers'] : array();
            $this->load_helper($helpers);

            // Load libraries
            $libraries = isset($autoloads['libraries']) ? $autoloads['libraries'] : array();
            $this->load_library($libraries);

            // Load Models
            $libraries = isset($autoloads['models']) ? $autoloads['models'] : array();
            $this->load_model($libraries);

            // Load all controllers
            $controllers = isset($autoloads['controllers']) ? $autoloads['controllers'] : array();
            $this->load_controller($controllers);

            // Load Widgets
            $libraries = isset($autoloads['widgets']) ? $autoloads['widgets'] : array();
            $this->load_widget($libraries);

            //Auto load elements
            add_action('init', array($this, '_load_elements'));

        }

        /*
         * Load Libs
         */
        function load_library($file)
        {
            if (is_array($file) and !empty($file)) {
                foreach ($file as $f) {
                    $this->load_library($f);
                }
            }

            if (is_string($file)) {
                $real_file = TravelAgency_Framework::inst()->app_dir . '/libraries/' . $file . '.php';
                if (file_exists($real_file)) {
                    include_once $real_file;

                }
            }
        }

        /*
         * Load helpers
         */
        function load_helper($file)
        {
            if (is_array($file) and !empty($file)) {
                foreach ($file as $f) {
                    $this->load_helper($f);
                }
            }

            if (is_string($file)) {
                $real_file = TravelAgency_Framework::inst()->app_dir . '/helpers/' . $file . '.php';
                if (file_exists($real_file)) {
                    include_once $real_file;

                }
            }
        }

        /**
         * Load controllers
         */
        function load_controller($file)
        {

            if (is_array($file) and !empty($file)) {
                foreach ($file as $f) {
                    $this->load_controller($f);
                }
            }

            if (is_string($file)) {
                $real_file = TravelAgency_Framework::inst()->app_dir . '/controllers/' . $file . '.php';
                if (file_exists($real_file)) {
                    include_once $real_file;

                }
            }
        }

        /*
         * Load models
         */
        function load_model($file)
        {

            if (is_array($file) and !empty($file)) {
                foreach ($file as $f) {
                    $this->load_model($f);
                }
            }

            if (is_string($file)) {

                $real_file = TravelAgency_Framework::inst()->app_dir . '/models/' . $file . '.php';
                if (file_exists($real_file)) {
                    include_once $real_file;

                }
            }
        }

        /*
         * Load widgets
         */
        function load_widget($file)
        {
            if (is_array($file) and !empty($file)) {
                foreach ($file as $f) {
                    $this->load_widget($f);
                }
            }

            if (is_string($file)) {
                $real_file = TravelAgency_Framework::inst()->app_dir . '/widgets/' . $file . '.php';
                if (file_exists($real_file)) {
                    include_once $real_file;

                }
            }
        }


        /*
         * Load elements
         */
        function _load_elements()
        {
            $dir = TravelAgency_Framework::inst()->app_dir;

            $elements = glob($dir . "/controllers/elements/*.php");

            // Auto load all $elements file
            if (!empty($elements)) {
                foreach ($elements as $filename) {
                    $this->load_element(basename($filename, ".php"));
                }
            }

        }

        /*
         * Load element file
         */
        function load_element($file)
        {
            if (!empty($file) and is_array($file)) {
                foreach ($file as $key)
                    $this->load_element($key);
            } else {
                if (!$file) return;

                if (!function_exists('kc_add_map') or !function_exists('stp_reg_shortcode')) return;

                $real_file = TravelAgency_Framework::inst()->app_dir . '/controllers/elements/' . $file . '.php';
                if (file_exists($real_file))

                    include_once $real_file;

            }

        }

        static function inst()
        {

            if (!self::$_inst) {
                self::$_inst = new self();
            }

            return self::$_inst;
        }
    }

    TravelAgency_Loader::inst();

    if (!function_exists('TravelAgency_Loader')) {
        function TravelAgency_Loader()
        {
            return TravelAgency_Loader::inst();
        }
    }
}