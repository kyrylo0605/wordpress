<?php
class SyncProductConfiguration
{
protected $merchantId;
protected $TVC_Admin_Helper;
protected $currentCustomerId;
protected $subscriptionId;
protected $country;
public function __construct(){
	$this->includes();
	$this->TVC_Admin_Helper = new TVC_Admin_Helper();
	$this->merchantId = $this->TVC_Admin_Helper->get_merchantId();
	$this->accountId = $this->TVC_Admin_Helper->get_main_merchantId();
	$this->currentCustomerId = $this->TVC_Admin_Helper->get_currentCustomerId();
  $this->subscriptionId = $this->TVC_Admin_Helper->get_subscriptionId();       
  $this->country = $this->TVC_Admin_Helper->get_woo_country();
  $this->site_url = "admin.php?page=enhanced-ecommerce-google-analytics-admin-display&tab="; 	
  $this->html_run();
}

public function includes(){
  if (!class_exists('Tatvic_Category_Wrapper')) {
    require_once(__DIR__ . '/tatvic-category-wrapper.php');
  }
}

public function html_run(){
	$this->TVC_Admin_Helper->add_spinner_html();
  $this->create_form();
}

public function wooCommerceAttributes() {
    $queries = new TVC_Queries();
    global $wpdb;
    $tve_table_prefix = $wpdb->prefix;
    $column1 = json_decode(json_encode($queries->getTableColumns($tve_table_prefix.'posts')), true);
    $column2 = json_decode(json_encode($queries->getTableData($tve_table_prefix.'postmeta', ['meta_key'])), true);
    return array_merge($column1, $column2);
}

public function create_form(){
  if(isset($_GET['welcome_msg']) && $_GET['welcome_msg'] == true){
    $class = 'notice notice-success';
    $message = esc_html__('Congratulation..! Everthing is now set up. One more step - Sync your WooCommerce products into your Merchant Center and reach out to millions of shopper across Google.');
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
  }
	$category_wrapper_obj = new Tatvic_Category_Wrapper();
	$category_wrapper = $category_wrapper_obj->category_table_content('mapping');
	$syncProductStat = [];
	$syncProductList = [];
  $last_api_sync_up ="";
	$google_detail = $this->TVC_Admin_Helper->get_ee_options_data();
	if(isset($google_detail['prod_sync_status'])){
    if ($google_detail['prod_sync_status']) {
      $syncProductStat = $google_detail['prod_sync_status'];
    }
  }
  if(isset($google_detail['prod_sync_list'])){
    if ($google_detail['prod_sync_list']) {
      $syncProductList = $google_detail['prod_sync_list'];
    }
  }
	if(isset($google_detail['setting'])){
    if ($google_detail['setting']) {
      $googleDetail = $google_detail['setting'];
    }
  }
  if(isset($google_detail['sync_time'])){
    if ($google_detail['sync_time']) {
      $last_api_sync_up = date( 'Y-m-d H:i',$google_detail['sync_time']);
    }
  }
?>
<div class="container-fluid">
	<div class="row">
		<div class= "col col-12">
			<div class="card mw-100" style="padding:0;">
				<div class="card-body">
	        <div class="tab-pane show active" id="googleShoppingFeed">
	          <div class="row">
	            <div class="col-md-6 col-lg-8 border-right">
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
                <?php echo get_google_shopping_tabs_html($this->site_url,$googleDetail->google_merchant_center_id); ?>                          
                </div>
	              <div class="mt-3" id="config-pt2">
	                <div class="sync-new-product" id="sync-product">
	                  <div class="row">
                      <div class="col-12">
                        <div class="d-flex justify-content-between ">
                          <p class="mb-0 align-self-center">Products in your Merchant Center account</p>
                          <button class="btn btn-primary btn-success align-self-center" data-toggle="modal" data-target="#syncProduct">Sync New Products</button>
                          <a href="admin.php?page=enhanced-ecommerce-google-analytics-admin-display&amp;tab=add_campaign_page" class="btn btn-primary btn-success">Create Smart Shopping Campaign</a>
                        </div>
                      </div>
                  	</div>
                    <div class="product-card">
                      <div class="row">
                        <div class="col-sm-6 col-lg-3">
                          <div class="card">
                            <h3 class="pro-title">Total Products</h3>
                            <p class="pro-count"><?php 
                            echo ((!empty($syncProductStat)) ? $syncProductStat->total : "0"); ?></p>
                          </div>
                        </div>
                          <div class="col-sm-6 col-lg-3">
                            <div class="card pending">
                              <h3 class="pro-title">Pending Review</h3>
                              <p class="pro-count">
                              <?php echo (!empty($syncProductStat)) ? $syncProductStat->pending : "0";?></p>
                            </div>
                          </div>
                          <div class="col-sm-6 col-lg-3">
                            <div class="card approved">
                              <h3 class="pro-title">Approved</h3>
                              <p class="pro-count"><?php echo (!empty($syncProductStat)) ? $syncProductStat->approved : "0";?></p>
                            </div>
                          </div>
                          <div class="col-sm-6 col-lg-3">
                            <div class="card disapproved">
                              <h3 class="pro-title">Disapproved</h3>
                              <p class="pro-count"><?php
                              echo (!empty($syncProductStat)) ? $syncProductStat->disapproved : "0"; ?></p>
                            </div>
                          </div>
                      </div>
                		</div>
	                  <div class="row">
	                    <div class="col-12">
	                      <div class="account-performance">
	                        <div class="table-section">
	                          <div class="table-responsive">
	                            <table class="table" style="width:100%">
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
					                            <img src="'.$sValue->imageLink.'" alt=""/></td>
					                            <td>'.$sValue->name.'</td>
					                            <td>'.$sValue->googleStatus.'</td>
					                            <td>';
					                            if (count($sValue->issues) > 0) {
				                                $str = '';
				                                foreach ($sValue->issues as $key => $issue) {
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
	      			</div>                            
	            <div class="col-md-6 col-lg-4">
	              <div class="right-content"> <?php echo get_tvc_help_html(); ?></div>
	            </div>
        		</div>
    			</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade popup-modal create-campaign" id="syncProduct" data-backdrop="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body mt-3">
        <h5>Map your product attributes <span style="float:right"><button type="button" class="close" data-dismiss="modal"> &times; </button> </span></h5>
        <p>Google Merchant Center uses attributes to format your product information for Shopping Ads. Map your product attributes to the Merchant Center product attributes below. You can also edit each product’s individual attributes after you sync your products. Not all fields below are marked required, however based on your shop\'s categories and your country you might map a few optional attributes as well. See the full guide <a target="_blank" href="https://support.google.com/merchants/answer/7052112">here</a>.
        </p>
        <div class="wizard-section campaign-wizard">
          <div class="wizard-content">
          	<input type="hidden" name="merchant_id" id="merchant_id" value="<?php echo $this->merchantId; ?>">
            <form class="tab-wizard wizard-	 wizard" id="productSync" method="POST">
              <h5><span class="wiz-title">Category Mapping</span></h5>
              <section>
                <div class="card-wrapper">                                        
                  <div class="row">
                    <div class="col-6">
                      <h6 class="heading-tbl">WooCommerce Category</h6>
                    </div>
                    <div class="col-6">
                      <h6 class="heading-tbl">Google Merchant Center Category</h6>
                    </div>
                  </div><?php echo $category_wrapper; ?>
                </div>
              </section>
              <!-- Step 2 -->
              <h5><span class="wiz-title">Product Attribution Mapping</span></h5>
              <section>
              <div class="card-wrapper">                                        
                <div class="row">
                  <div class="col-6">
                    <h6 class="heading-tbl">Google Merchant center product attributes</h6>
                  </div>
                  <div class="col-6">
                    <h6 class="heading-tbl">WooCommerce product attributes</h6>
                  </div>
                </div>
                <?php
                foreach ($this->TVC_Admin_Helper->get_gmcAttributes() as $key => $attribute) {
                  echo '<div class="row">
                    <div class="col-6 align-self-center">
                      <div class="form-group">
                        <span class="td-head">' . $attribute["field"] . " " . (isset($attribute["required"]) && $attribute["required"] == 1 ? '<span style="color: red;"> *</span>' : "") . '</span> 
                        <small class="form-label-control">' . (isset($attribute["desc"])? $attribute["desc"]:"") . '</small>
                      </div>
                    </div>
                    <div class="col-6 align-self-center">
                      <div class="form-group">';
                        if($attribute["field"]=='link'){
                            echo "product link";
                        }else if($attribute["field"]=='channel'){
                            echo "online";
                        }else{
                          echo '<select class="form-control select2 ' . (isset($attribute["required"]) && $attribute["required"] == 1 ? "field-required" : "") . '" name="' . $attribute["field"] . '" >
                          	<option value="0">Please Select Attribute</option>';
                            foreach ($this->wooCommerceAttributes() as $wKey => $wAttribute) {
                              echo '<option value="' . $wAttribute["field"] . '"';
                              echo (isset($attribute['required']) && $attribute['required'] == 1 && $attribute['wAttribute'] == $wAttribute["field"]) ? "selected" : "";
                              echo '>' . $wAttribute["field"] . '</option>';
                            }
                          echo '</select>';
                        }
                      echo '</div>
                    </div>
                  </div>';
                }?>
              </div>
              </section>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function call_tvc_api_sync_up(){
  var tvs_this = event.target;
  $("#tvc_msg").remove();
  $("#refresh_api").css("visibility","hidden");
  $(tvs_this).after('<div class="tvc-nb-spinner" id="tvc-nb-spinner"></div>');
  jQuery.post(myAjaxNonces.ajaxurl,{
    action: "tvc_call_api_sync",
    apiSyncupNonce: myAjaxNonces.apiSyncupNonce
  },function( response ){
    var rsp = JSON.parse(response);    
    if(rsp.status == "success"){
      $("#tvc-nb-spinner").remove();
      $(tvs_this).after('<span id="tvc_msg">'+rsp.message+"</span>");
      setTimeout(function(){ $("#tvc_msg").remove(); location.reload();}, 4000);
    }    
  });
}
  

$(document).ready(function() {
	$(".select2").select2();
});
$(".tab-wizard").steps({
  headerTag: "h5",
  bodyTag: "section",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Sync Products",
    next: "Next",
    previous: "Previous",
  },
  onStepChanged: function(event, currentIndex, priorIndex) {
    $('.steps .current').prevAll().addClass('disabled');
  },
  onFinished: function(event, currentIndex) {
    var valid=true;
    jQuery(".field-required").each(function() {
      if($(this).val()==0 && valid){
        valid=false;
      }
    });
    if(!valid){
      alert("Please select all required fields");
    }else{
      submitProductSyncUp();
    }//check for required fields end        	
  }
});

function submitProductSyncUp() {
	var merchantId = '<?php echo $this->merchantId; ?>';
  var accountId = '<?php echo $this->accountId; ?>';
  var customerId = '<?php echo $this->currentCustomerId; ?>';
  var subscriptionId = '<?php echo $this->subscriptionId; ?>';
  var platformCustomerId = jQuery("#platform_customer_id").val();                
	var formData = jQuery("#productSync").serialize();
	//console.log(formData);
	jQuery("#feed-spinner").css("display", "block");                
	jQuery.post(
    myAjaxNonces.ajaxurl,
    {
      action: "tvcajax-product-syncup",
      merchantId: merchantId,
      customerId: customerId,
      accountId: accountId,
      subscriptionId: subscriptionId,
      platformCustomerId: platformCustomerId,
      data: formData,
      productSyncupNonce: myAjaxNonces.productSyncupNonce
    },
    function( response ) {
      jQuery("#feed-spinner").css("display", "none");
      //console.log(response);
      var rsp = JSON.parse(response);
      if (rsp.status == "success") {
        $('#syncProduct').modal('hide');
        var message = "Your product are now being synced in your merchant center account. It takes up to 30 minutes to reflect the product data in merchant center. As soon as they are updated, they will be shown in the \"Product Sync\" dashboard.";
          if (rsp.skipProducts > 0) {
            message = message + "\n Because of pricing issues, " + rsp.skipProducts + " products did not sync.";
          }
          alert(message);
          window.location.replace("<?php echo $this->site_url.'sync_product_page'; ?>");
      } else {
        //var message = "Products sync face some unprocessable entity";
        var message = rsp.message;
        alert(message);
      }
    }
  );
}

$(document).on("show.bs.modal", "#syncProduct", function (e) {
	jQuery("#feed-spinner").css("display", "block");
  selectCategory();
  $("select[id^=catmap]").each(function(){
  	removeChildCategory($(this).attr("id"))
	});
});

function selectCategory() {
  var country_id = "<?php echo $this->country; ?>";
  var customer_id = '<?php echo $this->currentCustomerId?>';
  var parent = "";
  jQuery.post(
    myAjaxNonces.ajaxurl,
    {
      action: "tvcajax-gmc-category-lists",
      countryCode: country_id,
      customerId: customer_id,
      parent: parent,
      gmcCategoryListsNonce: myAjaxNonces.gmcCategoryListsNonce
    },
    function( response ) {
      var categories = JSON.parse(response);
      var obj;
			$("select[id^=catmap]").each(function(){
				obj = $("#catmap-"+$(this).attr("catid")+"_0");
      	obj.empty();
    		obj.append("<option id='0' value='0' resourcename='0'>Select a category</option>");
      	$.each(categories, function (i, value) {
          obj.append("<option id=" + JSON.stringify(value.id) + " value=" + JSON.stringify(value.id) + " resourceName=" + JSON.stringify(value.resourceName) + ">" + value.name + "</option>");                
        });
			});
			jQuery("#feed-spinner").css("display", "none");
  });
}

function selectSubCategory(thisObj) {
	var selectId;
	var wooCategoryId;
	var GmcCategoryId;
	var GmcParent;
	selectId = thisObj.id;
	wooCategoryId = $(thisObj).attr("catid");
	GmcCategoryId = $(thisObj).find(":selected").val();
	GmcParent = $(thisObj).find(":selected").attr("resourcename");
  //$("#"+selectId).select2().find(":selected").val();
  // $("#"+selectId).select2().find(":selected").data("id");
  //console.log(selectId+"--"+wooCategoryId+"--"+GmcCategoryId+"--"+GmcParent);
  	
  jQuery("#feed-spinner").css("display", "block");
	removeChildCategory(selectId);
	selectChildCategoryValue(wooCategoryId);
  if (GmcParent != undefined) {
  	var country_id = "<?php echo $this->country; ?>";
    var customer_id = '<?php echo $this->currentCustomerId?>';
  	jQuery.post(
      myAjaxNonces.ajaxurl,
      {
        action: "tvcajax-gmc-category-lists",
        countryCode: country_id,
        customerId: customer_id,
        parent: GmcParent,
        gmcCategoryListsNonce: myAjaxNonces.gmcCategoryListsNonce
      },
      function( response ) {
        var categories = JSON.parse(response);
        var newId;
      	var slitedId = selectId.split("_");
      	newId = slitedId[0]+"_"+ ++slitedId[1];
      	if(categories.length === 0){		
      	}else{
      		//console.log(newId);
        	$("#"+newId).empty();
        	$("#"+newId).append("<option id='0' value='0' resourcename='0'>Select a sub-category</option>");
          $.each(categories, function (i, value) {
            $("#"+newId).append("<option id=" + JSON.stringify(value.id) + " value=" + JSON.stringify(value.id) + " resourceName=" + JSON.stringify(value.resourceName) + ">" + value.name + "</option>");
          });
          $("#"+newId).addClass("form-control");
          //$("#"+newId).select2();
          $("#"+newId).css("display", "block");
      	}
      	jQuery("#feed-spinner").css("display", "none");
      }
    );	
  }
}

function removeChildCategory(currentId){
	var currentSplit = currentId.split("_");
  var childEleId;
	for (i = ++currentSplit[1]; i < 6; i++) {
		childEleId = currentSplit[0]+"_"+ i;
		//console.log($("#"+childEleId));
  	$("#"+childEleId).empty();
		$("#"+childEleId).removeClass("form-control");
    $("#"+childEleId).css("display", "none");
    if ($("#"+childEleId).data("select2")) {
		  $("#"+childEleId).off("select2:select");
			$("#"+childEleId).select2("destroy");
      $("#"+childEleId).removeClass("select2");
	 	}
	}
}

function selectChildCategoryValue(wooCategoryId){
	var childCatvala;
	for(i = 0; i < 6; i++){
		childCatvala = $("#catmap-"+wooCategoryId+"_"+i).find(":selected").attr("id");
    childCatname = $("#catmap-"+wooCategoryId+"_"+i).find(":selected").text();
		if($("#catmap-"+wooCategoryId+"_"+0).find(":selected").attr("id") <= 0){
			$("#category-"+wooCategoryId).val(0);
		}else{
			if(childCatvala > 0){
				$("#category-"+wooCategoryId).val(childCatvala);
        $("#category-name-"+wooCategoryId).val(childCatname);
			}
		}
	}
}
$( ".wizard-content" ).on( "click", ".change_prodct_feed_cat", function() {
 // console.log( $( this ).attr("data-id") );
  $(this).hide();
  var feed_select_cat_id = $( this ).attr("data-id");
  var woo_cat_id = $( this ).attr("data-cat-id");
  
  jQuery("#category-"+woo_cat_id).val("0");
  jQuery("#category-name-"+woo_cat_id).val("");
  jQuery("#label-"+feed_select_cat_id).hide();
  jQuery("#"+feed_select_cat_id).slideDown();
});
function changeProdctFeedCat(feed_select_cat_id){
  jQuery("#label-"+feed_select_cat_id).hide();
  jQuery("#"+feed_select_cat_id).slideDown();
}
</script>
		<?php
  }
}
?>