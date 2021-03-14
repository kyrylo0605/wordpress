<?php

/**
 * TVC Product Feed Manager Add Options Page Class.
 *
 * @package TVC Product Feed Manager/User Interface/Classes
 * @version 1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Add_Options_Page' ) ) :

	class TVC_Add_Options_Page extends TVC_Admin_Page {
		private $_options_form;

		public function __construct() {
			parent::__construct();

			add_option( 'wp_enqueue_scripts', TVC_i18n_Scripts::tvc_settings_i18n() );
			add_option( 'wp_enqueue_scripts', TVC_i18n_Scripts::tvc_backup_list_i18n() );

			$this->_options_form = new TVC_Options_Page();
		}

		public function show() {
			echo $this->options_page_header();

			echo $this->message_field();

			echo $this->options_page_body();

			echo $this->options_page_footer();
		}

		private function options_page_header() {
			$spinner_gif = ENHANCAD_PLUGIN_URL . '/images/ajax-loader.gif';

			return
				'
		<div class="wrap">
		<div class="feed-spinner" id="feed-spinner" style="display:none;">
			<img id="img-spinner" src="' . $spinner_gif . '" alt="Loading" />
		</div>
		<div class="tvc-main-wrapper tvc-header-wrapper" id="tvc-header-wrapper">
		<div class="header-text"><h1>' . esc_html__( 'Google Feed Manager Settings', 'tvc-product-feed-manager' ) . '</h1></div>
		<div class="logo"></div>
		</div>
		';
		}

		private function options_page_body() {
			$this->_options_form->display();
		}

		private function options_page_footer() {
		}
	}

	// end of TVC_Add_Options_Page class

endif;
