<?php

/**
 * TVC Main Admin Page Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 1.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Main_Admin_Page' ) ) :

	/**
	 * Main Admin Page Class
	 */
	class TVC_Main_Admin_Page extends TVC_Admin_Page {

		private $_list_table;

		function __construct() {

			parent::__construct();

			tvc_check_db_version();

			$this->_list_table = new TVC_List_Table();

			$this->prepare_feed_list();
		}

		/**
		 * Collects the html code for the main page and displays it.
		 */
		public function show() {

			echo $this->admin_page_header();

			echo $this->message_field();

			if ( tvc_wc_installed_and_active() ) {
				if ( ! tvc_wc_min_version_required() ) {
					echo tvc_update_your_woocommerce_version_message();
					exit;
				}

				echo $this->tabs();

				echo $this->tab_header( __( 'Feed List Table', 'tvc-product-feed-manager' ), __( 'Use the table below to manage your existing feeds.', 'tvc-product-feed-manager' ) );

				echo $this->main_admin_page();

				echo $this->main_admin_buttons();
			} else {
				echo tvc_you_have_no_woocommerce_installed_message();
			}
		}

		/**
		 * Prepares the list table
		 */
		private function prepare_feed_list() {
			$show_type_column = apply_filters( 'tvc_special_feeds_add_on_active', false );

			$this->_list_table->set_table_id( 'tvc-feed-list' );

			$list_columns = array(
				'col_feed_name'        => __( 'Feed Name', 'tvc-product-feed-manager' ),
				'col_feed_url'         => __( 'Feed XML', 'tvc-product-feed-manager' ),
				'col_feed_api'         => __( 'Push Feed to Merchant Center', 'tvc-product-feed-manager' ),
				'col_feed_last_change' => __( 'Updated', 'tvc-product-feed-manager' ),
				'col_feed_products'    => __( 'Products', 'tvc-product-feed-manager' ),
			);

			if ( $show_type_column ) {
				$list_columns['col_feed_type'] = __( 'Type', 'tvc-product-feed-manager' );
			}

			$list_columns['col_feed_status']  = __( 'Status', 'tvc-product-feed-manager' );
			$list_columns['col_feed_actions'] = __( 'Actions', 'tvc-product-feed-manager' );

			// set the column names
			$this->_list_table->set_column_titles( $list_columns );
		}

		/**
		 * Returns a html string containing the main admin page body code
		 *
		 * @return string html
		 */
		private function main_admin_page() {
			return $this->main_admin_body_top();
		}

		/**
		 * Activates the html for the main body top.
		 */
		private function main_admin_body_top() {
			$this->_list_table->display();
		}

		private function main_admin_buttons() {
			$wrapper_opening_html = '<div class="button-wrapper" id="page-bottom-buttons">';
			$button_html          = '<input class="button-primary feed-list-lower-button" type="button" ' .
				'onclick="parent.location=\'admin.php?page=tvc-product-feed-manager&tab=product-feed\'" name="new" value="' .
				__( 'Add New Feed', 'tvc-product-feed-manager' ) . '" id="add-new-feed-button" />';
			$wrapper_closing_html = '</div>';

			return $wrapper_opening_html . apply_filters( 'tvc_main_admin_bottom_buttons', $button_html ) . $wrapper_closing_html;
		}

	}

	// end of TVC_Main_Admin_Page class
endif;
