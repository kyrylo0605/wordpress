<?php

/**
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Google Product Feed Manager
 * @subpackage Google Product Feed Manager/Application/Classes
 * @author     Chetan Rode <chetan@tatvic.com>
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'TVC_Feed_Master_Class' ) ) :

    /**
     * Feed Master Class
     *
     * @since 2.0.0
     */
    class TVC_Feed_Master_Class {

        use TVC_Processing_Support;

        /**
         * Contains the general feed data
         *
         * @var object
         */
        protected $_feed = null;

        /**
         * Contains the feed is
         *
         * @var string
         */
        protected $_feed_id;

        /**
         * Instantiation of global background process class
         *
         * @var stdClass
         */
        protected $_background_process;

        /**
         * Placeholder for the correct channel class
         *
         * @var stdClass
         */
        protected $_channel_class;

        /**
         * Placeholder for the TVC_Data_Class
         *
         * @var stdClass
         */
        protected $_data_class;

        /**
         * Path and name of the feed file
         *
         * @var string
         */
        protected $_feed_file_path;

        /**
         * Initiate new Feed Master class.
         *
         * @param string $feed_id The id of the feed. Default 0
         *
         * @global stdClass $background_process
         *
         */
        public function __construct( $feed_id = '0' ) {
            $query_class = new TVC_Queries();

            /**
             * when this construct is called from a channel class, it will not specify the feed_id so the default 0 is used
             * in that case the feed_type_id will always be 1
             */

            $feed_type_id = $feed_id > 0 ? $query_class->get_feed_type_id( $feed_id ) : '1';

            $background_class = apply_filters( 'tvc_background_class', 'TVC_Feed_Processor', $feed_type_id );

            $this->_background_process = new $background_class();
            $this->_data_class         = new TVC_Data();
        }

        /**
         * The update feed file function that starts the update process
         *
         * @param bool $silent (default true)
         *
         * @return string|void or false
         */
        public function update_feed_file( $silent = true ) {

            $feed_id = TVC_Feed_Controller::get_next_id_from_feed_queue();

            do_action( 'tvc_feed_process_prepared', $feed_id, $silent );

            if ( false === $feed_id ) {
                return false;
            }

            if ( $silent ) {
                set_transient( 'tvc_running_silent', true, TVC_TRANSIENT_LIVE );
            }

            $feed_data = $this->_data_class->get_feed_data( $feed_id );

            if ( ! $feed_data ) {
                do_action( 'tvc_feed_generation_message', $feed_id, 'The update_feed_file function failed to get the feed data', 'ERROR' );
                if ( ! $silent ) {
                    _e( '1428 - Failed to load the feed data, please to generate a feed in the foreground mode (see Settings page) and then try the background mode again.', 'tvc-product-feed-manager' );
                }

                return false;
            }

            /**
             * store the feed data in a property
             */
            $this->_feed = $feed_data;

            /**
             * only one feed can be processing so if other feeds than the current feed are on a processing
             * status, set these to an error status
             */
            $this->_data_class->check_for_failed_feeds( $this->_feed->feedId );

            $prepare_update = $this->prepare_feed_file_update( $silent );

            if ( true !== $prepare_update ) {
                if ( ! $silent ) {
                    echo $prepare_update;
                }

                return false;
            }

            TVC_Feed_Controller::set_feed_processing_flag( true );

            $this->prepare_background_process();

            $this->fill_the_background_queue();

            $this->activate_feed_file_update( $this->_feed->feedId );

            delete_transient( 'tvc_running_silent' );

            if ( ! $silent ) {
                echo 'started_processing';
            }
        }

        public function feed_status_check( $feed_id ) {
            $queries_class = new TVC_Queries();

            $current_feed_status = $queries_class->get_feed_status_data( $feed_id );

            $current_feed_status['feed_type_name'] = tvc_list_feed_type_text()[ $current_feed_status['feed_type_id'] ];

            if ( '3' === $current_feed_status['status_id'] ) { // status still processing
                // get file name, including path
                $file_extension = function_exists( 'get_file_type' ) ? get_file_type( $current_feed_status['channel_id'] ) : 'xml';
                $feed_file      = tvc_get_file_path( $current_feed_status['title'] . '.' . $file_extension );

                // if it is, set the feed status to fail and change the $current_feed_status['status_id'] to 6
                if ( TVC_Feed_Controller::feed_processing_failed( $feed_file ) ) {
                    // change the status of the feed to failed processing
                    $this->_data_class->update_feed_status( $feed_id, 6 ); // feed status to failed

                    // update the current_feed_status variable before returning
                    $current_feed_status['status_id'] = '6';

                    // clear this feed from the feed queue
                    TVC_Feed_Controller::remove_id_from_feed_queue( $feed_id );
                    TVC_Feed_Controller::set_feed_processing_flag( false );

                    // if running silent (automatic feed update) inform the user about the failed feed
                    if ( get_transient( 'tvc_running_silent' ) ) {
                        TVC_Email::send_feed_failed_message();
                    }

                    if ( ! TVC_Feed_Controller::feed_queue_is_empty() ) {
                        $next_feed_id = TVC_Feed_Controller::get_next_id_from_feed_queue();

                        if ( $next_feed_id ) {
                            // so there is another feed in the queue
                            $feed_master_class = new TVC_Feed_Master_Class( $next_feed_id );
                            $feed_master_class->update_feed_file();
                        }
                    }

                    do_action( 'tvc_feed_processing_failed_file_size_stopped_increasing', $feed_id, TVC_Feed_Controller::nr_ids_remaining_in_queue() );
                    do_action( 'tvc_register_feed_url', $feed_id, $feed_file );
                }

                return $current_feed_status;
            } else {
                return $current_feed_status;
            }
        }

        /**
         * Perform all preparations for the feed update starts
         *
         * @param bool $silent (default true)
         *
         * @return bool true if feed file has been updated successfully
         */
        private function prepare_feed_file_update( $silent ) {
            // prepare the folder structure to support saving feed files
            if ( ! file_exists( TVC_FEEDS_DIR ) ) {
                TVC_Folders::make_feed_support_folder();
            }

            if ( ! is_writable( TVC_FEEDS_DIR ) ) {
                /* translators: %s: Folder where the feeds are stored */
                return sprintf( esc_html__( '1430 - %s is not a writable folder. Make sure you have admin rights to this folder.', 'tvc-product-feed-manager' ), TVC_FEEDS_DIR );
            }

            $initial_feed_status = $this->_feed->status;

            if ( ! $this->set_properties() ) {
                $message = sprintf( 'Failed to set the properties of feed %s.', $this->_feed->feedId );
                do_action( 'tvc_feed_generation_message', $this->_feed->feedId, $message, 'ERROR' );

                return false;
            }

            $this->_data_class->set_nr_of_feed_products( $this->_feed->feedId, '0' ); // 0 products
            $this->_data_class->update_feed_status( $this->_feed->feedId, 3 ); // set status to "Processing"

            $file_extension = function_exists( 'get_file_type' ) ? get_file_type( $this->_feed->channel ) : 'xml';

            $this->_feed_file_path = tvc_get_file_path( $this->_feed->title . '.' . $file_extension );

            // clear the existing feed
            file_put_contents( $this->_feed_file_path, '' );

            // clear the file size checker
            delete_transient( 'tvc_feed_file_size' );

            // clear the list of processed products @since 2.10.0.
            delete_option( 'tvc_processed_products' );

            $channel_class = new TVC_Channel();
            $channel_name  = $channel_class->get_channel_short_name( $this->_feed->channel );

            $logger_message = sprintf( 'Feed %s is a %s feed stored as %s, with an original feed status %s.', $this->_feed->feedId, $channel_name, $this->_feed_file_path, $initial_feed_status );
            do_action( 'tvc_feed_generation_message', $this->_feed->feedId, $logger_message );

            return true;
        }

        /**
         * Store common product metadata in the Background Process properties
         */
        private function prepare_background_process() {
            // start counting from zero
            delete_option( 'tvc_processed_products' );

            $this->_background_process->set_feed_data( $this->_feed );
            $this->_background_process->set_file_path( $this->_feed_file_path );
            $this->_background_process->set_pre_data( $this->get_required_pre_data() );
            $this->_background_process->set_channel_details( $this->get_channel_details() );
            $this->_background_process->set_relations_table( $this->get_channel_to_woocommerce_field_relations() );
        }

        /**
         * Fills the background queue
         */
        private function fill_the_background_queue() {
            // start with an empty queue
            $this->_background_process->clear_the_queue();
            $sw_status_control = 30000 * 3.3;
            $product_counter   = 0;

            // add the header to the queue
            $header_string = $this->get_feed_header();
            $this->_background_process->push_to_queue( array( 'file_format_line' => $header_string ) );

            do {
                $product_ids = $this->get_product_ids_for_feed();

                // add the product ids to the queue
                foreach ( $product_ids as $product_id ) {
                    $this->_background_process->push_to_queue( $product_id );

                    $product_counter++;

                    if ( $product_counter > $sw_status_control ) { // HWOTBERH
                        break;
                    }
                }
            } while ( ! empty( $product_ids ) && $sw_status_control > $product_counter );

            delete_transient( 'tvc_start_product_id' );

            // implement the tvc_feed_ids_in_queue filter on the queue.
            $this->_background_process->apply_filter_to_queue( $this->_feed->feedId );

            do_action( 'tvc_feed_queue_filled', $this->_feed->feedId, $product_counter );

            $product_ids = null;

            $file_extension = function_exists( 'get_file_type' ) ? get_file_type( $this->_feed->channel ) : 'xml';

            // add the xml footer to the queue
            if ( 'xml' === $file_extension ) {
                $this->_background_process->push_to_queue(
                    array(
                        'file_format_line' => apply_filters(
                            'tvc_footer_string',
                            $this->_channel_class->footer(),
                            $this->_feed->feedId,
                            $this->_feed->feedTypeId
                        ),
                    )
                );
            }
        }

        /**
         * Start the feed update process in the background
         *
         * @param string $feed_id
         */
        private function activate_feed_file_update( $feed_id ) {
            // save the queue data and then run the tvc-background-process dispatch function
            $this->_background_process->save( $this->_feed->feedId )->dispatch( $feed_id );
        }

        /**
         * Set all class properties
         *
         * @return bool
         */
        private function set_properties() {
            // some channels do not use channels and leave the main category empty which causes issues
            if ( function_exists( 'channel_uses_category' ) && ! channel_uses_category( $this->_feed->channel ) ) {
                $this->_feed->mainCategory = 'No Category Required';
            }

            // some channels only accept category id numbers, for these channels retrieve the category numbers
            if ( stripos( strrev( $this->_feed->mainCategory ), ')' ) === 0 ) {
                $start                     = stripos( $this->_feed->mainCategory, '(' ) + 1;
                $end                       = stripos( $this->_feed->mainCategory, '(' ) - $start;
                $this->_feed->mainCategory = substr( $this->_feed->mainCategory, $start, $end );
            }

            // instantiate the correct channel class
            $this->_channel_class = new TVC_Google_Feed_Class();
            return true;
        }

        /**
         * Returns the header that is correct for the selected feed type
         *
         * @return string
         */
        private function get_feed_header() {
            $header_string = '';

            if ( $this->_feed->channel ) {
                $file_extension = function_exists( 'get_file_type' ) ? get_file_type( $this->_feed->channel ) : 'xml';
                if ( '1' === $this->_feed->channel && ! empty( $this->_feed->feedTitle ) ) {
                    $header_string = $this->_channel_class->header( $this->_feed->feedTitle, $this->_feed->feedDescription );
                } elseif ( 'xml' === $file_extension ) {
                    $header_string = $this->_channel_class->header( $this->_feed->title );
                } elseif ( 'txt' === $file_extension ) {
                    $header_string = $this->make_tab_delimited_string_from_data_array( $this->get_active_fields() );
                } elseif ( 'csv' === $file_extension ) {
                    $csv_sep = apply_filters( 'tvc_csv_separator', get_correct_csv_header_separator( $this->_feed->channel ) );
                    $string  = $this->make_csv_header_string( $this->get_active_fields(), $csv_sep );
                    $header_string = $this->_channel_class->header( $string );
                }
            }

            return apply_filters( 'tvc_header_string', $header_string, $this->_feed->feedId, $this->_feed->feedTypeId );
        }

        /**
         * ALERT! has a javascript equivalent in channel-functions.js called setAttributeStatus();
         */

        /**
         * sets the activity status of a specific attribute to true or false depending on its level
         *
         * @param int $field_level
         * @param string $field_value
         *
         * @return boolean
         */
        protected function set_attribute_status( $field_level, $field_value ) {
            if ( $field_level > 0 && $field_level < 3 ) {
                return true;
            }
            $clean_field_value = trim( $field_value );
            if ( ! empty( $clean_field_value ) ) {
                return true;
            }

            return false;
        }

        /**
         * Produces an array with the ids of all products that should be added into the feed
         *
         * @return array with ids
         */
        private function get_product_ids_for_feed() {
            $queries_class = new TVC_Queries();

            $selected_categories = apply_filters( 'tvc_selected_categories', $this->make_category_selection_string(), $this->_feed->feedId );

            $include_variations = '1' === $this->_feed->includeVariations ? true : false;

            $products = $queries_class->get_post_ids( $selected_categories, $include_variations );

            array_filter( $products ); // just to make sure, remove all empty elements

            return $products;
        }

        /**
         * Returns a comma separated string with selected category numbers to be used as part of a query
         *
         * @return string
         */
        private function make_category_selection_string() {
            $category_selection_string = '';
            $category_mapping          = json_decode( $this->_feed->categoryMapping );

            if ( ! empty( $category_mapping ) ) {
                foreach ( $category_mapping as $category ) {
                    $category_selection_string .= $category->shopCategoryId . ', '; // phpcs:ignore
                }
            }

            return $category_selection_string ? substr( $category_selection_string, 0, - 2 ) : '';
        }

        /**
         * Get all general data required to make a feed
         *
         * @return array
         */
        private function get_required_pre_data() {
            // get the feed query string if the user has added to filter out specific products from the feed (Paid version only)
            $feed_filter = $this->_data_class->get_filter_query( $this->_feed->feedId );

            // should the feed include product variations?
            $include_variations = '1' === $this->_feed->includeVariations ? true : false;

            // get an array with all the field names that are required to make the feed (including the source fields, fields for the queries and fields for static data)
            $required_column_names = $this->get_column_names_required_for_feed( $feed_filter );

            // get the fields that are active and have to go into the feed
            $active_fields = $this->get_active_fields();

            $database_fields = $this->get_database_fields( $required_column_names );

            return array(
                'filters'         => $feed_filter,
                'include_vars'    => $include_variations,
                'column_names'    => $required_column_names,
                'active_fields'   => $active_fields,
                'database_fields' => $database_fields,
            );
        }

        /**
         * Get category name and description name from the active channel
         *
         * @return array
         */
        private function get_channel_details() {
            return function_exists( 'channel_file_text_data' ) ? channel_file_text_data( $this->_feed->channel ) :
                array(
                    'channel_id'       => $this->_feed->channel,
                    'category_name'    => 'google_product_category',
                    'description_name' => 'description',
                );
        }

        /**
         * returns the column names from the database that are required to get the data necessary to make the feed
         *
         * @param object $feed_filter_object
         *
         * @return array
         */
        private function get_column_names_required_for_feed( $feed_filter_object ) {
            $support_class = new TVC_Feed_Support();

            $fields         = array();
            $filter_columns = $support_class->get_column_names_from_feed_filter_array( $feed_filter_object );

            foreach ( $this->_feed->attributes as $attribute ) {
                if ( 'category_mapping' !== $attribute->fieldName ) {
                    $column_names = $this->get_db_column_name_from_attribute( $attribute );
                    foreach ( $column_names as $name ) {
                        if ( ! empty( $name ) ) {
                            array_push( $fields, $name );
                        }
                    }
                }
            }

            $result = array_unique( array_merge( $fields, $filter_columns ) ); // remove doubles

            if ( empty( $result ) ) {
                tvc_write_log_file( 'Function get_column_names_required_for_feed returned zero columns' );
            }

            return array_merge( $result ); // and resort the result before returning
        }

        /**
         * returns all active column names that are stored in the feed attributes
         *
         * @param array $attribute
         *
         * @return array
         */
        public function get_db_column_name_from_attribute( $attribute ) {
            $column_names = array();

            if ( property_exists( $attribute, 'isActive' ) && $attribute->isActive ) { // only select the active attributes
                // source columns
                if ( ! empty( $attribute->value ) ) {
                    $source_columns    = $this->get_source_columns_from_attribute_value( $attribute->value );
                    $condition_columns = $this->get_condition_columns_from_attribute_value( $attribute->value );
                    $query_columns     = $this->get_queries_columns_from_attribute_value( $attribute->value );

                    // TODO: I think the first $column_names array can be removed from the array_merge
                    $column_names = array_merge( $column_names, $source_columns, $condition_columns, $query_columns );
                }

                // advised sources
                if ( ! empty( $attribute->advisedSource )
                    && strpos( $attribute->advisedSource, esc_html__( 'Fill with a static value', 'tvc-product-feed-manager' ) ) === false
                    && strpos( $attribute->advisedSource, esc_html__( 'Use the settings in the Merchant Center', 'tvc-product-feed-manager' ) ) === false ) {

                    // add the relevant advised sources
                    array_push( $column_names, $attribute->advisedSource );
                } elseif ( property_exists( $attribute, 'advisedSource' )
                    && strpos( $attribute->advisedSource, esc_html__( 'Use the settings in the Merchant Center', 'tvc-product-feed-manager' ) ) !== false ) {

                    array_push( $column_names, 'woo_shipping' );
                }
            }
            return $column_names;
        }

        /**
         * extract the active fields from the attributes
         *
         * @return array
         */
        private function get_active_fields() {
            $active_fields = array();

            foreach ( $this->_feed->attributes as $attribute ) {
                if ( $attribute->isActive && 'category_mapping' !== $attribute->fieldName ) {
                    $push = false;

                    if ( '1' === $attribute->fieldLevel ) {
                        $push = true;
                    } else {
                        $value_object = property_exists( $attribute, 'value' ) ? json_decode( $attribute->value ) : new stdClass();

                        if ( ! empty( $attribute->value ) && property_exists( $value_object, 'm' ) && property_exists( $value_object->m[0], 's' ) ) {
                            $push = true;
                        } elseif ( ! empty( $attribute->advisedSource ) ) {
                            $push = true;
                        } elseif ( ! empty( $attribute->value ) && property_exists( $value_object, 't' ) ) {
                            $push = true;
                        } elseif ( ! empty( $attribute->value ) && property_exists( $value_object, 'v' ) ) {
                            $push = true;
                        }
                    }

                    if ( true === $push ) {
                        array_push( $active_fields, $attribute->fieldName );
                    }
                }
            }

            if ( empty( $active_fields ) ) {
                tvc_write_log_file( 'Function get_active_fields returned zero fields.' );
            }

            return $active_fields;
        }

        /**
         * gather all required column names from the database
         *
         * @param array $active_field_names
         *
         * @return array
         */
        private function get_database_fields( $active_field_names ) {
            $queries_class = new TVC_Queries();

            $post_fields                      = array();
            $meta_fields                      = array();
            $custom_fields                    = array();
            $active_custom_fields             = array();
            $active_third_party_custom_fields = array();
            $post_columns_string              = '';

            $columns_in_post_table     = $queries_class->get_columns_from_post_table(); // get all post table column names
            $all_custom_columns        = $queries_class->get_custom_product_attributes(); // get all custom name labels
            $third_party_custom_fields = $this->_data_class->get_third_party_custom_fields();

            // convert the query results to an array with only the name labels
            foreach ( $columns_in_post_table as $column ) {
                array_push( $post_fields, $column->Field );
            } // $post_fields containing the required names from the post table
            foreach ( $all_custom_columns as $custom ) {
                array_push( $custom_fields, $custom->attribute_name );
            }
            // $custom_fields containing the custom names
            // filter the post columns, the meta columns and the custom columns to only those that are actually in use

            foreach ( $active_field_names as $column ) {
                if ( in_array( $column, $post_fields ) && 'ID' !== $column ) { // because ID is always required, it's excluded here and hard coded in the query
                    $post_columns_string .= $column . ', '; // here a string is required to push in the query
                } elseif ( in_array( $column, $custom_fields ) ) {
                    array_push( $active_custom_fields, $column );
                } elseif ( in_array( $column, $third_party_custom_fields ) ) {
                    array_push( $active_third_party_custom_fields, $column );
                } else {
                    array_push( $meta_fields, $column );
                }
            }

            return array(
                'post_column_string'        => $post_columns_string,
                'meta_fields'               => $meta_fields,
                'active_custom_fields'      => $active_custom_fields,
                'third_party_custom_fields' => $third_party_custom_fields,
            );
        }

        /**
         * header text, override this function in the class-feed.php if required for a channel specific header
         *
         * @param string $title
         *
         * @return string
         */
        protected function header( $title ) {
            return apply_filters( 'tvc_xml_header', $title );
        }

        /**
         * footer text, override if required for a channel specific footer
         *
         * @return string
         */
        protected function footer() {
            return apply_filters( 'tvc_xml_footer', '</products></rss>' );
        }
    }
    // end of TVC_Feed_Master_Class
endif;
