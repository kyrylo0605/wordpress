<?php

/**
 * TVC Product Feed Main Input Wrapper Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Product_Feed_Main_Input_Wrapper' ) ) :

	class TVC_Product_Feed_Main_Input_Wrapper extends TVC_Main_Input_Wrapper {

		/**
		 * Display the product feed main input table.
		 *
		 * @return void
		 */
		public function display() {

			// Start with the table and body code
			echo $this->main_input_wrapper_table_start();

			// Feed file name input
			echo TVC_Main_Input_Selector_Element::file_name_input_element();

			// Source selector (currently not in use)
			echo TVC_Main_Input_Selector_Element::product_source_selector_element();

			// Channel selector
			echo TVC_Main_Input_Selector_Element::merchant_selector_element();

			do_action( 'tvc_add_feed_attribute_selector' );

			// Country selector
			echo TVC_Main_Input_Selector_Element::country_selector_element();

			// Category selector
			echo TVC_Main_Input_Selector_Element::category_list_element();

			// Aggregator selector
			echo TVC_Main_Input_Selector_Element::aggregator_selector_element();

            // Google product feed title input
            echo TVC_Main_Input_Selector_Element::google_product_feed_title_element();

            // Google product feed description input
			echo TVC_Main_Input_Selector_Element::google_product_feed_description_element();

			// Feed update schedule selector
			echo TVC_Main_Input_Selector_Element::feed_update_schedule_selector_element();


			// Close the body and table code
			echo $this->main_input_wrapper_table_end();
		}
	}

	// end of TVC_Product_Feed_Main_Input_Wrapper class

endif;
