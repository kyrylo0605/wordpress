<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       tatvic.com
 * @since      1.0.0
 *
 * @package    Enhanced_Ecommerce_Google_Analytics
 * @subpackage Enhanced_Ecommerce_Google_Analytics/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Enhanced_Ecommerce_Google_Analytics
 * @subpackage Enhanced_Ecommerce_Google_Analytics/admin
 * @author     Chiranjiv Pathak <chiranjiv@tatvic.com>
 */

class Enhanced_Ecommerce_Google_Analytics_Admin extends TVC_Admin_Helper {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since      1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    protected $ga_id;
    protected $ga_LC;
    protected $ga_eeT;
    protected $setting_status;
    protected $site_url;
    public function __construct($plugin_name, $version) {                       
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : '';
        $this->refreshToken = isset($_GET['refresh_token']) ? $_GET['refresh_token'] : '';
        $this->email = isset($_GET['email']) ? $_GET['email'] : '';
        $this->url = $this->get_connect_url();
        $this->site_url = "admin.php?page=enhanced-ecommerce-google-analytics-admin-display&tab=";
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        $screen = get_current_screen();
        if ($screen->id == 'toplevel_page_enhanced-ecommerce-google-analytics-admin-display' || (isset($_GET['page']) && $_GET['page'] == 'enhanced-ecommerce-google-analytics-admin-display')) {
            wp_register_style('font_awesome', '//use.fontawesome.com/releases/v5.0.13/css/all.css');
            wp_enqueue_style('font_awesome');
            wp_register_style('plugin-bootstrap',ENHANCAD_PLUGIN_URL . '/includes/setup/plugins/bootstrap/css/bootstrap.min.css');
            wp_enqueue_style('plugin-bootstrap');
            wp_register_style('aga_confirm', '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css');
            wp_enqueue_style('aga_confirm');
           
            wp_enqueue_style('custom-css', ENHANCAD_PLUGIN_URL . '/admin/css/custom-style.css', array(), $this->version, 'all' );
            if($this->is_current_tab_in(array('sync_product_page','gaa_config_page'))){
                wp_register_style('plugin-select2',ENHANCAD_PLUGIN_URL . '/includes/setup/plugins/select2/select2.min.css');
                wp_enqueue_style('plugin-select2');
                wp_register_style('plugin-steps',ENHANCAD_PLUGIN_URL . '/includes/setup/plugins/jquery-steps/jquery.steps.css');
                wp_enqueue_style('plugin-steps');
            }
            if($this->is_current_tab_in(array("shopping_campaigns_page","add_campaign_page"))){
                wp_register_style('plugin-select2',ENHANCAD_PLUGIN_URL . '/includes/setup/plugins/select2/select2.min.css');
                wp_enqueue_style('plugin-select2');
                wp_register_style('bootstrap-datepicker',ENHANCAD_PLUGIN_URL. '/includes/setup/plugins/datepicker/bootstrap-datepicker.min.css');
                wp_enqueue_style('bootstrap-datepicker');
            }
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/enhanced-ecommerce-google-analytics-admin.css', array(), $this->version, 'all');
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        $screen = get_current_screen();
        if ($screen->id == 'toplevel_page_enhanced-ecommerce-google-analytics-admin-display' || (isset($_GET['page']) && $_GET['page'] == 'enhanced-ecommerce-google-analytics-admin-display')) {

            wp_enqueue_script( 'custom-jquery', ENHANCAD_PLUGIN_URL . '/admin/js/jquery-3.5.1.min.js', array( 'jquery' ), $this->version, false );
            wp_register_script('popper_bootstrap', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js');
            wp_enqueue_script('popper_bootstrap');
            wp_register_script('aga_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js');
            wp_enqueue_script('aga_bootstrap');
            wp_register_script('aga_bootstrap_mod', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js');
            wp_enqueue_script('aga_bootstrap_mod');
            wp_register_script('aga_confirm_js', '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js');
            wp_enqueue_script('aga_confirm_js');
           // wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/enhanced-ecommerce-google-analytics-admin.js', array('jquery'), $this->version, false);
            if($this->is_current_tab_in(array('sync_product_page','gaa_config_page'))){
                wp_register_script('plugin-select2',ENHANCAD_PLUGIN_URL . '/includes/setup/plugins/select2/select2.min.js');
                wp_enqueue_script('plugin-select2');
                wp_register_script('plugin-step-js',ENHANCAD_PLUGIN_URL . '/includes/setup/plugins/jquery-steps/jquery.steps.js');
                wp_enqueue_script('plugin-step-js');
            }
            if($this->is_current_tab_in(array("shopping_campaigns_page","add_campaign_page"))){
                wp_register_script('plugin-select2',ENHANCAD_PLUGIN_URL . '/includes/setup/plugins/select2/select2.min.js');
                wp_enqueue_script('plugin-select2');
                wp_register_script('plugin-chart',ENHANCAD_PLUGIN_URL . '/includes/setup/plugins/chart/chart.js');
                wp_enqueue_script('plugin-chart');
                wp_register_script('bootstrap_datepicker',ENHANCAD_PLUGIN_URL . '/includes/setup/plugins/datepicker/bootstrap-datepicker.min.js');
                wp_enqueue_script('bootstrap_datepicker');
            }
        }
    }

    /**
     * Display Admin Page.
     *
     * @since    1.0.0
     */
    public function display_admin_page() {
        add_menu_page(
            'Tatvic EE Plugin', 'Tatvic EE Plugin', 'manage_options', "enhanced-ecommerce-google-analytics-admin-display", array($this, 'showPage'), plugin_dir_url(__FILE__) . 'images/tatvic_logo.png', 26
        );
        add_submenu_page(
            'enhanced-ecommerce-google-analytics-admin-display',
            esc_html__('Google Ads', 'actionable-google-analytics-admin-display'),
            esc_html__('Google Ads', 'actionable-google-analytics-admin-display'),
            'manage_woocommerce',
            'enhanced-ecommerce-google-analytics-admin-display&tab=google_ads',
            array($this, 'showPage')
        );
        add_submenu_page(
            'enhanced-ecommerce-google-analytics-admin-display',
            esc_html__('Google Shopping', 'enhanced-ecommerce-google-analytics-admin-display'),
            esc_html__('Google Shopping', 'enhanced-ecommerce-google-analytics-admin-display'),
            'manage_woocommerce',
            'enhanced-ecommerce-google-analytics-admin-display&tab=google_shopping_feed',
            array($this, 'showPage')
        );
    }
    /**
     * Display Tab page.
     *
     * @since    1.0.0
     */
    public function showPage() {
        echo '<div class="tvc_plugin_container">';
        require_once( 'partials/enhanced-ecommerce-google-analytics-admin-display.php');
        new TVC_Tabs();
        if (!empty($_GET['tab'])) {
            $get_action = $_GET['tab'];
        } else {
            $get_action = "general_settings";
        }
        if (method_exists($this, $get_action)) {
            $this->$get_action();
        }
        echo '</div>';
    }
    public function check_nall_and_message($val, $msg, $msg_false){
        if((isset($val) && $val != "" && $val != 0) ){
            return $msg;
        }else{
             return $msg_false;
        }
    }
    public function check_setting_status(){        
       if(!empty($this->setting_status)){
            return $this->setting_status;
        }else{
            $google_detail = $this->get_ee_options_data();
            $setting_status = array();
            if(isset($google_detail['setting'])){
                $googleDetail = $google_detail['setting'];               
                //for google analytic            
                if(isset($googleDetail->tracking_option) && isset($googleDetail->measurement_id) && isset($googleDetail->property_id) && $googleDetail->tracking_option == "BOTH" ){
                    if($googleDetail->property_id != "" && $googleDetail->measurement_id != ""){
                        $setting_status['google_analytic']= true;
                        $setting_status['google_analytic_msg']= "";
                    }else if($googleDetail->property_id == "" ){
                        $setting_status['google_analytic']= false;
                        $setting_status['google_analytic_msg']= "There is a configuration issue in your Google Analytics account set up <a target='_blank' href='".esc_url($this->url)."'>click here</a>.";
                    }else if($googleDetail->measurement_id == "" ){
                        $setting_status['google_analytic']= false;
                        $setting_status['google_analytic_msg']= "There is a configuration issue in your Google Analytics account set up <a target='_blank' href='".esc_url($this->url)."'>click here</a>.";
                    }
                }else if(isset($googleDetail->tracking_option) && isset($googleDetail->measurement_id) && $googleDetail->tracking_option == "GA4"){
                    if( $googleDetail->measurement_id != ""){
                        $setting_status['google_analytic']= true;
                        $setting_status['google_analytic_msg']= "";
                    }else{
                        $setting_status['google_analytic']= false;
                        $setting_status['google_analytic_msg']= "There is a configuration issue in your Google Analytics account set up <a target='_blank' href='".esc_url($this->url)."'>click here</a>.";
                    }
                }else if(isset($googleDetail->tracking_option) && isset($googleDetail->property_id) && $googleDetail->tracking_option == "UA" ){
                    if($googleDetail->property_id != ""){
                        $setting_status['google_analytic']= true;
                        $setting_status['google_analytic_msg']= "";
                    }else{
                        $setting_status['google_analytic']= false;
                        $setting_status['google_analytic_msg']= "There is a configuration issue in your Google Analytics account set up <a target='_blank' href='".esc_url($this->url)."'>click here</a>.";
                    }
                }else{
                    $setting_status['google_analytic']= false;
                    $setting_status['google_analytic_msg']= "";
                }
                // for google shopping
                if(property_exists($googleDetail,"google_merchant_center_id") && property_exists($googleDetail,"google_ads_id") ){
                    //main tab
                    if( $googleDetail->google_merchant_center_id != "" && $googleDetail->google_ads_id != ""){
                        $setting_status['google_shopping']= true;
                        $setting_status['google_shopping_msg']= "";
                    }else if($googleDetail->google_merchant_center_id == ""){
                        $setting_status['google_shopping']= false;
                        $setting_status['google_shopping_msg']= "Connect your merchant center account and make your products available to shoppers across Google <a target='_blank' href='".esc_url($this->url)."'>click here</a>.";
                    }else if($googleDetail->google_ads_id == ""){
                        $setting_status['google_shopping']= false;
                        $setting_status['google_shopping_msg']= "Link your Google Ads with Merchant center to start running shopping campaigns <a target='_blank' href='".esc_url($this->url)."'>click here</a>.";
                    }
                }else{
                    $setting_status['google_shopping']= false;
                    $setting_status['google_shopping_msg']= "";
                }
                
                //google_ads_id
                if(property_exists($googleDetail,"google_ads_id") && property_exists($googleDetail,"google_merchant_center_id") ){
                    if( $googleDetail->google_ads_id != "" && $googleDetail->google_merchant_center_id != ""){
                        $setting_status['google_ads']= true;
                        $setting_status['google_ads_msg']= "";
                    }else if($googleDetail->google_merchant_center_id == ""){
                        $setting_status['google_ads']= false;
                        $setting_status['google_ads_msg']= "Link your Google Ads with Merchant center to start running shopping campaigns <a target='_blank' href='".esc_url($this->url)."'>click here</a>.";
                    }else if($googleDetail->google_ads_id == ""){
                        $setting_status['google_ads']= false;
                        $setting_status['google_ads_msg']= "Configure Google Ads account to reach to millions of interested shoppers <a target='_blank' href='".esc_url($this->url)."'>click here</a>.";
                    }              
                }else{
                    $setting_status['google_ads']= false;
                    $setting_status['google_ads_msg']= "";
                }
            }
            $this->setting_status = $setting_status;            
            return $setting_status;
        }
    }
    public function check_setting_status_sub_tabs(){        
        $google_detail = $this->get_ee_options_data();
        $setting_status = array();
        if(isset($google_detail['setting'])){
            $googleDetail = $google_detail['setting'];            
            //sub tab shopping config
            if(property_exists($googleDetail,"google_merchant_center_id") && property_exists($googleDetail,"is_site_verified") && property_exists($googleDetail,"is_domain_claim") && property_exists($googleDetail,"google_ads_id")){
                if( $googleDetail->google_merchant_center_id != "" && $googleDetail->google_ads_id != "" && $googleDetail->is_site_verified == 1 && $googleDetail->is_domain_claim == 1 ){
                    $setting_status['google_shopping_conf']= true;
                    $setting_status['google_shopping_conf_msg']= "Google Shopping Configuration Success.";
                }else if($googleDetail->google_merchant_center_id == "" || $googleDetail->google_ads_id == "" ){
                    $setting_status['google_shopping_conf']= false;
                    $setting_status['google_shopping_conf_msg']= "Connect your merchant center account and make your products available to shoppers across Google <a target='_blank' href='".esc_url($this->url)."'>click here</a>.";
                }else if($googleDetail->is_site_verified ==0 && $googleDetail->is_domain_claim ==0 ){
                    $setting_status['google_shopping_conf']= false;
                    $setting_status['google_shopping_conf_msg']= "Site verification and domain claim for your merchant center account failed.";
                }else if($googleDetail->is_site_verified ==0 ){
                     $setting_status['google_shopping_conf']= false;
                     $setting_status['google_shopping_conf_msg']= "Site verification and domain claim for your merchant center account failed.";
                }else if($googleDetail->is_domain_claim ==0 ){
                    $setting_status['google_shopping_conf']= false;
                    $setting_status['google_shopping_conf_msg']= "Domain claim is pending. Your store url may be linked to other merchant center account.";
                }                                      
            }else{
                $setting_status['google_shopping_conf']= false;
                $missing="";
            }
            //sub tab product sync
            $syncProductList = [];
            $syncProductStat = [];
            if(property_exists($googleDetail,"google_merchant_center_id") && $googleDetail->google_merchant_center_id != ''){
                if(isset($google_detail['prod_sync_status']) && $google_detail['prod_sync_status']){                      
                    $syncProductStat = $google_detail['prod_sync_status'];
                    $sync_product_total = (!empty($syncProductStat)) ? $syncProductStat->total : "0";
                    $sync_product_approved = (!empty($syncProductStat)) ? $syncProductStat->approved : "0";
                    $sync_product_disapproved = (!empty($syncProductStat)) ? $syncProductStat->disapproved : "0";
                    $sync_product_pending = (!empty($syncProductStat)) ? $syncProductStat->pending : "0";

                    if($sync_product_total > 1 && $sync_product_approved > 1 && $sync_product_disapproved < 1){
                        $setting_status['google_shopping_p_sync']= true;
                        $setting_status['google_shopping_p_sync_msg']= "Google Shopping product sync is a success.";
                    }else if($sync_product_total < 1){
                        $setting_status['google_shopping_p_sync']= false;
                        $setting_status['google_shopping_p_sync_msg']= "Sync your product data into Merchant center and get eligible for free listing across Google.";
                    }else if($sync_product_disapproved > 0){
                        $setting_status['google_shopping_p_sync']= false;
                        $setting_status['google_shopping_p_sync_msg']= "There seems to be some problem with your product data. Rectify the issues by selecting right attributes.";
                    }                  
                }                
            }else{
                $setting_status['google_shopping_p_sync']= false;
                $setting_status['google_shopping_p_sync_msg']= "Connect your merchant center account and make your products available to shoppers across Google <a target='_blank' href='".esc_url($this->url)."'>click here</a>.";
            } 

            //sub tab product Campaigns
            if(property_exists($googleDetail,"google_merchant_center_id") && $googleDetail->google_merchant_center_id != ''){                
               if(isset($google_detail['campaigns_list']) && $google_detail['campaigns_list']){
                    $campaigns_list = $google_detail['campaigns_list'];
                    $totalCampaigns = count($campaigns_list);
                    if($totalCampaigns < 1){
                        $setting_status['google_shopping_p_campaigns']= false;
                        $setting_status['google_shopping_p_campaigns_msg']= "Reach out to customers based on their past site behavior by running start shopping campaign.";
                    }else{
                        $setting_status['google_shopping_p_campaigns']= true;
                    }                    
                }else{
                    $setting_status['google_shopping_p_campaigns']= false;
                    $setting_status['google_shopping_p_campaigns_msg']= "Reach out to customers based on their past site behavior by running start shopping campaign.";
                }                
            }else{
                $setting_status['google_shopping_p_campaigns']= false;
                $setting_status['google_shopping_p_campaigns_msg']= "Connect your merchant center account and make your products available to shoppers across Google <a target='_blank' href='".esc_url($this->url)."'>click here</a>.";
            }          
        }                  
        return $setting_status;        
    }
    public function general_settings() {       
        require_once( 'partials/general-fields.php');
    }
    public function google_ads() {        
        require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php');
        require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/google-ads.php');
        new GoogleAds();
    }
    public function google_shopping_feed() {
        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php');
        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/google-shopping-feed.php');
        new GoogleShoppingFeed();
    }
    public function gaa_config_page() { 
        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php');       
        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/google-shopping-feed-gaa-config.php');        
        new GAAConfiguration();
    }    
    public function sync_product_page() {
        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php');
        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/google-shopping-feed-sync-product.php');
        new SyncProductConfiguration();
    }
    public function shopping_campaigns_page() {
        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php');
        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/google-shopping-feed-shopping-campaigns.php');
        new CampaignsConfiguration();
    }
    public function add_campaign_page() {
        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/help-html.php');
        include(ENHANCAD_PLUGIN_DIR . 'includes/setup/add-campaign.php');
        new AddCampaign();
    }    
    public function conversion_tracking() {
        require_once( 'partials/conversion-tracking.php');
    }
    public function google_optimize() {
        require_once( 'partials/google-optimize.php');
    }
    public function about_plugin() {
        require_once( 'partials/about-plugin.php');
    }
    public function country_location() {
        // date function to hide 30% off sale after certain date
        return date_default_timezone_set('Australia/Sydney'); // Change this depending on what timezone your in
    }    
    public function today() {
        $this->country_location();
        return strtotime(date('Y-m-d'));
    }

    public function current_time() {
        $this->country_location();
        return strtotime(date('h:i A'));
    }

    public function start_date() {
        $this->country_location();
        return strtotime(date('Y') . '-09-01');
    }

    public function end_date() {
        $this->country_location();
        return strtotime(date('Y') . '-09-08');
    }

    public function end_time() {
        $this->country_location();
        return strtotime('11:59 PM');
    }
}
