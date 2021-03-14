<?php

/**
 * TVC Product Feed Category Wrapper Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Product_Feed_Category_Wrapper' ) ) :

	class TVC_Product_Feed_Category_Wrapper extends TVC_Category_Wrapper {

		/**
		 * Display the product feed category mapping table.
		 *
		 * @return void
		 */
		public function display() {

			// Start with the section code.
			echo '<section class="tvc-edit-feed-form-element-wrapper tvc-category-mapping-wrapper" id="tvc-category-map" style="display:none;">';
			echo '<div id="category-mapping-header" class="header"><h3>' . __( 'Category Mapping', 'tvc-product-feed-manager' ) . ':</h3></div>';
			echo '<table class="fm-category-mapping-table widefat" cellspacing="0" id="category-mapping-table">';

			// The category mapping table header.
			echo TVC_Category_Selector_Element::category_selector_table_head( 'mapping' );

			echo '<tbody id="tvc-category-mapping-body">';

			// The content of the table.
			echo $this->category_table_content( 'mapping' );

			echo '</tbody>';

			// Closing the section.
			echo '</table></section>';

			// Add the product filter element.
			echo $this->product_filter();
		}
	}

	// end of TVC_Product_Feed_Category_Wrapper class

endif;
