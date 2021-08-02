<?php

class ShoppingApi {

    private $customerId;
    private $merchantId;
    private $apiDomain;
    private $token;
    protected $TVC_Admin_Helper;

    public function __construct() {
        $this->TVC_Admin_Helper = new TVC_Admin_Helper();
        $this->customApiObj = new CustomApi();
        //$queries = new TVC_Queries();
        $this->apiDomain = TVC_API_CALL_URL;
        //$this->apiDomain = 'http://127.0.0.1:8000/api';
        $this->token = 'MTIzNA==';
        $this->merchantId = $this->TVC_Admin_Helper->get_merchantId();
        $this->customerId = $this->TVC_Admin_Helper->get_currentCustomerId();
    }

    public function getCampaigns() {
        try {
            $url = $this->apiDomain . '/campaigns/list';

            $data = [
                'merchant_id' => $this->merchantId,
                'customer_id' => $this->customerId
            ];
            $args = array(
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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

            if ((isset($response_body->error) && $response_body->error == '')) {

                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => $response_message,
                    'data' => $response_body->data
                        )
                );
            } else {
                return new WP_Error($response_code, $response_message, $response_body);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCategories($country_code) {
        try {
            $url = $this->apiDomain . '/products/categories';

            $data = [
                'customer_id' => $this->customerId,
                'country_code' => $country_code
            ];

            $args = array(
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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

            if ((isset($response_body->error) && $response_body->error == '')) {

                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => $response_message,
                    'data' => $response_body->data
                        )
                );
            } else {
                return new WP_Error($response_code, $response_message, $response_body);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function accountPerformance($date_range_type, $days = 0, $from_date = '', $to_date = '') {
        try {
            $days_diff = 0;
            if ($date_range_type == 2) {
                $days_diff = strtotime($to_date) - strtotime($from_date);
                $days_diff = abs(round($days_diff / 86400));
            }

            $url = $this->apiDomain . '/reports/account-performance';
            $data = [
                'customer_id' => $this->customerId,
                'graph_type' => ($date_range_type == 2 && $days_diff > 31) ? 'month' : 'day',
                'date_range_type' => $date_range_type,
                'days' => $days,
                'from_date' => $from_date,
                'to_date' => $to_date
            ];

            $args = array(
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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
            if (!is_wp_error($request) && (isset($response_body->error) && $response_body->error == '')) {
                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => $response_message,
                    'data' => $response_body->data
                        )
                );
            } else {
                return new WP_Error($response_code, $response_message, $response_body);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function campaignPerformance($date_range_type, $days = 0, $from_date = '', $to_date = '') {
        try {
            $url = $this->apiDomain . '/reports/campaign-performance';
            $days_diff = 0;
            if ($date_range_type == 2) {
                $days_diff = strtotime($to_date) - strtotime($from_date);
                $days_diff = abs(round($days_diff / 86400));
            }
            $data = [
                'customer_id' => $this->customerId,
                'graph_type' => ($date_range_type == 2 && $days_diff > 31) ? 'month' : 'day',
                'date_range_type' => $date_range_type,
                'days' => $days,
                'from_date' => $from_date,
                'to_date' => $to_date
            ];

            $args = array(
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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

            if (!is_wp_error($request) && (isset($response_body->error) && $response_body->error == '')) {
                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => $response_message,
                    'data' => $response_body->data
                        )
                );
            } else {
                return new WP_Error($response_code, $response_message, $response_body);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function productPerformance($campaign_id = '', $date_range_type='', $days = 0, $from_date = '', $to_date = '') {
        try {
            $url = $this->apiDomain . '/reports/product-performance';

            $data = [
                'merchant_id' => $this->merchantId,
                'customer_id' => $this->customerId,
                'campaign_id' => $campaign_id,
                'date_range_type' => $date_range_type,
                'days' => $days,
                'from_date' => $from_date,
                'to_date' => $to_date
            ];

            $args = array(
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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

            if (!is_wp_error($request) && (isset($response_body->error) && $response_body->error == '')) {
                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => $response_message,
                    'data' => $response_body->data
                        )
                );
            } else {
                return new WP_Error($response_code, $response_message, $response_body);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function productPartitionPerformance($campaign_id = '', $date_range_type='', $days = 0, $from_date = '', $to_date = '') {
        try {
            $url = $this->apiDomain . '/reports/product-partition-performance';

            $data = [
                'merchant_id' => $this->merchantId,
                'customer_id' => $this->customerId,
                'campaign_id' => $campaign_id,
                'date_range_type' => $date_range_type,
                'days' => $days,
                'from_date' => $from_date,
                'to_date' => $to_date
            ];

            $args = array(
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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

            if (!is_wp_error($request) && (isset($response_body->error) && $response_body->error == '')) {
                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => $response_message,
                    'data' => $response_body->data
                        )
                );
            } else {
                return new WP_Error($response_code, $response_message, $response_body);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getCampaignDetails($campaign_id = '') {
        try {
            $url = $this->apiDomain . '/campaigns/detail';

            $data = [
                'merchant_id' => $this->merchantId,
                'customer_id' => $this->customerId,
                'campaign_id' => $campaign_id
            ];

            $args = array(
                'headers' => array(
                    'Authorization' => "Bearer $this->token",
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
            if (!is_wp_error($request) && (isset($response_body->error) && $response_body->error == '')) {
                $response_body->data->category_id = (isset($response_body->data->category_id)) ? $response_body->data->category_id : '0';
                $response_body->data->category_level = (isset($response_body->data->category_level)) ? $response_body->data->category_level : '0';
                return new WP_REST_Response(
                        array(
                    'status' => $response_code,
                    'message' => $response_message,
                    'data' => $response_body->data
                        )
                );
            } else {
                return new WP_Error($response_code, $response_message, $response_body);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function createCampaign($campaign_name = '', $budget = 0, $target_country = 'US', $all_products = 0, $category_id = '', $category_level = '') {
        try {
            $header = array(
                "Authorization: Bearer MTIzNA==",
                "content-type: application/json"
            );
            $curl_url = $this->apiDomain . "/campaigns/create";  
            $data = [
                'merchant_id' => $this->merchantId,
                'customer_id' => $this->customerId,
                'campaign_name' => $campaign_name,
                'budget' => $budget,
                'target_country' => $target_country,
                'all_products' => $all_products,
                'filter_by' => 'category',
                'filter_data' => ["id" => $category_id, "level" => $category_level]
            ];          
            $postData = json_encode($data);           
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => esc_url($curl_url),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 1000,
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POSTFIELDS => $postData
            ));
            $response = curl_exec($ch);
            $response = json_decode($response);
            $return = new \stdClass();
            if (isset($response->error) && $response->error == false) {
                $return->error = false;
                $return->message = $response->message; 
                $return->data = $response->data;
                return $return;
            } else {                
                $return->error = true;
                $return->errors = $response->errors;            
                return $return;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateCampaign($campaign_name = '', $budget = 0, $campaign_id = '', $budget_id='', $target_country = '', $all_products = 0, $category_id = '', $category_level = '', $ad_group_id = '', $ad_group_resource_name = '') {
        try {
            $header = array(
                "Authorization: Bearer MTIzNA==",
                "content-type: application/json"
            );
            $curl_url = $this->apiDomain . '/campaigns/update';
            $data = [
                'merchant_id' => $this->merchantId,
                'customer_id' => $this->customerId,
                'campaign_id' => $campaign_id,
                'account_budget_id' => $budget_id,
                'campaign_name' => $campaign_name,
                'target_country' => $target_country,
                'budget' => $budget,
                'status' => 2, // ENABLE => 2, PAUSED => 3, REMOVED => 4
                'all_products' => $all_products,
                'ad_group_id' => $ad_group_id,
                'ad_group_resource_name' => $ad_group_resource_name,
                'filter_by' => 'category',
                'filter_data' => ["id" => $category_id, "level" => $category_level]
            ];        
            $postData = json_encode($data);           
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => esc_url($curl_url),
                CURLOPT_CUSTOMREQUEST => 'PATCH',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 1000,
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POSTFIELDS => $postData
            ));
            $response = curl_exec($ch);
            $response = json_decode($response);
            
            $return = new \stdClass();
            if (isset($response->error) && $response->error == false) {
                $return->error = false;
                $return->message = $response->message; 
                $return->data = $response->data;
                return $return;
            } else {                
                $return->error = true;
                $return->errors = $response->errors;            
                return $return;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}