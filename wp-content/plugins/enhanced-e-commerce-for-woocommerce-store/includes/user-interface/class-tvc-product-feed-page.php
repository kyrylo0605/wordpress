<?php

/**
 * WP Product Feed Manager Add Feed Page Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'TVC_Product_Feed_Page' ) ) :

    /**
     * TVC Feed Form Class
     */
    class TVC_Product_Feed_Page extends TVC_Admin_Page {

        /**
         * @var string|null contains the feed id, null for a new feed.
         */
        private $_feed_id;

        /**
         * @var array|null  contains the feed data.
         */
        private $_feed_data;

        private $feedURL;

        public function __construct() {
            global $wpdb;
            parent::__construct();

            tvc_check_db_version();

            $this->_feed_id = array_key_exists( 'id', $_GET ) && $_GET['id'] ? $_GET['id'] : null;

            $this->set_feed_data();

            add_option( 'wp_enqueue_scripts', TVC_i18n_Scripts::tvc_feed_settings_i18n(), 100 );
            add_option( 'wp_enqueue_scripts', TVC_i18n_Scripts::tvc_list_table_i18n() );
        }

        /**
         * Collects the html code for the product feed form page and displays it.
         */
        public function show() {

            $tab_header_sub_title = $this->_feed_id ? __( 'Here you can edit the parameters of your feed.', 'tvc-product-feed-manager' ) :
                __( 'Here you can setup your new feed. Start by entering a name for your feed and selecting a channel.', 'tvc-product-feed-manager' );

            echo $this->admin_page_header();

            echo $this->message_field();

            if ( tvc_wc_installed_and_active() ) {
                if ( ! tvc_wc_min_version_required() ) {
                    echo tvc_update_your_woocommerce_version_message();
                    exit;
                }

                echo $this->tabs();

                echo $this->subtabs();

                echo $this->tab_header( __( '', 'tvc-product-feed-manager' ), $tab_header_sub_title );

                echo $this->product_feed_page_data_holder();

                echo $this->main_input_table_wrapper();

                echo $this->category_selector_table_wrapper();

                echo $this->feed_top_buttons();

                echo $this->attribute_mapping_table_wrapper();

            } else {
                echo tvc_you_have_no_woocommerce_installed_message();
            }
        }

        /**
         * Fills the $feed_data_holder with the correct data that then can be passed through to the edit feed page.
         *
         * @return  string  Containing the data that is required to build the edit feed page.
         */
        private function product_feed_page_data_holder() {
            $feed_data_holder  = TVC_Form_Element::feed_data_holder( $this->_feed_data );
            $feed_data_holder .= TVC_Form_Element::ajax_to_db_conversion_data_holder();
            $feed_data_holder .= TVC_Form_Element::feed_url_holder();
            $feed_data_holder .= TVC_Form_Element::used_feed_names();

            return $feed_data_holder;
        }

        /**
         * Fetches feed data from the database and stores it in the _feed_data variable. This data is required to build the edit feed page. Stores empty
         * data when the page is opened from a new feed.
         */
        private function set_feed_data() {

            if ( $this->_feed_id ) {
                $queries_class = new TVC_Queries();
                $data_class    = new TVC_Data();

                $feed_data      = $queries_class->read_feed( $this->_feed_id )[0];
                $feed_filter    = $queries_class->get_product_filter_query( $this->_feed_id );
                $source_fields  = $data_class->get_source_fields( '1' );
                $attribute_data = $data_class->get_attribute_data( $this->_feed_id, $feed_data['channel'] );

            } else {
                $source_fields  = [];
                $attribute_data = [];
                $feed_filter    = '';
                $feed_data      = null; // a new feed
            }

            /* $feed_data variable contains category feed data fetch from database */

            $this->_feed_data = array(
                'feed_id'            => $this->_feed_id ? $this->_feed_id : false,
                'feed_file_name'     => $feed_data ? $feed_data['title'] : '',
                'channel_id'         => $feed_data ? $feed_data['channel'] : '',
                'language'           => $feed_data ? $feed_data['language'] : '',
                'target_country'     => $feed_data ? $feed_data['country'] : '',
                'category_mapping'   => $feed_data ? $feed_data['category_mapping'] : '',
                'main_category'      => $feed_data ? $feed_data['main_category'] : '',
                'include_variations' => $feed_data ? $feed_data['include_variations'] : '',
                'is_aggregator'      => $feed_data ? $feed_data['is_aggregator'] : '',
                'url'                => $feed_data ? $feed_data['url'] : '',
                'source'             => $feed_data ? $feed_data['source'] : '',
                'feed_title'         => $feed_data ? $feed_data['feed_title'] : '',
                'feed_description'   => $feed_data ? $feed_data['feed_description'] : '',
                'schedule'           => $feed_data ? $feed_data['schedule'] : '',
                'status_id'          => $feed_data ? $feed_data['status_id'] : '',
                'feed_filter'        => $feed_filter ? $feed_filter : null,
                'attribute_data'     => $attribute_data,
                'source_fields'      => $source_fields,
            );
        }

        /**
         * Returns the html code for the main input table.
         */
        private function main_input_table_wrapper() {
            $main_input_wrapper = new TVC_Product_Feed_Main_Input_Wrapper();
            $main_input_wrapper->display();
        }

        /**
         * Returns the html code for the category mapping table.
         */
        private function category_selector_table_wrapper() {
            $category_table_wrapper = new TVC_Product_Feed_Category_Wrapper();
            $category_table_wrapper->display();
        }

        /**
         * Return the html code for the attribute mapping table.
         */
        private function attribute_mapping_table_wrapper() {
            $attribute_mapping_wrapper = new TVC_Product_Feed_Attribute_Mapping_Wrapper();
            $attribute_mapping_wrapper->display();
        }

        /**
         * Returns the html code for the Save & Generate Feed and Save Feed buttons at the top of the attributes list.
         *
         * @return string
         */
        private function feed_top_buttons() {
            return TVC_Form_Element::feed_generation_buttons( 'tvc-generate-feed-button-top', 'tvc-save-feed-button-top', 'tvc-view-feed-button-top' );
        }
    }

    // end of TVC_Product_Feed_Form class

endif;
