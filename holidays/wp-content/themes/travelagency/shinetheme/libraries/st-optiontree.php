<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 2/28/15
 * Time: 7:07 PM
 */
if (!class_exists('TravelAgency_Optiontree_Config')) {
    class TravelAgency_Optiontree_Config
    {

        static $_inst;

        public $theme;

        function __construct()
        {

            //Load helper

            if (!class_exists('OT_Loader')) return;

            // Register theme options
            $this->_add_themeoptions();
            add_action('init', array($this, '_add_themeoptions'));

            $this->theme = wp_get_theme();

            add_filter('ot_header_version_text', array($this, '_ot_header_version_text'));

            add_filter('ot_theme_options_parent_slug', array($this, '_change_parent_slug'), 1);
            add_filter('ot_theme_options_menu_title', array($this, '_change_menu_title'));
            add_filter('ot_theme_options_page_title', array($this, '_change_menu_title'));

            add_filter('ot_theme_options_icon_url', array($this, '_change_menu_icon'));

            add_filter('ot_theme_options_position', array($this, '_change_menu_pos'));

            add_action('admin_menu', array($this, '_change_admin_menu'));

            add_filter('ot_header_logo_link', array($this, '_change_header_logo_link'));

            add_action('admin_enqueue_scripts', array($this, '_add_theme_option_css'));
        }

        function _add_theme_option_css()
        {
            $screen = get_current_screen();
            $page_id = apply_filters('ot_theme_options_menu_slug', 'ot-theme-options');
            if (isset($screen->base) and $screen->base == 'toplevel_page_' . $page_id) {
                wp_enqueue_style('font-awesome', TravelAgency_Assets()->url('css/lib/font-awesome/font-awesome.css'));
            }
        }

        function _change_header_logo_link()
        {
            return "<a ><img src='" . TravelAgency_Assets()->url('admin/img/logo.png') . "'></a>";
        }

        function _change_admin_menu()
        {

        }

        function _change_menu_pos()
        {
            return 59;
        }

        function _change_menu_icon()
        {
            return 'dashicons-shinetheme';
        }

        function _change_parent_slug($slug)
        {
            return false;
        }

        function _change_menu_title($title)
        {
            return TravelAgency_Config::inst()->get('theme_option_menu_title');
        }

        function _add_themeoptions()
        {
            /* OptionTree is not loaded yet, or this is not an admin request */
            if (!function_exists('ot_settings_id') || !is_admin())
                return false;


            $saved_settings = get_option(ot_settings_id(), array());

            TravelAgency_Config::inst()->load('theme-options');


            $custom_settings = TravelAgency_Config::inst()->get('theme_options', array());

            if (is_array($custom_settings) and !empty($custom_settings)) {
                /* allow settings to be filtered before saving */
                $custom_settings = apply_filters(ot_settings_id() . '_args', $custom_settings);

                /* settings are not the same update the DB */
                if ($saved_settings !== $custom_settings) {
                    update_option(ot_settings_id(), $custom_settings);
                }
            }

            return true;
        }

        function _ot_header_version_text()
        {
            $title = esc_html($this->theme->display('Name'));
            $title .= ' - ' . sprintf(esc_html__('Version %s', 'travelagency'), $this->theme->display('Version'));

            return $title;
        }

        static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }

            return self::$_inst;
        }


    }

    TravelAgency_Optiontree_Config::inst();

}
