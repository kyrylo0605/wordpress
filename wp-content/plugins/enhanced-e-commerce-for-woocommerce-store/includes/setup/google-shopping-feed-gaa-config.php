<?php
class GAAConfiguration {
  protected $TVC_Admin_Helper;
  protected $subscriptionId;
  protected $TVCProductSyncHelper;
  public function __construct() {
    ini_set('max_execution_time', '0'); 
    ini_set('memory_limit','-1');
    $this->includes();
    $this->TVC_Admin_Helper = new TVC_Admin_Helper();
    $this->TVCProductSyncHelper = new TVCProductSyncHelper();
    $this->subscriptionId = $this->TVC_Admin_Helper->get_subscriptionId(); 
    $this->site_url = "admin.php?page=enhanced-ecommerce-google-analytics-admin-display&tab=";     
    $this->url = $this->TVC_Admin_Helper->get_connect_url();     
    $this->html_run();
  }
  public function includes() {
    if (!class_exists('Tatvic_Category_Wrapper')) {
      require_once(__DIR__ . '/class-tvc-product-sync-helper.php');
    }
  }

  public function html_run() {
    $this->TVC_Admin_Helper->add_spinner_html();
    $this->create_form();
  }

  public function create_form() {
    if(isset($_GET['welcome_msg']) && $_GET['welcome_msg'] == true){
      $class = 'notice notice-success';
      $message = esc_html__('Get your WooCommerce products in front of the millions of shoppers across Google by setting up your Google Merchant Center account from below.');
      printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
      ?>
      <script>
        $(document).ready(function() {
          var msg="<?php echo $message;?>"
          tvc_helper.tvc_alert("success","Hey!",msg,true);
        });
      </script>
      <?php
    }
    $category_wrapper_obj = new Tatvic_Category_Wrapper();
    $category_wrapper = $category_wrapper_obj->category_table_content('mapping');
    $googleDetail = [];
    $google_detail = $this->TVC_Admin_Helper->get_ee_options_data();
    if(isset($google_detail['setting'])){
      if ($google_detail['setting']) {
        $googleDetail = $google_detail['setting'];
      }
    }?>
<div class="tab-content">
	<div class="tab-pane show active" id="googleShoppingFeed">
    <div class="tab-card">
      <div class="row">
        <div class="col-md-6 col-lg-8 edit-section">
          <div class="edit-header-section">           
            <script>
              var back_img = '<img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/icon/left-angle-arrow.svg'; ?>" alt="back"/>';
              document.write('<a href="' + document.referrer + '" class="back-btn">'+back_img+'<span>Back</span></a>');
            </script>
          </div>
          <div class="configuration-section" id="config-pt1">
            <?php echo get_google_shopping_tabs_html($this->site_url,(isset($googleDetail->google_merchant_center_id))?$googleDetail->google_merchant_center_id:""); ?>
          </div>
          <div class="mt-3" id="config-pt2">
            <div class="google-account-analytics" id="gaa-config">
              <div class="row mb-3">
              <div class="col-6 col-md-6 col-lg-6">
                <h2 class="ga-title">Connected Google Merchant center account:</h2>
              </div>
              <div class="col-6 col-md-6 col-lg-6 text-right">
                <div class="acc-num">
                  <p class="ga-text"><?php echo ((isset($googleDetail->google_merchant_center_id) && $googleDetail->google_merchant_center_id != '') ? $googleDetail->google_merchant_center_id : '<span>Get started</span>'); ?></p>
                  <?php
                    if(isset($googleDetail->google_merchant_center_id) && $googleDetail->google_merchant_center_id != ''){
                      echo '<p class="ga-text text-right"><a target="_blank" href="' . $this->url . '" class="text-underline"><img src="'. ENHANCAD_PLUGIN_URL.'/admin/images/icon/refresh.svg" alt="refresh"/></a></p>';
                    }else{
                      echo '<p class="ga-text text-right"><a href="#" class="text-underline" data-toggle="modal" data-target="#tvc_google_connect"><img src="'. ENHANCAD_PLUGIN_URL.'/admin/images/icon/add.svg" alt="connect account"/></a></p>';
                    }?>
                </div>
              </div>              
            </div>
            <div class="row mb-3">
              <div class="col-6 col-md-6 col-lg-6">
                <h2 class="ga-title">Linked Google Ads Account:</h2>
              </div>
              <div class="col-6 col-md-6 col-lg-6 text-right">
                <div class="acc-num">
                  <p class="ga-text"><?php echo (isset($googleDetail->google_ads_id) && $googleDetail->google_ads_id != '' ? $googleDetail->google_ads_id : '<span>Get started</span>');?></p>
                  <?php
                  if (isset($googleDetail->google_ads_id) && $googleDetail->google_ads_id != '') {
                    echo '<p class="ga-text text-right"><a target="_blank" href="' . $this->url . '" class="text-underline"><img src="'. ENHANCAD_PLUGIN_URL.'/admin/images/icon/refresh.svg" alt="refresh"/></a></p>';
                  } else {
                    echo '<p class="ga-text text-right"><a href="#"  data-toggle="modal" data-target="#tvc_google_connect" class="text-underline"><img src="'. ENHANCAD_PLUGIN_URL.'/admin/images/icon/add.svg" alt="connect account"/></a></p>';
                  } ?>
                </div>
              </div>
            </div>
            <?php
            if (isset($googleDetail->google_merchant_center_id) && $googleDetail->google_merchant_center_id != '') {?>
            <div class="row mb-3">
              <div class="col-6 col-md-4">
                <h2 class="ga-title">Sync Products:</h2>
              </div>
              <div class="col-6 col-md-4">
                <button id="tvc_btn_product_sync" type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#syncProduct">Sync New Products</button>                        
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-6 col-md-4">
                <h2 class="ga-title">Smart Shopping Campaigns:</h2>
              </div>
              <div class="col-6 col-md-6">
                <a href="admin.php?page=enhanced-ecommerce-google-analytics-admin-display&tab=add_campaign_page" class="btn btn-primary btn-success">Create Smart Shopping Campaign</a>
              </div>
            </div>
            <?php }else{ ?>
            <div class="row mb-3">
              <div class="col-6 col-md-4">
                <h2 class="ga-title">Sync Products:</h2>
              </div>
              <div class="col-6 col-md-4">                     
                <button type="button" class="btn btn-primary btn-success" data-toggle="modal" data-target="#tvc_google_connect">Sync New Products</button>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-6 col-md-4">
                <h2 class="ga-title">Smart Shopping Campaigns:</h2>
              </div>
              <div class="col-6 col-md-6">
                <a href="#" class="btn btn-primary btn-success" data-toggle="modal" data-target="#tvc_google_connect">Create Smart Shopping Campaign</a>
              </div>
            </div>
            <?php } ?>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <?php echo get_tvc_help_html(); ?>
        </div>
      </div>
    </div>
	</div>
</div>
<?php 
// add product sync popup
echo $this->TVCProductSyncHelper->tvc_product_sync_popup_html();
//add connect popup
echo get_connect_google_popup_html()
?>
<?php  
$is_need_to_domain_claim = false;
if(isset($googleDetail->google_merchant_center_id) && $googleDetail->google_merchant_center_id && $this->subscriptionId != "" && isset($googleDetail->is_domain_claim) && $googleDetail->is_domain_claim == '0'){
  $is_need_to_domain_claim = true;
}?>
<script type="text/javascript">
  $(document).ready(function() {
    $(document).on("click", "#tvc_btn_product_sync", function(event){
      var is_need_to_domain_claim = "<?php echo $is_need_to_domain_claim; ?>";
      if(is_need_to_domain_claim == 1 || is_need_to_domain_claim == true){
        event.preventDefault();
        jQuery.post(myAjaxNonces.ajaxurl,{
          action: "tvc_call_domain_claim",
          apiDomainClaimNonce: myAjaxNonces.apiDomainClaimNonce
        },function( response ){
          
        });
      }
    }); 
  });
</script>
  <?php 
  } //create_form
} ?>