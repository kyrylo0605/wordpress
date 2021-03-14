<?php

/**
 * Google Product Feed Manager Admin Page Class.
 *
 * @package Google Product Feed Manager/User Interface/Classes
 * @version 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Admin_Page' ) ) :

	/**
	 *  TVC Admin Page Class
	 */
	class TVC_Admin_Page {

		public $tab_data;

		public function __construct() {

			$tvc_active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : null;

			$this->tab_data = apply_filters(
				'tvc_main_form_tabs',
				array(
					array(
						'page'       => 'tvc-product-feed-manager&tab=feed-list',
						'tab_status' => 'feed-list' === $tvc_active_tab ? ' nav-tab-active' : '',
						'title'      => esc_html__('Feed List', 'tvc-product-feed-manager'),
					),
					array(
						'page'       => 'tvc-product-feed-manager&tab=product-feed',
						'tab_status' => 'product-feed' === $tvc_active_tab ? ' nav-tab-active' : '',
						'title'      => esc_html__('Product Feed', 'tvc-product-feed-manager'),
					),
				),
				$tvc_active_tab
			);
		}

		/**
		 * Returns a string containing the standard header for an admin page.
		 *
		 * @param   string  $header_text
		 *
		 * @return  string
		 */
		protected function admin_page_header( $header_text = 'Google Shopping Feeds by Tatvic' ) {
			$spinner_gif = ENHANCAD_PLUGIN_URL . '/images/ajax-loader.gif';
			$feed_queue  = implode( ',', get_site_option( 'tvc_feed_queue', array() ) ); // Get the active feed queue.

			return
				'<div class="wrap">
			<div class="feed-spinner" id="feed-spinner" style="display:none;">
				<img id="img-spinner" src="' . $spinner_gif . '" alt="Loading" />
			</div>
			<div class="data" id="tvc-product-feed-manager-data" style="display:none;"><div id="wp-plugin-url">' . TVC_UPLOADS_URL . '</div><div id="tvc-feed-list-feeds-in-queue">' . $feed_queue . '</div></div>
			<div class="tvc-main-wrapper tvc-header-wrapper" id="header-wrapper">
			<div class="header-text"><h1>' . $header_text . '</h1></div>
			
			</div>
			';
		}

		protected function message_field( $alert = '' ) {
			$display_alert = empty( $alert ) ? 'none' : 'block';

			return
				'<div class="message-field notice notice-error" id="error-message" style="display:none;"></div>
			 <div class="message-field notice notice-success" id="success-message" style="display:none;"></div>
			 <div class="message-field notice notice-warning" id="disposable-warning-message" style="display:' . $display_alert . ';"><p>' . $alert . '</p>
			<button type="button" id="disposable-notice-button" class="notice-dismiss"></button>
			</div>'

			;
		}

		/**
		 * returns the html code for the tabs
		 *
		 * @return string
		 */
		protected function tabs() {
			return TVC_Form_Element::main_form_tabs();
		}

        /**
         * returns the html code for the sub tabs
         *
         * @return string
         */
        protected function subtabs() {
            return TVC_Form_Element::sub_form_tabs();
        }

		/**
		 * Returns the html code for the tab header.
		 *
		 * @param   string  $header_title       String for the tab header text.
		 * @param   string  $header_sub_title   String for the sub title below the tab header text.
		 *
		 * @return  string  Html code containing the tab header.
		 *@since 2.11.0.
		 */
		protected function tab_header( $header_title, $header_sub_title ) {
            $tvc_active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : null;

            if('feed-list' === $tvc_active_tab || $tvc_active_tab == null) {
                $header_html = '<div class="bg-wrap d-inline-block" id="tab-header"><strong style="font-size: 20px;">' . $header_title . '</strong>
			<div><p>' . $header_sub_title . '</p></div></div>';
                $header_html .= '<div class="button-wrapper float-right" id="page-bottom-buttons">';
                $header_html .= '<input class="button-primary feed-list-lower-button" type="button" ' .
                    'onclick="parent.location=\'admin.php?page=tvc-product-feed-manager&tab=product-feed\'" name="new" value="' .
                    __( 'Add New Feed', 'tvc-product-feed-manager' ) . '" id="add-new-feed-button" />
				<input class="button-primary feed-list-lower-button" type="button" data-toggle="modal" data-target="#conversionModal" name="create-campaign" value="' .
                    __( 'Create Smart shopping Campaign', 'tvc-product-feed-manager' ) . '" id="create-campaign-button" />';
                $header_html .= '</div>';
            } else {
                $header_html = '<div class="bg-wrap" id="tab-header"><strong style="font-size: 20px;">' . $header_title . '</strong>
			<div><p>' . $header_sub_title . '</p></div>';
            }

            return $header_html;
		}
	}
	// end of TVC_Admin_Page class
endif;
