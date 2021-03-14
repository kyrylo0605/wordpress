<?php

/**
 * TVC Product Feed Attribute Mapping Wrapper Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'TVC_Product_Feed_Attribute_Mapping_Wrapper' ) ) :

    class TVC_Product_Feed_Attribute_Mapping_Wrapper extends TVC_Attribute_Mapping_Wrapper {

        /**
         * Display the product feed attribute mapping table.
         *
         * @return void
         */
        protected $feedURL;

        protected $_feed_id;

        protected $merchant_id;

        protected $_feed_name;

        protected $_status_id;

        public function display() {

            $queries3 = new TVC_Queries();

            $this->_feed_id = array_key_exists( 'id', $_GET ) && $_GET['id'] ? $_GET['id'] : null;

            $this->merchant_id = $queries3->get_set_merchant_id();

            if(!is_null($this->_feed_id)){
                $queryResult = $queries3->getFeedURL($this->_feed_id);
                $this->feedURL = $queryResult[0]->url;
                $this->_feed_name = $queryResult[0]->feed_title;
                $this->_status_id = $queryResult[0]->status_id;
            }
            echo $this->feed_attribute_buttons_top($this->feedURL,$this->_feed_id);
            // Start the section code.

            echo $this->attribute_mapping_wrapper_table_start( 'none' );

            echo $this->attribute_mapping_wrapper_table_title();

            echo TVC_Attribute_Selector_Element::required_fields();

            echo TVC_Attribute_Selector_Element::highly_recommended_fields();

            echo TVC_Attribute_Selector_Element::recommended_fields();

            echo TVC_Attribute_Selector_Element::optional_fields();

            echo TVC_Attribute_Selector_Element::custom_fields();

            echo $this->feed_attribute_buttons($this->feedURL,$this->_feed_id);

            if(isset($_GET['show']) && $_GET['show'] == '1'){
                echo $this->create_feed_option_model($this->feedURL,$this->_feed_id,$this->_feed_name,$this->merchant_id);
                $spinner_gif = ENHANCAD_PLUGIN_URL . '/images/ajax-loader.gif';
                echo "<script>var id = '.$this->merchant_id.'</script>";
                echo '<div class="feed-spinner" id="feed-spinner" style="display:none;">
				<img id="img-spinner" src="' . $spinner_gif . '" alt="Loading" />
			</div>';
                if($_GET['show'] == '1' && $_GET['subtab'] == 'attribute_map'){
                    echo '<script>
                        function c() {
                            setTimeout(function () {
                                jQuery("#feed-spinner" ).hide();
	                            jQuery("body" ).css( "cursor", "default" );
                                jQuery("button.feedModel").trigger("click");
                                jQuery(".feed-push-button").css("display","none");
                                jQuery("div.modal-popup").append("<p style=\'text-align:left\'>You can push this feed into Merchant center account: <strong>" + id +"</strong> or Go to feed listing.</p>");
                            }, 3000);
                        }
                        c();
                      </script>';
                }else{
                    echo '<script>
                        function c() {
                            setTimeout(function () {
                                jQuery("#feed-spinner" ).hide();
	                            jQuery("body" ).css( "cursor", "default" );
                                jQuery("button.feedModel").trigger("click");
                                jQuery(".feed-push-button").css("display","none");
                                jQuery("div.modal-popup").append("<p style=\'text-align:left\'>You can go to Feed listing to push this into merchant center account:  <strong>" + id +"</strong> OR Continue with additional product attribute mapping and push feed to merchant center from there.</p>");
                                var newURL = location.href.split("&show=1");
                                window.history.pushState(document.title, newURL);
                            }, 3000);
                        }
                        c();
                      </script>';
                }
            }
            echo '<script>
                    jQuery("#attribute_mapping_button").on("click", function(){
                        jQuery("button#tvc-attribute-map-2").trigger("click");
                         var newURL = location.href.split("&show=1&subtab=attribute_map");
                         window.history.pushState(newURL, "&subtab=attribute_map");
                        jQuery("button.close").trigger("click");
                    });
                  </script>';

            echo $this->attribute_mapping_wrapper_table_end();
        }


        /**
         * Returns the html code for the Save & Generate Feed and Save Feed buttons at the bottom of the attributes list.
         *
         * @return string
         */
        private function feed_bottom_buttons() {
            return TVC_Form_Element::feed_generation_buttons(
                'tvc-generate-feed-button-bottom',
                'tvc-save-feed-button-bottom',
                'tvc-view-feed-button-bottom'
            );
        }


        private function feed_attribute_buttons($feed_url,$feed_id) {
            $new = new TVC_List_Table();
            echo $new->api_data($feed_url,$feed_id);
            return TVC_Form_Element::feed_save_generation_buttons( 'tvc-generate-feed-button-bottom', 'tvc-save-feed-button-bottom', 'tvc-view-feed-button-bottom',$feed_url,$feed_id );
        }
        private function feed_attribute_buttons_top($feed_url,$feed_id) {
            $new = new TVC_List_Table();
            echo $new->api_data($feed_url,$feed_id);
            return TVC_Form_Element::feed_save_generation_buttons_top( 'tvc-generate-feed-button-bottom', 'tvc-save-feed-button-bottom', 'tvc-view-feed-button-bottom',$feed_url,$feed_id );
        }


        public function create_feed_option_model($feed_url,$feed_id,$feed_name,$merchant_id){
            $new = new TVC_List_Table();
            echo $new->api_data($feed_url,$feed_id);
            return '<form method="post">
                <div class="modal fade" id="feedModel" tabindex="-1" role="dialog" aria-labelledby="feedModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Your Feed <strong>'.$feed_name.'</strong> is ready to use !</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                                 <div class="modal-body modal-popup"></div>
                                <div class="modal-footer">
                                    <input class="button-primary feed-push-button" id="tvc-save-feed-button-bottom" type="button"  name="new" href="javascript:void(0);" onclick="parent.location=\'admin.php?page=tvc-product-feed-manager&tab=feed-list&url='.$feed_url.'&id=' .$feed_id. '&status=update\'" value="' .  esc_html__('Push Feed', 'tvc-product-feed-manager') . '">
                                    <input class="button-primary" type="button" ' . 'onclick="parent.location=\'admin.php?page=tvc-product-feed-manager\'" name="new" value="' . esc_html__( 'Go to feed listing', 'tvc-product-feed-manager' ) . '" id="tvc-save-feed-button-bottom"/>
                                    <input class="button-primary" type="button" value="' . esc_html__( 'Additional attribute mapping', 'tvc-product-feed-manager' ) . '" id="attribute_mapping_button"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <button type="button" class="btn btn-primary feedModel" data-toggle="modal" data-target="#feedModel" data-whatever="@mdo">feed model</button>';
        }
    }

    // end of TVC_Product_Feed_Attribute_Mapping_Wrapper class

endif;

/*<input class="button-primary" id="tvc-save-feed-button-bottom" type="button" name="new" href="javascript:void(0);" onclick="parent.location=\'admin.php?page=tvc-product-feed-manager&tab=feed-list&url='.$feed_url.'&id=' .$feed_id. '&status=update\'" value="' .  esc_html__('Yes', 'tvc-product-feed-manager') . '">*/
