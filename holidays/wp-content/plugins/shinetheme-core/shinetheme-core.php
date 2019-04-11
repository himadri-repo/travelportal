<?php

/**
 * Plugin Name: Shinetheme-Core
 * Plugin URI: #
 * Description: Required with themes of Shinetheme. Contains all helper functions.
 * Version: 1.0.1
 * Author: Shinetheme
 * Author URI: http://shinetheme.com
 * Requires at least: 3.8
 * Tested up to: 4.0
 *
 * Text Domain: shinetheme-core
 *
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!defined('STP_TEXTDOMAIN')){
    define('STP_TEXTDOMAIN','shinetheme-core');
}

if(!class_exists('ShinethemeCore'))
{
    class ShinethemeCore
    {
        static protected $_dir='';
        static protected $_uri='';

        static function init()
        {

            add_action( 'plugins_loaded', array(__CLASS__,'_load_text_domain') );

            self::$_dir=plugin_dir_path(__FILE__);
            self::$_uri=plugin_dir_url(__FILE__);

            global $this_file;
            $this_file=__FILE__;

            $update_check="http://dungdt.shinethemedev.com/plugins/shinetheme-core/update.chk";

            self::load_core_class();

            self::load_required_class();


            require_once self::dir('libs/menu.exporter.php');
            require_once self::dir('libs/importer/importer.php');


        }
        static function _load_text_domain()
        {
            load_plugin_textdomain( STP_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }

        static function load_core_class()
        {
            $dirs = array_filter(glob(self::dir().'core/*'), 'is_file');

            if(!empty($dirs))
            {
                foreach($dirs as $key=>$value)
                {
                    require_once $value;

                }
            }
        }


        static function load_required_class()
        {
            // Fix array_filter argument should be an array
            $class=glob(self::dir().'class/*');
            if(!is_array($class)) return false;

            $dirs = array_filter($class, 'is_file');

            if(!empty($dirs))
            {
                foreach($dirs as $key=>$value)
                {
                    require_once $value;

                }
            }
        }



        // Helper functions
        static function dir($file=false)
        {
            return self::$_dir.$file;
        }


        static function uri($file=false)
        {
            return self::$_uri.$file;
        }
    }
    ShinethemeCore::init();
}
