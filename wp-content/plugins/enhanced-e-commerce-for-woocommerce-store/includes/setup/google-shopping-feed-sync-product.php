<?php

class SyncProductConfiguration
{
protected $TVC_Admin_Helper;
protected $subscriptionId;
protected $TVC_Admin_DB_Helper;
protected $TVCProductSyncHelper;
public function __construct(){
  $this->includes();
	$this->TVC_Admin_Helper = new TVC_Admin_Helper();
  $this->TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
  $this->TVCProductSyncHelper = new TVCProductSyncHelper();
  $this->subscriptionId = $this->TVC_Admin_Helper->get_subscriptionId();  
  $this->site_url = "admin.php?page=enhanced-ecommerce-google-analytics-admin-display&tab=";
  $this->TVC_Admin_Helper->need_auto_update_db(); 	
  $this->html_run();
}
public function includes(){
  if (!class_exists('TVCProductSyncHelper')) {
    require_once(__DIR__ . '/class-tvc-product-sync-helper.php');
  }
}
public function html_run(){
	$this->TVC_Admin_Helper->add_spinner_html();
  $this->create_form();
}

public function create_form(){
  if(isset($_GET['welcome_msg']) && $_GET['welcome_msg'] == true){
    $this->TVC_Admin_Helper->call_domain_claim();
    $class = 'notice notice-success';
    $message = esc_html__('Everthing is now set up. One more step - Sync your WooCommerce products into your Merchant Center and reach out to millions of shopper across Google.');
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    ?>
    <script>
      $(document).ready(function() {
        var msg="<?php echo $message;?>"
        tvc_helper.tvc_alert("success","Congratulation..!",msg,true);
      });
    </script>
    <?php
  }
	
	$syncProductStat = [];
	$syncProductList = [];
  $last_api_sync_up ="";  
	$google_detail = $this->TVC_Admin_Helper->get_ee_options_data();
	if(isset($google_detail['prod_sync_status'])){
    if ($google_detail['prod_sync_status']) {
      $syncProductStat = $google_detail['prod_sync_status'];
    }
  }

  $syncProductList = $this->TVC_Admin_DB_Helper->tvc_get_results("ee_products_sync_list");
	if(isset($google_detail['setting'])){
    if ($google_detail['setting']) {
      $googleDetail = $google_detail['setting'];
    }
  }
  $last_api_sync_up = "";
  if(isset($google_detail['sync_time']) && $google_detail['sync_time']){      
    $date_formate=get_option('date_format')." ".get_option('time_format');
    if($date_formate ==""){
      $date_formate = 'M-d-Y H:i';
    }
    $last_api_sync_up = date( $date_formate, $google_detail['sync_time']);      
  }
  $is_need_to_update = $this->TVC_Admin_Helper->is_need_to_update_api_to_db();
  $args = array('post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1);
  $products = new WP_Query($args);
  $woo_product = $products->found_posts;
?>

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
            <?php if($this->subscriptionId != ""){?>
            <div class="tvc-api-sunc">
              <span>
              <?php if($last_api_sync_up){
                echo "Details last synced at ".$last_api_sync_up; 
              }else{
                echo "Refresh sync up";
              }?></span><img id="refresh_api" onclick="call_tvc_api_sync_up();" src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/refresh.png'; ?>">
            </div>
          <?php } ?>
          <?php echo get_google_shopping_tabs_html($this->site_url,(isset($googleDetail->google_merchant_center_id))?$googleDetail->google_merchant_center_id:""); ?>                          
          </div>
          <div class="mt-3" id="config-pt2">
            <div class="sync-new-product" id="sync-product">
              <div class="row">
                <div class="col-12">
                  <div class="d-flex justify-content-between ">
                    <p class="mb-0 align-self-center product-title">Products in your Merchant Center account</p>
                    <button id="tvc_btn_product_sync" class="btn btn-outline-primary align-self-center" data-toggle="modal" data-target="#syncProduct">Sync New Products</button>
                  </div>
                </div>
            	</div>
              <?php
              $sync_product_total = (property_exists($syncProductStat,"total")) ? $syncProductStat->total : "0";
              $sync_product_approved = (property_exists($syncProductStat,"approved")) ? $syncProductStat->approved : "0";
              $sync_product_disapproved = (property_exists($syncProductStat,"disapproved")) ? $syncProductStat->disapproved : "0";
              $sync_product_pending = (property_exists($syncProductStat,"pending")) ? $syncProductStat->pending : "0"; ?>
              <div class="product-card">
                <div class="row row-cols-5">
                  <div class="col">
                    <div class="card">
                      <h3 class="pro-count"><?php 
                      echo (($woo_product) ? $woo_product : "0"); ?></h3>
                      <p class="pro-title">Total Products</p>                      
                    </div>
                  </div>
                  <div class="col">
                    <div class="card">
                      <h3 class="pro-count"><?php 
                      echo $sync_product_total ; ?></h3>
                      <p class="pro-title">Sync Products</p>                      
                    </div>
                  </div>
                  <div class="col">
                    <div class="card pending">
                      <h3 class="pro-count">
                      <?php echo $sync_product_pending;?></h3>
                      <p class="pro-title">Pending Review</p>                        
                    </div>
                  </div>
                  <div class="col">
                    <div class="card approved">
                      <h3 class="pro-count"><?php echo $sync_product_approved;?></h3>
                      <p class="pro-title">Approved</p>                        
                    </div>
                  </div>
                  <div class="col">
                    <div class="card disapproved">
                      <h3 class="pro-count"><?php
                      echo $sync_product_disapproved; ?></h3>
                      <p class="pro-title">Disapproved</p>                        
                    </div>
                  </div>
                </div>
          		</div>
              <div class="total-products">                
                <div class="account-performance tvc-sync-product-list-wapper">
                  <div class="table-section">
                    <div class="table-responsive">
                      <table id="tvc-sync-product-list" class="table table-striped" style="width:100%">
                      	<thead>
                        	<tr>
                          	<th></th>
                          	<th style="vertical-align: top;">Product</th>
                          	<th style="vertical-align: top;">Google status</th>
                          	<th style="vertical-align: top;">Issues</th>
                        	</tr>
                      	</thead>
                      	<tbody>
                      	<?php
	                      if (isset($syncProductList) && count($syncProductList) > 0) {
                          foreach ($syncProductList as $skey => $sValue) {
                            echo '<tr><td class="product-image">
	                            <img src="'.$sValue->image_link.'" alt=""/></td>
	                            <td>'.$sValue->name.'</td>
	                            <td>'.$sValue->google_status.'</td>
	                            <td>';
                              $p_issues = json_decode($sValue->issues);
	                            if (count($p_issues) > 0) {
                                $str = '';
                                foreach ($p_issues as $key => $issue) {
                                  if ($key <= 2) {
                                    ($key <= 1) ? $str .= $issue.", <br>" : "";
                                  }
                                    ($key == 3) ? $str .= "..." : "";      			
                                 }
                                 echo $str;
                              } else {
	                              echo "---";
	                            }
	                            echo '</td></tr>';
                          }	
                        }else{
                          echo '<tr><td colspan="4">Record not found</td></tr>';
                        } ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                
              </div>
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
$is_need_to_domain_claim = false;
if(isset($googleDetail->google_merchant_center_id) && $googleDetail->google_merchant_center_id && $this->subscriptionId != "" && isset($googleDetail->is_domain_claim) && $googleDetail->is_domain_claim == '0'){
  $is_need_to_domain_claim = true;
}?>
<script type="text/javascript">
$(document).ready(function() {
  //data table js
  $('#tvc-sync-product-list').DataTable({
    "ordering": false,
    "scrollY": "600px",
    "lengthMenu": [ 10, 20, 50, 100, 200 ]
  });
  //auto syncup call
  var is_need_to_update = "<?php echo $is_need_to_update; ?>";  
  if(is_need_to_update == 1 || is_need_to_update == true){
    call_tvc_api_sync_up();
  } 
  //custom call for domain clain while product sync call
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
//Update syncup detail by ajax call
function call_tvc_api_sync_up(){
  var tvs_this = $("#refresh_api");
  $("#tvc_msg").remove();
  $("#refresh_api").css("visibility","hidden");
  $(tvs_this).after('<div class="tvc-nb-spinner" id="tvc-nb-spinner"></div>');
  tvc_helper.tvc_alert("error","Attention !","Sync up is in the process do not refresh the page. it may take few minutes, if GMC product sync count is large.");
  jQuery.post(myAjaxNonces.ajaxurl,{
    action: "tvc_call_api_sync",
    apiSyncupNonce: myAjaxNonces.apiSyncupNonce
  },function( response ){
    var rsp = JSON.parse(response);    
    if(rsp.error == false){
      $("#tvc-nb-spinner").remove();
      tvc_helper.tvc_alert("success","",rsp.message,true,2000);
    }else{
      tvc_helper.tvc_alert("error","",rsp.message,true,2000);
    }
    setTimeout(function(){ location.reload();}, 2000); 
  });
}
</script>
		<?php
  }
}
?>