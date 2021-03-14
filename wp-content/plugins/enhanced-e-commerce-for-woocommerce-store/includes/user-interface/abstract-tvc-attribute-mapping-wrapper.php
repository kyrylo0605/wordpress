<?php

/**
 * TVC Attribute Mapping Wrapper Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Attribute_Mapping_Wrapper' ) ) :

	abstract class TVC_Attribute_Mapping_Wrapper {

		abstract public function display();

		protected function attribute_mapping_wrapper_table_start( $display = 'none' ) {
			return '<section class="tvc-edit-feed-form-element-wrapper tvc-attribute-mapping-wrapper" id="tvc-attribute-map" style="display:' . $display . ';">';
		}

		protected function attribute_mapping_wrapper_table_title() {
			return '<div class="header" id="fields-form-header"><h3>' . __( 'Attribute Mapping', 'tvc-product-feed-manager' ) . ':</h3></div>';
		}

		protected function attribute_mapping_wrapper_table_end() {
			return '</section>';
		}
	}

	// end of TVC_Attribute_Mapping_Wrapper class

endif;
