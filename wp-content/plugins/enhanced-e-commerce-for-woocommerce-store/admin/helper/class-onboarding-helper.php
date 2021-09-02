<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * Woo Order Reports
 */

if(!defined('ABSPATH')){
	exit; // Exit if accessed directly
}
if(!class_exists('Conversios_Onboarding_Helper')):
	class Conversios_Onboarding_Helper{
		protected $apiDomain;
		protected $token;
		public function __construct(){
			$this->req_int();
      //analytics
			add_action('wp_ajax_get_analytics_web_properties', array($this,'get_analytics_web_properties') );
      add_action('wp_ajax_save_analytics_data', array($this,'save_analytics_data') );
      //googl_ads
      add_action('wp_ajax_list_googl_ads_account', array($this,'list_googl_ads_account') );
      add_action('wp_ajax_create_google_ads_account', array($this,'create_google_ads_account') );
      add_action('wp_ajax_save_google_ads_data', array($this,'save_google_ads_data') );
      add_action('wp_ajax_link_analytic_to_ads_account', array($this,'link_analytic_to_ads_account') );
      add_action('wp_ajax_get_conversion_list', array($this,'get_conversion_list') );
      
      //google_merchant
      add_action('wp_ajax_list_google_merchant_account', array($this,'list_google_merchant_account') );
      add_action('wp_ajax_create_google_merchant_center_account', array($this,'create_google_merchant_center_account') );
      add_action('wp_ajax_save_merchant_data', array($this,'save_merchant_data') );
      add_action('wp_ajax_link_google_ads_to_merchant_center', array($this,'link_google_ads_to_merchant_center') );

      //get subscription details
      add_action('wp_ajax_get_subscription_details', array($this,'get_subscription_details') );
      add_action('wp_ajax_update_setup_time_to_subscription', array($this,'update_setup_time_to_subscription') );
      
      
      
		}

		public function req_int(){
			if (!class_exists('CustomApi.php')) {
        require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php');
      }
		}
		protected function admin_safe_ajax_call( $nonce, $registered_nonce_name ) {
			// only return results when the user is an admin with manage options
			if ( is_admin() && wp_verify_nonce($nonce,$registered_nonce_name) ) {
				return true;
			} else {
				return false;
			}
		}
		
		/**
		 * Ajax code for get analytics web properties.
		 * @since    4.0.2
		 */
		public function get_analytics_web_properties(){
			$nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
			if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){	
			  $tvc_data = (object)$_POST['tvc_data'];
				$api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
			  echo json_encode($api_obj->getAnalyticsWebProperties($_POST));
			  wp_die();
			}else{
				echo "Admin security nonce is not verified.";
			}
		}

    /**
     * Ajax code for save analytics data.
     * @since    4.0.2
     */
    public function save_analytics_data(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        /*sendingblue*/
        $data = array();
        $data["email"] = $tvc_data->g_mail;
        $data["attributes"]["PRODUCT"] = "Woocommerce Free Plugin";
        $data["attributes"]["SET_GA"] = true;
        $data["listIds"]=[40,41];
        $data["updateEnabled"]=true;
        $this->add_sendinblue_contant($data, $api_obj);
        /*end sendingblue*/        
        echo json_encode($api_obj->saveAnalyticsData($_POST));
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }

    /**
     * Ajax code for list googl ads account.
     * @since    4.0.2
     */
    public function list_googl_ads_account(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        echo json_encode($api_obj->getGoogleAdsAccountList($_POST));
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }
    /**
     * Ajax code for create google ads account.
     * @since    4.0.2
     */
    public function create_google_ads_account(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        echo json_encode($api_obj->createGoogleAdsAccount($_POST));
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }

    /**
     * Ajax code for save google ads data.
     * @since    4.0.2
     */
    public function save_google_ads_data(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        /*sendingblue*/
        $data = array();
        $data["email"] = $tvc_data->g_mail;
        $data["attributes"]["PRODUCT"] = "Woocommerce Free Plugin";
        $data["attributes"]["SET_ADS"] = true;
        $data["listIds"]=[40,41];
        $data["updateEnabled"]=true;
        $this->add_sendinblue_contant($data, $api_obj);
        /*end sendingblue*/
        echo json_encode($api_obj->saveGoogleAdsData($_POST));
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }

    /**
     * Ajax code for link analytic to ads account.
     * @since    4.0.2
     */
    public function link_analytic_to_ads_account(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        echo json_encode($api_obj->linkAnalyticToAdsAccount($_POST));
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }

    /**
     * Ajax code for list google merchant account.
     * @since    4.0.2
     */
    public function list_google_merchant_account(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        echo json_encode($api_obj->listMerchantCenterAccount($_POST));
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }
    /**
     * Ajax code for link analytic to ads account.
     * @since    4.0.2
     */
    public function create_google_merchant_center_account(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        echo json_encode($api_obj->createMerchantAccount($_POST));
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }

    /**
     * Ajax code for save merchant data.
     * @since    4.0.2
     */
    public function save_merchant_data(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        /*sendingblue*/
        $data = array();
        $data["email"] = $tvc_data->g_mail;
        $data["attributes"]["PRODUCT"] = "Woocommerce Free Plugin";
        $data["attributes"]["SET_GMC"] = true;
        $data["listIds"]=[40,41];
        $data["updateEnabled"]=true;
        $this->add_sendinblue_contant($data, $api_obj);
        /*end sendingblue*/
        echo json_encode($api_obj->saveMechantData($_POST));
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }
    /**
     * Ajax code for link analytic to ads account.
     * @since    4.0.2
     */
    public function get_conversion_list(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        unset($_POST['tvc_data']);
        unset($_POST['conversios_onboarding_nonce']);
        echo json_encode($api_obj->getConversionList($_POST));
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }
    
    /**
     * Ajax code for link google ads to merchant center.
     * @since    4.0.2
     */
    public function link_google_ads_to_merchant_center(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        echo json_encode($api_obj->linkGoogleAdsToMerchantCenter($_POST));
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }
    /**
     * Ajax code for link google ads to merchant center.
     * @since    4.0.2
     */
    public function get_subscription_details(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        echo json_encode($api_obj->getSubscriptionDetails($_POST['tvc_data'], $_POST['subscription_id']));
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }
    
    /**
     * Ajax code for update setup time to subscription.
     * @since    4.0.2
     */
    public function update_setup_time_to_subscription(){
      $nonce = (isset($_POST['conversios_onboarding_nonce']))?$_POST['conversios_onboarding_nonce']:"";
      if($this->admin_safe_ajax_call($nonce, 'conversios_onboarding_nonce')){ 
        $tvc_data = (object)$_POST['tvc_data'];
        $api_obj = new Conversios_Onboarding_ApiCall($tvc_data->access_token,$tvc_data->refresh_token);
        $return_url = $this->save_wp_setting_from_subscription_api($api_obj, $tvc_data, $_POST['subscription_id']);
        $return_rs = $api_obj->updateSetupTimeToSubscription($_POST);
        $return_rs->return_url = $return_url;
        echo json_encode($return_rs);
        wp_die();
      }else{
        echo "Admin security nonce is not verified.";
      }
    }

    /**
     * save wp setting from subscription api
     * @since    4.0.2
     */
    public function save_wp_setting_from_subscription_api($api_obj, $tvc_data, $subscription_id){ 
      //print_r($tvc_data); 
      //echo "=================";  
      $TVC_Admin_Helper = new TVC_Admin_Helper(); 
      $google_detail = $api_obj->getSubscriptionDetails($tvc_data, $subscription_id);
      /**
       * active licence key while come from server page
       */
      $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
      if(isset($ee_additional_data['temp_active_licence_key']) && $ee_additional_data['temp_active_licence_key'] != ""){
        $licence_key = $ee_additional_data['temp_active_licence_key'];
        $TVC_Admin_Helper->active_licence($licence_key, $_GET['subscription_id']);
        unset($ee_additional_data['temp_active_licence_key']);
        $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
      }
      if(property_exists($google_detail,"error") && $google_detail->error == false){
        /**
         * for save conversion send to in WP DB
         */      
        $googleDetail = $google_detail->data;
        if($googleDetail->plan_id != 1 && $googleDetail->google_ads_conversion_tracking == 1){
          $TVC_Admin_Helper->update_conversion_send_to();
        }
        /**
         * for site verifecation
         */
        if(isset($googleDetail->google_merchant_center_id) && $googleDetail->google_merchant_center_id){
          $this->site_verification_and_domain_claim($googleDetail);
        }

        $_POST['subscription_id'] = $googleDetail->id;
        $_POST['ga_eeT'] = (isset($googleDetail->enhanced_e_commerce_tracking) && $googleDetail->enhanced_e_commerce_tracking == "1") ? "on" : "";
        
        $_POST['ga_ST'] = (isset($googleDetail->add_gtag_snippet) && $googleDetail->add_gtag_snippet == "1") ? "on" : "";           
        $_POST['gm_id'] = $googleDetail->measurement_id;
        $_POST['ga_id'] = $googleDetail->property_id;
        $_POST['google_ads_id'] = $googleDetail->google_ads_id;
        $_POST['google_merchant_id'] = $googleDetail->google_merchant_center_id;
        $_POST['tracking_option'] = $googleDetail->tracking_option;
        $_POST['ga_gUser'] = 'on';
        //$_POST['ga_gCkout'] = 'on';
        $_POST['ga_Impr'] = 6;
        $_POST['ga_IPA'] = 'on';
        $_POST['ga_OPTOUT'] = 'on';
        $_POST['ga_PrivacyPolicy'] = 'on';
        $_POST['google-analytic'] = '';
        //update option in wordpress local database
        update_option('google_ads_conversion_tracking',  $googleDetail->google_ads_conversion_tracking);
        update_option('ads_tracking_id',  $googleDetail->google_ads_id);
        update_option('ads_ert', $googleDetail->remarketing_tags);
        update_option('ads_edrt', $googleDetail->dynamic_remarketing_tags);
        Enhanced_Ecommerce_Google_Settings::add_update_settings('ee_options');
        /*
         * function call for save API data in WP DB
         */
        $TVC_Admin_Helper->set_update_api_to_db($googleDetail, false);  
               
        /**
         * function call for save remarketing snippets in WP DB
         */
        $TVC_Admin_Helper->update_remarketing_snippets();
        /**
         * save gmail and view ID in WP DB
         */
        if(property_exists($tvc_data,"g_mail") && $tvc_data->g_mail){
          update_option('ee_customer_gmail', $tvc_data->g_mail);     
        }
        if(isset($_POST['ga_view_id']) && $_POST['ga_view_id']){
          update_option('ee_ga_view_id', $_POST['ga_view_id']);
        }
        $return_url = "admin.php?page=enhanced-ecommerce-google-analytics-admin-display&tab=gaa_config_page";
        if(isset($googleDetail->google_merchant_center_id) || isset($googleDetail->google_ads_id) ){
          if( $googleDetail->google_merchant_center_id != "" && $googleDetail->google_ads_id != ""){      
            $return_url = "admin.php?page=enhanced-ecommerce-google-analytics-admin-display&tab=sync_product_page&welcome_msg=true";            
          }else{
            $return_url = "admin.php?page=enhanced-ecommerce-google-analytics-admin-display&tab=gaa_config_page&welcome_msg=true";
          }          
        }
        return $return_url;
      }
    }
    /**
     * site verification and_domain claim code
     * @since    4.0.2
     */
    public function site_verification_and_domain_claim($googleDetail){
      $TVC_Admin_Helper = new TVC_Admin_Helper();
      $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
      $customApiObj = new CustomApi();
      $postData = [
          'merchant_id' => $googleDetail->merchant_id,          
          'website_url' => get_site_url(),
          'subscription_id' => $googleDetail->id,
          'account_id' => $googleDetail->google_merchant_center_id
      ];
      //is site verified
      if ($googleDetail->is_site_verified == '0') {
        $postData['method']="file";
        $siteVerificationToken = $customApiObj->siteVerificationToken($postData);
        if (isset($siteVerificationToken->error) && !empty($siteVerificationToken->errors)) {
            goto call_method_tag;
        } else {
          $myFile = ABSPATH.$siteVerificationToken->data->token; 
          if (!file_exists($myFile)) {
              $fh = fopen($myFile, 'w+');
              chmod($myFile,0777);
              $stringData = "google-site-verification: ".$siteVerificationToken->data->token;
              fwrite($fh, $stringData);
              fclose($fh);
          }
          $postData['method']="file";
          $siteVerification = $customApiObj->siteVerification($postData);
          if (isset($siteVerification->error) && !empty($siteVerification->errors)) {
            call_method_tag:
            //methd using tag
            $postData['method']="meta";
            $siteVerificationToken_tag = $customApiObj->siteVerificationToken($postData);
            if(isset($siteVerificationToken_tag->data->token) && $siteVerificationToken_tag->data->token){
              $ee_additional_data["add_site_varification_tag"]=1;
              $ee_additional_data["site_varification_tag_val"]=base64_encode($siteVerificationToken_tag->data->token);
              $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
              sleep(1);
              $siteVerification_tag = $customApiObj->siteVerification($postData);
              if(isset($siteVerification_tag->error) && !empty($siteVerification_tag->errors)){
              }else{
                  $googleDetail->is_site_verified = '1';
              }
            }
          } else {
              $googleDetail->is_site_verified = '1';
          }
        }
      }
      //is domain claim
      if ($googleDetail->is_domain_claim == '0') {
          $claimWebsite = $customApiObj->claimWebsite($postData);
          if (isset($claimWebsite->error) && !empty($claimWebsite->errors)) {    
          } else {
              $googleDetail->is_domain_claim = '1';
          }
      }

      /**
       * function call for save API data in WP DB
       */
      $TVC_Admin_Helper->set_update_api_to_db($googleDetail, false);
    }
    /**
     * update contact details on sendinblue.
     * @since    4.0.2
     */
    function add_sendinblue_contant($data, $api_obj){
        $api_obj->TVC_CALL_API("POST", "https://api.sendinblue.com/v3/contacts", json_encode($data));    
    }
		
	}
endif; // class_exists
new Conversios_Onboarding_Helper();

if(!class_exists('Conversios_Onboarding_ApiCall') ){
	class Conversios_Onboarding_ApiCall {
		protected $apiDomain;
		protected $token;
    protected $merchantId;
    protected $access_token;
    protected $refresh_token;
		public function __construct($access_token, $refresh_token) {
      $merchantInfo = json_decode(file_get_contents(ENHANCAD_PLUGIN_URL.'/includes/setup/json/merchant-info.json'), true);
      $this->refresh_token = $refresh_token;
      $this->access_token = $this->generateAccessToken($access_token, $this->refresh_token);
      $this->apiDomain = TVC_API_CALL_URL;
      $this->token = 'MTIzNA==';
      $this->merchantId = $merchantInfo['merchantId'];
		}

    function TVC_CALL_API($method, $url, $data, $headers = false){
      $curl = curl_init();
      switch ($method){
        case "POST":
           curl_setopt($curl, CURLOPT_POST, 1);
           if ($data)
              curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
           break;
        case "PUT":
           curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
           if ($data)
              curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                                
           break;
        default:
           if ($data)
              $url = sprintf("%s?%s", $url, http_build_query($data));
      }
      // OPTIONS:
      curl_setopt($curl, CURLOPT_URL, $url);
      if(!$headers){
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
          'api-key: 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',
          'Content-Type: application/json',
        ));
      }else{
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
          'api-key: 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',
          'Content-Type: application/json',
          $headers
        ));
      }
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      // EXECUTE:
      $result = curl_exec($curl);
      curl_close($curl);
      return $result;
    }

    public function updateTokenToSubscription($tvc_data) {
      try {
          $tvc_data = json_decode(base64_decode($tvc_data));
          $url = $this->apiDomain . '/customer-subscriptions/update-token';
          $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json");          
          $data = [
            'subscription_id' => "",//$this->subscription_id,
            'gmail' => $tvc_data->g_mail,
            'access_token' => $this->access_token,
            'refresh_token' => $this->refresh_token,
            'domain' => $tvc_data->user_domain
          ];
          $curl_url = $url;
          $data = json_encode($data);
          $ch = curl_init();
          curl_setopt_array($ch, array(
              CURLOPT_URL => $curl_url, //esc_url($curl_url),
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_HTTPHEADER => $header,
              CURLOPT_POSTFIELDS => $data
          ));
          $response = curl_exec($ch);
          $response = json_decode($response);
          return $response;
      } catch (Exception $e) {
          return $e->getMessage();
      }
    }

    public function getSubscriptionDetails($tvc_data, $subscription_id){
      try{
        $tvc_data = (object)$tvc_data;
        $url = $this->apiDomain . '/customer-subscriptions/subscription-detail';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json", "AccessToken:$this->access_token");
        $data = [
          'subscription_id' => $subscription_id,//$this->subscription_id,
          'domain' => $tvc_data->user_domain
        ];
        $curl_url = $url;
        $postData = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
          CURLOPT_URL => $curl_url, //esc_url($curl_url),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_HTTPHEADER => $header,
          CURLOPT_POSTFIELDS => $postData
        ));
        $response = curl_exec($ch);
        $response = json_decode($response);
        return $response;
      }catch(Exception $e){
        return $e->getMessage();
      }
    }

		public function getAnalyticsWebProperties($postData) {
      try {
       // print_r($postData);
        //$tvc_data = json_decode(base64_decode($postData['tvc_data']));
        //unset($postData['tvc_data']);
        $url = $this->apiDomain . '/google-analytics/account-list';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json", "AccessToken:$this->access_token");        
        $data = [
            'merchant_id' => $this->merchantId,
            'type' => $postData['type']
        ];
        $curl_url = $url;
        $postData = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
          CURLOPT_URL => $curl_url, //esc_url($curl_url),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_HTTPHEADER => $header,
          CURLOPT_POSTFIELDS => $postData
        ));
        $response = curl_exec($ch);
        $response = json_decode($response);
        return $response;
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function getGoogleAdsAccountList($postData) {
      try {
        if($this->refresh_token != ""){
          //$tvc_data = json_decode(base64_decode($postData['tvc_data']));
          $url = $this->apiDomain . '/adwords/list';
          $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json", "RefreshToken:$this->refresh_token");         
          $curl_url = $url;
          $ch = curl_init();
          curl_setopt_array($ch, array(
              CURLOPT_URL => $curl_url, //esc_url($curl_url),
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_HTTPHEADER => $header,
              CURLOPT_POSTFIELDS => ""
          ));
          $response = curl_exec($ch);
          return json_decode($response);
        }else{
          return json_decode(array("error"=>true));
        }
      } catch (Exception $e) {
          return $e->getMessage();
      }
    }

    public function listMerchantCenterAccount() {
      try {
        $url = $this->apiDomain . '/gmc/user-merchant-center/list';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json");
        $data = [
          'access_token' => $this->access_token,
        ];
        $curl_url = $url;
        $postData = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $curl_url, //esc_url($curl_url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $postData
        ));
        $response = curl_exec($ch);
        return json_decode($response);
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function createGoogleAdsAccount($postData) {
      try {
        $tvc_data = (object)$postData['tvc_data'];
        $url = $this->apiDomain . '/adwords/create-ads-account';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json");
        $data = [
          'email' => $tvc_data->g_mail,
          'currency' => $tvc_data->currency_code,
          'time_zone' => $tvc_data->timezone_string, //'Asia/Kolkata',
          'domain' => $tvc_data->user_domain
        ];
        $curl_url = $url;
        $postData = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $curl_url, //esc_url($curl_url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $postData
        ));
        $response = curl_exec($ch);
        $response = json_decode($response);
        return $response;
      } catch (Exception $e) {
          return $e->getMessage();
      }
    }
    public function createMerchantAccount($postData) {
      try {
        $url = $this->apiDomain . '/gmc/create';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json");
        $data = [
          'merchant_id' => $this->merchantId, //'256922349',
          'name' => $postData['store_name'],
          'website_url' => $postData['website_url'],
          'customer_id' => $postData['customer_id'],
          'adult_content' => isset($postData['adult_content']) && $postData['adult_content'] == 'true' ? true : false,
          'country' => $postData['country'],
          'users' => [
            [
              "email_address" => $postData['email_address'], //"sarjit@pivotdrive.ca"
              "admin" => true
            ]
          ],
          'business_information' => [
            'address' => [
                'country' => $postData['country']
            ]
          ]
        ];
        $curl_url = $url;
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $curl_url, //esc_url($curl_url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $data
        ));
        $response = curl_exec($ch);
        return json_decode($response);
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }
    public function doCustomerLogin($tvc_data) {
      try {
        $tvc_data = json_decode(base64_decode($tvc_data));
        $url = $this->apiDomain . '/customers/login';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json");
        $data = [
          'email' => $tvc_data->g_mail,
          'access_token' => $this->access_token,
          'refresh_token' => $this->refresh_token,
          'sign_in_type' => $tvc_data->sign_in_type
        ];
        $curl_url = $url;
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $curl_url, //esc_url($curl_url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $data
        ));
        $response = curl_exec($ch);
        return json_decode($response);
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }//doCustomerLogin

    

    public function saveAnalyticsData($postData = array()) {
      try {
        $url = $this->apiDomain . '/customer-subscriptions/update-detail';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json");
        $data = array(
          'subscription_id' => (isset($postData['subscription_id']))?$postData['subscription_id'] : '',
          'tracking_option' => (isset($postData['tracking_option']))?$postData['tracking_option'] : '',
          'measurement_id' => (isset($postData['web_measurement_id']))?$postData['web_measurement_id'] : '',
          'ga4_analytic_account_id' => (isset($postData['ga4_account_id']))?$postData['ga4_account_id'] : '',
          'property_id' => (isset($postData['web_property_id'])) ? $postData['web_property_id'] : '',
          'ua_analytic_account_id' => (isset($postData['ua_account_id'])) ? $postData['ua_account_id'] : '',
          'enhanced_e_commerce_tracking' => (isset($postData['enhanced_e_commerce_tracking']) && $postData['enhanced_e_commerce_tracking'] == 'true') ? 1 : 0,
          'user_time_tracking' => (isset($postData['user_time_tracking']) && $postData['user_time_tracking']=='true')?1:0,
          'add_gtag_snippet' => (isset($postData['add_gtag_snippet']) && $postData['add_gtag_snippet'] == 'true')? 1:0,
          'client_id_tracking' => (isset($postData['client_id_tracking']) && $postData['client_id_tracking']=='true')?1:0,
          'exception_tracking' => (isset($postData['exception_tracking']) && $postData['exception_tracking']=='true')?1:0,
          'enhanced_link_attribution_tracking' => (isset($postData['enhanced_link_attribution_tracking']) && $postData['enhanced_link_attribution_tracking'] == 'true')? 1 : 0
        );
        $curl_url = $url;
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $curl_url, //esc_url($curl_url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $data
        ));
        $response = curl_exec($ch);
        return json_decode($response);
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function saveGoogleAdsData($postData = array()){
      try {
        $url = $this->apiDomain . '/customer-subscriptions/update-detail';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json");
        $data = [
            'subscription_id' => (isset($postData['subscription_id']))?$postData['subscription_id'] : '',
            'google_ads_id' => (isset($postData['google_ads_id']))? $postData['google_ads_id'] : '',
            'remarketing_tags' => (isset($postData['remarketing_tags']) && $postData['remarketing_tags'] == 'true') ? 1 : 0,
            'dynamic_remarketing_tags' => (isset($postData['dynamic_remarketing_tags']) && $postData['dynamic_remarketing_tags'] == 'true') ? 1 : 0,
            'google_ads_conversion_tracking' => (isset($postData['google_ads_conversion_tracking']) && $postData['google_ads_conversion_tracking'] == 'true') ? 1 : 0,
            'link_google_analytics_with_google_ads' => (isset($postData['link_google_analytics_with_google_ads']) && $postData['link_google_analytics_with_google_ads'] == 'true') ? 1 : 0
        ];
        $curl_url = $url;
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
          CURLOPT_URL => $curl_url, //esc_url($curl_url),
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_HTTPHEADER => $header,
          CURLOPT_POSTFIELDS => $data
        ));
        $response = curl_exec($ch);
        return json_decode($response);
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function saveMechantData($postData = array()) {
      try {
        $url = $this->apiDomain . '/customer-subscriptions/update-detail';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json");
        $data = [
          'merchant_id' => ($postData['merchant_id'] == 'NewMerchant') ? $this->merchantId: $postData['merchant_id'],
          'subscription_id' => (isset($postData['subscription_id']))?$postData['subscription_id'] : '',
          'google_merchant_center_id' => (isset($postData['google_merchant_center']))? $postData['google_merchant_center'] : '',
          'website_url' => $postData['website_url'],
          'customer_id' => $postData['customer_id']
        ];
        $curl_url = $url;
        $postData = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $curl_url, //esc_url($curl_url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $postData
        ));
        $response = curl_exec($ch);
        return json_decode($response);
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function linkAnalyticToAdsAccount($postData) {
      try {
        $url = $this->apiDomain . '/google-analytics/link-ads-to-analytics';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json", "AccessToken:$this->access_token", "RefreshToken:$this->refresh_token");
        if ($postData['type'] == "UA") {
          $data = [
            'type' => $postData['type'],
            'ads_customer_id' => $postData['ads_customer_id'], //'7894072776', //$postData['ads_customer_id']
            'analytics_id' => $postData['analytics_id'], //'184918792', //$postData['analytics_id']
            'web_property_id' => $postData['web_property_id'], //'UA-184918792-2', //$postData['web_property_id']
            'profile_id' => $postData['profile_id'], //'234239637', //$postData['profile_id']
          ];
        } else {
          $data = [
            'type' => $postData['type'],
            'ads_customer_id' => $postData['ads_customer_id'], //'7894072776', //$postData['ads_customer_id']
            'analytics_id' => '', //$postData['analytics_id']
            'web_property_id' => $postData['web_property_id'], //'properties/257833054', //$postData['web_property_id']
            'profile_id' => '', //$postData['profile_id']
            'web_property' => $postData['web_property'], //'234239637', //$postData['profile_id']
          ];
        }
        $curl_url = $url;
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $curl_url, //esc_url($curl_url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $data
        ));
        $response = curl_exec($ch);
        return json_decode($response);
      } catch (Exception $e) {
          return $e->getMessage();
      }
    }
    public function linkGoogleAdsToMerchantCenter($postData) {
      try {
        $url = $this->apiDomain . '/adwords/link-ads-to-merchant-center';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json", "AccessToken:$this->access_token");
        $data = [
          'merchant_id' => ($postData['merchant_id'] == 'NewMerchant') ? $this->merchantId: $postData['merchant_id'],
          'account_id' => $postData['account_id'],
          'adwords_id' => $postData['adwords_id']
        ];
        $curl_url = $url;
        $data = json_encode($data);        
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $curl_url, //esc_url($curl_url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $data
        ));
        $response = curl_exec($ch);
        return json_decode($response);
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }
    public function updateSetupTimeToSubscription($postData) {
      try {
        $url = $this->apiDomain . '/customer-subscriptions/update-setup-time';
        $this->header = array("Authorization: Bearer MTIzNA==", "content-type: application/json");
        $data = [
          'subscription_id' => (isset($postData['subscription_id']))?$postData['subscription_id'] : '',
          'setup_end_time' => date('Y-m-d H:i:s')
        ];
        $this->curl_url = $url;
        $data = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $this->curl_url, //esc_url($this->curl_url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => $this->header,
            CURLOPT_POSTFIELDS => $data
        ));
        $this->response = curl_exec($ch);
        $this->response = json_decode($this->response);
        return $this->response;
      } catch (Exception $e) {
          return $e->getMessage();
      }
    }

    public function getConversionList($data) {
      try {
        $url = $this->apiDomain . '/google-ads/conversion-list';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json");        
        $curl_url = $url;
        $postData = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $curl_url, //esc_url($curl_url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $postData
        ));
        $response = curl_exec($ch);
        $response = json_decode($response);
        $return = new \stdClass();
        
        if(isset($response->data) && count($response->data) > 0){  
          $return->error = false;
          $return->message = "Google Ads conversion tracking setting success.";      
        }else{
          if(isset($response->error) && $response->error == false){
            $response = $this->createConversion($data); 
            if(isset($response->error) && $response->error == false){         
              $return->error = false;
              $return->message = $response->message; 
            }else{
             $return->error = true;
             $errors = json_decode($response->errors[0]);
            $return->errors = $errors->message;
            }
          }else{
            $return->error = true;
            $return->errors = $response->errors[0]; 
          }
        }
        return $return;
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    public function createConversion($postData) {
      try {
        $url = $this->apiDomain . '/google-ads/create-conversion';
        $header = array("Authorization: Bearer MTIzNA==", "content-type: application/json");
        $data = [
          'customer_id' => (isset($postData['customer_id']))?$postData['customer_id'] : '',
          'name' => "Order Conversion"
        ];
        $curl_url = $url;
        $postData = json_encode($data);
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $curl_url, //esc_url($curl_url),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_POSTFIELDS => $postData
        ));
        $response = curl_exec($ch);
        $response = json_decode($response);                
        return $response;
      } catch (Exception $e) {
        return $e->getMessage();
      }
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
          $credentials = json_decode(file_get_contents(ENHANCAD_PLUGIN_DIR . 'includes/setup/json/client-secrets.json'), true);
          $url = 'https://www.googleapis.com/oauth2/v4/token';
          $header = array("content-type: application/json");
          $clientId = $credentials['web']['client_id'];
          $clientSecret = $credentials['web']['client_secret'];
          
          $data = [
              "grant_type" => 'refresh_token',
              "client_id" => $clientId,
              'client_secret' => $clientSecret,
              'refresh_token' => $refresh_token,
          ];

          $postData = json_encode($data);
          $ch = curl_init();
          curl_setopt_array($ch, array(
              CURLOPT_URL => $url, //esc_url($curl_url),
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
    }//generateAccessToken

	}
}