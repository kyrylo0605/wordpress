<?php

/**
 * @package WP Product Feed Manager/User Interface/Functions
 * @version 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function tvc_feed_manager_main_page() {

	global $tvc_tab_data;

	$active_tab          = isset( $_GET['tab'] ) ? $_GET['tab'] : 'feed-list';
	$page_start_function = 'tvc_main_admin_page'; // default

	$list_tab = new TVC_Tab(
		'feed-list',
		'feed-list' === $active_tab ? true : false,
		__( 'All Feeds', 'tvc-product-feed-manager' ),
		'tvc_main_admin_page'
	);

	$product_feed_tab = new TVC_Tab(
		'product-feed',
		'product-feed' === $active_tab ? true : false,
		__( 'Create Product Feed', 'tvc-product-feed-manager' ),
		'tvc_add_product_feed_page'
	);

	$tvc_tab_data = apply_filters( 'tvc_main_form_tabs', array( $list_tab, $product_feed_tab ), $active_tab );

	foreach ( $tvc_tab_data as $tab ) {
		if ( $tab->get_page_identifier() === $active_tab ) {
			$page_start_function = $tab->get_class_identifier();
			break;
		}
	}

	$page_start_function();
}

/**
 * starts the main admin page
 */
function tvc_main_admin_page() {
	$start = new TVC_Main_Admin_Page();

	// now let's get things going
	$start->show();
}

function tvc_add_product_feed_page() {
	$add_new_feed_page = new TVC_Product_Feed_Page();
	$add_new_feed_page->show();
}

function call_api(){
    if(!empty(unserialize(get_option('pfm_purchase_code')))){
        tvc_main_admin_page();
    }
    else{
        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/pfm-envato-api.php');
    }
}

/**
 * options page
 */
function tvc_options_page() {
	$add_options_page = new TVC_Add_Options_Page();
	$add_options_page->show();
}

/**
 * Returns an array of possible feed types that can be altered using the tvc_feed_types filter.
 *
 * @return array with possible feed types
 */
function tvc_list_feed_type_text() {

	return apply_filters(
		'tvc_feed_types',
		array(
			'1' => 'Product Feed',
		)
	);
}


