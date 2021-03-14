<?php

/**
 * TVC Product Feed Category Selector Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Main_Input_Selector_Element' ) ) :

	class TVC_Main_Input_Selector_Element {

		/**
		 * Returns the file name input field code.
		 *
		 * @return string
		 */
		public static function file_name_input_element() {
			return '<tr class="tvc-main-feed-input-row">
					<th id="tvc-main-feed-input-label"><label
						for="file-name">' . esc_html__( 'File Name', 'tvc-product-feed-manager' ) . '</label> :
					</th>
					<td><input type="text" name="file-name" id="file-name" /></td></tr>';
		}

		/**
		 * Returns the code for the products source selector.
		 *
		 * @return string
		 */
		public static function product_source_selector_element() {
			return '<tr class="tvc-main-feed-input-row" style="display:none;">
					<th id="tvc-main-feed-input-label"><label
						for="source-list">' . esc_html__( 'Products source', 'tvc-product-feed-manager' ) . '</label> :
					</th>
					<td>' . TVC_Feed_Form_Control::source_selector() . '</td></tr>';
		}

		/**
		 * Returns the code for the merchant selector.
		 *
		 * @return string
		 */
		public static function merchant_selector_element() {
			return '<tr class="tvc-main-feed-input-row">
					<th id="tvc-main-feed-input-label"><label
						for="merchant-list">' . esc_html__( 'Channel', 'tvc-product-feed-manager' ) . '</label> :
					</th>
					<td>' . TVC_Feed_Form_Control::channel_selector() . '</td></tr>';
		}

		/**
		 * Returns the code for the country selector.
		 *
		 * @return string
		 */
		public static function country_selector_element() {
			return '<tr class="tvc-main-feed-input-row" id="country-list-row" style="display:none;">
					<th id="tvc-main-feed-input-label"><label
						for="country-list">' . esc_html__( 'Target Country', 'tvc-product-feed-manager' ) . '</label> :
					</th>
					<td>' . TVC_Feed_Form_Control::country_selector() . '</td></tr>';
		}

		/**
		 * Returns the code for the default category list.
		 *
		 * @return string
		 */
		public static function category_list_element() {
			return '<tr class="tvc-main-feed-input-row" id="category-list-row" style="display:none;">
					<th id="tvc-main-feed-input-label"><label
						for="categories-list">' . esc_html__( 'Default Category', 'tvc-product-feed-manager' ) . '</label> :
					</th>
					<td>' . TVC_Category_Selector_Element::category_mapping_selector( 'lvl', '-1', true ) . '</td></tr>';
		}

		/**
		 * Returns the code for the aggregator selector.
		 *
		 * @return string
		 */
		public static function aggregator_selector_element() {
			return '<tr class="tvc-main-feed-input-row" id="aggregator-selector-row" style="display:none">
					<th id="tvc-main-feed-input-label"><label
						for="aggregator-selector">' . esc_html__( 'Aggregator Shop', 'tvc-product-feed-manager' ) . '</label> :
					</th>
					<td>' . TVC_Feed_Form_Control::aggregation_selector() . '</td></tr>';
		}

		/**
		 * Returns the code for the product feed title field.
		 *
		 * @return string
		 */
		public static function google_product_feed_title_element() {
			return '<tr class="tvc-main-feed-input-row" id="google-feed-title-row" style="display:none">
					<th id="tvc-main-feed-input-label"><label
						for="google-feed-title-selector">' . esc_html__( 'Feed Title', 'tvc-product-feed-manager' ) . '</label> :
					</th>
					<td>' . TVC_Feed_Form_Control::google_feed_title_selector() . '</td></tr>';
		}

		/**
		 * Returns the code for the product feed description field.
		 *
		 * @return string
		 */
		public static function google_product_feed_description_element() {
			return '<tr class="tvc-main-feed-input-row" id="google-feed-description-row" style="display:none">
					<th id="tvc-main-feed-input-label"><label
						for="google-feed-description-selector">' . esc_html__( 'Feed Description', 'tvc-product-feed-manager' ) . '</label> :
					</th>
					<td>' . TVC_Feed_Form_Control::google_feed_description_selector() . '</td></tr>';
		}

		/**
		 * Returns the code for the feed update schedule selector.
		 *
		 * @param  string display style
		 *
		 * @return string
		 */
		public static function feed_update_schedule_selector_element( $display = 'none' ) {
			return '<tr class="tvc-main-feed-input-row" id="update-schedule-row" style="display:' . $display . '">
					<th id="tvc-main-feed-input-label"><label
						for="update-schedule">' . esc_html__( 'Update Schedule', 'tvc-product-feed-manager' ) . '</label> :
					</th>
					<td>' . TVC_Feed_Form_Control::schedule_selector() . '</td></tr>';
		}
	}

	// end of TVC_Main_Input_Selector_Element class

endif;
