<?php

/**
 * TVC Main Input Wrapper Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Main_Input_Wrapper' ) ) :

	abstract class TVC_Main_Input_Wrapper {

		abstract public function display();

		/**
		 * Returns the table and tbody opening code for the main input wrapper.
		 *
		 * @return string
		 */
		protected function main_input_wrapper_table_start() {
			return '<section class="tvc-edit-feed-form-element-wrapper tvc-main-input-wrapper" id="tvc-main-input-map"><table class="tvc-feed-main-input-table"><tbody id="tvc-main-feed-data">';
		}

		/**
		 * Returns the table and tbody closing code for the main input wrapper.
		 *
		 * @return string
		 */
		protected function main_input_wrapper_table_end() {
			return '</tbody></table></section>';
		}
	}

	// end of TVC_Main_Input_Wrapper class

endif;
