<?php

/**
 * TVC Attribute Selector Element Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Attribute_Selector_Element' ) ) :

	class TVC_Attribute_Selector_Element {

		/**
		 * Returns the code for the required fields.
		 *
		 * @return string
		 */
		public static function  required_fields() {
			return '<div id="required-fields" style="display:initial;">
				<legend class="field-level">
				<h4 id="tvc-required-attributes-header">' . esc_html__( 'Required attributes', 'tvc-product-feed-manager' ) . ':</h4>
				</legend>'
				. self::field_form_table_titles() .
				'<div class="field-table" id="required-field-table"></div>
				</div>';
		}

		/**
		 * Returns the code for the highly recommended fields.
		 *
		 * @return string
		 */
		public static function highly_recommended_fields() {
			return '<div id="highly-recommended-fields" style="display:none;">
				<legend class="field-level">
				<h4 id="tvc-highly-recommended-attributes-header">' . esc_html__( 'Highly recommended attributes', 'tvc-product-feed-manager' ) . ':</h4>
				</legend>'
				. self::field_form_table_titles() .
				'<div class="field-table" id="highly-recommended-field-table"></div>
				</div>';
		}

		/**
		 * Returns the code for the recommended fields.
		 *
		 * @return string
		 */
		public static function recommended_fields() {
			return '<div id="recommended-fields" style="display:none;">
				<legend class="field-level">
				<h4 id="tvc-recommended-attributes-header">' . esc_html__( 'Recommended attributes', 'tvc-product-feed-manager' ) . ':</h4>
				</legend>'
				. self::field_form_table_titles() .
				'<div class="field-table" id="recommended-field-table"></div>
				</div>';
		}

		/**
		 * Returns the code for the optional fields.
		 *
		 * @return string
		 */
		public static function optional_fields() {
			return '<div id="optional-fields" style="display:initial;">
				<legend class="field-level">
				<h4 id="tvc-optional-attributes-header">' . esc_html__( 'Optional attributes', 'tvc-product-feed-manager' ) . ':</h4>
				</legend>'
				. self::field_form_table_titles() .
				'<div class="field-table" id="optional-field-table"></div>
				</div>';
		}

		/**
		 * Returns the code for the custom fields.
		 *
		 * @return string
		 */
		public static function custom_fields() {
			return '<div id="custom-fields" style="display:initial;">
				<legend class="field-level">
				<h4 id="tvc-custom-attributes-header">' . esc_html__( 'Custom attributes', 'tvc-product-feed-manager' ) . ':</h4>
				</legend>'
				. self::field_form_table_titles() .
				'<div class="field-table" id="custom-field-table"></div>
				</div>';
		}

		/**
		 * Returns the feed form table titles
		 *
		 * @return string
		 */
		private static function field_form_table_titles() {
			return '<div class="tvc-field-header-wrapper">
				<div class="field-header col20w">' . esc_html__( 'Add to feed', 'tvc-product-feed-manager' ) . '&nbsp;<i class="fas fa-question-circle" title="Google mechandise product data"></i></div>
				<div
					class="field-header col30w">' . esc_html__( 'From WooCommerce source', 'tvc-product-feed-manager' ) . '&nbsp;<i class="fas fa-question-circle" title="WooCommerce Product Data"></i></div>
				<div class="field-header col40w">' . esc_html__( 'Condition', 'tvc-product-feed-manager' ) . '</div>
				<div class="field-header col50w" style="float:right">' . esc_html__( 'Edit Value', 'tvc-product-feed-manager' ) . '</div>
				<div class="end-row">&nbsp;</div>
			</div>';
		}
	}

	// end of TVC_Attribute_Selector_Element class

endif;
