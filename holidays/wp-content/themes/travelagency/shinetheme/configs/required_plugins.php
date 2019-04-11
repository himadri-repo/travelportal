<?php
/**
 * Created by ShineTheme.
 * Developer: nasanji
 * Date: 9/26/2017
 * Version: 1.0
 */

/**
 * List all required plugins for themes
 *
 * @see STRequiredPlugins::register_required_plugins();
 *
 *
 * */
$config['required_plugins'] = array(
    array(
        'name' => esc_html__('Option Tree', 'travelagency'), // The plugin name.
        'slug' => 'option-tree', // The plugin slug (typically the folder name).
        'required' => true, // If false, the plugin is only 'recommended' instead of required.
    ),
    array(
        'name' => esc_html__('Contact Form 7', 'travelagency'),
        'slug' => 'contact-form-7',
        'required' => true,
    ),
    array(
        'name' => esc_html__('King Composer', 'travelagency'),
        'slug' => 'kingcomposer',
        'required' => true,
    ),
    array(
        'name' => esc_html__('ShineTheme Core', 'travelagency'),
        'slug' => 'shinetheme-core',
        'required' => true,
        'source' => 'http://shinetheme.com/demosd/stplugin_booking/shinetheme-core.zip'
    ),
    array(
        'name' => esc_html__('WPBooking','travelagency'),
        'slug' => 'wp-booking-management-system',
        'required' => true,
    ),
);