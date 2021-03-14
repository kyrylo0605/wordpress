<?php

/**
 * @package TVC Product Feed Manager/User Interface/Functions
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the feed manager menu in the Admin page
 *
 * @param bool $channel_updated default false
 */
function tvc_add_feed_manager_menu( $channel_updated = false ) {
	// defines the feed manager menu
    if(!empty(unserialize(get_option('pfm_purchase_code')))) {
        add_menu_page(
        	__(esc_html__('Google Feed Manager', 'tvc-product-feed-manager')),	
            __(esc_html__('Google Feed Manager', 'tvc-product-feed-manager')),
            'manage_woocommerce',
            'tvc-product-feed-manager',
            'tvc_feed_manager_main_page',
            esc_url(ENHANCAD_PLUGIN_URL . '/images/app-rss-plus-xml-icon.png')
        );

        // add the settings
        add_submenu_page(
            'tvc-product-feed-manager',
            __(esc_html__('Settings', 'tvc-product-feed-manager')),	
            __(esc_html__('Settings', 'tvc-product-feed-manager')),
            'manage_woocommerce',
            'tvc-options-page',
            'tvc_options_page'
        );
    }else{
        add_menu_page(
           __(esc_html__('Google Feed Manager', 'tvc-product-feed-manager')),	
            __(esc_html__('Google Feed Manager', 'tvc-product-feed-manager')),
            'manage_woocommerce',
            'aga-envato-api',
            'call_api',
            esc_url(ENHANCAD_PLUGIN_URL . '/images/app-rss-plus-xml-icon.png'),
            10
        );
    }
}

add_action( 'admin_menu', 'tvc_add_feed_manager_menu' );

/**
 * Checks if the backups are valid for the current database version and warns the user if not
 *
 * @since 1.9.6
 */
function tvc_check_backups() {
	if ( ! tvc_check_backup_status() ) {
		$msg = esc_html__('Due to the latest update your Feed Manager backups are no longer valid! Please open the Feed Manager Settings page, remove all your backups in and make a new one.', 'tvc-product-feed-manager' );
		?>
		<div class="notice notice-warning is-dismissible">
			<p><?php echo is_string($msg); ?></p>
		</div>
		<?php
	}
}

add_action( 'admin_notices', 'tvc_check_backups' );

/**
 * Sets the global background process
 *
 * @since 1.10.0
 *
 * @global TVC_Feed_Processor $background_process
 */
function initiate_background_process() {
	global $background_process;

	if ( isset( $_GET['tab'] ) ) {
		$active_tab = $_GET['tab'];
		set_transient( 'tvc_set_global_background_process', $active_tab, TVC_TRANSIENT_LIVE );
	} else {
		$active_tab = ! get_transient( 'tvc_set_global_background_process' ) ? 'feed-list' : get_transient( 'tvc_set_global_background_process' );
	}

	if ( ( 'product-feed' === $active_tab || 'feed-list' === $active_tab ) ) {
		if ( ! class_exists( 'TVC_Feed_Processor' ) ) {
			require_once( __DIR__ . '/../application/class-tvc-feed-processor.php' );
		}

		$background_process = new TVC_Feed_Processor();
	}

	if ( 'product-review-feed' === $active_tab ) {
		if ( ! class_exists( 'WPPRFM_Review_Feed_Processor' ) ) {
			require_once( __DIR__ . '/../../../wp-product-review-feed-manager/includes/classes/class-wpprfm-review-feed-processor.php' );
		}

		$background_process = new WPPRFM_Review_Feed_Processor();
	}
}

// register the background process
add_action( 'wp_loaded', 'initiate_background_process' );
