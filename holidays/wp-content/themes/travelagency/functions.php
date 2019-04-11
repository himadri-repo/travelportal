<?php
/**
 * agency functions and definitions
 *
 * @package ST Framework
 *
 * @version 1.0
 *
 * @date 29.10.2014
 * @update_date 26.09.2017
 */

load_theme_textdomain('travelagency', get_template_directory() . '/languages');
require get_template_directory() . '/updater/theme-updater.php';

/**
 * Set the content width based on the theme's design and stylesheet.
 */
load_theme_textdomain('travelagency', get_template_directory() . '/languages');

if (!isset($content_width)) {
    $content_width = 640; /* pixels */
}
 add_action('after_setup_theme', 'theme_init', 10);
 function theme_init(){
	require_once(trailingslashit(get_template_directory()) . '/shinetheme/index.php');

	if (is_admin()) {
	    // Run admin
	    require_once(trailingslashit(get_template_directory()) . '/shinetheme/admin/index.php');
	}
}

function agency_theme_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'admin_init', 'agency_theme_add_editor_styles' );
register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'travelagency' ),
    'social'  => __( 'Social Links Menu', 'travelagency' ),
) );