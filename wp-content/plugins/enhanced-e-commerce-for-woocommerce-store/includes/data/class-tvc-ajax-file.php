<?php

/**
 * TVC Ajax File Class.
 *
 * @package TVC Product Feed Manager/Data/Classes
 */
if(!defined('ABSPATH')){
    exit;
}

if(!class_exists('TVC_Ajax_File')) :
    /**
     * Ajax File Class
     */
    class TVC_Ajax_File extends TVC_Ajax_Calls {
        private $apiDomain;
        protected $access_token;
        protected $refresh_token;
        public function __construct(){
            parent::__construct();
            $this->apiDomain = TVC_API_CALL_URL;
            // hooks
            add_action('wp_ajax_tvcajax-get-campaign-categories', array($this, 'tvcajax_get_campaign_categories'));
            add_action('wp_ajax_tvcajax-update-campaign-status', array($this, 'tvcajax_update_campaign_status'));
            add_action('wp_ajax_tvcajax-delete-campaign', array($this, 'tvcajax_delete_campaign'));
            add_action('wp_ajax_tvcajax-get-next-categories', array($this, 'tvcajax_read_next_categories'));
            add_action('wp_ajax_tvcajax-get-category-lists', array($this, 'tvcajax_read_category_lists'));
            add_action('wp_ajax_tvcajax-delete-feed-file', array($this, 'tvcajax_delete_feed_file'));
            add_action('wp_ajax_tvcajax-update-feed-file', array($this, 'tvcajax_update_feed_file'));
            add_action('wp_ajax_tvcajax-log-message', array($this, 'tvcajax_log_message'));
            add_action('wp_ajax_tvcajax-auto-feed-fix-mode-selection', array($this, 'tvcajax_auto_feed_fix_mode_selection'));
            add_action('wp_ajax_tvcajax-background-processing-mode-selection', array($this, 'tvcajax_background_processing_mode_selection'));
            add_action('wp_ajax_tvcajax-feed-logger-status-selection', array($this, 'tvcajax_feed_logger_status_selection'));
            add_action('wp_ajax_tvcajax-show-product-identifiers-selection', array($this, 'tvcajax_show_product_identifiers_selection'));
            add_action('wp_ajax_tvcajax-debug-mode-selection', array($this, 'tvcajax_debut_mode_selection'));
            add_action('wp_ajax_tvcajax-third-party-attribute-keywords', array($this, 'tvcajax_set_third_party_attribute_keywords'));
            add_action('wp_ajax_tvcajax-set-notice-mailaddress', array($this, 'tvcajax_set_notice_mailaddress'));
            add_action('wp_ajax_tvcajax-clear-feed-process-data', array($this, 'tvcajax_clear_feed_process_data'));
            add_action('wp_ajax_tvcajax-reinitiate-plugin', array($this, 'tvcajax_reinitiate_plugin'));
            add_action('wp_ajax_tvcajax-product-syncup', array($this, 'tvcajax_product_syncup'));
            add_action('wp_ajax_tvcajax-gmc-category-lists', array($this, 'tvcajax_get_gmc_categories'));
            add_action('wp_ajax_tvcajax-custom-metrics-dimension', array($this, 'tvcajax_custom_metrics_dimension'));
            add_action('wp_ajax_tvcajax-store-time-taken', array($this, 'tvcajax_store_time_taken'));
            add_action('wp_ajax_tvc_call_api_sync', array($this, 'tvc_call_api_sync'));
        }
        public function tvc_call_api_sync(){
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'apiSyncupNonce'), 'tvc_call_api_sync-nonce')){
                $TVC_Admin_Helper = new TVC_Admin_Helper();
                $tvc_msg = $TVC_Admin_Helper->set_update_api_to_db();
                sleep(2);
                echo json_encode(array('status' => 'success', 'message' => $tvc_msg));
                exit;
            }
            exit;
          }
        public function get_tvc_access_token(){
            if(!empty($this->access_token)){
                return $this->access_token;
            }else   if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
                $this->access_token = $_SESSION['access_token'];
                return $this->access_token;
            }else{
                $TVC_Admin_Helper = new TVC_Admin_Helper();
                $google_detail = $TVC_Admin_Helper->get_ee_options_data();          
                $this->access_token = $google_detail['setting']->access_token;
                return $this->access_token;
            }
        }
        
        public function get_tvc_refresh_token(){
            if(!empty($this->refresh_token)){
                return $this->refresh_token;
            }else   if(isset($_SESSION['refresh_token']) && $_SESSION['refresh_token']){
                $this->refresh_token = $_SESSION['refresh_token'];
                return $this->refresh_token;
            }else{
                $TVC_Admin_Helper = new TVC_Admin_Helper();
                $google_detail = $TVC_Admin_Helper->get_ee_options_data();          
                $this->refresh_token = $google_detail['setting']->refresh_token;
                return $this->refresh_token;
            }
        }
        /**
         * Delete the campaign
         */
        public function tvcajax_delete_campaign(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'campaignDeleteNonce'), 'tvcajax-delete-campaign-nonce')){

                $merchantId = filter_input(INPUT_POST, 'merchantId');
                $customerId = filter_input(INPUT_POST, 'customerId');
                $campaignId = filter_input(INPUT_POST, 'campaignId');

                $url = $this->apiDomain.'/campaigns/delete';

                $data = [
                    'merchant_id' => $merchantId,
                    'customer_id' => $customerId,
                    'campaign_id' => $campaignId
                ];

                $args = array(
                    'headers' => array(
                        'Authorization' => "Bearer MTIzNA==",
                        'Content-Type' => 'application/json'
                    ),
                    'method' => 'DELETE',
                    'body' => wp_json_encode($data)
                );

                // Send remote request
                $request = wp_remote_request($url, $args);

                // Retrieve information
                $response_code = wp_remote_retrieve_response_code($request);
                $response_message = wp_remote_retrieve_response_message($request);
                $response_body = json_decode(wp_remote_retrieve_body($request));

                if((isset($response_body->error) && $response_body->error == '')){
                    $message = $response_body->message;
                    echo json_encode(['status' => 'success', 'message' => $message]);
//                    return new WP_REST_Response(
//                        array(
//                            'status' => $response_code,
//                            'message' => $response_message,
//                            'data' => $response_body->data
//                        )
//                    );
                }else{
                    $message = is_array($response_body->errors) ? $response_body->errors[0] : "Face some unprocessable entity";
                    echo json_encode(['status' => 'error', 'message' => $message]);
                    // return new WP_Error($response_code, $response_message, $response_body);
                }

                //   echo json_encode( $categories );
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Update the campaign status pause/active
         */
        public function tvcajax_update_campaign_status(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'campaignStatusNonce'), 'tvcajax-update-campaign-status-nonce')){

                if(!class_exists('ShoppingApi')){
                    //require_once(__DIR__ . '/ShoppingApi.php');
                    include(ENHANCAD_PLUGIN_DIR . 'includes/setup/ShoppingApi.php');
                }

                $merchantId = filter_input(INPUT_POST, 'merchantId');
                $customerId = filter_input(INPUT_POST, 'customerId');
                $campaignId = filter_input(INPUT_POST, 'campaignId');
                $budgetId = filter_input(INPUT_POST, 'budgetId');
                $campaignName = filter_input(INPUT_POST, 'campaignName');
                $budget = filter_input(INPUT_POST, 'budget');
                $status = filter_input(INPUT_POST, 'status');
                $url = $this->apiDomain.'/campaigns/update';
                $shoppingObj = new ShoppingApi();
                $campaignData = $shoppingObj->getCampaignDetails($campaignId);

                $data = [
                    'merchant_id' => $merchantId,
                    'customer_id' => $customerId,
                    'campaign_id' => $campaignId,
                    'account_budget_id' => $budgetId,
                    'campaign_name' => $campaignName,
                    'budget' => $budget,
                    'status' => $status,
                    'target_country' => $campaignData->data['data']->targetCountry,
                    'ad_group_id' => $campaignData->data['data']->adGroupId,
                    'ad_group_resource_name' => $campaignData->data['data']->adGroupResourceName
                ];

                $args = array(
                    'headers' => array(
                        'Authorization' => "Bearer MTIzNA==",
                        'Content-Type' => 'application/json'
                    ),
                    'method' => 'PATCH',
                    'body' => wp_json_encode($data)
                );

                // Send remote request
                $request = wp_remote_request($url, $args);

                // Retrieve information
                $response_code = wp_remote_retrieve_response_code($request);
                $response_message = wp_remote_retrieve_response_message($request);
                $response_body = json_decode(wp_remote_retrieve_body($request));
                if((isset($response_body->error) && $response_body->error == '')){
                    $message = $response_body->message;
                    echo json_encode(['status' => 'success', 'message' => $message]);
//                    return new WP_REST_Response(
//                        array(
//                            'status' => $response_code,
//                            'message' => $response_message,
//                            'data' => $response_body->data
//                        )
//                    );
                }else{
                    $message = is_array($response_body->errors) ? $response_body->errors[0] : "Face some unprocessable entity";
                    echo json_encode(['status' => 'error', 'message' => $message]);
                    // return new WP_Error($response_code, $response_message, $response_body);
                }

                //   echo json_encode( $categories );
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Returns the campaign categories from a selected country
         */
        public function tvcajax_get_campaign_categories(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'campaignCategoryListsNonce'), 'tvcajax-campaign-category-lists-nonce')){

                $country_code = filter_input(INPUT_POST, 'countryCode');
                $customer_id = filter_input(INPUT_POST, 'customerId');
                $url = $this->apiDomain.'/products/categories';

                $data = [
                    'customer_id' => $customer_id,
                    'country_code' => $country_code
                ];

                $args = array(
                    'headers' => array(
                        'Authorization' => "Bearer MTIzNA==",
                        'Content-Type' => 'application/json'
                    ),
                    'body' => wp_json_encode($data)
                );

                // Send remote request
                $request = wp_remote_post($url, $args);

                // Retrieve information
                $response_code = wp_remote_retrieve_response_code($request);
                $response_message = wp_remote_retrieve_response_message($request);
                $response_body = json_decode(wp_remote_retrieve_body($request));

                if((isset($response_body->error) && $response_body->error == '')){
                    echo json_encode($response_body->data);
//                    return new WP_REST_Response(
//                        array(
//                            'status' => $response_code,
//                            'message' => $response_message,
//                            'data' => $response_body->data
//                        )
//                    );
                }else{
                    echo json_encode([]);
                    // return new WP_Error($response_code, $response_message, $response_body);
                }

                //   echo json_encode( $categories );
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Returns the sub-categories from a selected category
         */
        public function tvcajax_read_next_categories(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'nextCategoryNonce'), 'tvcajax-next-category-nonce')){
                $file_class = new TVC_File();

                $channel_id = filter_input(INPUT_POST, 'channelId');
                $requested_level = filter_input(INPUT_POST, 'requestedLevel');
                $parent_category = filter_input(INPUT_POST, 'parentCategory');
                $file_language = filter_input(INPUT_POST, 'fileLanguage');
                $categories = $file_class->get_categories_for_list($channel_id, $requested_level, $parent_category, $file_language);

                if(!is_array($categories)){
                    if('0' === substr($categories, - 1)){
                        chop($categories, '0');
                    }
                }

                echo json_encode($categories);
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Read the category list
         */
        public function tvcajax_read_category_lists(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'categoryListsNonce'), 'tvcajax-category-lists-nonce')){
                $file_class = new TVC_File();

                $channel_id = filter_input(INPUT_POST, 'channelId');
                $main_categories_string = filter_input(INPUT_POST, 'mainCategories');
                $file_language = filter_input(INPUT_POST, 'fileLanguage');
                $categories_array = explode(' > ', $main_categories_string);
                $categories = array();
                $required_levels = count($categories_array) > 0 ? ( count($categories_array) + 1 ) : count($categories_array);

                for($i = 0; $i < $required_levels; $i ++){
                    $parent_category = $i > 0 ? $categories_array[$i - 1] : '';
                    $c = $file_class->get_categories_for_list($channel_id, $i, $parent_category, $file_language);
                    if($c){
                        array_push($categories, $c);
                    }
                }

                echo json_encode($categories);
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Delete a specific feed file
         */
        public function tvcajax_delete_feed_file(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'deleteFeedNonce'), 'tvcajax-delete-feed-nonce')){
                $file_name = filter_input(INPUT_POST, 'fileTitle');

                if(file_exists(WP_PLUGIN_DIR . '/tvc-product-feed-manager-support/feeds/' . $file_name)){
                    $file = WP_PLUGIN_DIR . '/tvc-product-feed-manager-support/feeds/' . $file_name;
                }else{
                    $file = TVC_FEEDS_DIR . '/' . $file_name;
                }

                // only return results when the user is an admin with manage options
                if(is_admin()){
                    /* translators: %s: Title of the feed file */
                    echo file_exists($file) ? unlink($file) : tvc_show_wp_error(sprintf(esc_html__('Could not find file %s.', 'tvc-product-feed-manager'), $file));
                }else{
                    echo tvc_show_wp_error(esc_html__('Error deleting the feed. You do not have the correct authorities to delete the file.', 'tvc-product-feed-manager'));
                }
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * This function fetches the posted data and triggers the update of the feed file on the server.
         */
        public function tvcajax_update_feed_file(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'updateFeedFileNonce'), 'tvcajax-update-feed-file-nonce')){

                // fetch the data from $_POST
                $feed_id = filter_input(INPUT_POST, 'feedId');
                $background_mode_disabled = get_option('tvc_disabled_background_mode', 'false');

                TVC_Feed_Controller::add_id_to_feed_queue($feed_id);

                // if there is no feed processing in progress, of background processing is switched off, start updating the current feed
                if(!TVC_Feed_Controller::feed_is_processing() || 'true' === $background_mode_disabled){
                    do_action('tvc_manual_feed_update_activated', $feed_id);

                    $feed_master_class = new TVC_Feed_Master_Class($feed_id);
                    $feed_master_class->update_feed_file(false);
                }else{
                    $data_class = new TVC_Data();
                    $data_class->update_feed_status($feed_id, 4); // feed status to waiting in queue
                    echo 'pushed_to_queue';
                }
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Logs a message from a javascript call to the server
         */
        public function tvcajax_log_message(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'logMessageNonce'), 'tvcajax-log-message-nonce')){
                // fetch the data from $_POST
                $message = filter_input(INPUT_POST, 'messageList');
                $file_name = filter_input(INPUT_POST, 'fileName');
                $text_message = strip_tags($message);

                // only return results when the user is an admin with manage options
                if(is_admin()){
                    //tvc_write_log_file( $text_message, $file_name );
                }else{
                    echo tvc_show_wp_error(esc_html__('Error writing the feed. You do not have the correct authorities to write the file.', 'tvc-product-feed-manager'));
                }
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Changes the Auto Feed Fix setting from the Settings page
         *
         * @since 1.7.0
         */
        public function tvcajax_auto_feed_fix_mode_selection(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'updateAutoFeedFixNonce'), 'tvcajax-auto-feed-fix-nonce')){
                $selection = filter_input(INPUT_POST, 'fix_selection');
                update_option('tvc_auto_feed_fix', $selection);

                echo get_option('tvc_auto_feed_fix');
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Changes the Disable Background processing setting from the Settings page
         *
         * @since 2.0.7
         */
        public function tvcajax_background_processing_mode_selection(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'backgroundModeNonce'), 'tvcajax-background-mode-nonce')){
                $selection = filter_input(INPUT_POST, 'mode_selection');
                update_option('tvc_disabled_background_mode', $selection);

                echo get_option('tvc_disabled_background_mode');
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Changes the Feed Process Logger setting from the Settings page.
         *
         * @since 2.8.0
         */
        public function tvcajax_feed_logger_status_selection(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'feedLoggerStatusNonce'), 'tvcajax-logger-status-nonce')){
                $selection = filter_input(INPUT_POST, 'statusSelection');
                update_option('tvc_process_logger_status', $selection);

                echo get_option('tvc_process_logger_status');
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Changes the Show Product Identifiers setting from the Settings page.
         *
         * @since 2.10.0
         */
        public function tvcajax_show_product_identifiers_selection(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'showPINonce'), 'tvcajax-show-pi-nonce')){
                $selection = filter_input(INPUT_POST, 'showPiSelection');
                update_option('tvc_show_product_identifiers', $selection);

                echo get_option('tvc_show_product_identifiers');
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Changes the Debug setting from the Settings page
         *
         * @since 1.9.0
         */
        public function tvcajax_debug_mode_selection(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'debugNonce'), 'tvcajax-debug-nonce')){
                $selection = filter_input(INPUT_POST, 'debug_selection');
                update_option('tvc_debug_mode', $selection);

                echo get_option('tvc_debug_mode');
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        public function tvcajax_set_third_party_attribute_keywords(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'thirdPartyKeywordsNonce'), 'tvcajax-set-third-party-keywords-nonce')){
                $keywords = filter_input(INPUT_POST, 'keywords');
                update_option('tvc_third_party_attribute_keywords', $keywords);

                echo get_option('tvc_third_party_attribute_keywords');
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        public function tvcajax_set_notice_mailaddress(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'noticeMailaddressNonce'), 'tvcajax-set-notice-mailaddress-nonce')){
                $mailaddress = filter_input(INPUT_POST, 'mailaddress');
                update_option('tvc_notice_mailaddress', $mailaddress);

                echo get_option('tvc_notice_mailaddress');
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Re-initiates the plugin, updates the database and loads all cron jobs
         *
         * @since 1.9.0
         */
        public function tvcajax_reinitiate_plugin(){
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'reInitiateNonce'), 'tvcajax-reinitiate-nonce')){

                if(tvc_reinitiate_plugin()){
                    echo 'Plugin re-initiated';
                }else{
                    echo 'Re-initiation failed!';
                }
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Clears all option data that is related to the feed processing
         *
         * @since 1.10.0
         */
        public function tvcajax_clear_feed_process_data(){
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'clearFeedNonce'), 'tvcajax-clear-feed-nonce')){

                if(tvc_clear_feed_process_data()){
                    echo esc_html__('Feed processing data cleared', 'tvc-product-feed-manager');
                }else{
                    /* translators: clearing the feed data failed */
                    echo esc_html__('Clearing failed!', 'tvc-product-feed-manager');
                }
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * Returns the campaign categories from a selected country
         */
        public function tvcajax_get_gmc_categories(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'gmcCategoryListsNonce'), 'tvcajax-gmc-category-lists-nonce')){

                $country_code = filter_input(INPUT_POST, 'countryCode');
                $customer_id = filter_input(INPUT_POST, 'customerId');
                $parent = filter_input(INPUT_POST, 'parent');
                $url = $this->apiDomain.'/products/gmc-categories';

                $data = [
                    'customer_id' => $customer_id,
                    'country_code' => $country_code,
                    'parent' => $parent
                ];

                $args = array(
                    'headers' => array(
                        'Authorization' => "Bearer MTIzNA==",
                        'Content-Type' => 'application/json'
                    ),
                    'body' => wp_json_encode($data)
                );

                // Send remote request
                $request = wp_remote_post($url, $args);

                // Retrieve information
                $response_code = wp_remote_retrieve_response_code($request);
                $response_message = wp_remote_retrieve_response_message($request);
                $response_body = json_decode(wp_remote_retrieve_body($request));

                if((isset($response_body->error) && $response_body->error == '')){
                    echo json_encode($response_body->data);
//                    return new WP_REST_Response(
//                        array(
//                            'status' => $response_code,
//                            'message' => $response_message,
//                            'data' => $response_body->data
//                        )
//                    );
                }else{
                    echo json_encode([]);
                    // return new WP_Error($response_code, $response_message, $response_body);
                }

                //   echo json_encode( $categories );
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        public function getPostMetaData($id){
            $queries = new TVC_Queries();
            $column2 = json_decode(json_encode($queries->getTablePostMeta($id)), true);
            $arr = array();
            foreach($column2 as $val){
                $arr[$val['meta_key']] = $val['meta_value'];
            }
            return $arr;
        }

        /**
         * create product batch for product sync up
         */
        public function tvcajax_product_syncup(){
            // make sure this call is legal
            ini_set('max_execution_time', '0'); 
            ini_set('memory_limit','-1');
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'productSyncupNonce'), 'tvcajax-product-syncup-nonce')){


                if(!class_exists('CustomApi')){
                    include(ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php');
                }
                $customObj = new CustomApi();

                $batchId = time();
                $merchantId = filter_input(INPUT_POST, 'merchantId');
                $customerId = filter_input(INPUT_POST, 'customerId');
                $accountId = filter_input(INPUT_POST, 'accountId');
                $subscriptionId = filter_input(INPUT_POST, 'subscriptionId');
                $platformCustomerId = filter_input(INPUT_POST, 'platformCustomerId');
                $data = filter_input(INPUT_POST, 'data');
                /* echo "=============="; */
                // echo "<pre>";    
                parse_str($data, $formArray);
                //print_r($formArray); // Only for print array
                $mappedCatsDB = [];
                $mappedCats = [];
                $mappedAttrs = [];
                $skipProducts = [];
                foreach($formArray as $key => $value){
                    if(preg_match("/^category-name-/i", $key)){
                        if($value != ''){
                            $keyArray = explode("name-", $key);
                            $mappedCatsDB[$keyArray[1]]['name'] = $value;
                        }
                    }else if(preg_match("/^category-/i", $key)){
                        if($value != '' && $value > 0){
                            $keyArray = explode("-", $key);
                            $mappedCats[$keyArray[1]] = $value;
                            $mappedCatsDB[$keyArray[1]]['id'] = $value;
                        }
                    }else{
                        if($value){
                            $mappedAttrs[$key] = $value;
                        }
                    }
                }              
                update_option("ee_prod_mapped_cats", serialize($mappedCatsDB));
                update_option("ee_prod_mapped_attrs", serialize($mappedAttrs));               

                if(!empty($mappedCats)){
                    $catMapRequest = [];
                    $catMapRequest['subscription_id'] = $subscriptionId;
                    $catMapRequest['customer_id'] = $customerId;
                    $catMapRequest['merchant_id'] = $merchantId;
                    $catMapRequest['category'] = $mappedCats;
                    $catMapResponse = $customObj->setGmcCategoryMapping($catMapRequest);
                }

                if(!empty($mappedAttrs)){
                    $attrMapRequest = [];
                    $attrMapRequest['subscription_id'] = $subscriptionId;
                    $attrMapRequest['customer_id'] = $customerId;
                    $attrMapRequest['merchant_id'] = $merchantId;
                    $attrMapRequest['attribute'] = $mappedAttrs;
                    $attrMapResponse = $customObj->setGmcAttributeMapping($attrMapRequest);
                }

                $entries = [];
                if(!empty($mappedCats)){
                    foreach($mappedCats as $key => $mappedCat){
                        $all_products = get_posts(array(
                            'post_type' => 'product',
                            'numberposts' => -1,
                            'post_status' => 'publish',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field' => 'term_id',
                                    'terms' => $key, /* category name */
                                    'operator' => 'IN',
                                )
                            ),'meta_query' => array(
                                'relation' => 'AND',
                                array(
                                    'key' => '_stock_status',    
                                    'value' => 'instock'
                                )
                            )
                        ));
                        $tvc_currency = ((get_option('woocommerce_currency') != '')? get_option('woocommerce_currency') : 'USD');
                        $tvc_country = (isset($this->woo_country()[0]) ? $this->woo_country()[0] : '');
                        foreach($all_products as $postkey => $postvalue){
                            $postmeta = [];
                            $postmeta = $this->getPostMetaData($postvalue->ID);
                            $prd = wc_get_product($postvalue->ID);
                            $postObj = (object) array_merge((array) $postvalue, (array) $postmeta);
                            $product = [];
                            foreach($formArray as $key => $value){
                                $product['channel'] = 'online';
                                $product['google_product_category'] = $mappedCat;
                                $product['link'] = get_permalink($postvalue->ID);
                                if($key == 'image_link'){
                                    $image_id = $prd->get_image_id();
                                    $product['image_link'] = wp_get_attachment_image_url($image_id, 'full');
                                }else if($key == 'price'){
                                    $product[$key]['value'] = $postObj->_price;
                                    $product[$key]['currency'] = $tvc_currency;
                                    if ($postObj->_price == '' || $postObj->_price == null) {
                                        $skipProducts[$postObj->ID] = $postObj;
                                    }
                                }else if($key == 'sale_price'){
                                    $product[$key]['value'] = $postObj->_sale_price;
                                    $product[$key]['currency'] = $tvc_currency;
                                    if ($postObj->_sale_price == '' || $postObj->_sale_price == null) {
                                        $skipProducts[$postObj->ID] = $postObj;
                                    }
                                }else if($key == 'target_country'){
                                    $product[$key] = ($postObj->$value != '' ? $postObj->$value : $tvc_country);
                                }else if($key == 'content_language'){
                                    $product[$key] = ($postObj->$value != '' ? $postObj->$value : 'en');
                                }else if(isset($postObj->$value)){
//                                    echo $product[$key]."==".$postObj->$value."<br>";
                                    $product[$key] = $postObj->$value;
                                }
                            }

                            $entrie = [
                                'merchant_id' => $merchantId,
                                'batch_id' => ++$batchId,
                                'method' => 'insert',
                                'product' => $product
                            ];
                            $entries[] = $entrie;
                        }

                        wp_reset_query();
                    }
                }else{
                    $qArgs = array(
                        'post_type' => 'product',
                        'post_status' => 'publish',
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => '_stock_status',    
                                'value' => 'instock'
                            )
                        )
                    );
                    $loop = new WP_Query($qArgs);
                    $tvc_currency = ((get_option('woocommerce_currency') != '')? get_option('woocommerce_currency') : 'USD');
                    $tvc_country = (isset($this->woo_country()[0]) ? $this->woo_country()[0] : '');
                    foreach($loop->posts as $postkey => $postvalue){
                        $postmeta = [];
                        $postmeta = $this->getPostMetaData($postvalue->ID);
                        $postObj = (object) array_merge((array) $postvalue, (array) $postmeta);

                        $product = [];
                        foreach($formArray as $key => $value){
                            $product['content_language'] = 'en';
                            //$product['target_country'] = 'US';
                            $product['target_country'] = $tvc_country;
                            $product['channel'] = 'online';
                            if($key == 'price'){
                                $product[$key]['value'] = $postObj->_price;
                               // $product[$key]['currency'] = 'USD';
                                $product[$key]['currency'] = $tvc_currency;
                                if ($postObj->_price == '' || $postObj->_price == null) {
                                    $skipProducts[$postObj->ID] = $postObj;
                                }
                            }else if($key == 'sale_price'){
                                $product[$key]['value'] = $postObj->_sale_price;
                                //$product[$key]['currency'] = 'USD';
                                $product[$key]['currency'] = $tvc_currency;
                                if ($postObj->_sale_price == '' || $postObj->_sale_price == null) {
                                    $skipProducts[$postObj->ID] = $postObj;
                                }
                            }else if($key == 'target_country'){
                                $product[$key] = ($postObj->$value != '' ? $postObj->$value : (isset($this->woo_country()[0]) ? $this->woo_country()[0] : ''));
                            }else if(isset($postObj->$value)){
                                $product[$key] = $postObj->$value;
                            }
                        }

                        $entrie = [
                            'merchant_id' => $merchantId,
                            'batch_id' => ++$batchId,
                            'method' => 'insert',
                            'product' => $product
                        ];
                        $entries[] = $entrie;
                    }
                    wp_reset_query();
                }

                $data = [
                    'merchant_id' => $accountId,
                    'account_id' => $merchantId,
                    //'platform_customer_id' => $platformCustomerId,
                    'subscription_id' => $subscriptionId,
                    'entries' => $entries,
                ];

                $url = $this->apiDomain.'/products/batch';
                //$url = 'http://127.0.0.1:8000/api/products/batch';

                $args = array(
                    'timeout' => 10000,
                    'headers' => array(
                        'Authorization' => "Bearer MTIzNA==",
                        'Content-Type' => 'application/json',
                        'AccessToken' => $this->generateAccessToken($this->get_tvc_access_token(), $this->get_tvc_refresh_token())
                    ),
                    'body' => wp_json_encode($data)
                );

                // Send remote request
                //echo "<pre>";
                //print_r($args); 
                $request = wp_remote_post($url, $args);

                //print_r($request); 
                 
                // Retrieve information
                $response_code = wp_remote_retrieve_response_code($request);
                $response_message = wp_remote_retrieve_response_message($request);
                $response_body = json_decode(wp_remote_retrieve_body($request));
                //print_r($response_body);
                //die;
                if((isset($response_body->error) && $response_body->error == '')){
                    echo json_encode(['status' => 'success', 'skipProducts' => count($skipProducts)]);
                }else{
                    foreach($response_body->errors as $err){
                        $message = $err;
                        break;
                    }
                    echo json_encode(['status' => 'error', 'message' => $message]);
                }

                //   echo json_encode( $categories );
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        /**
         * create product batch for product sync up
         */
        public function tvcajax_custom_metrics_dimension(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'customMetricsDimensionNonce'), 'tvcajax-custom-metrics-dimension-nonce')){

                if(!class_exists('CustomApi')){
                    include(ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php');
                }
                $customObj = new CustomApi();

                $accountId = filter_input(INPUT_POST, 'accountId');
//                $accountId = '184918792';
                $webPropertyId = filter_input(INPUT_POST, 'webPropertyId');
//                $webPropertyId = 'UA-184918792-5';
                $subscriptionId = filter_input(INPUT_POST, 'subscriptionId');
                $data = filter_input(INPUT_POST, 'data');
                parse_str($data, $formArray);
                //print_r($formArray); // Only for print array

                $customDimension = [];
                $customMetrics = [];
                $dimensions = [];
                $metrics = [];

                for($i = 1; $i <= 12; $i++){
                    $dimension['id'] = "";
                    $dimension['index'] = $formArray['did-' . $i];
                    $dimension['active'] = true;
                    $dimension['kind'] = "";
                    $dimension['name'] = $formArray['dname-' . $i];
                    $dimension['scope'] = $formArray['dscope-' . $i];
                    $dimension['created'] = "";
                    $dimension['updated'] = "";
                    $dimension['self_link'] = "";
                    $dimension['parent_link']['href'] = "";
                    $dimension['parent_link']['parent_link_type'] = "";
                    $dimensions[] = $dimension;
                }

                for($i = 1; $i <= 7; $i++){
                    $metric['id'] = "";
                    $metric['index'] = $formArray['mid-' . $i];
                    $metric['active'] = true;
                    $metric['kind'] = "";
                    $metric['name'] = $formArray['mname-' . $i];
                    $metric['scope'] = $formArray['mscope-' . $i];
                    $metric['created'] = "";
                    $metric['updated'] = "";
                    $metric['self_link'] = "";
                    $metric['max_value'] = "";
                    $metric['min_value'] = "";
                    $metric['type'] = "INTEGER";
                    $metric['parent_link']['href'] = "";
                    $metric['parent_link']['parent_link_type'] = "";
                    $metrics[] = $metric;
                }

                if(!empty($dimensions)){
                    $dimenRequest = [];
                    $dimenRequest['account_id'] = $accountId;
                    $dimenRequest['web_property_id'] = $webPropertyId;
                    $dimenRequest['subscription_id'] = $subscriptionId;
                    $dimenRequest['data'] = $dimensions;
                    $dimenResponse = $customObj->createCustomDimensions($dimenRequest);
                }
                if(!empty($metrics)){
                    $metrRequest = [];
                    $metrRequest['account_id'] = $accountId;
                    $metrRequest['web_property_id'] = $webPropertyId;
                    $metrRequest['subscription_id'] = $subscriptionId;
                    $metrRequest['data'] = $metrics;
                    $metrResponse = $customObj->createCustomMetrics($metrRequest);
                }


                // Retrieve information
                /* $response_code = wp_remote_retrieve_response_code($request);
                  $response_message = wp_remote_retrieve_response_message($request);
                  $response_body = json_decode(wp_remote_retrieve_body($request)); */

//                 print_r($dimenResponse);
//                 echo "=======";
//                 print_r($metrResponse);
//                 exit;


                if((isset($dimenResponse->error) && $dimenResponse->error == '' && isset($metrResponse->error) && $metrResponse->error == '')){
                    echo json_encode(['status' => 'success']);
//                    return new WP_REST_Response(
//                        array(
//                            'status' => $response_code,
//                            'message' => $response_message,
//                            'data' => $response_body->data
//                        )
//                    );
                }else{
                    $metrError = '';
                    $dimenError = '';
                    $message = NULL;
                    if($dimenResponse->errors){
                        /* print_r($dimenResponse->errors); */
                        $dimenError = $dimenResponse->errors[0];
                        $message = str_replace('this entity', 'dimensions ', $dimenError);
                    }
                    if($metrResponse->errors){
                        /* print_r($metrResponse->errors); */
                        $metrError = str_replace('this entity', 'metrics ', $metrResponse->errors[0]);
                        $message = is_null($message) ? $metrError : $message . ' ' . $metrError;
                    }
                    echo json_encode(['status' => 'error', 'message' => $message]);
                }
            }

            // IMPORTANT: don't forget to exit
            exit;
        }

        public function tvcajax_store_time_taken(){
            // make sure this call is legal
            if($this->safe_ajax_call(filter_input(INPUT_POST, 'campaignCategoryListsNonce'), 'tvcajax-store-time-taken-nonce')){
                $ee_options_data = unserialize(get_option('ee_options'));
                if(isset($ee_options_data['subscription_id'])) {
                    $ee_subscription_id = $ee_options_data['subscription_id'];
                } else {
                    $ee_subscription_id = null;
                }
                $url = $this->apiDomain.'/customer-subscriptions/update-setup-time';
                $data = [
                    'subscription_id' => $ee_subscription_id,
                    'setup_start_time' => date('Y-m-d H:i:s'),
                ];
                $args = array(
                    'headers' => array(
                        'Authorization' => "Bearer MTIzNA==",
                        'Content-Type' => 'application/json'
                    ),
                    'body' => wp_json_encode($data)
                );
                // Send remote request
                $request = wp_remote_post($url, $args);
                // Retrieve information
                $response_code = wp_remote_retrieve_response_code($request);
                $response_message = wp_remote_retrieve_response_message($request);
                $response_body = json_decode(wp_remote_retrieve_body($request));
                if((isset($response_body->error) && $response_body->error == '')){
                    echo json_encode($response_body->data);
//                    return new WP_REST_Response(
//                        array(
//                            'status' => $response_code,
//                            'message' => $response_message,
//                            'data' => $response_body->data
//                        )
//                    );
                }else{
                    echo json_encode([]);
                    // return new WP_Error($response_code, $response_message, $response_body);
                }

                //   echo json_encode( $categories );
            }

            // IMPORTANT: don't forget to exit
            exit;
        }
        public function generateAccessToken($access_token, $refresh_token) {
            $request = "https://www.googleapis.com/oauth2/v1/tokeninfo?"
                    . "access_token=" . $access_token;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $response = curl_exec($ch);
            $result = json_decode($response);

            if (isset($result->error) && $result->error) {
                $credentials_file = ENHANCAD_PLUGIN_DIR . 'includes/setup/json/client-secrets.json';
                $str = file_get_contents($credentials_file);
                $credentials = $str ? json_decode($str, true) : [];
                $url = 'https://www.googleapis.com/oauth2/v4/token';
                $header = array("content-type: application/json");
                $clientId = $credentials['web']['client_id'];
                $clientSecret = $credentials['web']['client_secret'];
                $refreshToken = $refresh_token;
                $data = [
                    "grant_type" => 'refresh_token',
                    "client_id" => $clientId,
                    'client_secret' => $clientSecret,
                    'refresh_token' => $refreshToken,
                ];

                $postData = json_encode($data);
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $url, //esc_url($this->curl_url),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_HTTPHEADER => $header,
                    CURLOPT_POSTFIELDS => $postData
                ));
                $response = curl_exec($ch);
                $response = json_decode($response);
                return $response->access_token;
            } else {
                return $access_token;
            }
        }
        public function woo_country(){
            // The country/state
            $store_raw_country = get_option('woocommerce_default_country');
            // Split the country/state
            $split_country = explode(":", $store_raw_country);
            return $split_country;
        }

    }

    // End of TVC_Ajax_File_Class

endif;

$tvcajax_file_class = new TVC_Ajax_File();
