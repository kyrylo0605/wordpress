<?php
class TVC_Pricings {
  protected $TVC_Admin_Helper="";
  protected $url = "";
  protected $subscriptionId = "";
  protected $google_detail;
  protected $customApiObj;
  protected $pro_plan_site;

  public function __construct() {
    $this->TVC_Admin_Helper = new TVC_Admin_Helper();
    $this->customApiObj = new CustomApi();
    $this->url = $this->TVC_Admin_Helper->get_connect_url(); 
    $this->subscriptionId = $this->TVC_Admin_Helper->get_subscriptionId(); 
    $this->google_detail = $this->TVC_Admin_Helper->get_ee_options_data(); 
    $this->TVC_Admin_Helper->add_spinner_html();
    $this->pro_plan_site = $this->TVC_Admin_Helper->get_pro_plan_site();     
    $this->create_form();
  }

  public function create_form() {
    $message = ""; $class="";        
    $googleDetail = [];
    $plan_id = 1;
    if(isset($this->google_detail['setting'])){
      if ($this->google_detail['setting']) {
        $googleDetail = $this->google_detail['setting'];
        if(isset($googleDetail->plan_id) && !in_array($googleDetail->plan_id, array("1"))){
          $plan_id = $googleDetail->plan_id;
        }
      }
    }

    $close_icon =ENHANCAD_PLUGIN_URL.'/admin/images/close.png';
    $check_icon =ENHANCAD_PLUGIN_URL.'/admin/images/check.png';
    ?>
<div class="tab-content">
  <?php if($message){
    printf('<div class="%1$s"><div class="alert">%2$s</div></div>', esc_attr($class), esc_html($message));
  }?>
	<div class="tab-pane show active" id="tvc-account-page">
		<div class="tab-card" >			
       <div class="tvc-price-table-features columns-5">
        <div class="tvc-container"> 
          <div class="clearfix">
            <div class="row-heading clearfix">
               <div class="column tvc-blank-col"><span>Features</span></div>
               <div class="column discounted tvc-free-plan">
                  <div class="name-wrap"><div class="name">STARTER</div></div>
                  <div class="tvc-list-price">
                    <div class="price-current"><span class="inner">FREE</span></div>
                    <div class="tvc_month_free">FOREVER FREE</div>
                  </div>
                  <span>
                    <a href="javascript:void(0)" class="btn tvc-btn">Currently Active</a>
                  </span>
               </div>
               <div class="column discounted ">
                  <div class="name-wrap"><div class="name">HUSTLE</div></div>
                  <div class="tvc-list-price-month">
                    <div class="tvc-list-price">
                      <div class="price-normal">
                        <span>$19.00</span>
                        <div class="tvc-plan-off">50% OFF</div>
                      </div>
                      <div class="price-current"><span class="inner">$9<span>/month</span></span></div>
                      <div class="tvc_month_free">Limited Offer</div>
                    </div>
                    <a target="_blank" href="https://conversios.io/checkout/?pid=plan_1_m&utm_source=EE+Plugin+User+Interface&utm_medium=HUSTLE&utm_campaign=Upsell+at+Conversios" class="btn tvc-btn">Try 1 Month Free</a>
                  </div>              
               </div>
               <div class="column discounted popular">
                <div class="tvc_popular">
                  <div class="tvc_popular_inner">POPULAR</div>
                </div>
                  <div class="name-wrap">
                     <div class="name">GROWTH</div>                
                  </div>
                  <div class="tvc-list-price-month">
                    <div class="tvc-list-price">
                      <div class="price-normal"><span>$39.00</span>
                        <div class="tvc-plan-off">50% OFF</div>
                      </div>
                      <div class="price-current"><span class="inner">$19<span>/month</span></span></div>
                      <div class="tvc_month_free">Limited Offer</div>
                    </div>
                    <a target="_blank"  href="https://conversios.io/checkout/?pid=plan_2_m&utm_source=EE+Plugin+User+Interface&utm_medium=GROWTH&utm_campaign=Upsell+at+Conversios" class="btn tvc-btn">Try 1 Month Free</a>
                  </div>              
               </div>
               <div class="column discounted ">
                  <div class="name-wrap">
                     <div class="name">LEAP</div>                
                  </div>
                  <div class="tvc-list-price-month">
                    <div class="tvc-list-price">
                      <div class="price-normal"><span>$59.00</span>
                        <div class="tvc-plan-off">50% OFF</div>
                      </div>
                      <div class="price-current"><span class="inner">$29<span>/month</span></span></div>
                      <div class="tvc_month_free">Limited Offer</div>
                    </div>
                    <a target="_blank" href="https://conversios.io/checkout/?pid=plan_3_m&utm_source=EE+Plugin+User+Interface&utm_medium=LEAP&utm_campaign=Upsell+at+Conversios" class="btn tvc-btn">Try 1 Month Free</a>
                  </div>                
               </div>
            </div>
            <div class="row-subheading clearfix">Google Analytics</div>
            <div class="row-feature clearfix">
               <div class="column">Universal Analytics Tracking</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div>
            <div class="row-feature clearfix">
               <div class="column">Google Analytics 4 Tracking</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div>
            <div class="row-feature clearfix">
               <div class="column">Dual Set up (UA + GA4)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div>
            <div class="row-feature clearfix">
               <div class="column">eCommerce tracking</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Limited)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
            </div><div class="row-feature clearfix">
               <div class="column">Shopping Behavior Analysis</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Limited)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
            </div><div class="row-feature clearfix">
               <div class="column">Checkout Behavior Tracking</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Limited)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
            </div><div class="row-feature clearfix">
               <div class="column">Channel Performance Analysis</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Limited)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
            </div><div class="row-feature clearfix">
               <div class="column">All Pages tracking</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Google Analytics and Google Ads linking</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Custom dimensions tracking</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Custom metrics tracking</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">User id tracking</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">client id tracking</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Scroll tracking</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div>
            <div class="row-feature clearfix">
               <div class="column">Affiliate performance tracking</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div>
            <div class="row-feature clearfix">
               <div class="column">Coupon Performance Tracking</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div>
            <div class="row-feature clearfix">
               <div class="column">Actionable Dashboard</div>
               <div class="column">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
               <div class="column popular">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
            </div>
            <div class="row-feature clearfix">
               <div class="column">Menu tracking</div>
               <div class="column">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
               <div class="column popular">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
            </div>
            <div class="row-feature clearfix">
               <div class="column">Search Query Tracking</div>
               <div class="column">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
               <div class="column popular">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
            </div>
            <div class="row-subheading clearfix">Google Shopping</div>
            <div class="row-feature clearfix">
               <div class="column">Google Merchant Center account management</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Site verification</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Domain claim</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Products Sync via Content API</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(upto 100)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(upto 1000)</div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"><br>(upto 5000)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Unlimited)</div>
            </div><div class="row-feature clearfix">
               <div class="column">Automatic Products Update</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(upto 100)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(upto 1000)</div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"><br>(upto 5000)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Unlimited)</div>
            </div><div class="row-feature clearfix">
               <div class="column">Schedule Product Sync</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Smart Shopping Campaign management</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Smart Shopping reports</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Google Ads and Google Merchant Center account linking</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Remarketing tags</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Dynamic Remarketing Tags for eCommerce events</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Limited)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"><br>(Complete)</div>
            </div><div class="row-feature clearfix">
               <div class="column">Google Ads Conversion tracking</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Product filters for selected products sync</div>
               <div class="column">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
               <div class="column popular">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
            </div><div class="row-feature clearfix">
               <div class="column">Product filter for Smart Shopping Campaign</div>
               <div class="column">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
               <div class="column popular">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
            </div>
            <div class="row-feature clearfix">
               <div class="column">Rule Based Shopping Campaigns</div>
               <div class="column">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
               <div class="column popular">(Upcoming)</div>
               <div class="column">(Upcoming)</div>
            </div>
            <div class="row-subheading clearfix">Support</div>
            <div class="row-feature clearfix">
               <div class="column">Free Google Analytics Audit</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Free Consultation with Shopping Expert</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-feature clearfix">
               <div class="column">Dedicated Customer Success Manager</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div><div class="row-footer clearfix">
               <div class="column">Premium Support (24*7)</div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column"><img src="<?php echo $close_icon; ?>" alt="no"></div>
               <div class="column popular "><img src="<?php echo $check_icon; ?>" alt="yes"></div>
               <div class="column"><img src="<?php echo $check_icon; ?>" alt="yes"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="tvc-guarantee">
        <div class="guarantee">
          <div class="title"><span>15 Days</span>100% No-Risk Money Back Guarantee!</div>
          <div class="description">You are fully protected by our 100% No-Risk-Double-Guarantee. If you don’t like over the next 15 days, then we will happily refund 100% of your money. No questions asked.</div>
        </div>
      </div>
    </div>
	</div>
</div>
<?php
    }
}
?>