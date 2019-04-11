<?php
/**
 * Travelagency Updater
 */

// Includes the files needed for the theme updater
if ( !class_exists( 'Travelagency_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

// Loads the updater classes
$updater = new Travelagency_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => 'https://wpbooking.org', // Site where EDD is hosted
		'item_name' => 'Travelagency',
		'theme_slug' => 'travelagency',
		'version' => '1.3.1',
		'author' => 'travelagency',
		'download_id' => '',
		'renew_url' => ''
	),

	// Strings
	$strings = array(
		'theme-license' => __( 'Travelagency License', 'travelagency' ),
		'enter-key' => __( 'Enter your theme license key.', 'travelagency' ),
		'license-key' => __( 'License Key', 'travelagency' ),
		'license-action' => __( 'License Action', 'travelagency' ),
		'deactivate-license' => __( 'Deactivate License', 'travelagency' ),
		'activate-license' => __( 'Activate License', 'travelagency' ),
		'status-unknown' => __( 'License status is unknown.', 'travelagency' ),
		'renew' => __( 'Renew?', 'travelagency' ),
		'unlimited' => __( 'unlimited', 'travelagency' ),
		'license-key-is-active' => __( 'License key is active.', 'travelagency' ),
		'expires%s' => __( 'Expires %s.', 'travelagency' ),
		'%1$s/%2$-sites' => __( 'You have %1$s / %2$s sites activated.', 'travelagency' ),
		'license-key-expired-%s' => __( 'License key expired %s.', 'travelagency' ),
		'license-key-expired' => __( 'License key has expired.', 'travelagency' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'travelagency' ),
		'license-is-inactive' => __( 'License is inactive.', 'travelagency' ),
		'license-key-is-disabled' => __( 'License key is disabled.', 'travelagency' ),
		'site-is-inactive' => __( 'Site is inactive.', 'travelagency' ),
		'license-status-unknown' => __( 'License status is unknown.', 'travelagency' ),
		'update-notice' => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'travelagency' ),
		'update-available' => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'travelagency' )
	)

);