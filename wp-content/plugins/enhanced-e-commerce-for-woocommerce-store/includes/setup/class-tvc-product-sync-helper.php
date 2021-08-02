<?php
if ( ! class_exists( 'TVCProductSyncHelper' ) ) {
	class TVCProductSyncHelper {
		protected $merchantId;
		protected $accountId;
		protected $currentCustomerId;
		protected $subscriptionId;
		protected $country;
		protected $site_url;
		protected $category_wrapper_obj;
		protected $TVC_Admin_Helper;
		protected $TVC_Admin_DB_Helper;
		public function __construct(){
			$this->includes();
			$this->add_table_in_db();
			$this->TVC_Admin_Helper = new TVC_Admin_Helper();
			$this->TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper(); 
			$this->category_wrapper_obj = new Tatvic_Category_Wrapper();
			$this->merchantId = $this->TVC_Admin_Helper->get_merchantId();
			$this->accountId = $this->TVC_Admin_Helper->get_main_merchantId();
			$this->currentCustomerId = $this->TVC_Admin_Helper->get_currentCustomerId();
			$this->subscriptionId = $this->TVC_Admin_Helper->get_subscriptionId();
			$this->country = $this->TVC_Admin_Helper->get_woo_country();
			$this->site_url = "admin.php?page=enhanced-ecommerce-google-analytics-admin-display&tab=";
			add_action('admin_init', array($this,'add_table_in_db'));
		}
		public function includes(){
		  if (!class_exists('Tatvic_Category_Wrapper')) {
		    require_once(__DIR__ . '/tatvic-category-wrapper.php');
		  }
		  
		}
     /*
     * careate table batch wise for product sync
     */
		public function add_table_in_db(){     
      global $wpdb;
      $tablename = $wpdb->prefix ."ee_product_sync_profile";
      $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $tablename ) );   
      if ( $wpdb->get_var( $query ) === $tablename ) {
          
      }else{     
        $sql_create = "CREATE TABLE ".$tablename." ( `id` BIGINT(20) NOT NULL AUTO_INCREMENT , `profile_title` VARCHAR(100) NULL , `g_cat_id` INT(10) NULL , `g_attribute_mapping` LONGTEXT NOT NULL , `update_date` DATE NOT NULL , `status` INT(1) NOT NULL DEFAULT '1', PRIMARY KEY (`id`) );";         
        if(maybe_create_table( $tablename, $sql_create )){  
          
        }
      }

      $tablename = $wpdb->prefix ."ee_prouct_pre_sync_data";
      $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $tablename ) );   
      if ( $wpdb->get_var( $query ) === $tablename ) {
          
      }else{     
        $sql_create = "CREATE TABLE ".$tablename." ( `id` BIGINT(20) NOT NULL AUTO_INCREMENT , `w_product_id` BIGINT(20) NOT NULL , `w_cat_id` INT(10) NOT NULL , `g_cat_id` INT(10) NOT NULL , `product_sync_profile_id` INT(10) NOT NULL , `update_date` DATE NOT NULL , `status` INT(1) NOT NULL DEFAULT '0', PRIMARY KEY (`id`) );";         
        if(maybe_create_table( $tablename, $sql_create )){  
          
        }
      }
    }
    /*
     * careate products object for product sync
     */
    public function tvc_get_map_product_attribute($products, $tvc_currency, $merchantId){
      if(!empty($products)){
        $items = [];
        $skipProducts = [];
        $product_ids = [];
        $batchId = time();
        $sync_profile = $this->TVC_Admin_DB_Helper->tvc_get_results('ee_product_sync_profile');
        // set profile id in array key
        $sync_profile_data = array();
        if(!empty($sync_profile)){
        	foreach ($sync_profile as $key => $value) {
        		$sync_profile_data[$value->id]= $value; 
        	}
        }
        if(empty($sync_profile_data)){
        	return array("error"=>true,"message"=>"No product sync profiles find.");
        }
        if(empty($products)){
        	return array("error"=>true,"message"=>"Products not found.");
        }
        foreach ($products as $postkey => $postvalue) {
          $product_ids[] = $postvalue->w_product_id;
          $postmeta = [];
          $postmeta = $this->TVC_Admin_Helper->tvc_get_post_meta($postvalue->w_product_id);
          $prd = wc_get_product($postvalue->w_product_id);
          $postObj = (object) array_merge((array) get_post($postvalue->w_product_id), (array) $postmeta);
          
          $product = array(
            'offer_id'=>$postvalue->w_product_id,
            'channel'=>'online',
            'link'=>get_permalink($postvalue->w_product_id),
            'google_product_category'=>$postvalue->g_cat_id
          );

          $temp_product=array();
          $fixed_att_select_list = array("gender", "age_group", "shipping", "tax", "content_language", "target_country", "condition");
          $formArray = "";
          if(isset($sync_profile_data[$postvalue->product_sync_profile_id]) && $sync_profile_data[$postvalue->product_sync_profile_id]->g_attribute_mapping ){
          	$g_attribute_mapping = $sync_profile_data[$postvalue->product_sync_profile_id]->g_attribute_mapping;
          	$formArray = json_decode($g_attribute_mapping, true);
          }
          if(empty($formArray)){
        		return array("error"=>true,"message"=>"Product sync profile not found.");
        	}
          //$formArray = json_decode($postvalue->g_attribute_mapping, true);
          foreach ($fixed_att_select_list as $fixed_key) {
            if(isset($formArray[$fixed_key]) && $formArray[$fixed_key] != "" ){
              if($fixed_key == "shipping" && $formArray[$fixed_key] != ""){
                $temp_product[$fixed_key]['price']['value'] = $formArray[$fixed_key];
                $temp_product[$fixed_key]['price']['currency'] = $tvc_currency;
                $temp_product[$fixed_key]['country'] = $formArray['target_country'];        
              }else if($fixed_key == "tax" && $formArray[$fixed_key] != ""){                
                $temp_product['taxes']['rate'] = $formArray[$fixed_key];
                $temp_product['taxes']['country'] = $formArray['target_country'];
              }else if( $formArray[$fixed_key] != ""){
                $temp_product[$fixed_key] = $formArray[$fixed_key];
              }          
            }
            unset($formArray[$fixed_key]);
          }

          $product = array_merge($temp_product,$product);

          if($prd->get_type() == "variable"){             
            $variation_attributes = $prd->get_variation_attributes();
            $can_add_item_group_id = false;
            $is_color_size = false;
            if(!empty($variation_attributes)){
              foreach($variation_attributes as $va_key => $va_value ){
                $va_key = str_replace("_", " ", $va_key);                  
                if (strpos($va_key, 'color') !== false) {
                  $can_add_item_group_id = true;
                  $is_color_size = true;
                  break;
                }else if (strpos($va_key, 'size') !== false) {
                  $can_add_item_group_id = true;
                  $is_color_size = true;
                  break;
                }
              }
            }
            if(isset($formArray['gender']) && $formArray['gender']!= ""){
              $can_add_item_group_id = true;
            }else if(isset($formArray['age_group']) && $formArray['age_group']!= ""){
              $can_add_item_group_id = true;
            }
            if($can_add_item_group_id == true){
              $p_variations = $prd->get_available_variations();                
              if(!empty($p_variations)){                  
                foreach ($p_variations as $v_key => $v_value) {
                  $postmeta_var = (object)$this->TVC_Admin_Helper->tvc_get_post_meta($v_value['variation_id']);
                  $formArray_val = $formArray['title'];
                  $product['title'] = (isset($postObj->$formArray_val))?$postObj->$formArray_val:get_the_title($postvalue->w_product_id);
                  $tvc_temp_desc_key = $formArray['description'];
                  $product['description'] = (isset($v_value['variation_description']) && $v_value['variation_description'] != "")?$v_value['variation_description']:$postObj->$tvc_temp_desc_key;
                  $product['offer_id'] = $v_value['variation_id'];
                  $product['id'] = $v_value['variation_id'];
                  $product['item_group_id'] = $postvalue->w_product_id;
                  $image_id = $v_value['image_id'];
                  $product['image_link'] = wp_get_attachment_image_url($image_id, 'full');
                  if($is_color_size == true){
                    if(isset($v_value['attributes']) && !empty($v_value['attributes']) ){
                      foreach($v_value['attributes'] as $va_key => $va_value ){
                        $va_key = str_replace("_", " ", $va_key);                  
                        if (strpos($va_key, 'color') !== false) {
                          $product['color'] = $va_value;
                        }else if (strpos($va_key, 'size') !== false) {
                          $product['sizes'] = $va_value;
                        }
                      }
                    }
                  }
                  foreach($formArray as $key => $value){
                    if($key == 'price'){
                      if(isset($postmeta_var->$value) && $postmeta_var->$value > 0){
                        $product[$key]['value'] = $postmeta_var->$value;
                      }else if(isset($postmeta_var->_regular_price) && $postmeta_var->_regular_price && $postmeta_var->_regular_price >0 ){
                        $product[$key]['value'] = $postmeta_var->_regular_price;
                      }else if(isset($postmeta_var->_price) && $postmeta_var->_price && $postmeta_var->_price >0 ){
                        $product[$key]['value'] = $postmeta_var->_price;
                      }else if(isset($postmeta_var->_sale_price) && $postmeta_var->_sale_price && $postmeta_var->_sale_price >0 ){
                        $product[$key]['value'] = $postmeta_var->_sale_price;
                      }
                      if(isset($product[$key]['value']) && $product[$key]['value'] >0){
                        $product[$key]['currency'] = $tvc_currency;
                      }else{
                        $skipProducts[$postmeta_var->ID] = $postmeta_var;
                      }
                    }else if($key == 'sale_price'){
                      if(isset($postmeta_var->$value) && $postmeta_var->$value > 0){
                        $product[$key]['value'] = $postmeta_var->$value;
                      }else if(isset($postmeta_var->_sale_price) && $postmeta_var->_sale_price && $postmeta_var->_sale_price >0 ){
                        $product[$key]['value'] = $postmeta_var->_sale_price;
                      }
                      if(isset($product[$key]['value']) && $product[$key]['value'] >0){
                        $product[$key]['currency'] = $tvc_currency;
                      }                                                
                    }else if($key == 'availability'){
                      $tvc_find = array("instock","outofstock","onbackorder");
                      $tvc_replace = array("in stock","out of stock","preorder");
                      if(isset($postmeta_var->$value) && $postmeta_var->$value != ""){
                        $stock_status = $postmeta_var->$value;
                        $stock_status = str_replace($tvc_find,$tvc_replace,$stock_status);
                        $product[$key] = $stock_status;
                      }else{
                        $stock_status = $postmeta_var->_stock_status;
                        $stock_status = str_replace($tvc_find,$tvc_replace,$stock_status);
                        $product[$key] = $stock_status;
                      }
                    }else if(isset($postmeta_var->$value) && $postmeta_var->$value != ""){$product[$key] = $postmeta_var->$value;                        
                    }
                  }
                  $item = [
                    'merchant_id' => $merchantId,
                    'batch_id' => ++$batchId,
                    'method' => 'insert',
                    'product' => $product
                  ];
                  $items[] = $item;
                }
              }
            }else{
              goto simpleproduct;
            }
          }else{
            simpleproduct: 
            $image_id = $prd->get_image_id();
            $product['image_link'] = wp_get_attachment_image_url($image_id, 'full');     
            foreach($formArray as $key => $value){
              if($key == 'price'){
                if(isset($postObj->$value) && $postObj->$value > 0){
                  $product[$key]['value'] = $postObj->$value;
                }else if(isset($postObj->_regular_price) && $postObj->_regular_price && $postObj->_regular_price >0 ){
                  $product[$key]['value'] = $postObj->_regular_price;
                }else if(isset($postObj->_price) && $postObj->_price && $postObj->_price >0 ){
                  $product[$key]['value'] = $postObj->_price;
                }else if(isset($postObj->_sale_price) && $postObj->_sale_price && $postObj->_sale_price >0 ){
                  $product[$key]['value'] = $postObj->_sale_price;
                }
                if(isset($product[$key]['value']) && $product[$key]['value'] >0){
                  $product[$key]['currency'] = $tvc_currency;
                }else{
                  $skipProducts[$postObj->ID] = $postObj;
                }
              }else if($key == 'sale_price'){
                if(isset($postObj->$value) && $postObj->$value > 0){
                  $product[$key]['value'] = $postObj->$value;
                }else if(isset($postObj->_sale_price) && $postObj->_sale_price && $postObj->_sale_price >0 ){
                  $product[$key]['value'] = $postObj->_sale_price;
                }
                if(isset($product[$key]['value']) && $product[$key]['value'] >0){
                  $product[$key]['currency'] = $tvc_currency;
                }                  
              }else if($key == 'availability'){
                $tvc_find = array("instock","outofstock","onbackorder");
                $tvc_replace = array("in stock","out of stock","preorder");
                if(isset($postObj->$value) && $postObj->$value != ""){
                  $stock_status = $postObj->$value;
                  $stock_status = str_replace($tvc_find,$tvc_replace,$stock_status);
                  $product[$key] = $stock_status;
                }else{
                  $stock_status = $postObj->_stock_status;
                  $stock_status = str_replace($tvc_find,$tvc_replace,$stock_status);
                  $product[$key] = $stock_status;
                }
              }else if(isset($postObj->$value) && $postObj->$value != ""){
                //echo $key."==".$postObj->$value."<br>";
                $product[$key] = $postObj->$value;
              }
            }
            $item = [
              'merchant_id' => $merchantId,
              'batch_id' => ++$batchId,
              'method' => 'insert',
              'product' => $product
            ];            
            $items[] = $item;
          }
        }
        return array('error'=>false, 'items' => $items, 'skip_products'=> $skipProducts, 'product_ids'=>$product_ids);        
      }
    }
    /*
     * batch wise sync product, its call from ajax fuction
     */
    public function call_batch_wise_sync_product($last_sync_product_id = null, $product_batch_size = 100){
    	if (!class_exists('CustomApi')) {
        require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php');
      }
      $CustomApi = new CustomApi();
    	$product_count = $this->TVC_Admin_DB_Helper->tvc_row_count('ee_prouct_pre_sync_data');
      //$count = 0;
      $pre_last_sync_product_id = $last_sync_product_id;
      if( $product_count > 0 ){  
        $tvc_currency =  $this->TVC_Admin_Helper->get_woo_currency(); 
        $merchantId = $this->merchantId;
        $customerId = $this->currentCustomerId;
        $accountId = $this->accountId;
        $subscriptionId =  $this->subscriptionId;  
        $last_sync_product_id =( $last_sync_product_id > 0)?$last_sync_product_id:0;
        global $wpdb;
        $tablename = $wpdb->prefix .'ee_prouct_pre_sync_data';
        $sql = "select * from ".$tablename." where id > ".$last_sync_product_id." LIMIT ".$product_batch_size;
        $products = $wpdb->get_results($sql, OBJECT); 
        $entries = [];       
        if(!empty($products)){
          $p_map_attribute = $this->tvc_get_map_product_attribute($products, $tvc_currency, $merchantId);
          if(!empty($p_map_attribute) && isset($p_map_attribute['items']) && !empty($p_map_attribute['items'])){
            // call product sync API
            $data = [
              'merchant_id' => $accountId,
              'account_id' => $merchantId,
              'subscription_id' => $subscriptionId,
              'entries' => $p_map_attribute['items']
            ];
            $response = $CustomApi->products_sync($data);
            $last_sync_product_id =end($products)->id;
            if($response->error== false){ 
            	//"data"=> $p_map_attribute['items']
            	$products_sync =count($products);
            	return array('error'=> false, 'products_sync' => $products_sync, 'skip_products' => $p_map_attribute['skip_products'], 'last_sync_product_id'=>$last_sync_product_id);
            }else{
            	return array('error'=> true, 'message' => $response->message );
            }          
            // End call product sync API
            $sync_product_ids = (isset($p_map_attribute['product_ids']))?$p_map_attribute['product_ids']:"";
          }else if(!empty($p_map_attribute['message'])){
          	return array('error'=> true, 'message' => $p_map_attribute['message'] );
          }       
        }
      }

    }
		public function wooCommerceAttributes() {
		  global $wpdb;
		  $tve_table_prefix = $wpdb->prefix;
		  $column1 = json_decode(json_encode($this->TVC_Admin_Helper->getTableColumns($tve_table_prefix.'posts')), true);
		  $column2 = json_decode(json_encode($this->TVC_Admin_Helper->getTableData($tve_table_prefix.'postmeta', ['meta_key'])), true);
		  return array_merge($column1, $column2);
		}

		public function tvc_product_sync_popup_html(){			
			$category_wrapper = $this->category_wrapper_obj->category_table_content('mapping');
			ob_start();
			?>
			<div class="modal fade popup-modal create-campa overlay" id="syncProduct" data-backdrop="false">
			  <div class="modal-dialog modal-dialog-centered">
			    <div class="modal-content">      
			      <div class="modal-body">
			        <button type="button" class="close tvc-popup-close" data-dismiss="modal"> &times; </button>
			        <h5>Map your product attributes</h5>
			        <p>Google Merchant Center uses attributes to format your product information for Shopping Ads. Map your product attributes to the Merchant Center product attributes below. You can also edit each product’s individual attributes after you sync your products. Not all fields below are marked required, however based on your shop's categories and your country you might map a few optional attributes as well. See the full guide <a target="_blank" href="https://support.google.com/merchants/answer/7052112">here</a>.
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
			                      <h6 class="heading-tbl"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/icon/woocommerce.svg'; ?>" alt="WooCommerce"/>Commerce Category</h6>
			                    </div>
			                    <div class="col-6">
			                      <h6 class="heading-tbl gmc-image-heading"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/icon/google-shopping.svg'; ?>" alt="google-shopping"/>Google Merchant Center Category</h6>
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
			                    <h6 class="heading-tbl gmc-image-heading"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/icon/google-shopping.svg'; ?>" alt="google-shopping"/>Google Merchant center product attributes</h6>
			                  </div>
			                  <div class="col-6">
			                    <h6 class="heading-tbl"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/icon/woocommerce.svg'; ?>" alt="WooCommerce"/>Commerce product attributes</h6>
			                  </div>
			                </div>
			                <?php
			                $ee_mapped_attrs = unserialize(get_option('ee_prod_mapped_attrs'));
			                $wooCommerceAttributes = $this->wooCommerceAttributes();
			                foreach ($this->TVC_Admin_Helper->get_gmcAttributes() as $key => $attribute) {
			                  $sel_val="";
			                  echo '<div class="row">
			                    <div class="col-6 align-self-center">
			                      <div class="form-group">
			                        <span class="td-head">' . $attribute["field"] . " " . (isset($attribute["required"]) && $attribute["required"] == 1 ? '<span style="color: red;"> *</span>' : "") . '
			                        <div class="tvc-tooltip">
			                          <span class="tvc-tooltiptext tvc-tooltip-right">'.(isset($attribute["desc"])? $attribute["desc"]:"") .'</span>
			                          <img src="'. ENHANCAD_PLUGIN_URL.'/admin/images/icon/informationI.svg" alt=""/>
			                        </div>
			                        </span>                       
			                      </div>
			                    </div>
			                    <div class="col-6 align-self-center">
			                      <div class="form-group">';
			                        $tvc_select_option = $wooCommerceAttributes;
			                        $require = (isset($attribute['required']) && $attribute['required'])?true:false;
			                        $sel_val_def = (isset($attribute['wAttribute']))?$attribute['wAttribute']:"";
			                        if($attribute["field"]=='link'){
			                            echo "product link";
			                        }else if($attribute["field"]=='shipping'){
			                          //$name, $class_id, string $label=null, $sel_val = null, bool $require = false
			                          $sel_val = (isset($ee_mapped_attrs[$attribute["field"]]))?$ee_mapped_attrs[$attribute["field"]]:$sel_val_def;
			                          echo $this->TVC_Admin_Helper->tvc_text($attribute["field"], 'number', '', 'Add shipping flat rate', $sel_val, $require);
			                        }else if($attribute["field"]=='tax'){
			                          //$name, $class_id, string $label=null, $sel_val = null, bool $require = false
			                          $sel_val = (isset($ee_mapped_attrs[$attribute["field"]]))?$ee_mapped_attrs[$attribute["field"]]:$sel_val_def;
			                          echo $this->TVC_Admin_Helper->tvc_text($attribute["field"], 'number', '', 'Add TAX flat (%)', $sel_val, $require);
			                        }else if($attribute["field"]=='content_language'){
			                          echo $this->TVC_Admin_Helper->tvc_language_select($attribute["field"], 'content_language', 'Please Select Attribute', 'en',$require);
			                        }else if($attribute["field"]=='target_country'){
			                          //$name, $class_id, bool $require = false
			                          echo $this->TVC_Admin_Helper->tvc_countries_select($attribute["field"], 'target_country', 'Please Select Attribute', $require);
			                        }else{
			                          if(isset($attribute['fixed_options']) && $attribute['fixed_options'] !=""){
			                            $tvc_select_option_t = explode(",", $attribute['fixed_options']);
			                            $tvc_select_option=[];
			                            foreach( $tvc_select_option_t as $o_val ){
			                              $tvc_select_option[]['field'] = $o_val;
			                            } 
			                            $sel_val = $sel_val_def;
			                            $this->TVC_Admin_Helper->tvc_select($attribute["field"],$attribute["field"],'Please Select Attribute', $sel_val, $require, $tvc_select_option);
			                          }else{
			                            $sel_val = (isset($ee_mapped_attrs[$attribute["field"]]))?$ee_mapped_attrs[$attribute["field"]]:$sel_val_def;
			                          //$name, $class_id, $label="Please Select", $sel_val, $require, $option_list
			                          $this->TVC_Admin_Helper->tvc_select($attribute["field"],$attribute["field"],'Please Select Attribute', $sel_val, $require, $tvc_select_option);
			                          }                          
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
			<div class="progress-bar-wapper">
				<span class="tvc-sync-message">Initializing...</span>
				<div class="progress tvc-sync-progress-db">
				  <div class="progress-bar progress-bar-striped progress-bar-animated tvc-sync-progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
				</div>
				<div class="progress tvc-sync-progress-gmc">
				  <div class="progress-bar progress-bar-striped progress-bar-animated bg-success tvc-sync-success-progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
				<div class="tvc-progress-info">
					<span class="tvc-sync-count">0</span>					
					<span class="tvc-total-count">--</span>
				</div>
			</div>
			<?php
			echo $this->add_product_sync_script();
			return ob_get_clean();
		}//tvc_product_sync_popup_html

		public function add_product_sync_script(){
			$shop_categories_list = $this->TVC_Admin_Helper->get_tvc_product_cat_list();
			?>
			<script>
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
			  onStepChanging: function(e, currentIndex, newIndex) {
			    var shop_categories = JSON.parse("<?php echo $shop_categories_list; ?>");
			    var is_tvc_cat_selecte = false;
			    shop_categories.forEach(function(v,i){
			      if(is_tvc_cat_selecte == false && $("#category-"+v).val() != "" && $("#category-"+v).val() != 0){
			        is_tvc_cat_selecte =true;
			        return false;
			      }
			    });    
			    if(is_tvc_cat_selecte == 1 || is_tvc_cat_selecte == true){
			      return true;
			    }else{
			      tvc_helper.tvc_alert("error","","Select at least one Google Merchant Center Category.",true);
			      return false;
			    }
			  },
			  onStepChanged: function(event, currentIndex, priorIndex) {   
			    $('.steps .current').prevAll().addClass('disabled');    
			  },
			  onFinished: function(event, currentIndex) {
			    var valid=true;
			    jQuery(".field-required").each(function() {
			      if($(this).val()==0 && valid){
			        valid=false;
			        $(this).select2('focus');
			      }
			    });
			    if(!valid){
			      tvc_helper.tvc_alert("error","","Please select all required fields");
			    }else{
			    	$(".actions a[href='#finish']").prop( "disabled", true );
			      submitProductSyncUp();

			    }//check for required fields end        	
			  }
			});

			function submitProductSyncUp(sync_progressive_data = null){
				jQuery("#feed-spinner").css("display", "block");
				$('.progress-bar-wapper').addClass('open');
				var data = {
						action:'tvcajax_product_sync_bantch_wise',
						merchant_id:'<?php echo $this->merchantId; ?>',
						account_id:'<?php echo $this->accountId; ?>',
						customer_id: '<?php echo $this->currentCustomerId; ?>',
						subscription_id: '<?php echo $this->subscriptionId; ?>',
						tvc_data: jQuery("#productSync").serialize(),
						sync_progressive_data:sync_progressive_data
					}
				$.ajax({
	        type: "POST",
	        dataType: "json",
	        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
	        data: data,
	        beforeSend: function(){
	        },
	        success: function(response){
	        	console.log(response);
	        	jQuery("#feed-spinner").css("display", "none");
	        	//var rs = JSON.parse(response);
	        	let sync_produt_p = Math.round(response.sync_progressive_data.sync_produt_p);
	        	let total_product = response.sync_progressive_data.total_product;
	        	let sync_produt = response.sync_progressive_data.sync_produt;
	        	let sync_message = response.sync_progressive_data.sync_message;
	        	let sync_step = response.sync_progressive_data.sync_step;
	        	let is_synced_up = response.sync_progressive_data.is_synced_up;
	        	let last_sync_product_id = response.sync_progressive_data.last_sync_product_id;
	        	let skip_products = response.sync_progressive_data.last_sync_product_id;
	        	let tvc_sync_progress_bar_class = "tvc-sync-progress-bar";
	        	if(sync_step == 1){
	        		jQuery(".tvc-sync-progress-db").css("display","flex");
	        	}else if(sync_step == 2){
	        		jQuery(".tvc-sync-progress-db").hide();
	        		jQuery(".tvc-sync-progress-gmc").css("display","flex");
	        		tvc_sync_progress_bar_class = "tvc-sync-success-progress-bar";
	        	}
	        	
	        	jQuery("."+tvc_sync_progress_bar_class).css("width",sync_produt_p+"%");
	        	jQuery("."+tvc_sync_progress_bar_class).html(sync_produt_p+"%");
	        	jQuery("."+tvc_sync_progress_bar_class).attr("aria-valuenow",sync_produt_p);
	        	jQuery(".tvc-progress-info").show();
	        	jQuery(".tvc-sync-count").html(sync_produt);
	        	jQuery(".tvc-total-count").html(total_product);
	        	jQuery(".tvc-sync-message").html(sync_message);
	        	if(sync_step == 1 &&  is_synced_up == true){
	        		is_synced_up = false;
	        	}

	        	if(is_synced_up == false && sync_step <= 2){
	        		submitProductSyncUp(response.sync_progressive_data);
	        	}else if(sync_step == 2 ){
	        		setTimeout(function(){
	        			$('.progress-bar-wapper').removeClass('open');
	        			jQuery(".tvc-sync-progress-bar").css("width","0%");
	        			jQuery(".tvc-sync-success-progress-bar").css("width","0%");
	        			jQuery(".tvc-sync-progress-bar").html("0%");
	        			jQuery(".tvc-sync-success-progress-bar").html("0%");
	        			jQuery(".tvc-sync-progress-bar").attr("aria-valuenow","0");
	        			jQuery(".tvc-sync-success-progress-bar").attr("aria-valuenow","0");
	        			jQuery(".tvc-sync-count").html("0");
	        			jQuery(".tvc-total-count").html("--");
	        			jQuery(".tvc-sync-message").html("Initialization of products data for push data in Google shopping");
	        			if (response.api_rs.error == false) {
		        			var message = "Your products have been synced in your merchant center account. It takes up to 30 minutes to reflect the product data in merchant center. As soon as they are updated, they will be shown in the \"Product Sync\" dashboard.";
				          if (response.sync_progressive_data.skip_products.length > 0) {
				            message = message + "\n Because of pricing issues, " + response.sync_progressive_data.skip_products.length + " products did not sync.";
				          }
				          tvc_helper.tvc_alert("success","",message);			          
				          window.location.replace("<?php echo $this->site_url.'sync_product_page'; ?>");
				        }else {
					        tvc_helper.tvc_alert("error","",response.api_rs.message);
					      }			          
	        		}, 2000);
	        		setTimeout(function(){
	        			//window.location.replace("<?php echo $this->site_url.'sync_product_page'; ?>");
	        		}, 7000);
	        	}
	          //console.log(response);      
	        }
		    });               
				/*var merchantId = '<?php echo $this->merchantId; ?>';
			  var accountId = '<?php echo $this->accountId; ?>';
			  var customerId = '<?php echo $this->currentCustomerId; ?>';
			  var subscriptionId = '<?php echo $this->subscriptionId; ?>';               
				var formData = jQuery("#productSync").serialize();
				jQuery.post(
			    myAjaxNonces.ajaxurl,
			    {
			      action: "tvcajax-product-syncup",
			      merchantId: merchantId,
			      customerId: customerId,
			      accountId: accountId,
			      subscriptionId: subscriptionId,
			      data: formData,
			      productSyncupNonce: myAjaxNonces.productSyncupNonce
			    },
			    function( response ) {
			      jQuery("#feed-spinner").css("display", "none");
			      //console.log(response);
			      var rsp = JSON.parse(response);
			      if (rsp.status == "success") {
			        $('#syncProduct').modal('hide');
			        var message = "Your products have been synced in your merchant center account. It takes up to 30 minutes to reflect the product data in merchant center. As soon as they are updated, they will be shown in the \"Product Sync\" dashboard.";
			          if (rsp.skipProducts > 0) {
			            message = message + "\n Because of pricing issues, " + rsp.skipProducts + " products did not sync.";
			          }
			          tvc_helper.tvc_alert("success","",message);
			          setTimeout(function(){ 
			            window.location.replace("<?php echo $this->site_url.'sync_product_page'; ?>");
			          }, 7000);
			      } else {
			        tvc_helper.tvc_alert("error","",rsp.message);
			      }
			    }
			  );*/
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
}