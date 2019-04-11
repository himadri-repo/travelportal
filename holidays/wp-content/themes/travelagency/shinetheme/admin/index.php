<?php
/**
 * Created by ShineTheme.
 * User: Sejinichi
 * Date: 9/26/2017
 * Since: 1.0
 */

if (!defined('ABSPATH')) return;

if (!class_exists('TravelAgency_Admin')) {
    class TravelAgency_Admin
    {
        static $_inst;
        public $admin_dir;

        function __construct()
        {

            $this->admin_dir = apply_filters('st_admin_dir', get_template_directory() . '/shinetheme/admin');
            $this->load_controllers();

        }

        function dir($file = false)
        {

            return $this->admin_dir . '/' . $file;

        }

        function load_controllers()
        {
            // Auto load all config file
            $files = glob($this->dir() . "controllers/*.php");

            if (!empty($files)) {
                foreach ($files as $filename) {
                    require_once $filename;
                }
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

    TravelAgency_Admin::inst();
}