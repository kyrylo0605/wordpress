<?php

/**
 * TVC Form Element Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.2
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'TVC_Form_Element' ) ) :

    /**
     * TVC Category Selector Element Class
     *
     * Contains the html elements code for the forms
     */
    class TVC_Form_Element {

        /**
         * Returns the code for the tabs in all main forms
         *
         * @return string html code for the tabs
         */
        public static function main_form_tabs() {

            // Get the TVC_Tab objects
            $tabs = $GLOBALS['tvc_tab_data'];

            $html_code = '<div class="tvc-main-wrapper tvc-header-wrapper" id="tab-bar"><h2 class="nav-tab-wrapper">';

            // Html for the tab_selected_string
            foreach ( $tabs as $tab ) {
                $html_code .= '<a href="admin.php?' . $tab->get_page_tab_url() . '"';
                $html_code .= 'class="nav-tab' . $tab->tab_selected_string() . '">' . $tab->get_tab_title() . '</a>';
            }

            $html_code .= '</h2></div>';

            return $html_code;
        }

        public static function sub_form_tabs(){

            $label = empty($_GET['id']) ? "Create Product Feed" : "Edit Product Feed";
            $html_code = '<div class="nav-tab-wrapper">';
            $html_code .= '<button id="tvc-attribute-map-1"';
            $html_code .= 'class="nav-tab nav-tab-active">'. $label .'</button>';
            $html_code .= '<button id="tvc-attribute-map-2"';
            $html_code .= 'class="nav-tab" >Attribute Mapping</button>';
            $html_code .= '</div>';
            return $html_code;
        }

        /**
         * @param   array   $feed_data_to_store An array with the feed data.
         *
         * @return  string  An html string that contains the json encoded feed data.
         */
        public static function feed_data_holder( $feed_data_to_store ) {
            return '<var id="tvc-ajax-feed-data-array" style="display:none;">' . json_encode( $feed_data_to_store ) . '</var>';
        }

        /**
         * Returns the code that stores product feed specific data in the page source code.
         *
         * @return string
         */
        public static function ajax_to_db_conversion_data_holder() {
            $feed_data_to_store = json_encode( self::ajax_feed_data_to_database_array() );
            return '<var id="tvc-ajax-feed-data-to-database-conversion-array" style="display:none;">' . $feed_data_to_store . '</var>';
        }

        /**
         * Returns the code that stores the feeds url
         *
         * @return string with var code containing the feeds url
         */
        public static function feed_url_holder() {
            $query_class   = new TVC_Queries();
            $feed_file_url = array_key_exists( 'id', $_GET ) && $_GET['id'] ? $query_class->get_file_url_from_feed( $_GET['id'] ) : '';

            return '<var id="tvc-feed-url" style="display:none;" >' . $feed_file_url . '</var>';
        }

        public static function used_feed_names() {
            $query_class = new TVC_Queries();
            $feed_names  = $query_class->get_all_feed_names();
            $used_names  = [];

            foreach( $feed_names as $name ) {
                array_push( $used_names, $name->title );
            }

            return '<var id="tvc-all-feed-names" style="display:none;" >' . json_encode( $used_names )  . '</var>';
        }

        /**
         * Returns the code for both Save & Generate and Save buttons.
         *
         * @param   string  $generate_button_id     ID for the Save & Generate button
         * @param   string  $save_button_id         ID for the Save button
         * @param   string  $open_feed_button_id    ID for the Open Feed button
         * @param   string  $initial_display        sets the initial display to any of the display style options (default none)
         *
         * @return string
         */
        public static function feed_generation_buttons( $generate_button_id, $save_button_id, $open_feed_button_id, $initial_display = 'none' ) {
            return '<div class="button-wrapper" id="page-center-buttons" style="display:' . $initial_display . ';">
				<input class="button-primary save-and-proceed" onClick="tvc_saveAndProceed()" type="button" name="generate-top"
					value="' . esc_html__( 'Generate Feed', 'tvc-product-feed-manager' ) .
                '" id="' . $generate_button_id . '" disabled/>
				</div></div>';

            /*<input class="button-primary" type="button" name="save-top"
					value="' . esc_html__( 'Save Feed', 'tvc-product-feed-manager' ) .
                '" id="' . $save_button_id . '" disabled/>*/
        }

        /**
         * Returns the code for both Save & Generate and Save buttons.
         *
         * @param   string  $generate_button_id     ID for the Save & Generate button
         * @param   string  $save_button_id         ID for the Save button
         * @param   string  $open_feed_button_id    ID for the Open Feed button
         * @param   string  $initial_display        sets the initial display to any of the display style options (default none)
         *
         * @return string
         */
        public static function feed_save_generation_buttons( $generate_button_id, $save_button_id, $open_feed_button_id,$feed_url,$feed_id, $initial_display = 'none' ) {

            return '<div class="button-wrapper" id="page-center-buttons" style="display:' . $initial_display . ';">
				<input class="button-primary" type="button" name="generate-top"
					value="' . esc_html__( 'Save & Update Feed', 'tvc-product-feed-manager' ) .
                '" id="' . $generate_button_id . '" disabled/>
				</div></div>';
        }

        public static function feed_save_generation_buttons_top( $generate_button_id, $save_button_id, $open_feed_button_id,$feed_url,$feed_id, $initial_display = 'none' ) {

            return '<div class="button-wrapper d-inline-block float-right mr-20" id="page-center-buttons" style="display:' . $initial_display . ';">
				<input class="button-primary" type="button" name="generate-top"
					value="' . esc_html__( 'Save & Update Feed', 'tvc-product-feed-manager' ) .
                '" id="' . $generate_button_id . '" disabled/>
				</div></div>';
        }

        /**
         * Returns the code for the Open Feed List button.
         *
         * @return string
         */

        public static function open_feed_list_button() {
            return '<div class="button-wrapper" id="page-bottom-buttons" style="display:none;"><input class="button-primary" type="button" ' .
                'onclick="parent.location=\'admin.php?page=tvc-product-feed-manager\'" name="new" value="' .
                esc_html__( 'Open Feed List', 'tvc-product-feed-manager' ) . '" id="add-new-feed-button" /></div>';
        }

        /**
         * Returns a conversion table between the ajax data items from a feed generation process to the corresponding database items.
         *
         * @since 2.5.0
         *
         * @return mixed|void
         */
        private static function ajax_feed_data_to_database_array() {
            return apply_filters(
                'tvc_feed_data_ajax_to_database_conversion_table',
                array(
                    (object) [ 'feed' => 'feedId', 'db' => 'product_feed_id', 'type' => '%d' ],
                    (object) [ 'feed' => 'channel', 'db' => 'channel_id', 'type' => '%d' ],
                    (object) [ 'feed' => 'language', 'db' => 'language', 'type' => '%s' ],
                    (object) [ 'feed' => 'includeVariations', 'db' => 'include_variations', 'type' => '%d' ],
                    (object) [ 'feed' => 'isAggregator', 'db' => 'is_aggregator', 'type' => '%d' ],
                    (object) [ 'feed' => 'country', 'db' => 'country_id', 'type' => '%s' ],
                    (object) [ 'feed' => 'dataSource', 'db' => 'source_id', 'type' => '%d' ],
                    (object) [ 'feed' => 'title', 'db' => 'title', 'type' => '%s' ],
                    (object) [ 'feed' => 'feedTitle', 'db' => 'feed_title', 'type' => '%s' ],
                    (object) [ 'feed' => 'feedDescription', 'db' => 'feed_description', 'type' => '%s' ],
                    (object) [ 'feed' => 'mainCategory', 'db' => 'main_category', 'type' => '%s' ],
                    (object) [ 'feed' => 'url', 'db' => 'url', 'type' => '%s' ],
                    (object) [ 'feed' => 'status', 'db' => 'status_id', 'type' => '%d' ],
                    (object) [ 'feed' => 'updateSchedule', 'db' => 'schedule', 'type' => '%s' ],
                    (object) [ 'feed' => 'feedType', 'db' => 'feed_type_id', 'type' => '%d' ],
                )
            );
        }
    }

    // end of TVC_Form_Element class

endif;
