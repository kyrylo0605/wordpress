<?php

/**
 * TVC List Table Class.
 *
 * @package TVC Product Feed Manager/User Interface/Classes
 * @version 1.4.0
 */
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('TVC_List_Table')) :

    /**
     * List Table Class
     */
    class TVC_List_Table {

        private $_column_titles = array();
        private $_table_id;
        private $_table_id_string;
        private $_table_list;
        private $queries;
        private $curl_url;
        private $returnUrl;
        private $theURL;
        private $merchantID;
        private $merchant_feeds = array();

        public function __construct() {

            $this->queries = new TVC_Queries();
            $this->_table_id = '';
            $this->_table_id_string = '';
            //$this->_table_list      = $this->queries->get_feeds_list();
            $this->merchantID = $this->queries->get_set_merchant_id();
            $this->_table_feeds = $this->queries->get_feeds_list();
            $this->merchant_feeds = $this->tvc_merchant_fetch_all_feeds();
            $this->_table_list = $this->merge_all_feeds();
            $this->curl_url = "https://www.googleapis.com/content/v2/accounts/authinfo?scope=https://www.googleapis.com/auth/content";

            $this->returnUrl = $_SERVER['REQUEST_URI'];
            $this->theURL = "http://plugins.tatvic.com/tat_ga/ga_rdr_gmc.php?return_url=" . $_SERVER['HTTP_HOST'] . $this->returnUrl;

            add_option('wp_enqueue_scripts', TVC_i18n_Scripts::tvc_feed_settings_i18n());
            add_option('wp_enqueue_scripts', TVC_i18n_Scripts::tvc_list_table_i18n());
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

        /**
         * Sets the column titles
         *
         * @param array of strings containing the column titles
         */
        public function set_column_titles($titles) {
            if (!empty($titles)) {
                $this->_column_titles = $titles;
            }
        }

        public function set_table_id($id) {
            if ($id !== $this->_table_id) {
                $this->_table_id = $id;
                $this->_table_id_string = ' id="' . $id . '"';
            }
        }

        public function display() {

            if (isset($_GET['access_token'])) {
                $this->admin_notice_signin_success();
                echo $this->feed_home_page();
            }
            if (isset($_POST['add_conversion_tag'])) {
                echo "<script>parent.location='admin.php?page=tvc-configuration-page'</script>";
            }
            if (isset($_POST['create_campaign'])) {
                $campaign_name = $_POST['campaign_name'];
                $campaign_budget = $_POST['campaign_budget'];

                include(ENHANCAD_PLUGIN_DIR . 'includes/setup/SmartShoppingCampaign.php');
                $campaignClsObj = new SmartShoppingCampaign();
                $campaignClsObj->createConversionAction();
                $response = $campaignClsObj->createSmartShoppingCampaign($campaign_name, $campaign_budget);
                if (isset($response['error'])) {
                    $class = 'notice notice-error';
                    $message = esc_html__(isset($response['error']->message) ? $response['error']->message : $response['error'], 'sample-text-domain');
                    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html('Error : ' . $message));
                } else {
                    $class = 'notice notice-success';
                    $message = esc_html__('Smart Shopping Campaign Created Successfully with Resource name ' . $response, 'sample-text-domain');
                    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
                }
            }
            echo '<table class="wp-list-table tablepress widefat fixed posts" id="feedlisttable">';

            echo $this->table_header();

            echo $this->table_body();

            echo $this->table_footer();

            echo '</table>';

            // echo $this->create_campaign_modal();

            echo $this->conversion_modal();
        }

        /**
         * @return array
         * Get woocommerce default set country
         */
        private function woo_country() {
            // The country/state
            $store_raw_country = get_option('woocommerce_default_country');
            // Split the country/state
            $split_country = explode(":", $store_raw_country);
            return $split_country;
        }

        /**
         * @return mixed
         */
        private function get_currency_symbol() {
            $woo_country = $this->woo_country();
            $country = (!empty($woo_country)) ? $woo_country[0] : 'US';
            $getCurrency = file_get_contents(ENHANCAD_PLUGIN_DIR . "includes/setup/json/currency.json");
            $contData = json_decode($getCurrency);
            return get_woocommerce_currency_symbol($contData->{$country});
        }

        private function conversion_modal() {
            $html = '';
            $html .= '<form method="post">
                    <div class="modal fade" id="conversionModal" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close gmc_tandc_close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h2 class="modal-title lead" style="font-weight: 400;">Conversion Tracking Tags</h2>
                                <br>
                                <p><b>Please note: </b>if you already have Google Ads Conversion Actions with <a href="https://support.google.com/google-ads/answer/6095947" target="_blank">transaction-specific conversion values</a> configured on your site for this Google Ads account, then you do not need to set new conversion tags on your site.</p>
                            </div>
                             <div class="form-group text-left">
                                <input class="form-check-input" type="checkbox" name="addConversion"  id="feed-form-checkbox" value="1" checked>&nbsp;&nbsp;
                                <label class="form-check-label ml-5 p-1" for="addConversion">Yes, set new conversion tracking tags on my site</label>
                            </div>
                            <div class="mx-auto p-3">
                             <button type="submit" class="btn btn-primary" id="addConversionTag" name="add_conversion_tag" >Continue</button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>';
            return $html;
        }

        private function create_campaign_modal() {

            $currency = $this->get_currency_symbol();
            $html = '';
            $html .= '<form method="post">
                <div class="modal fade" id="createCampaignModal" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                    
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="close gmc_tandc_close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h2 class="modal-title lead" style="font-weight: 400;">Create a new Smart Shopping Campaign</h2>
                            </div>
                             <div class="form-group row" style="margin-bottom: 0">
                                   <div class="col-md-12 row">
                                        <label for="campaign-name" class="col-md-5 ml-3 mt-2 text-left">Campaign name </label>
                                        <input type="text" class="form-control col-md-6" name="campaign_name" id="campaign-name" required>
                                    </div>
                                     <div class="col-md-12 row mt-2">
                                        <label for="campaign-budget" class="col-md-5 ml-3 mt-2 text-left">Campaign Budget (' . $currency . ') </label>
                                        <input type="text" class="form-control col-md-6" name="campaign_budget" id="campaign-budget" required>
                                    </div>
                                </div>
                          
                            <div class="mx-auto p-3">
                                <button type="submit" class="btn btn-primary" id="create_campaign" name="create_campaign">Save Campaign</button>
                            </div>
                        </div>
                    
                    </div>
                </div>
                </form>';

            return $html;
        }

        private function table_header() {
            $html = '<thead><tr>';

            foreach ($this->_column_titles as $title) {
                $html .= '<th id="tvc-feed-list-table-header-column-' . strtolower($title) . '">' . $title . '</th>';
            }

            $html .= '</tr></thead>';

            return $html;
        }

        private function table_footer() {
            $html = '<tfoot><tr>';

            foreach ($this->_column_titles as $title) {
                $html .= '<th>' . $title . '</th>';
            }

            $html .= '</tr></tfoot>';

            return $html;
        }

        private function getColorByStatus($status = '') {

            if ($status == 'ok' || $status == 'success' || $status == 'none') {
                return '#0073AA';
            } else if ($status == 'on_hold') {
                return '#0173AA';
            } else if ($status == 'processing' || $status == 'in progress') {
                return '#0000FF';
            } else if ($status == 'in_processing_queue') {
                return '#00CCFF';
            } else if ($status == 'has_errors') {
                return '#FF0000';
            } else if ($status == 'failed_processing' || $status == 'failure') {
                return '#FF3300';
            } else {
                return "#6549F7";
            }
        }

        private function table_body() {
            $html = '<tbody' . $this->_table_id_string . '>';
            $alternator = 0;
            $nr_products = '';
            $show_type_column = apply_filters('tvc_special_feeds_add_on_active', false);
            $feed_types = tvc_list_feed_type_text();
//            foreach ($this->merchant_feeds as $merchant_feed) {
//                $html .= '<tr id="tvc-feed-row"';
//                $html .= 0 === ( $alternator % 2 ) ? ' class="tvc-feed-row alternate">' : ' class="tvc-feed-row">';
//                $html .= '<td id="title-' . $merchant_feed->id . '" value="' . $merchant_feed->id . '">' . $merchant_feed->name . '</td>';
//                $html .= '<td id="url">' . ($merchant_feed->fetchSchedule->fetchUrl) . '</td>';
//                $html .= $this->tvc_merchant_feed_api( esc_url($merchant_feed->fetchSchedule->fetchUrl),$merchant_feed->id );
//                $html .= '<td id="updated-' . $merchant_feed->id . '">' .$merchant_feed->id . '</td>';
//                $html .= '<td id="url">' . esc_url($merchant_feed->fetchSchedule->fetchUrl) . '</td>';
//                $html .= '<td id="url">' . esc_url($merchant_feed->id) . '</td>';
//                $html .= '<td id="url">' . esc_url($merchant_feed->id) . '</td>';
//              //  $html .= '<td id="url">' . esc_url($merchant_feed->id) . '</td>';
//                $html .= '</tr>';
//
//                $alternator++;
//            }
            foreach ($this->_table_list as $list_item) {
                $feed_ready_status = ('on_hold' === $list_item->status || 'ok' === strtolower($list_item->status) || 'success' === $list_item->status);
                $feed_name_id = tvc_convert_string_with_spaces_to_lower_case_string_with_dashes($list_item->title);

                if ($feed_ready_status) {
                    $nr_products = $list_item->products;
                } elseif ('processing' === $list_item->status || 'in progress' === $list_item->status) {
                    $nr_products = esc_html__('Processing the feed, please wait...', 'tvc-product-feed-manager');
                } elseif ('failed_processing' === $list_item->status || 'in_processing_queue' === $list_item->status || 'none' === $list_item->status || 'failure' === $list_item->status) {
                    $nr_products = esc_html__('Unknown', 'tvc-product-feed-manager');
                }

                $color = $list_item->type == 'local' ? $list_item->color : $this->getColorByStatus($list_item->status);

                $html .= '<tr id="tvc-feed-row"';
                $html .= 0 === ($alternator % 2) ? ' class="tvc-feed-row alternate">' : ' class="tvc-feed-row">'; // alternate background color per row
                $html .= '<td id="title-' . $list_item->product_feed_id . '" value="' . $feed_name_id . '">' . $list_item->title . '</td>';
                $html .= '<td id="url">' . esc_url($list_item->url) . '</td>';

                $html .= $list_item->type == 'local' ? $this->tvc_merchant_feed_api(esc_url($list_item->url), $list_item->product_feed_id) : '<td></td>';

                $html .= '<td id="updated-' . $list_item->product_feed_id . '">' . $list_item->updated . '</td>';
                $html .= '<td id="products-' . $list_item->product_feed_id . '"><a href="https://merchants.google.com/mc/items?a=' . $this->merchantID . '" target="_blank">' . $nr_products . '</a></td>';
                $html .= $show_type_column ? '<td id="type-' . $list_item->product_feed_id . '">' . $feed_types[$list_item->feed_type_id] . '</td>' : '';
                $html .= '<td id="feed-status-' . $list_item->product_feed_id . '" value="' . $list_item->status . '" style="color:' . $color . '"><strong>';
                $html .= $list_item->type == 'local' ? $this->list_status_text($list_item->status) : $this->list_merchant_feeds_status_text($list_item->status);
                $html .= '</strong></td>';
                $html .= '<td id="actions-' . $list_item->product_feed_id . '">';

                if ($list_item->type == 'local') {
                    if ($feed_ready_status) {
                        $html .= $this->feed_ready_action_links($list_item->product_feed_id, $list_item->url, $list_item->status, $list_item->title, $feed_types[$list_item->feed_type_id]);
                    } else {
                        $html .= $this->feed_not_ready_action_links($list_item->product_feed_id, $list_item->url, $list_item->title, $feed_types[$list_item->feed_type_id]);
                    }
                }


                $html .= '</td>';
                $html .= '</tr>';

                $alternator++;
            }

            $html .= '</tbody>';

            return $html;
        }

        private function merge_all_feeds() {
            $feeds_data = [];
            if (!empty($this->_table_feeds)) {
                $feeds = [];
                $localFeeds = [];
                foreach ($this->_table_feeds as $list_item) {

                    if (!empty($this->merchant_feeds)) {
                        foreach ($this->merchant_feeds as $merchant_feed) {

                            if (isset($list_item->gmc_feed_id) && ($merchant_feed->id == $list_item->gmc_feed_id)) {

                                $localFeeds['id'] = $list_item->product_feed_id;
                                $localFeeds['title'] = $list_item->title;
                                $localFeeds['type'] = 'local';
                                $localFeeds['url'] = $list_item->url;
                                $localFeeds['status'] = $list_item->status;
                                $localFeeds['updated'] = $list_item->updated;
                                $localFeeds['product_feed_id'] = $list_item->product_feed_id;
                                $localFeeds['products'] = $list_item->products;
                                $localFeeds['color'] = $list_item->color;
                                $localFeeds['feed_type_id'] = $list_item->feed_type_id;
                            } else {
                                $feeds['id'] = $merchant_feed->id;
                                $feeds['title'] = $merchant_feed->name;
                                $feeds['type'] = 'merchant';
                                $feeds['url'] = str_replace('drive://', 'https://drive.google.com/', $merchant_feed->fetchSchedule->fetchUrl);
                                $feeds['status'] = $merchant_feed->status;
                                $feeds['updated'] = $merchant_feed->lastUploaded;
                                $feeds['product_feed_id'] = $merchant_feed->id;
                                $feeds['products'] = $merchant_feed->products;
                                $feeds['color'] = '';
                                $feeds['feed_type_id'] = '';

                                $localFeeds['id'] = $list_item->product_feed_id;
                                $localFeeds['title'] = $list_item->title;
                                $localFeeds['type'] = 'local';
                                $localFeeds['url'] = $list_item->url;
                                $localFeeds['status'] = $list_item->status;
                                $localFeeds['updated'] = $list_item->updated;
                                $localFeeds['product_feed_id'] = $list_item->product_feed_id;
                                $localFeeds['products'] = $list_item->products;
                                $localFeeds['color'] = $list_item->color;
                                $localFeeds['feed_type_id'] = $list_item->feed_type_id;
                            }
                        }
                    } else {
                        $localFeeds['id'] = $list_item->product_feed_id;
                        $localFeeds['title'] = $list_item->title;
                        $localFeeds['type'] = 'local';
                        $localFeeds['url'] = $list_item->url;
                        $localFeeds['status'] = $list_item->status;
                        $localFeeds['updated'] = $list_item->updated;
                        $localFeeds['product_feed_id'] = $list_item->product_feed_id;
                        $localFeeds['products'] = $list_item->products;
                        $localFeeds['color'] = $list_item->color;
                        $localFeeds['feed_type_id'] = $list_item->feed_type_id;
                    }
                    if (!empty($localFeeds)) {
                        array_push($feeds_data, (object) $localFeeds);
                    }
                }
                if (!empty($feeds)) {
                    array_push($feeds_data, (object) $feeds);
                }
            } else {
                if (!empty($this->merchant_feeds)) {
                    foreach ($this->merchant_feeds as $merchant_feed) {
                        $feeds = [];
                        $feeds['id'] = $merchant_feed->id;
                        $feeds['title'] = $merchant_feed->name;
                        $feeds['type'] = 'merchant';
                        $feeds['url'] = str_replace('drive://', 'https://drive.google.com/', $merchant_feed->fetchSchedule->fetchUrl);
                        $feeds['status'] = $merchant_feed->status;
                        $feeds['updated'] = $merchant_feed->lastUploaded;
                        $feeds['product_feed_id'] = $merchant_feed->id;
                        $feeds['products'] = $merchant_feed->products;
                        array_push($feeds_data, (object) $feeds);
                    }
                }
            }

            return $feeds_data;
        }

        private function list_status_text($status) {

            switch ($status) {
                case 'OK': // sometimes the status is stored in capital letters
                case 'ok':
                    return esc_html__('Ready (auto)', 'tvc-product-feed-manager');

                case 'on_hold':
                    return esc_html__('Ready (manual)', 'tvc-product-feed-manager');

                case 'processing':
                    return esc_html__('Processing', 'tvc-product-feed-manager');

                case 'in_processing_queue':
                    return esc_html__('In processing queue', 'tvc-product-feed-manager');

                case 'has_errors':
                    return esc_html__('Has errors', 'tvc-product-feed-manager');

                case 'failed_processing':
                    return esc_html__('Failed processing', 'tvc-product-feed-manager');

                default:
                    return esc_html__('Unknown', 'tvc-product-feed-manager');
            }
        }

        /**
         * Generates the code for the Action buttons used in the feed list row where the feed is in ready mode.
         * This function is the PHP equal for the feedReadyActions() function in the tvc_feed-list.js file.
         *
         * @param string $feed_id
         * @param string $feed_url
         * @param string $status
         * @param string $title
         * @param string $feed_type
         *
         * @return string with the html code
         */
        private function feed_ready_action_links($feed_id, $feed_url, $status, $title, $feed_type) {
            $file_exists = 'No feed generated' !== $feed_url;
            $url_strings = explode('/', $feed_url);
            $file_name = stripos($feed_url, '/') ? end($url_strings) : $title;
            $change_status = 'ok' === strtolower($status) ? esc_html__('Auto-off', 'tvc-product-feed-manager') : esc_html__('Auto-on', 'tvc-product-feed-manager');
            $feed_tab_link = tvc_convert_string_with_spaces_to_lower_case_string_with_dashes($feed_type);
            $action_id = tvc_convert_string_with_spaces_to_lower_case_string_with_dashes($title);

            $html = '<strong><a href="javascript:void(0);" id="tvc-edit-' . $action_id . '-action" onclick="parent.location=\'admin.php?page=tvc-product-feed-manager&tab=' . $feed_tab_link . '&id=' . $feed_id . '\'">' . esc_html__('Edit', 'tvc-product-feed-manager') . '</a>';
            $html .= $file_exists ? ' | <a href="javascript:void(0);" id="tvc-view-' . $action_id . '-action" onclick="tvc_viewFeed(\'' . $feed_url . '\')">' . esc_html__('View', 'tvc-product-feed-manager') . '</a>' : '';
            $html .= ' | <a href="javascript:void(0);" id="tvc-delete-' . $action_id . '-action" onclick="tvc_deleteSpecificFeed(' . $feed_id . ', \'' . $file_name . '\')">' . esc_html__('Delete', 'tvc-product-feed-manager') . '</a>';
            $html .= $file_exists ? '<a href="javascript:void(0);" id="tvc-deactivate-' . $action_id . '-action" onclick="tvc_deactivateFeed(' . $feed_id . ')" id="feed-status-switch-' . $feed_id . '"> | ' . $change_status . '</a>' : '';
            $html .= ' | <a href="javascript:void(0);" id="tvc-duplicate-' . $action_id . '-action" onclick="tvc_duplicateFeed(' . $feed_id . ', \'' . $title . '\')">' . esc_html__('Clone Feed', 'tvc-product-feed-manager') . '</a>';
            $html .= 'Product Feed' === $feed_type ? ' | <a href="javascript:void(0);" id="tvc-regenerate-' . $action_id . '-action" onclick="tvc_regenerateFeed(' . $feed_id . ')">' . esc_html__('Update Feed', 'tvc-product-feed-manager') . '</a></strong>' : '';
            return $html;
        }

        /**
         * Generates the code for the Action buttons used in the feed list row where the feed is in processing or error mode.
         * This function is the PHP equal for the feedNotReadyActions() function in the tvc_feed-list.js file.
         *
         * @param string $feed_id
         * @param string $feed_url
         * @param string $title
         * @param string $feed_type
         *
         * @return string with the html code
         */
        private function feed_not_ready_action_links($feed_id, $feed_url, $title, $feed_type) {
            if (stripos($feed_url, '/')) {
                $url_array = explode('/', $feed_url);
                $file_name = end($url_array);
            } else {
                $file_name = $title;
            }

            $feed_tab_link = tvc_convert_string_with_spaces_to_lower_case_string_with_dashes($feed_type);
            $action_id = tvc_convert_string_with_spaces_to_lower_case_string_with_dashes($title);

            $html = '<strong><a href="javascript:void(0);" id="tvc-edit-' . $action_id . '-action" onclick="parent.location=\'admin.php?page=tvc-product-feed-manager&tab=' . $feed_tab_link . '&id=' . $feed_id . '\'">' . esc_html__('Edit', 'tvc-product-feed-manager') . '</a>';
            $html .= ' | <a href="javascript:void(0);" id="tvc-delete-' . $action_id . '-action" onclick="tvc_deleteSpecificFeed(' . $feed_id . ', \'' . $file_name . '\')">' . esc_html__('Delete', 'tvc-product-feed-manager') . '</a>';
            $html .= 'Product Feed' === $feed_type ? ' | <a href="javascript:void(0);" id="tvc-regenerate-' . $action_id . '-action" onclick="tvc_regenerateFeed(' . $feed_id . ')">' . esc_html__('Update Feed', 'tvc-product-feed-manager') . '</a></strong>' : '';
            $html .= $this->feed_status_checker_script($feed_id);
            return $html;
        }

        private function tvc_merchant_feed_api($feed_url, $feed_id) {

            if (!isset($_SESSION['access_token']) && isset($_GET['access_token'])) {
                $this->admin_notice_signin_success();
                setcookie('access_token', $_GET['access_token']);
            }
            $this->api_data($feed_url, $feed_id);
            $gmcID = $this->queries->getGmcFeedId($feed_id);
            if ($gmcID[0]->gmc_feed_id != 0) {
                return '<td><strong><a href="javascript:void(0);" name="submit" onclick="parent.location=\'admin.php?page=tvc-product-feed-manager&tab=feed-list&url=' . $feed_url . '&id=' . $feed_id . '\'">' . esc_html__('Update Feed ' . $gmcID[0]->gmc_feed_id, 'tvc-product-feed-manager') . '</a></strong></td>';
            } else {
                return '<td><strong><a href="javascript:void(0);" name="submit" onclick="parent.location=\'admin.php?page=tvc-product-feed-manager&tab=feed-list&url=' . $feed_url . '&id=' . $feed_id . '\'">' . esc_html__('Push Feed', 'tvc-product-feed-manager') . '</a></strong></td>';
            }
        }

        public function tvc_merchant_fetch_all_feeds() {
            $access_token = $this->generateAccessToken($_SESSION['access_token'], $_SESSION['refresh_token']);
            if (!empty($access_token)) {

                try {
                    $header = array("Authorization: Bearer " . $access_token, "content-type: application/json");
                    $ch = curl_init();
                    $fetchFeedsURL = esc_url("https://www.googleapis.com/content/v2.1/" . $this->merchantID . "/datafeeds");
                    curl_setopt_array($ch, array(
                        CURLOPT_URL => $fetchFeedsURL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_TIMEOUT => 20,
                        CURLOPT_HTTPHEADER => $header
                    ));
                    $response = json_decode(curl_exec($ch));
                    if (isset($response->error) && $response->error->code == '401') {
                        /*  $message = $response->error->message;
                          $reason = $response->error->errors[0]->reason;
                          $this->admin_notice_response_error($message, $reason);
                          include(ENHANCAD_PLUGIN_DIR . 'includes/setup/tvc-configuration-page.php');
                          new Configuration();
                          exit(1); */
                        return [];
                    } else {
                        if (!empty($response->resources) && $response->kind == "content#datafeedsListResponse") {
                            $statuses = $this->tvc_merchant_fetch_feed_status();

                            if ($statuses && count($statuses) > 0) {
                                foreach ($response->resources as $key => &$resource) {
                                    if ($resource->id == $statuses[$key]->datafeedId) {
                                        $resource->status = $statuses[$key]->processingStatus;
                                        $resource->products = isset($statuses[$key]->itemsValid) ? $statuses[$key]->itemsValid : '';
                                        $resource->lastUploaded = isset($statuses[$key]->lastUploadDate) ? $statuses[$key]->lastUploadDate : '';
                                    }
                                }
                            }
                            //  print_r($response->resources);
                            return $response->resources;
                        }
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                    return [];
                }
            } else {
                return [];
            }
        }

        public function tvc_merchant_fetch_feed_status() {
            $access_token = $this->generateAccessToken($_SESSION['access_token'], $_SESSION['refresh_token']);
            if (!empty($access_token)) {

                try {
                    $header = array("Authorization: Bearer " . $access_token, "content-type: application/json");
                    $ch = curl_init();
                    $fetchFeedsURL = esc_url("https://www.googleapis.com/content/v2.1/" . $this->merchantID . "/datafeedstatuses");
                    curl_setopt_array($ch, array(
                        CURLOPT_URL => $fetchFeedsURL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_TIMEOUT => 20,
                        CURLOPT_HTTPHEADER => $header
                    ));
                    $response = json_decode(curl_exec($ch));
                    if (isset($response->error) && $response->error->code == '401') {
                        $message = $response->error->message;
                        $reason = $response->error->errors[0]->reason;
                        $this->admin_notice_response_error($message, $reason);
                        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/tvc-configuration-page.php');
                        new Configuration();
                        exit(1);
                    } else {
                        if (!empty($response->resources) && $response->kind == "content#datafeedstatusesListResponse") {
                            return $response->resources;
                        }
                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                    return [];
                }
            }
        }

        private function list_merchant_feeds_status_text($status) {
            switch ($status) {
                case 'failure':
                    return esc_html__('Failed processing', 'tvc-product-feed-manager');

                case 'in progress':
                    return esc_html__('Processing', 'tvc-product-feed-manager');

                case 'none':
                    return esc_html__('Ready (manual)', 'tvc-product-feed-manager');

                case 'success':
                    return esc_html__('Ready (auto)', 'tvc-product-feed-manager');

                default:
                    return esc_html__('Unknown', 'tvc-product-feed-manager');
            }
        }

        public function api_data($feed_url, $feed_id) {

            $feedUrl = isset($_GET['url']) ? $_GET['url'] : '';

            if ($feedUrl == $feed_url) {
                if ($feed_id != '') {
                    $countryID = $this->queries->getCountryID($feed_id);
                    $countryCode = $this->queries->getCountryCode($countryID);
                    $scheduleTime = $this->queries->getScheduleTime($feed_id);
                    $feedData = $this->queries->getFeedData($feed_id);
                    $time = $scheduleTime[0]->schedule;
                    $Time = explode(":", $time);
                    $dayofweek = strtolower(jddayofweek($Time[0], 1));
                    $items = array();
                    $gmcFeedId = $this->queries->getGmcFeedId($feed_id);
                    if (!empty($this->merchantID)) {
                        $timeZone = get_option('timezone_string');
                        $hour = intval($Time[1]);
                        $gmcid = intval(trim($gmcFeedId[0]->gmc_feed_id));
                        if ($gmcid != 0) {
                            $items["id"] = $gmcid;
                        }
                        $items["contentType"] = "products";
                        $items["fileName"] = isset($feedData[0]->title) ? $feedData[0]->title : 'Unname File';
                        $items["name"] = isset($feedData[0]->feed_title) ? $feedData[0]->feed_title : 'Unname Feed';
                        $items["fetchSchedule"] = array(
                            "weekday" => isset($dayofweek) ? $dayofweek : "sunday",
                            "hour" => isset($hour) ? $hour : 0,
                            "timeZone" => trim($timeZone),
                            "fetchUrl" => trim($feedUrl),
                            "paused" => false
                        );
                        $items["kind"] = "content#datafeed";
                        $items["format"]["quotingMode"] = "value quoting";
                        $items["targets"] = array([
                                "country" => $countryCode[0]->name_short,
                                "language" => "en",
                                "includedDestinations" => array(
                                    "SurfacesAcrossGoogle"
                                )
                            ]
                        );
                        $pushData = json_encode($items);
                        $access_token = $this->generateAccessToken($_SESSION['access_token'], $_SESSION['refresh_token']);
                        if (!empty($access_token)) {
                            $header = array("Authorization: Bearer " . $access_token, "content-type: application/json");
                            $ch = curl_init();
                            if ($gmcid != 0) {
                                $insertURL = esc_url("https://www.googleapis.com/content/v2.1/" . $this->merchantID . "/datafeeds/" . $gmcid);

                                curl_setopt_array($ch, array(
                                    CURLOPT_URL => $insertURL,
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_TIMEOUT => 20,
                                    CURLOPT_HTTPHEADER => $header,
                                    CURLOPT_PUT => $pushData
                                ));
                            } else {
                                $insertURL = esc_url("https://www.googleapis.com/content/v2.1/" . $this->merchantID . "/datafeeds");
                                curl_setopt_array($ch, array(
                                    CURLOPT_URL => $insertURL,
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_TIMEOUT => 20,
                                    CURLOPT_HTTPHEADER => $header,
                                    CURLOPT_POSTFIELDS => $pushData
                                ));
                            }

                            try {
                                $response = json_decode(curl_exec($ch));

                                $feedID = trim($response->id);
                                $feedName = !empty($response->name) ? $response->name : '';
                                if ($feedID != '') {
                                    $this->queries->insertGmcFeedId($feedID, $feed_id);
                                }
                                if (isset($response->error) && $response->error->code == '400' || $response->error->code == '500' || $response->error->code == '409' || $response->error->code == '404') {
                                    $message = $response->error->message;
                                    $reason = $response->error->errors[0]->reason;
                                    $this->admin_notice_response_error($message, $reason);
                                } else {
                                    if ($_GET['status'] == 'update' && $_GET['tab'] == 'feed-list') {
                                        $this->api_update__success($feedName, $feedID);
                                        echo $this->product_feed_redirect_url($feedID);
                                    }
                                    if ($_GET['tab'] == 'feed-list' && !isset($_GET['status'])) {
                                        $this->admin_notice__success($feedName, $feedID);
                                        echo $this->feed_list_redirect_url();
                                    }
                                    if ($_GET['tab'] == 'product-feed' && $_GET['id'] == $feed_id && !isset($_GET['status'])) {
                                        $this->admin_notice__success($feedName, $feedID);
                                        echo $this->product_feed_redirect_url($feedID);
                                    }
                                }
                            } catch (Exception $e) {
                                echo $e->getMessage();
                            }
                        } else {

                            echo "<script>parent.location='admin.php?page=tvc-configuration-page'</script>";
                        }
                    }
                }
            }
        }

        public static function feed_list_redirect_url() {
            return "<script>parent.location='admin.php?page=tvc-product-feed-manager&tab=feed-list'</script>";
        }

        public static function product_feed_redirect_url($feedID) {
            return "<script>parent.location='admin.php?page=tvc-product-feed-manager&tab=product-feed&id='.$feedID</script>";
        }

        public static function feed_home_page() {
            return "<script>parent.location='admin.php?page=tvc-product-feed-manager'</script>";
        }

        private static function admin_notice__success($feedName, $feedID) {
            $class = 'notice notice-success';
            $message = esc_html__('Products feed name ' . ucwords($feedName) . ' Successfully Generated and Feed ID is ' . $feedID, 'sample-text-domain');
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        }

        private static function admin_notice_signin_success() {
            $class = 'notice notice-success';
            $message = esc_html__('You are successfully logged in', 'sample-text-domain');
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        }

        private static function admin_notice_signin_required() {
            $class = 'notice notice-error';
            $message = esc_html__('Google Sign In required for API Update!', 'sample-text-domain');
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        }

        private static function api_update__success($feedName, $feedID) {
            $class = 'notice notice-success';
            $message = esc_html__('Products feed name ' . ucwords($feedName) . ' Successfully Update', 'sample-text-domain');
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        }

        private static function api_item_update_error($errMessage) {
            $class = 'notice notice-error';
            $message = esc_html__($errMessage, 'sample-text-domain');
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        }

        private static function admin_notice_response_error($message, $reason) {
            $class = 'notice notice-error';
            $message = esc_html__($message, 'sample-text-domain');
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html('Reason : ' . $reason . ' ' . $message));
        }

        private function tvc_authinfo_api() {
            $access_token = $this->generateAccessToken($_SESSION['access_token'], $_SESSION['refresh_token']);
            if (!empty($access_token)) {
                $header = array("Authorization: Bearer " . $access_token, "content-type: application/json");
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => esc_url($this->curl_url),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 20,
                    CURLOPT_HTTPHEADER => $header,
                ));
                $response = curl_exec($ch);
                return json_decode($response);
            }
        }

        private function tvc_google_auth() {

            return '<td>
                        <a class="oauth-container btn darken-4 white black-text" href="' . esc_url($this->theURL) . '" style="text-transform:none">
                            <div class="left">
                                <img width="20px" style="margin-top:7px; margin-right:8px" alt="Google sign-in" 
                                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />
                            </div>
                            Sign In
                        </a>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
		    </td>';
        }

        /**
         * Returns a script that is placed on rows of feeds that are still processing or waiting in the queue. This script then runs every 10 seconds and checks the status
         * of that specific feed generation processes. It is responsible for showing the correct status of this feed in the feed list.
         *
         * @param   string  $feed_id
         *
         * @return  string  script to be placed on the feed list page on the row of a running or waiting feed.
         */
        private function feed_status_checker_script($feed_id) {
            return '<script type="text/javascript">
            var tvcStatusCheck_' . $feed_id . ' = null;
				(function(){ 
				tvcStatusCheck_' . $feed_id . ' = window.setInterval(tvc_checkAndSetStatus_' . $feed_id . ', 10000, ' . $feed_id . ' ); 
				})();
				function tvc_checkAndSetStatus_' . $feed_id . '( feedId ) {
				  tvc_getCurrentFeedStatus( feedId, function( result ) {
				    var data = JSON.parse( result );
				    tvc_resetFeedStatus( data );
				    if( data["status_id"] !== "3" && data["status_id"] !== "4" ) {
				      window.clearInterval( tvcStatusCheck_' . $feed_id . ' );
	  				  tvcRemoveFromQueueString( feedId );
				    }
				  } );
				}
				</script>';
        }

    }

    

    

    
    // end of TVC_List_Table class
endif;
