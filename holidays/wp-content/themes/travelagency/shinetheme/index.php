<?php
/**
 * Created by ShineTheme.
 * Developer: nasanji
 * Date: 9/26/2017
 * Version: 1.0
 */

if (!defined('ABSPATH')) return;

if (!class_exists('TravelAgency_Framework')) {
    class TravelAgency_Framework
    {

        static $_inst;
        public $app_dir;

        public $framework_version = "1.0";

        /**
         * Define system variable
         *
         * Load required core file
         *
         * Run theme app
         *
         * */
        function __construct()
        {
            // Init some variable
            $this->app_dir = apply_filters('st_app_dir', get_template_directory() . '/shinetheme');

            // Load Core Files
            $this->load_cores();

        }

        /**
         *
         * Autoload libraries, helpers, models
         *
         * */


        function load_cores()
        {

            $core_files = array(
                '/cores/config',
                '/cores/loader',
                '/cores/session',
                '/cores/template'
            );

            $this->load($core_files, true);

        }

        function load($file, $include_once = false)
        {

            if (!empty($file) && is_array($file)) {
                foreach ($file as $f) {
                    $this->load($f);
                }
            } else {
                $f = $this->app_dir . $file . '.php';
                if (file_exists($f)) {
                    if ($include_once) include_once $f;
                    include $f;
                }
            }

        }


        static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }

            return self::$_inst;
        }
    }

    TravelAgency_Framework::inst();
}
