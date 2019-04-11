<?php
/**
 * Created by ShineTheme.
 * Developer: nasanji
 * Date: 9/26/2017
 * Version: 1.0
 */
$config['version'] = '1.0';

/**
 * Envato Item ID for Auto Update function
 * @see Agency_Framework_Updater::request()
 * @since 1.0
 */

$config['autoload']['helpers'] = array(
    'optiontree_helper',
    'application_helper',
    'post_helper',
    'service_helper',
);

/**
 * List all libraries file autoload
 * @see ShinethemeFramework::_autoload();
 *
 * */
$config['autoload']['libraries'] = array(
    'st-assets',
    'st-input',
    'st-optiontree',
    'st-optiontree-css-output',
    'otf_regen_thumbs',
    'tgm/class-tgm-plugin-activation',
    'required_plugins',
    'custom_walker/custom_navwalker',
    'importer'
);

$config['autoload']['controllers'] = array(
    'base-controller',
    'page-controller',
    'location-controller',
    'service-controller',
    'tour-controller',
);

$config['autoload']['widgets'] = array();


/**
 * Array of defaults navigation menu
 *
 * @see ShinethemeFramework::_after_setup_theme()
 *
 *
 * */
$config['nav_menus'] = array(
    'primary' => esc_html__('Primary Navigation', 'travelagency'),
);


/**
 * Default sidebar
 * @see BaseController::_add_sidebars();
 *
 * */
$config['sidebars'] = array(
    array(
        'name' => esc_html__('Blog Sidebar', 'travelagency'),
        'id' => 'blog-sidebar',
        'description' => esc_html__('Widgets in this area will be shown on all blog page.', 'travelagency'),
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
        'before_widget' => '<div id="%1$s" class="sidebar-widget widget %2$s">',
        'after_widget' => '</div>',
    )


);


/**
 * Default get assets folder
 * @see Agency_Assets::url()
 *
 * */
$config['asset_url'] = get_template_directory_uri() . '/assets';


/**
 * Default Theme Options Menu Title
 *
 * @see STOptiontreeConfig::_change_menu_title()
 *
 *
 * */
$config['theme_option_menu_title'] = 'Theme Settings';