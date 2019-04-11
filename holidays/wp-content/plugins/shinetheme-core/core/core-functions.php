<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 3/19/15
 * Time: 3:19 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


/**
 * Register post type
 *
 *
 * */

if(!function_exists('stp_reg_post_type'))
{
    function stp_reg_post_type($post_type, $args)
    {
        register_post_type($post_type, $args);
    }
}
/**
 * Register post type
 *
 *
 * */

if(!function_exists('stp_reg_taxonomy'))
{
    function stp_reg_taxonomy($taxonomy, $object_type, $args )
    {
        register_taxonomy($taxonomy, $object_type, $args );
    }
}
/**
 * Add shortcode
 *
 *
 * */

if(!function_exists('stp_reg_shortcode'))
{
    function stp_reg_shortcode($tag , $func )
    {
        add_shortcode($tag , $func );
    }
}

if(!function_exists('stp_add_sub_menu')){
    function stp_add_sub_menu($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = ''){
        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
    }
}

if(!function_exists('stp_request_uri'))
{
    function stp_request_uri()
    {
        return $_SERVER['REQUEST_URI'];
    }
}