<?php
/**
 * @since      4.0.2
 * Description: Conversios Onboarding page, It's call while active the plugin
 */
if ( ! class_exists( 'Conversios_Onboarding' ) ) {
	class Conversios_Onboarding {		
		protected $TVC_Admin_Helper;
		protected $subscriptionId;
		protected $version;
		protected $connect_url;
		protected $customApiObj;
		protected $app_id =1;
		protected $plan_id = 1;
		protected $tvc_data = array();
		protected $last_login;
		public function __construct( ){
			if ( ! is_admin() ) {
				return;
			}
			$this->includes();
			/**
			 *  Set Var
			 */
			$this->version = PLUGIN_TVC_VERSION;
			$this->customApiObj = new CustomApi();
			$this->TVC_Admin_Helper = new TVC_Admin_Helper();
			$ee_additional_data = $this->TVC_Admin_Helper->get_ee_additional_data();

			$this->connect_url =  $this->TVC_Admin_Helper->get_connect_url();
			$this->tvc_data = $this->TVC_Admin_Helper->get_store_data();
			/**
				* check last login for check RefreshToken
				*/
			//print_r($ee_additional_data); exit;
			if(isset($ee_additional_data['ee_last_login']) && $ee_additional_data['ee_last_login'] != ""){
				$this->last_login = $ee_additional_data['ee_last_login'];
				$current = current_time( 'timestamp' );
				$diffrent_days = floor(( $current - $this->last_login)/(60*60*24));
				if($diffrent_days < 100){
					$this->subscriptionId = $this->TVC_Admin_Helper->get_subscriptionId();
					$g_mail = get_option('ee_customer_gmail');
					$this->tvc_data['g_mail']="";
					if($g_mail){
						$this->tvc_data['g_mail']=$g_mail;
					}
				}
			}

			/**
			 *  call Hooks and function
			 */
			add_action( 'admin_menu', array( $this, 'register' ) );
			// Add the welcome screen to the network dashboard.
			add_action( 'network_admin_menu', array( $this, 'register' ) );
			if($this->subscriptionId == ""){
				add_action( 'admin_init', array( $this, 'maybe_redirect' ), 9999 );
			}
			add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );			
		}
		public function includes() {
	    if (!class_exists('CustomApi.php')) {
	      require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php');
	    }   
	  }	

	  public function get_countries($user_country) {
        $getCountris = file_get_contents(ENHANCAD_PLUGIN_DIR . "/includes/setup/json/countries.json");
        $contData = json_decode($getCountris);
        if (!empty($user_country)) {
            $data = "<select id='selectCountry' name='country' class='form-control slect2bx' readonly='true'>";
            $data .= "<option value=''>Please select country</option>";
            foreach ($contData as $key => $value) {
                $selected = ($value->code == $user_country) ? "selected='selected'" : "";
                $data .= "<option value=" . $value->code . " " . $selected . " >" . $value->name . "</option>";
            }
            $data .= "</select>";
        } else {
            $data = "<select id='selectCountry' name='country' class='form-control slect2bx'>";
            $data .= "<option value=''>Please select country</option>";
            foreach ($contData as $key => $value) {
                $data .= "<option value=" . $value->code . ">" . $value->name . "</option>";
            }
            $data .= "</select>";
        }
        return $data;
    }
	  public function is_checked($tracking_option, $is_val){        
      if($tracking_option == $is_val){
        return 'checked="checked"';
      }
    }
		/**
		 * onboarding page HTML
		 */
		public function welcome_screen() {
			$googleDetail = "";
			$defaulSelection = 1;
			$tracking_option = "UA";
			$login_customer_id ="";
			$completed_last_step ="step-0";
			$complete_step = array("step-0"=>1,"step-1"=>0,"step-2"=>0,"step-3"=>0);
			
			if ( isset($_GET['subscription_id']) && $_GET['subscription_id']){
				$this->subscriptionId = $_GET['subscription_id'];
				if ( isset($_GET['g_mail']) && $_GET['g_mail']){
					$this->tvc_data['g_mail'] = $_GET['g_mail'];
					$completed_last_step ="step-1";
					$complete_step["step-0"] = 1;

					$ee_additional_data = $this->TVC_Admin_Helper->get_ee_additional_data();
					$ee_additional_data['ee_last_login'] = current_time( 'timestamp' );
					$this->TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
				}			
	  		//$this->tvc_data = json_encode($this->tvc_data);
			}

			if($this->subscriptionId != ""){
				$google_detail = $this->customApiObj->getGoogleAnalyticDetail($this->subscriptionId);				
	  		if(property_exists($google_detail,"error") && $google_detail->error == false){
	  			if( property_exists($google_detail, "data") && $google_detail->data != "" ){
		        $googleDetail = $google_detail->data;
		        $this->tvc_data['subscription_id'] = $googleDetail->id;
		        $this->tvc_data['access_token'] = $googleDetail->access_token;
		        $this->tvc_data['refresh_token'] = $googleDetail->refresh_token;
		        $this->plan_id = $googleDetail->plan_id;
		        $login_customer_id = $googleDetail->customer_id;
		        $tracking_option = $googleDetail->tracking_option;
		        if($googleDetail->tracking_option != ''){
		          $defaulSelection = 0;
		        }
		        if($this->tvc_data['g_mail'] != ""){
			        if($googleDetail->measurement_id != "" || $googleDetail->property_id != ""){
			        	$complete_step["step-1"] = 1;
			        }
			        if($googleDetail->google_ads_id != "" ){
			        	$complete_step["step-2"] = 1;
			        }
			        if($googleDetail->google_merchant_center_id != "" ){
			        	$complete_step["step-3"] = 1;
			        }
			      }

		      }
	  		}
			}

			$is_e_e_tracking = (property_exists($googleDetail,"enhanced_e_commerce_tracking") && $googleDetail->enhanced_e_commerce_tracking == 1)?"checked":(($defaulSelection == 1)?"checked":"");
      $is_u_t_tracking = (property_exists($googleDetail,"user_time_tracking") && $googleDetail->user_time_tracking == 1)?"checked":(($defaulSelection == 1)?"checked":"");
      $is_a_g_snippet = (property_exists($googleDetail,"add_gtag_snippet") && $googleDetail->add_gtag_snippet == 1)?"checked":(($defaulSelection == 1)?"checked":"");
      $is_c_i_tracking = (property_exists($googleDetail,"client_id_tracking") && $googleDetail->client_id_tracking == 1)?"checked":(($defaulSelection == 1)?"checked":"");
      $is_e_tracking = (property_exists($googleDetail,"exception_tracking") && $googleDetail->exception_tracking == 1)?"checked":(($defaulSelection == 1)?"checked":"");
      $is_e_l_a_tracking = (property_exists($googleDetail,"enhanced_link_attribution_tracking") && $googleDetail->enhanced_link_attribution_tracking == 1)?"checked":(($defaulSelection == 1)?"checked":"");

      $countries = json_decode(file_get_contents(ENHANCAD_PLUGIN_DIR . "/includes/setup/json/countries.json"));
      $credit = json_decode(file_get_contents(ENHANCAD_PLUGIN_DIR . "/includes/setup/json/country_reward.json"));

      $off_country = "";
      $off_credit_amt = "";
      if(is_array($countries) || is_object($countries)){
        foreach( $countries as $key => $value ){
          if($value->code == $this->tvc_data['user_country']){
            $off_country = $value->name;
            break;
          }
        }
      }

      if(is_array($credit) || is_object($credit)){
        foreach( $credit as $key => $value ){
          if($value->name == $off_country){
            $off_credit_amt = $value->price;
            break;
          }
        }
      }
		?>
		<style>
			#menu-dashboard li.current{display: none;}
			#wpadminbar{display: none;}
		</style>
		<div class="bodyrightpart onbordingbody-wapper">
			<div class="loader-section" id="loader-section"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/ajax-loader.gif';?>" alt="loader"></div>
			<div class="alert-message" id="tvc_popup_box"></div>
			<div class="onbordingbody">
				<div class="site-header">
				  <div class="container">
				    <div class="brand"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/logo.png';?>" alt="Conversios" /></div>
				  </div>
				</div>
				<div class="onbording-wrapper">
			    <div class="container">
			      <div class="smallcontainer">
			        <div class="onbordingtop">
		            <h2>Let’s get you started.</h2>
		            <p>Automate Google Analytics, Dynamic Remarketing & Google Shopping in just 5 minutes.</p>
		          </div>
		          <div class="row">
		            <!-- onborading left start -->
								<div class="onboardingstepwrap">									
									<!-- step-0 start -->
								  <div class="onbordording-step onbrdstep-0 gglanystep <?php if($this->subscriptionId == "" || $this->tvc_data['g_mail']==""){ echo "activestep"; }else{echo "selectedactivestep";} ?>">
							      <div class="stepdtltop" data-is-done="<?php echo $complete_step['step-0']; ?>" id="google-signing" data-id="step_0">
						          <div class="stepleftround">
						            <img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/check-wbg.png'; ?>" alt="" />
						          </div>
						          <div class="stepdetwrap">
					              <h4>Connect Conversios with your website <?php /*<span class="helpicon"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/help-icon.png'; ?>" alt="" /></span> */ ?></h4>
					              <p><?php echo (isset($this->tvc_data['g_mail']) && $this->subscriptionId)?$this->tvc_data['g_mail']:""; ?></p>
						          </div>
							      </div>
							      <div class="stepmoredtlwrp">
						          <div class="stepmoredtl">
						          	
						          	<?php if(!isset($this->tvc_data['g_mail']) || $this->tvc_data['g_mail'] == "" || $this->subscriptionId == ""){?>
						          		<div class="google_connect_url google-btn">
													  <div class="google-icon-wrapper">
													    <img class="google-icon" src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/g-logo.png'; ?>"/>
													  </div>
													  <p class="btn-text"><b>Sign in with google</b></p>
													</div>
						          	<?php } else{?>
						          		<div class="google_connect_url google-btn">
													  <div class="google-icon-wrapper">
													    <img class="google-icon" src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/g-logo.png'; ?>"/>
													  </div>
													  <p class="btn-text mr-35"><b>Reauthorize</b></p>
													</div>
						          	<?php } ?>
						          	<p>Make sure you sign in with the google account that has all privileges to access google analytics, google ads and google merchant center account.</p>						          	
						          </div>
						        </div>
								  </div>
								  <!-- step-0 over -->
								  <!-- step-1 start -->
								  <div class="onbordording-step onbrdstep-1 gglanystep <?php echo ($complete_step['step-1']==1 && $this->tvc_data['g_mail'])?'selectedactivestep':''; ?> <?php if($this->subscriptionId != "" && $this->tvc_data['g_mail']){ echo "activestep"; } ?>">
							      <div class="stepdtltop" data-is-done="<?php echo $complete_step['step-1']; ?>" id="google-analytics" data-id="step_1">
						          <div class="stepleftround">
						            <img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/check-wbg.png'; ?>" alt="" />
						          </div>
						          <div class="stepdetwrap">
					              <h4>Connect Google Analytics Account </h4>
					              <p>Tag your website with all important e-commerce events in Google Analytics.</p>
						          </div>
							      </div>
							      <div class="stepmoredtlwrp">
						          <div class="stepmoredtl">
						            <form action="#">
					                <div class="form-row">
					                  <h5>How do you plan to tag your website?</h5>
				                    <div class="cstmrdobtn-item">
				                      <label for="univeral">
			                          <input type="radio" <?php echo $this->is_checked($tracking_option, "UA"); ?> name="analytic_tag_type" id="univeral" value="UA">
			                          <span class="checkmark"></span>
			                          Universal Analytics (Google Analytics 3)
				                      </label>
				                      <div id="UA" class="slctunivr-filed">
			                          <select class="slect2bx google_analytics_sel" id="ua_web_property_id">
		                              <option value=''>Select Property Id</option>  
			                          </select>
				                      </div>
				                    </div>
					                    <div class="cstmrdobtn-item">
					                      <label for="gglanytc">
				                          <input type="radio" <?php echo $this->is_checked($tracking_option, "GA4"); ?> name="analytic_tag_type" id="gglanytc" value="GA4">
				                          <span class="checkmark"></span>
				                          Google Analytics 4
					                      </label>
					                      <div id="GA4" class="slctunivr-filed">
				                          <select class="slect2bx google_analytics_sel" id="ga4_web_measurement_id">
			                              <option value=''>Select Measurement Id</option>   
				                          </select>   
					                      </div>
					                    </div>
					                    <div class="cstmrdobtn-item">
					                      <label for="both">
				                          <input type="radio" <?php echo $this->is_checked($tracking_option, "BOTH"); ?> name="analytic_tag_type" id="both" value="BOTH">
				                          <span class="checkmark"></span>
				                          Both
					                      </label>
					                      <div id="BOTH" class="slctunivr-filed">
				                          <div class="botslectbxitem">
				                            <select class="slect2bx google_analytics_sel" id="both_web_property_id">
				                              <option value=''>Select Property Id</option>
				                          </select>
				                          </div>
				                          <div class="botslectbxitem">
			                              <select class="slect2bx google_analytics_sel" id="both_web_measurement_id">
			                                <option value=''>Select Measurement Id</option>  
				                            </select>
				                          </div>
					                      </div>
					                    </div>
					                </div>
					                <div class="form-row">
				                    <h5>Advance Settings (Optional)</h5>
				                    <div class="chckbxbgbx">
			                        <div class="cstmcheck-item">
		                            <label for="enhanced_e_commerce_tracking">
		                              <input type="checkbox"  class="custom-control-input" name="enhanced_e_commerce_tracking" id="enhanced_e_commerce_tracking" <?php echo $is_e_e_tracking; ?>>
		                              <span class="checkmark"></span>
		                                Enhaced e-commerce tracking
		                            </label>
			                        </div>
			                        <div class="cstmcheck-item">
		                            <label for="add_gtag_snippet">
		                              <input type="checkbox" class="custom-control-input" name="add_gtag_snippe" id="add_gtag_snippet" <?php echo $is_a_g_snippet; ?>>
		                              <span class="checkmark"></span>
		                                Add gtag.js snippet
		                            </label>
			                        </div>
				                    </div>
					                </div>
					                <div class="stepsbmtbtn">
					                	<input type="hidden" id="subscriptionPropertyId" name="subscriptionPropertyId"  value="<?php echo (property_exists($googleDetail,"property_id"))?$googleDetail->property_id:""; ?>">
					                	<input type="hidden" id="subscriptionMeasurementId" name="subscriptionMeasurementId" value="<?php echo (property_exists($googleDetail,"measurement_id"))?$googleDetail->measurement_id:""; ?>">
					                  <button type="button" disabled id="step_1" class="stepnextbtn stpnxttrgr">Next</button>
					                  <!-- remove dslbbtn class for green button -->
					                </div>
						            </form>
						          </div>
							      </div>
								  </div>
								  <!-- step-1 over -->
								  <!-- step-2 start -->
								  <div class="onbordording-step onbrdstep-2 ggladsstep <?php echo ($complete_step['step-2']==1)?'selectedactivestep':''; ?>">
							      <div class="stepdtltop" data-is-done="<?php echo $complete_step['step-2']; ?>" id="google-ads" data-id="step_2">
						          <div class="stepleftround">
						            <img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/check-wbg.png'; ?>" alt="" />
						          </div>
						          <div class="stepdetwrap">
						            <h4>Select Google Ads account</h4>
						            <p>With dynamic reamarketing tags, you will be able to show ads to your past visitors with specific product information tailored to your customer’s previous site visit.</p>
						          </div>
							      </div>
							      <div class="stepmoredtlwrp">
						          <div class="stepmoredtl">
						            <form action="#">
						              <div class="selcttopwrap" id="tvc_ads_section">
						                <div class="ggladsselectbx">
						                	<input type="hidden" id="subscriptionGoogleAdsId" name="subscriptionGoogleAdsId" value="<?php echo property_exists($googleDetail,"google_ads_id")?$googleDetail->google_ads_id:""; ?>">
						                  <select class="slect2bx google_ads_sel" id="ads-account" name="customer_id">
					                      <option value=''>Select Google Ads Account</option>  
						                  </select>
						                </div>
						                <div class="orwrp">or</div>
						                <div class="creatnewwrp">
						                  <button type="button" class="cretnewbtn newggladsbtn"><span class="plusicon"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/blue-plus.png'; ?>" alt="" /></span> Create New</button>
						                </div>
						              </div>

						              <div class="selcttopwrap">                          
	                          <div class="onbrdpp-body alert alert-primary" style="display:none;" id="new_google_ads_section">
                              <h4>Account Created</h4>
                              <p>Your Google Ads Account has been created <strong>(<b><span id="new_google_ads_id"></span></b>).</strong></p>
                             	<h5>Steps to claim your Google Ads Account:</h5>
                              <ol>
						                    <li>Accept invitation mail from Google Ads sent to your email address <em><?php echo (isset($this->tvc_data['g_mail']))?$this->tvc_data['g_mail']:""; ?></em></li>
						                    <li>Log into your Google Ads account and set up your <em>billing preferences</em></li>
							                </ol>                          
	                          </div>
	                        </div>

						              <div class="form-row">
						              	<?php
						              	$is_r_tags = (property_exists($googleDetail,"remarketing_tags") && $googleDetail->remarketing_tags == 1)?"checked":(($defaulSelection == 1)?"checked":"");
		                        $is_l_g_an_w_g_ad = (property_exists($googleDetail,"link_google_analytics_with_google_ads") && $googleDetail->link_google_analytics_with_google_ads == 1)?"checked":(($defaulSelection == 1)?"checked":"");
		                        $is_d_r_tags = (property_exists($googleDetail,"dynamic_remarketing_tags") && $googleDetail->dynamic_remarketing_tags == 1)?"checked":(($defaulSelection == 1)?"checked":"");
		                        $is_g_ad_c_tracking = (property_exists($googleDetail,"google_ads_conversion_tracking") && $googleDetail->google_ads_conversion_tracking == 1)?"checked":(($defaulSelection == 1)?"checked":"");
		                        ?>
						                <h5>Advance Settings (Optional)</h5>
					                  <div class="chckbxbgbx dsplcolmview">
				                      <div class="cstmcheck-item">
			                          <label for="remarketing_tag">
			                            <input type="checkbox" class="custom-control-input" name="remarketing_tag" id="remarketing_tag" value="1" <?php echo $is_r_tags; ?>>
			                            <span class="checkmark"></span>
			                              Enable Google Remarketing Tag
			                          </label>
				                      </div>
				                      <div class="cstmcheck-item">
			                          <label for="dynamic_remarketing_tags">
			                            <input type="checkbox" class="custom-control-input" name="dynamic_remarketing_tags" id="dynamic_remarketing_tags" value="1" <?php echo $is_d_r_tags; ?>>
			                            <span class="checkmark"></span>
			                              Enable Dynamic Remarketing Tag
			                          </label>
				                      </div>
				                       <div class="cstmcheck-item <?php if($this->plan_id == 1){?>cstmcheck-item-pro <?php } ?>">
			                          <label for="google_ads_conversion_tracking">
			                          	<?php if($this->plan_id != 1){?>
				                            <input type="checkbox" class="custom-control-input" name="google_ads_conversion_tracking" id="google_ads_conversion_tracking" value="1" <?php echo $is_g_ad_c_tracking; ?>>
				                            <span class="checkmark"></span>
				                            Google Ads conversion tracking
				                          <?php }else{?>
				                          	<img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/icon/lock.svg'; ?>"><label>Google Ads conversion tracking (Pro Plan)</label>
				                          <?php } ?>                 				                          
			                          </label>
				                      </div>
				                      <div class="cstmcheck-item">
			                          <label for="link_google_analytics_with_google_ads">
			                             <input type="checkbox" class="custom-control-input" name="link_google_analytics_with_google_ads" id="link_google_analytics_with_google_ads" value="1" <?php echo $is_l_g_an_w_g_ad; ?>>
			                            <span class="checkmark"></span>
			                              Link Google Analytics with Google Ads
			                          </label>
				                      </div>				                      
					                  </div>
						              </div>
						              <div class="stepsbmtbtn">
						                <button type="button" id="step_2" class="stepnextbtn stpnxttrgr">Next</button>
						                  <!-- add dslbbtn class for disable button -->
						              </div>
						            </form>
						          </div>
							      </div>
								  </div>
								  <!-- step-2 over -->
								  <!-- step-3 start -->
								  <div class="onbordording-step onbrdstep-3 gglmrchntstep <?php echo ($complete_step['step-3']==1)?'selectedactivestep':''; ?>">
							      <div class="stepdtltop" data-is-done="<?php echo $complete_step['step-3']; ?>" id="gmc-account" data-id="step_3">
						          <div class="stepleftround">
						            <img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/check-wbg.png'; ?>" alt="" />
						          </div>
						          <div class="stepdetwrap">
					              <h4>Select Google Merchant Center Account</h4>
					              <p>Make your WooCommerce shop and products available to millions of shoppers across google.</p>
						          </div>
							      </div>
							      <div class="stepmoredtlwrp">
						          <div class="stepmoredtl">
						            <form action="#">
					                <div class="selcttopwrap">
					                	<div class="form-group" style="display:none;" id="new_merchant_section">
		                          <div class="text-center">                        
		                            <div class="alert alert-primary" style="padding: 10px;" role="alert">                          
		                              <label class="form-label-control font-weight-bold">We have created new merchant center account with ID: <span id="new_merchant_id"></span>. Click on finish button to save new account.</label>
		                            </div>
		                          </div>
		                        </div>
		                        <div id="tvc_merchant_section">
						                  <div class="ggladsselectbx">
						                    <select class="slect2bx" id="google_merchant_center_id" name="google_merchant_center_id">
					                        <option value=''>Select Measurement Id</option>   
						                    </select>
						                  </div>
						                  <div class="orwrp">or</div>
						                  <div class="creatnewwrp">
						                    <button type="button" class="cretnewbtn newmrchntbtn"><span class="plusicon"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/blue-plus.png'; ?>" alt="" /></span> Create New</button>
						                  </div>
						                </div>
					                </div>
						              <div class="stepsbmtbtn">
						                <button type="button" id="step_3" data-enchanter="finish" class="stepnextbtn finishbtn">Finish</button>
						                <!-- add dslbbtn class for disable button -->
						              </div>
						              <input type="hidden" id="subscriptionMerchantCenId" name="subscriptionMerchantCenId" value="<?php echo property_exists($googleDetail,"google_merchant_center_id")?$googleDetail->google_merchant_center_id:""; ?>">
                          <input type="hidden" id="loginCustomerId" name="loginCustomerId"  value="<?php echo $login_customer_id; ?>">
                          <input type="hidden" id="subscriptionId" name="subscriptionId"  value="<?php echo $this->subscriptionId; ?>">
                          <input type="hidden" id="plan_id" name="plan_id" value="<?php echo $this->plan_id; ?>">
						              <input type="hidden" id="conversios_onboarding_nonce" name="conversios_onboarding_nonce" value="<?php echo wp_create_nonce( 'conversios_onboarding_nonce' ); ?>">

						              <input type="hidden" id="ga_view_id" name="ga_view_id" value="<?php echo get_option('ee_ga_view_id'); ?>">
						            </form>
						          </div>
						          <div class="stepnotewrp">
						            If you are in the European Economic Area or Switzerland your Merchant Center account must be associated with a Comparison Shopping Service (CSS). Please find more information at <a href="">Google Merchant Center Help</a> website. If you create a new Merchant Center account through this application, it will be associated with Google Shopping, Google’s CSS, by default. You can change the CSS associated with your account at any time. Please find more information about our CSS Partners <a href="">here</a>. Once you have set up your Merchant Center account you can use our onboarding tool regardless of which CSS you use.
						          </div>
							      </div>
								  </div>
								  <!-- step-3 over -->
								</div> 
								<!-- onborading left over -->
	              <!-- onborading right panel start -->
	              <div class="onbording-right">
	                <div class="sidebrcontainer">
	                  <div class="onbrd-rdmbx">
	                    <div class="rdm-amnt">
	                      <small>Redeem upto</small>
	                      <?php echo $off_credit_amt; ?>
	                    </div>
	                    <p>Create your first Google Ads account with us and redeem upto <?php echo $off_credit_amt; ?> on the spend you make in the next 31 days.</p>
	                    <a target="_blank" href="https://conversios.io/help-center/Google-Spend-Match.pdf" class="lrnmorbtn">Learn more <img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/arrow_right.png'; ?>" alt="" /></a>
	                  </div>
	                  <div class="onbrdrgt-nav">
	                    <ul>
	                      <li><a target="_blank" href="https://conversios.io/help-center/Installation-Manual.pdf">Installation Manual</a></li>
	                      <li><a target="_blank" href="https://conversios.io/help-center/Google-shopping-Guide.pdf" href="">Google Shopping Guide</a></li>
	                      <li><a target="_blank" href="https://wordpress.org/plugins/enhanced-e-commerce-for-woocommerce-store/faq/" href="">FAQ</a></li>
	                    </ul>
	                  </div>
	                </div>
	              </div>
	              <!-- onborading right panel over -->
		          </div>
			      </div>
			    </div>
				</div>
			</div>
		</div>
		<!-- google ads skip confirm poppup -->
		<div class="pp-modal onbrd-popupwrp" id="tvc_ads_skip_confirm" tabindex="-1" role="dialog">
      <div class="onbrdppmain" role="document">
        <div class="onbrdnpp-cntner acccretppcntnr">
          <div class="onbrdnpp-hdr">
          	<h4>You have not selected Google Ads account.</h4>
            <div class="ppclsbtn clsbtntrgr"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/close-icon.png';?>" alt="" /></div>
          </div>
          <div class="onbrdpp-body">
            <p>If you do not select Google Ads account, you will not be able to use some of the major features like:</p>
            <ul>
              <li>Dynamic Remarketing Tags </li>
              <li>Google Smart Shopping Campaigns</li>
              <li>Google Analytics and Google Ads linking</li>
            </ul>
            <p>Are you sure you want to continue without selecting Google Ads account?</p>
          </div>
          <div class="ppfooterbtn">
            <button type="button" class="ppblubtn btn-secondary" data-dismiss="modal" id="ads-skip-cancel">Cancel</button>
            <button type="button" class="ppblubtn btn-primary" data-dismiss="modal" id="ads-skip-continue">Continue</button>
          </div>
        </div>
      </div>
    </div>
		<!-- google ads poppup -->
		<div id="ggladspopup" class="pp-modal onbrd-popupwrp ggladspp">
	    <div class="onbrdppmain">
        <div class="onbrdnpp-cntner ggladsppcntnr">
          <div class="onbrdnpp-hdr">
            <h4>Enable Google Ads Account</h4>
            <div class="ppclsbtn clsbtntrgr"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/close-icon.png';?>" alt="" /></div>
          </div>
          <div class="onbrdpp-body">
            <p>You’ll receive an invite from Google on your email. Accept the invitation to enable your Google Ads Account.</p>
          </div>
          <div class="ppfooterbtn">
            <button type="button" id="ads-continue" class="ppblubtn sndinvitebtn">Send Invite</button>
          </div>
        </div>
	    </div>
		</div>
		<!-- merchant center skip confirm -->
		<div class="pp-modal onbrd-popupwrp" id="tvc_merchant_center_skip_confirm">
      <div class="onbrdppmain">
        <div class="onbrdnpp-cntner acccretppcntnr">
          <div class="onbrdnpp-hdr">
            <h4>You have not selected Google merchant center account.</h4>
            <div class="ppclsbtn clsbtntrgr"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/close-icon.png';?>" alt="" /></div>
          </div>
          <div class="onbrdpp-body">
            <p>If you do not select a merchant center account, you will not be able to use complete google shopping features.</p>
            <p>Are you sure you want to continue without selecting a merchant center account?</p>
          </div>
          <div class="ppfooterbtn">
            <button type="button" class="ppblubtn btn-secondary" data-dismiss="modal" id="merchant-center-skip-cancel">Cancel</button>
            <button type="button" class="ppblubtn btn-primary" data-dismiss="modal" id="merchant-center-skip-continue">Continue</button>
          </div>
        </div>
      </div>
    </div>
		<!-- Create New Merchant poppup -->
		<div id="createmerchantpopup" class="pp-modal onbrd-popupwrp crtemrchntpp">
	    <div class="onbrdppmain">
        <div class="onbrdnpp-cntner crtemrchntppcntnr">
          <div class="ppclsbtn clsbtntrgr"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/close-icon.png'; ?>" alt="" /></div>
          <div class="onbrdpp-body">
            <div class="row">
              <div class="crtemrchnpp-lft">
                <div class="crtemrchpplft-top">
                  <h4>Create Google Merchant Center Account</h4>
                  <p>Before you can upload product data, you’ll need to verify and claim your store’s website URL. Claiming associates your website URL with your Google Merchant Center account.</p>
                </div>
                <div class="claimedbx">
                    Your site will automatically be claimed and verified.
                </div>
                <div class="mrchntformwrp">
                  <form action="#">
                    <div class="form-row">
                    	<input type="hidden" id="get-mail" name="g_email" value="<?php echo isset($this->tvc_data['g_mail'])?$this->tvc_data['g_mail']:""; ?>">
                    	<input type="text" value="<?php echo $this->tvc_data['user_domain']; ?>" class="fromfiled" name="url" id="url" placeholder="Enter Website">
                      <div class="cstmcheck-item mt15">
                        <label for="adult_content">
                          <input class="" type="checkbox" name="adult_content" id="adult_content">
                          <span class="checkmark"></span>
                          My site contains
                        </label>
                        <strong>Adult Content</strong>
                      </div>
                    </div>
                    <div class="form-row">
                      <input type="text" class="fromfiled" name="store_name" id="store_name" placeholder="Enter Store Name" required>
                      <div class="inputinfotxt">This name will appear in your Shopping Ads.</div>
                    </div>
                    <div class="form-row">
                    	<?php echo $this->get_countries($this->tvc_data['user_country']); ?>
                    </div>
                    <div class="form-row">
                      <div class="cstmcheck-item">
                        <label for="terms_conditions">
                          <input class="" type="checkbox" name="concent"  id="terms_conditions">
                          <span class="checkmark"></span>
                          I accept the
                        </label>
                        <a target="_blank" href="https://support.google.com/merchants/answer/160173?hl=en">terms & conditions</a>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="ppfooterbtn">
                  <button type="button" id="create_merchant_account" class="cretemrchntbtn">Create Account
                  </button>
                </div>
              </div>
              <div class="crtemrchnpp-right">
                <h6>To use Google Shopping, your website must meet these requirements:</h6>
                <ul>
                  <li><a target="_blank" href="https://support.google.com/merchants/answer/6149970?hl=en">Google Shopping ads policies</a></li>
                  <li><a target="_blank" href="https://support.google.com/merchants/answer/6150127">Accurate Contact Information</a></li>
                  <li><a target="_blank" href="https://support.google.com/merchants/answer/6150122">Secure collection of process and personal data</a></li>
                  <li><a target="_blank" href="https://support.google.com/merchants/answer/6150127">Return Policy</a></li>
                  <li><a target="_blank" href="https://support.google.com/merchants/answer/6150127">Billing terms & conditions</a></li>
                  <li><a target="_blank" href="https://support.google.com/merchants/answer/6150118">Complete checkout process</a></li>
                </ul>
              </div>
            </div>
          </div>
            
        </div>
	    </div>
		</div>

		<!-- congratulation poppup -->
		<div id="tvc_confirm_submite" class="pp-modal onbrd-popupwrp congratepp">
	    <div class="onbrdppmain">
        <div class="onbrdnpp-cntner congratppcntnr">
          <div class="onbrdnpp-hdr txtcnter">
            <h2>Congratulations!!</h2>
            <div class="ppclsbtn clsbtntrgr"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/close-icon.png'; ?>" alt="" /></div>
          </div>
          <div class="onbrdpp-body congratppbody">
            <p>You have been successfully onboarded. Please check the account summary below and confirm.</p>
            <div class="congratppdtlwrp">
              <div class="cngrtppdtl-item" id="google_analytics_property_id_info">
                <div class="cngtrpplft">
                  <span class="cngrtchckicon"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/green-check.png'; ?>" alt="" /></span>
                    Google Analytics Property Id:
                </div>
                <div class="cngtrpprgt" id="selected_google_analytics_property"></div>
              </div>
              <div class="cngrtppdtl-item" id="google_analytics_measurement_id_info">
                <div class="cngtrpplft">
                  <span class="cngrtchckicon"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/green-check.png'; ?>" alt="" /></span>
                    Google Analytics Measurement Id:
                </div>
                <div class="cngtrpprgt" id="selected_google_analytics_measurement"></div>
              </div>
              <div class="cngrtppdtl-item" id="google_ads_info">
                <div class="cngtrpplft">
                  <span class="cngrtchckicon"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/green-check.png'; ?>" alt="" /></span>
                    Google Ads Account:
                </div>
                <div class="cngtrpprgt" id="selected_google_ads_account"></div>
              </div>
              <div class="cngrtppdtl-item" id="google_merchant_center_info">
                <div class="cngtrpplft">
                  <span class="cngrtchckicon"><img src="<?php echo ENHANCAD_PLUGIN_URL.'/admin/images/green-check.png'; ?>" alt="" /></span>
                    Google Merchant Center Account
                </div>
                <div class="cngtrpprgt" id="selected_google_merchant_center"></div>
              </div>
            </div>
          </div>
          <div class="ppfooterbtn">
            <button type="button" id="confirm_selection" class="ppblubtn">Confirm</button>
          </div>
        </div>
	    </div>
		</div>
		<?php
			$this->page_script();
		}
		/**
		 * onboarding page javascript
		 */
		public function page_script(){
			$j_tvc_data = json_encode($this->tvc_data);
			?>
			<script>
				let tvc_data = <?php echo $j_tvc_data; ?>;
				var tvc_ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
				let subscription_id ="<?php echo $this->subscriptionId; ?>";
      	let plan_id ="<?php echo $this->plan_id; ?>";
      	let app_id ="<?php echo $this->app_id; ?>"; 
				/**
				 * Convesios custom script
				 */
				//Step-0
				$(".google_connect_url").on( "click", function() {
		     	const w =600; const h=650;
				 	const dualScreenLeft = window.screenLeft !==  undefined ? window.screenLeft : window.screenX;
			    const dualScreenTop = window.screenTop !==  undefined   ? window.screenTop  : window.screenY;

			    const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
			    const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

			    const systemZoom = width / window.screen.availWidth;
			    const left = (width - w) / 2 / systemZoom + dualScreenLeft;
			    const top = (height - h) / 2 / systemZoom + dualScreenTop;
			 		/*window.open('<?php echo $this->connect_url; ?>','newwindow', config=`height=670,width=670,top=100,left=${left},toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,directories=no,status=no`);*/
			 		var url ='<?php echo $this->connect_url; ?>';
			 		url = url.replace(/&amp;/g, '&');
			    const newWindow = window.open(url, "newwindow", config=      `scrollbars=yes,
			      width=${w / systemZoom}, 
			      height=${h / systemZoom}, 
			      top=${top}, 
			      left=${left},toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,directories=no,status=no
			      `);
			    if (window.focus) newWindow.focus();
				});

				//Step-1				
				$(document).ready(function() {
					let tracking_option = $('input[type=radio][name=analytic_tag_type]:checked').val();
					if(tracking_option != ""){
						if(subscription_id != ""){
	        		call_list_analytics_web_properties(tracking_option, tvc_data);
	        	}
	        	$(".slctunivr-filed").slideUp();
			      $("#"+tracking_option).slideDown();
			      //is_validate_step("step_1");
	        }

		      $("input[type=radio][name=analytic_tag_type]").on( "change", function() {
		      	let tracking_option = this.value;
		      	if(subscription_id != ""){
		        	call_list_analytics_web_properties(tracking_option, tvc_data);
		        	is_validate_step("step_1");
		        }
		        $(".slctunivr-filed").slideUp();
		        $("#"+tracking_option).slideDown();		        
		      });
		    });
				if(subscription_id != ""){
			  	call_list_googl_ads_account(tvc_data);
			  	call_list_google_merchant_account(tvc_data);
			  }

        //Step-2
        // create google ads account
        $("#ads-continue").on('click', function(e){
		    	e.preventDefault();
		      create_google_ads_account(tvc_data);	
		      $('.ggladspp').removeClass('showpopup');	      
		    });
		    // skip google ads account selection
		    $("#ads-skip-continue").on('click', function(e){
          e.preventDefault();
          save_google_ads_data("", tvc_data, subscription_id, true );
         	go_next($("#step_2"));
        });
        //Step - 3
        $("#create_merchant_account").on('click', function(e){
          e.preventDefault();
          create_google_merchant_center_account(tvc_data);
        });
        //Click skip merchant center account on popup
        $("#merchant-center-skip-continue").on('click', function(e){
        	e.preventDefault();
        	if(is_validate_step("step_1")){
          	save_merchant_data("", "", tvc_data, subscription_id, plan_id, true );
          }else{
          	add_message("error","Please select property/measurement id.");
          }
        })
        //Click finish button
        $("#step_3").on('click', function(e){
          e.preventDefault();
          let google_merchant_center_id = $("#new_merchant_id").text();
          let merchant_id = "NewMerchant";
          if( google_merchant_center_id == null || google_merchant_center_id =="" ){
            google_merchant_center_id = $('#google_merchant_center_id').val();
            merchant_id =$("#google_merchant_center_id").find(':selected').data('merchant_id');
          }
          if( google_merchant_center_id == null || google_merchant_center_id == "" ){
          	$('#tvc_merchant_center_skip_confirm').addClass('showpopup');
						$('body').addClass('scrlnone');
          }else{
          	if(is_validate_step("step_1")){
            	save_merchant_data(google_merchant_center_id, merchant_id, tvc_data, subscription_id, plan_id, false );
            }else{
          		add_message("error","Please Connect Google Analytics Account.");
          	}
          }
        })
        //Click confirm button on confirm popup
        $('#confirm_selection').on('click', function(e){
          var conversios_onboarding_nonce = $("#conversios_onboarding_nonce").val();
          var tracking_option = $('input[type=radio][name=analytic_tag_type]:checked').val();
          var view_id = "";
          add_message("warning","Process to save your settings. Do not refresh..",false);
          if(tracking_option == "UA"){
          	ga_view_id = $("#ua_web_property_id").find(':selected').data('profileid');
          }else{
          	ga_view_id = $("#both_web_property_id").find(':selected').data('profileid');
          }
          $.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: {action: "update_setup_time_to_subscription", tvc_data:tvc_data, subscription_id:subscription_id, ga_view_id:ga_view_id, conversios_onboarding_nonce:conversios_onboarding_nonce},
            beforeSend: function () {
              loaderSection(true);
            },
            success: function (response) {
            	//console.log(response);
              if (response.error === false) {  
              	if(response.return_url){
              		location.replace( response.return_url);
              	}else{           
                	location.replace( "admin.php?page=enhanced-ecommerce-google-analytics-admin-display&tab=general_settings");  
                }              
              }else{
                loaderSection(false);
              }
            }
          });
        });

				/**
				 * Convesios defoult html script
				 */
				 $(document).ready(function() {
			    $( ".stepdtltop" ).each(function() {
			        $(this).on("click", function(){
			        	if(subscription_id != ""){
			        		if($(this).attr("data-is-done") == "1"){
			        			if($(this).parent('.onbordording-step').hasClass("activestep")){
			        				$(this).parent('.onbordording-step').removeClass('activestep');
			        			}else{
			          			$('.onbordording-step').removeClass('activestep');
			          			$(this).parent('.onbordording-step').addClass('activestep');
			          		}
			          	}
			          }else{
					    		//alert("First Connect you website.");
					    	}
			        });
			    });

			    $( ".stpnxttrgr" ).each(function() {
			      $(this).on("click", function(event){			      	
				      	var step =$(this).attr("id");	
				      	//step 1 next button call			    
						    if(step == "step_1"){
						    	if(is_validate_step(step)){
						        let tracking_option = $('input[type=radio][name=analytic_tag_type]:checked').val();
						        save_analytics_web_properties( tracking_option, tvc_data, subscription_id );
						        go_next(this);
						        call_list_google_merchant_account(tvc_data);
						      }
						    }
						    //step 2 next button call
						    if(step == "step_2" ){
						    	//event.preventDefault();
					      	let google_ads_id = $("#new_google_ads_id").text();
			            if(google_ads_id ==null || google_ads_id ==""){
			              google_ads_id = $('#ads-account').val();
			            }
			            let tr_ads = save_google_ads_data(google_ads_id, tvc_data, subscription_id, false );
			            if(tr_ads){			            	
			            	go_next(this);
			            }		            
					      }					      
			          
			       });
			    });

			  });
		    $('.slctunivr-filed').slideUp();
		    //
		    
		    function go_next(next_this){
		    	$(next_this).closest('.onbordording-step').find('.stepdtltop').attr("data-is-done","1");
		    	$(next_this).closest('.onbordording-step').addClass('selectedactivestep');
		      $(next_this).closest('.onbordording-step').removeClass('activestep');
		      $( next_this ).closest('.onbordording-step').next('.onbordording-step').addClass('activestep');
		    }
		</script>
		<script>
		  $(document).ready(function(){
		      $(".slect2bx").select2();
		  });
		</script>
		<!-- popup script -->
		<script>
    $(document).ready(function() {
    	//open now google ads account popup
      $(".newggladsbtn").on( "click", function() {
          $('.ggladspp').addClass('showpopup');
          $('body').addClass('scrlnone');
      });
      
      //close any poup whie click on out side
      $('body').click(function(evt){    
        if($(evt.target).closest('#step_2,.cretnewbtn,.finishbtn,.onbrdnpp-cntner, .crtemrchntpp .onbrdppmain').length)
        return;
          $('.onbrd-popupwrp').removeClass('showpopup');
          $('body').removeClass('scrlnone');
        });
      });
      $(".clsbtntrgr, .ppblubtn").on( "click", function() {
          $(this).closest('.onbrd-popupwrp').removeClass('showpopup');
          $('body').removeClass('scrlnone');
      });
      /*
      $(".sndinvitebtn").on( "click", function() {
          
          //$('.acccretpp').addClass('showpopup');
          //$('body').addClass('scrlnone');
      });
      $(".finishbtn").on( "click", function() {
          $('.congratepp').addClass('showpopup');
          $('body').addClass('scrlnone');
          $('.alertbx').removeClass('show');
      });*/
      $(".newmrchntbtn").on( "click", function() {
          $('.crtemrchntpp').addClass('showpopup');
          $('body').addClass('scrlnone');
      });
      /*$(".cretemrchntbtn").on( "click", function() {
          $('.mrchntalert').addClass('show');
      });
      $(".alertclsbtn").on( "click", function() {
          $(this).parent('.alertbx').removeClass('show');
      });*/
			</script>
			<?php
		}
		/**
		 * onboarding page add scripts file
		 */
		public function add_scripts(){
			if(isset($_GET['page']) && $_GET['page'] == "conversios_onboarding"){
				wp_register_style('conversios-select2-css',ENHANCAD_PLUGIN_URL . '/admin/css/select2.css');
				wp_enqueue_style('conversios-style-css',ENHANCAD_PLUGIN_URL . '/admin/css/style.css', array(), $this->version, 'all');
				wp_enqueue_style('conversios-responsive-css',ENHANCAD_PLUGIN_URL . '/admin/css/responsive.css', array(), $this->version, 'all');		
				wp_enqueue_style('conversios-select2-css');


				wp_enqueue_script( 'conversios-jquery-js', ENHANCAD_PLUGIN_URL . '/admin/js/jquery-3.5.1.min.js', array( 'jquery' ), $this->version, false );
				wp_register_script('conversios-select2-js', ENHANCAD_PLUGIN_URL.'/admin/js/select2.min.js');
				wp_enqueue_script('conversios-select2-js');
				wp_enqueue_script( 'conversios-onboarding-js', ENHANCAD_PLUGIN_URL . '/admin/js/onboarding-custom.js', array( 'jquery' ), $this->version, false );
			}
		}
		/**
		 * Onboarding page register menu
		 */
		public function register() {
			// Getting started - shows after installation.
			if(isset($_GET['page']) && $_GET['page'] == "conversios_onboarding"){
				add_dashboard_page(
					esc_html__( 'Welcome to Conversios Onboarding', 'conversios' ),
					esc_html__( 'Welcome to Conversios Onboarding', 'google-analytics-for-wordpress' ),
					apply_filters( 'conversios_welcome', 'manage_options' ),
					'conversios_onboarding',
					array( $this, 'welcome_screen' )
				);
				/*add_submenu_page(
	          '__FILE__',
	          esc_html__('Welcome to Conversios Onboarding', 'enhanced-ecommerce-google-analytics-admin-display'),
	          esc_html__('Welcome to Conversios Onboarding', 'enhanced-ecommerce-google-analytics-admin-display'),
	          'administrator',
	          'conversios_onboarding',
	          array($this, 'welcome_screen'),10
      	);*/
			}
		}
		/**
		 * Check if we should do any redirect.
		 */
		public function maybe_redirect() {

			// Bail if no activation redirect.
			if ( ! get_transient( '_conversios_activation_redirect' ) || isset( $_GET['conversios-redirect'] ) ) {
				return;
			}
			// Delete the redirect transient.
			delete_transient( '_conversios_activation_redirect' );

			// Bail if activating from network, or bulk.
			if ( isset( $_GET['activate-multi'] ) ) { // WPCS: CSRF ok, input var ok.
				return;
			}			
			
			$path = '?page=conversios_onboarding';
			$redirect = admin_url( $path );
			wp_safe_redirect( $redirect );
			exit;
			
		}		
		//End function
	}//End Conversios_Onboarding Class
} 
new Conversios_Onboarding();