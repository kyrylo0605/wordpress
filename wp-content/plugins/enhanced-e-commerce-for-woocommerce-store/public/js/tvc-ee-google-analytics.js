(function( $ ) {
  'use strict';
  /**
   * This enables you to define handlers, for when the DOM is ready:
   * $(function() { });
   * When the window is loaded:
   * $( window ).load(function() { }); 
   */  
})( jQuery );
class TVC_Enhanced {
  constructor(options = {}){
    /*"is_admin"=>is_admin(),
    "tracking_option"=>$this->tracking_option,
    "property_id"=>$this->ga_id,
    "measurement_id"=>$this->gm_id,
    "google_ads_id"=>$this->google_ads_id,
    "google_merchant_center_id"=>$this->google_merchant_id,
    "o_add_gtag_snippet"=>$this->ga_ST,
    "o_enhanced_e_commerce_tracking"=>$this->ga_eeT,
    "o_log_step_gest_user"=>$this->ga_gUser,
    "o_impression_thresold"=>$this->ga_imTh,
    "o_ip_anonymization"=>$this->ga_IPA,
    "o_ga_OPTOUT"=>$this->ga_OPTOUT,
    "ads_tracking_id"=>$this->ads_tracking_id,
    "remarketing_tags"=>$this->ads_ert,
    "dynamic_remarketing_tags"=>$this->ads_edrt*/
    this.options = {
      tracking_option: 'UA'
    };
    if(options){
      Object.assign(this.options, options);
    } 
    //console.log(this.options);
    //this.addEventBindings();  
  }
  singleProductaddToCartEventBindings(variations_data){
   // alert("call first");
    var single_btn = document.getElementsByClassName("single_add_to_cart_button");
    if(single_btn.length > 0){
      single_btn[0].addEventListener("click", () => this.add_to_cart_click(variations_data, "Product Pages"));
    }    
  }
  /*
   * check remarketing option 
   */
  is_add_remarketing_tags(){
    if(this.options.is_admin == false && this.options.ads_tracking_id != "" && ( this.options.remarketing_tags == 1 || this.options.dynamic_remarketing_tags == 1)){
      return true;
    }else{
      return false;
    }
  }
  /*
   * check remarketing option 
   */
  view_item_pdp(tvc_po){
    if(this.options.is_admin == true){
      return;
    }

    this.options.page_type="Product Page";
    /*
     * Start UA or GA4
     */
    if((this.options.tracking_option =="UA" || this.options.tracking_option == "BOTH") && this.options.property_id ){
      try {
        gtag("event", "view_item", {
          "event_category": "Enhanced-Ecommerce",
          "event_label": "view_item_"+tvc_po.tvc_n,
          "items": [{
            "id": tvc_po.tvc_i,// Product details are provided in an impressionFieldObject.
            "name":  tvc_po.tvc_n,
            "category":tvc_po.tvc_c,
            "variant": tvc_po.tvc_var,
            "list_name": this.options.page_type,
            "list_position": 1,
            "quantity": tvc_po.tvc_q,
            "price": tvc_po.tvc_p
          }],
          "non_interaction": true,
          "page_type": this.options.page_type,
          "user_type": this.options.user_type,
          "user_id": this.options.user_id,
          "client_id": this.getClientId(),
          "day_type": this.options.day_type,
          "local_time_slot_of_the_day": this.options.local_time
        });

      }catch(err){
        gtag("event", "exception", {
          "description": err,
          "fatal": false
        });
      }
    /*
     * Start GA4
     */
    }else if( this.options.tracking_option == "GA4" && this.options.measurement_id ){
      try {
        gtag("event", "view_item", {
          "event_category": "Enhanced-Ecommerce",
          "event_label": "view_item_"+tvc_po.tvc_n,
          "currency": tvc_lc,
          "items": [{
            "item_id": tvc_po.tvc_i,
            "item_name":  tvc_po.tvc_n,
            "item_category": tvc_po.tvc_c,
            "discount": tvc_po.tvc_pd,
            "affiliation": this.options.affiliation,
            "item_variant": tvc_po.tvc_var,
            "price": tvc_po.tvc_p,
            "currency": tvc_lc,
            "quantity": tvc_po.tvc_q,
            "index":1
          }],
          "non_interaction": true,
          "value": tvc_po.tvc_p * tvc_po.tvc_q,
          "page_type": this.options.page_type,
          "user_type": this.options.user_type,
          "user_id": this.options.user_id,
          "client_id": this.getClientId(),
          "day_type": this.options.day_type,
          "local_time_slot_of_the_day": this.options.local_time
        });
      }catch(err){
        gtag("event", "exception", {
          "description": err,
          "fatal": false
        });
      }
    }

    //add remarketing and dynamicremarketing tags
    if(this.is_add_remarketing_tags()){
      gtag("event","view_item", {
        "send_to":this.options.remarketing_snippet_id,
        "ecomm_pagetype":"product",
        "value": tvc_po.tvc_p,
        "items": [{
          "id": tvc_po.tvc_id, 
          "google_business_vertical": "retail"
        }]
      });
    }

    

  }
  get_variation_data_by_id(variations_data, variation_id){
    //console.log(variations_data.available_variations)
    var r_val = "";
    if(variations_data.available_variations.length > 0 ){
      variations_data.available_variations.forEach((element, index) => { 
        if(element.variation_id == variation_id){
          r_val = element;
        }
      });
      return r_val;
      //console.log(variations_data.available_variations)
    }
  }
  get_variation_attribute_name(p_attributes){
    //console.log(p_attributes);
    var p_v_title = "";
    if(Object.keys(p_attributes).length >0 ){
      for(var index in p_attributes) {
        p_v_title+=(p_v_title=="")?p_attributes[index]:' | '+p_attributes[index];
        
      }
      return p_v_title;
    }
  }
  add_to_cart_click( variations_data, page_type ="Product Pages" ){
    if(this.options.is_admin == true){
      return;
    }
    this.options.page_type = page_type;
    var varPrice = tvc_po.tvc_p;
    var event_label="add_to_cart_";    
    var variation_attribute_name= "";
    var vari_data ="";
    var variation_id = "";
    var variation_id_obj = document.getElementsByClassName("variation_id");
    if (variation_id_obj.length > 0) {
      variation_id = document.getElementsByClassName("variation_id")[0].value;
    }
    
    if(variation_id != ""){
      vari_data = this.get_variation_data_by_id(variations_data, variation_id);
      var p_attributes = vari_data.attributes;
      if( Object.keys(p_attributes).length > 0){
        variation_attribute_name = this.get_variation_attribute_name(p_attributes);
      }
      if(vari_data.display_price){
        varPrice = vari_data.display_price;
      }else if(vari_data.display_regular_price){
        varPrice = vari_data.display_regular_price;
      }      
    }
    //console.log(variation_attribute_name);
   
    
    if(variation_id != ""){
      event_label="add_to_cart_"+this.options.page_type+" | "+tvc_po.tvc_n+" | "+variation_attribute_name;
    }else if (tvc_po.is_featured == true){
      event_label="add_to_cart_"+this.options.page_type+" | " + this.options.feature_product_label + " | "+tvc_po.tvc_n;
      //console.log("is_featured");
    }else if (tvc_po.is_onSale == true){
      event_label="add_to_cart_"+this.options.page_type+" | " + this.options.on_sale_label + " | "+tvc_po.tvc_n;
      //console.log("is_onSale");
    }else{
      event_label="add_to_cart_"+this.options.page_type+" | "+tvc_po.tvc_n;
    }
    var lastCartTime = this.getCookie("time_add_to_cart");
    var curCartTime = this.getCurrentTime();
    var timeToCart = curCartTime - lastCartTime;
    this.eraseCookie("time_add_to_cart");
    this.setCookie("time_add_to_cart",curCartTime,7);
    /*
     * Start UA or GA4
     */
    if((this.options.tracking_option =="UA" || this.options.tracking_option == "BOTH") && this.options.property_id ){
      try {
        gtag("event", "add_to_cart", {
          "event_category":"Enhanced-Ecommerce",
          "event_label":event_label,
          "non_interaction": true,
          "items": [{
            "id" : tvc_po.tvc_i,
            "name": tvc_po.tvc_n,
            "category" :tvc_po.tvc_c,
            "price": varPrice,
            "quantity" :jQuery(this).parent().find("input[name=quantity]").val(),
            "list_name":this.options.page_type,
            "list_position": 1,
            "variant": variation_attribute_name
          }],
          "page_type": this.options.page_type,
          "user_type": this.options.user_type,
          "user_id": this.options.user_id,
          "client_id":this.getClientId(),
          "day_type": this.options.day_type,
          "local_time_slot_of_the_day": this.options.local_time,
          "product_discount": tvc_po.tvc_pd,
          "stock_status": tvc_po.tvc_ps,
          "inventory": tvc_po.tvc_tst,
          "time_taken_to_add_to_cart": timeToCart
        });
      }catch(err){
        gtag("event", "exception", {
          "description": err,
          "fatal": false
        });
      }
    /*
     * Start GA4
     */
    }else if( this.options.tracking_option == "GA4" && this.options.measurement_id ){
      try {
        gtag("event", "add_to_cart", {
          "event_category":"Enhanced-Ecommerce",
          "event_label":"add_to_cart_click",
          "currency": tvc_lc,
          "non_interaction": true,
          "items": [{
            "item_id" : tvc_po.tvc_i,
            "item_name": tvc_po.tvc_n,
            "item_category" :tvc_po.tvc_c,
            "price":varPrice,
            "currency": tvc_lc,
            "quantity": jQuery(this).parent().find("input[name=quantity]").val(),
            "item_variant": variation_attribute_name,
            "discount": tvc_po.tvc_pd,
            "affiliation":this.options.affiliation
          }],
          "page_type": this.options.page_type,
          "user_type": this.options.user_type,
          "user_id": this.options.user_id,
          "client_id":this.getClientId(),
          "day_type": this.options.day_type,
          "local_time_slot_of_the_day": this.options.local_time,
          "product_discount": tvc_po.tvc_pd,
          "stock_status": tvc_po.tvc_ps,
          "inventory": tvc_po.tvc_tst,
          "time_taken_to_add_to_cart": timeToCart
        }); 
      }catch(err){
        gtag("event", "exception", {
          "description": err,
          "fatal": false
        });
      }
    }    
    //add remarketing and dynamicremarketing tags
    if(this.is_add_remarketing_tags()){
      gtag("event","add_to_cart", {
        "send_to":this.options.remarketing_snippet_id,
        "ecomm_pagetype":"cart",
        "value": varPrice,
        "items": [{
          "id": tvc_po.tvc_id, 
          "google_business_vertical": "retail"
        }]
      });
    }
  }

  /*
   * below code run on thenk you page. 
   * ( Event=> purchase )
   */
  thnkyou_page(tvc_oc, tvc_td, order_status, purchase_time){
    if(this.options.is_admin == true){
      return;
    }
    this.options.page_type="Thankyou Page";
    if(this.is_add_remarketing_tags()){
      var ads_items = [];
      var ads_value=0;
      for(var t_item in tvc_oc){
        ads_value=ads_value + parseFloat(tvc_oc[t_item].tvc_p);
          ads_items.push({
            item_id: tvc_oc[t_item].tvc_i,
            google_business_vertical: "retail"
          });
      }
      gtag("event","purchase", {
        "send_to":this.options.remarketing_snippet_id,
        "ecomm_pagetype":"purchase",
        "value": tvc_td.revenue,
        "items": ads_items
      });
    }
    var last_purchase_time = this.getCookie("time_to_purchase");
    var time_to_purchase = purchase_time - last_purchase_time;
    this.eraseCookie("time_to_purchase");
    this.setCookie("time_to_purchase",purchase_time,7);
    /*
     * Start UA or GA4
     */
    if((this.options.tracking_option =="UA" || this.options.tracking_option == "BOTH") && this.options.property_id ){
      try {
        var items = [];
        //set local currencies
        gtag("set", {"currency": this.options.currency});
        var item_position=1;
        for(var t_item in tvc_oc){
          items.push({
            "id": tvc_oc[t_item].tvc_i,
            "name": tvc_oc[t_item].tvc_n,
            "list_name": this.options.page_type, 
            "category": tvc_oc[t_item].tvc_c,
            "variant": tvc_oc[t_item].tvc_var,            
            "price": tvc_oc[t_item].tvc_p,
            "quantity": tvc_oc[t_item].tvc_q,           
            "list_position": item_position
            //"attributes": tvc_oc[t_item].tvc_attr,
          });  
          item_position++;       
        }

        if( order_status == "failed" ){
          gtag("event", "purchase_failure", {
            "event_category":"Custom",
            "event_label":"purchase_failure_error",
            "transaction_id":tvc_td.id,
            "affiliation": tvc_td.affiliation,
            "value":tvc_td.revenue,
            "currency": tvc_lc,
            "tax": tvc_td.tax,
            "shipping": tvc_td.shipping,
            "coupon": tvc_td.coupon,
            "event_value": tvc_td.revenue, 
            "items":items,
            "non_interaction": true,
            "shipping_tier": tvc_td.shipping,
            "page_type": this.options.page_type,
            "user_type": this.options.user_type,
            "user_id": this.options.user_id,
            "client_id":this.getClientId(),
            "day_type": this.options.day_type,
            "local_time_slot_of_the_day": purchase_time
          });
        }else{      
          gtag("event", "purchase", {
            "event_category": "Enhanced-Ecommerce",
            "transaction_id":tvc_td.id,
            "affiliation": tvc_td.affiliation,
            "value":tvc_td.revenue,
            "currency": tvc_lc,
            "tax": tvc_td.tax,
            "shipping": tvc_td.shipping,
            "coupon": tvc_td.coupon,          
            "event_label":"order_confirmation",         
            "items":items,
            "non_interaction": true,
            "shipping_tier": tvc_td.shipping,
            "page_type": this.options.page_type,
            "user_type": this.options.user_type,
            "user_id": this.options.user_id,
            "client_id":this.getClientId(),
            "day_type": this.options.day_type,
            "local_time_slot_of_the_day": purchase_time,
            "time_taken_to_make_the_purchase": time_to_purchase
          });
        }
      }catch(err){
        gtag("event", "exception", {
          "description": err,
          "fatal": false
        });
      }
    /*
     * Start GA4
     */
    }else if( this.options.tracking_option == "GA4" && this.options.measurement_id ){
      try {
        var items = [];
        for(var t_item in tvc_oc){
          items.push({
            "item_id": tvc_oc[t_item].tvc_i,
            "item_name": tvc_oc[t_item].tvc_n, 
            "coupon": tvc_td.coupon,
            "affiliation": tvc_td.affiliation,
            "discount":tvc_oc[t_item].tvc_pd,
            "item_category": tvc_oc[t_item].tvc_c,
            "item_variant": tvc_oc[t_item].tvc_attr,
            "price": tvc_oc[t_item].tvc_p,
            "currency": tvc_lc,
            "quantity": tvc_oc[t_item].tvc_q
          });         
        }

        if( order_status == "failed" ){
          gtag("event", "purchase_failure", {
            "event_category":"Custom",
            "event_label":"purchase_failure_error",
            "transaction_id":tvc_td.id,
            "affiliation": tvc_td.affiliation,
            "value":tvc_td.revenue,
            "currency": tvc_lc,
            "tax": tvc_td.tax,
            "shipping": tvc_td.shipping,
            "coupon": tvc_td.coupon,
            "event_value": tvc_td.revenue, 
            "items":items,
            "non_interaction": true,
            "shipping_tier": tvc_td.shipping,
            "page_type": this.options.page_type,
            "user_type": this.options.user_type,
            "user_id": this.options.user_id,
            "client_id":this.getClientId(),
            "day_type": this.options.day_type,
            "local_time_slot_of_the_day": purchase_time
          });
        }else{   
          gtag("event", "purchase", {
            "event_category": "Enhanced-Ecommerce",
            "transaction_id":tvc_td.id,
            "affiliation": tvc_td.affiliation,
            "value":tvc_td.revenue,
            "currency": tvc_lc,
            "tax": tvc_td.tax,
            "shipping": tvc_td.shipping,
            "coupon": tvc_td.coupon,          
            "event_label":"order_confirmation",         
            "items":items,
            "non_interaction": true,
            "shipping_tier": tvc_td.shipping,
            "page_type": this.options.page_type,
            "user_type": this.options.user_type,
            "user_id": this.options.user_id,
            "client_id":this.getClientId(),
            "day_type": this.options.day_type,
            "local_time_slot_of_the_day": purchase_time,
            "time_taken_to_make_the_purchase": time_to_purchase
          });
        }
      }catch(err){
        gtag("event", "exception", {
          "description": err,
          "fatal": false
        });
      }    
    }
  }
  getCurrentTime(){
    if (!Date.now) {
       return new Date().getTime(); 
    }else{
      //Math.floor(Date.now() / 1000)
      return Date.now();
    }
  }
  getClientId() {
    let client_id = this.getCookie("_ga");
    if(client_id != null && client_id != ""){
     return client_id;
    }else{
      return ;
    }

     
  }
  setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
  }
  getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(";");
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==" ") c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
  }
  eraseCookie(name) {   
    document.cookie = name +"=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
  }
  static test(){
    
  }
}