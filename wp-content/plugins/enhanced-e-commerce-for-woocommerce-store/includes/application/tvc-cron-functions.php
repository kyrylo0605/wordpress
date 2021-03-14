<?php
/**
 * @package TVC Product Feed Manager/Application/Functions
 * @version 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Activates the feed update schedules using Cron Jobs
 */
function tvc_update_feeds() {
	// include the required WordPress files
	require_once( ABSPATH . 'wp-load.php' );
	require_once( ABSPATH . 'wp-admin/includes/admin.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' ); // required for using the file system
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // required to prevent a fatal error about not finding the is_plugin_active function
	// include all product feed manager files
    require_once( ENHANCAD_PLUGIN_DIR . 'includes/user-interface/tvc-messaging-functions.php' );
    //require_once( ENHANCAD_PLUGIN_DIR . 'includes/tvc-wpincludes.php' );
    //require_once( ENHANCAD_PLUGIN_DIR . 'includes/data/tvc-admin-functions.php' );
	//require_once( ENHANCAD_PLUGIN_DIR . 'includes/user-interface/tvc-url-functions.php' );
	//require_once( ENHANCAD_PLUGIN_DIR . 'includes/application/tvc-feed-processing-support.php' );
	//require_once( ENHANCAD_PLUGIN_DIR . 'includes/application/tvc-feed-processor-functions.php' );

	// WooCommerce needs to be installed and active
	if ( ! tvc_wc_installed_and_active() ) {
		tvc_write_log_file( 'Tried to start the auto update process but failed because WooCommerce is not installed.' );
		exit;
	}

	// Feed Manager requires at least WooCommerce version 3.0.0
	if ( ! tvc_wc_min_version_required() ) {
		tvc_write_log_file( sprintf( 'Tried to start the auto update process but failed because WooCommerce is older than version %s', TVC_MIN_REQUIRED_WC_VERSION ) );
		exit;
	}

	WC_Post_types::register_taxonomies(); // make sure the woocommerce taxonomies are loaded
	WC_Post_types::register_post_types(); // make sure the woocommerce post types are loaded

	// include all required classes
	//include_classes();
	//include_channels();

	do_action( 'tvc_automatic_feed_processing_triggered' );

	// update the database if required
	tvc_check_db_version();

	// start updating the active feeds
	$tvc_schedules = new TVC_Schedules();
	$tvc_schedules->update_active_feeds();
}
