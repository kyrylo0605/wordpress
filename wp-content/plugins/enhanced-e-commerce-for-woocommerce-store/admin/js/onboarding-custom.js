function loaderSection(isShow) {
  if (isShow){
    $('#loader-section').show();
  }else{
    $('#loader-section').hide();
  }
}
var tvc_time_out="";
function add_message(type, msg, is_close = true){
  let tvc_popup_box = document.getElementById('tvc_popup_box');
  tvc_popup_box.classList.remove("tvc_popup_box_close");
  tvc_popup_box.classList.add("tvc_popup_box");
  if(type == "success"){
    document.getElementById('tvc_popup_box').innerHTML ="<div class='alert tvc-alert-success'>"+msg+"</div>";
  }else if(type == "error"){
    document.getElementById('tvc_popup_box').innerHTML ="<div class='alert tvc-alert-error'>"+msg+"</div>";
  }else if(type == "warning"){
    document.getElementById('tvc_popup_box').innerHTML ="<div class='alert tvc-alert-warning'>"+msg+"</div>";
  }
  if(is_close){
    tvc_time_out = setTimeout(function(){  //tvc_popup_box.style.display = "none";       
      tvc_popup_box.classList.add("tvc_popup_box_close");
      tvc_popup_box.classList.remove("tvc_popup_box");        
    }, 4000);
  } 
}

function is_validate_step(step){
  var is_valide = false;
  if(step == "step_1"){
    var web_property_id = ""; var ua_account_id = ""; var web_measurement_id = ""; var ga4_account_id = "";
    var tracking_option = $('input[type=radio][name=analytic_tag_type]:checked').val();
    //console.log(tracking_option);
    if(tracking_option == "UA"){
      web_property_id = $('#ua_web_property_id').val();
      ua_account_id = $("#ua_web_property_id").find(':selected').data('accountid');
      if(web_property_id == ""){        
        msg = "Please select web property id.";        
      }else{
        is_valide = true;        
      }
    }else if(tracking_option == "GA4"){
      web_measurement_id = $('#ga4_web_measurement_id').val();
      ga4_account_id = $("#ga4_web_measurement_id").find(':selected').data('accountid');
      if(web_measurement_id == ""){
        msg = "Please select measurement id.";
      }else{
        is_valide = true;
      }
    }else{
      web_property_id = $('#both_web_property_id').val();
      ua_account_id = $("#both_web_property_id").find(':selected').data('accountid');
      web_measurement_id = $('#both_web_measurement_id').val();
      ga4_account_id = $("#both_web_measurement_id").find(':selected').data('accountid');

      if(web_property_id == "" || web_measurement_id == ""){
        msg = "Please select property/measurement id.";
      }else{
        is_valide = true;
      }
    }
    //console.log("is_valide"+is_valide+"-"+tracking_option+"-"+web_property_id);
    if(is_valide){
      $('#step_1').prop('disabled', false);
    }else{
      $('#step_1').prop('disabled', true);
    }
  }else if(step == "step_2"){
    google_ads_id = $('#ads-account').val();
    if(google_ads_id == ""){
      msg = "Please select Google Ads account.";
    }else{
      is_valide = true;
    }
    if(is_valide){
      $('#step_2').prop('disabled', false);
    }else{
      $('#step_2').prop('disabled', true);
    }
    
  }
  return is_valide;
}
$(document).ready(function () {
  loaderSection(false);
  //step-1
  $(".google_analytics_sel").on( "change", function() {
    is_validate_step("step_1");
    $(".onbrdstep-1").removeClass('selectedactivestep');
    $(".onbrdstep-3").removeClass('selectedactivestep');
    $(".onbrdstep-2").removeClass('selectedactivestep');
    $("[data-id=step_1]").attr("data-is-done",0);
    $("[data-id=step_2]").attr("data-is-done",0);
    $("[data-id=step_3]").attr("data-is-done",0);
  }); 
  //step-2
  $(".google_ads_sel").on( "change", function() {
    //$(".onbrdstep-1").removeClass('selectedactivestep');
    $(".onbrdstep-3").removeClass('selectedactivestep');
    $(".onbrdstep-2").removeClass('selectedactivestep');
    //$("[data-id=step_1]").attr("data-is-done",0);
    $("[data-id=step_2]").attr("data-is-done",0);
    $("[data-id=step_3]").attr("data-is-done",0);
  }); 
  $('input[type=checkbox]:not(#adult_content, #terms_conditions)').change(function() {
    //$(".onbrdstep-1").removeClass('selectedactivestep');
    $(".onbrdstep-3").removeClass('selectedactivestep');
    $(".onbrdstep-2").removeClass('selectedactivestep');
   // $("[data-id=step_1]").attr("data-is-done",0);
    $("[data-id=step_2]").attr("data-is-done",0);
    $("[data-id=step_3]").attr("data-is-done",0);
  });
  
  //select2
	//$(".select2").select2();
  // desable to close advance settings
	$(".advance-settings .dropdown-menu").click(function(e){
      e.stopPropagation();
  });
});
//save nalytics web properties while next button click
function save_analytics_web_properties(tracking_option, tvc_data, subscription_id){
  if(subscription_id != ""){
    var web_measurement_id = "";
    var web_property_id = "";
    var ga4_account_id = "";
    var ua_account_id = "";
    var is_valide = true;
    var msg ="";
    if(tracking_option == "UA"){
      web_property_id = $('#ua_web_property_id').val();
      ua_account_id = $("#ua_web_property_id").find(':selected').data('accountid');
      if(web_property_id == ""){
        is_valide = false;
        msg = "Please select web property id.";
      }
    }else if(tracking_option == "GA4"){
      web_measurement_id = $('#ga4_web_measurement_id').val();
      ga4_account_id = $("#ga4_web_measurement_id").find(':selected').data('accountid');
      if(web_measurement_id == ""){
        is_valide = false;
        msg = "Please select measurement id.";
      }
    }else{
      web_property_id = $('#both_web_property_id').val();
      ua_account_id = $("#both_web_property_id").find(':selected').data('accountid');
      web_measurement_id = $('#both_web_measurement_id').val();
      ga4_account_id = $("#both_web_measurement_id").find(':selected').data('accountid');

      if(web_property_id == "" || web_measurement_id == ""){
        is_valide = false;
        msg = "Please select property/measurement id.";
      }
    }
    if(is_valide == true){
      var conversios_onboarding_nonce = $("#conversios_onboarding_nonce").val();
      var data = {
        action: "save_analytics_data",
        subscription_id:subscription_id,
        tracking_option: tracking_option,
        web_measurement_id: web_measurement_id,
        web_property_id: web_property_id,
        ga4_account_id: ga4_account_id,
        ua_account_id: ua_account_id,
        enhanced_e_commerce_tracking: $('#enhanced_e_commerce_tracking').is(':checked'),
        user_time_tracking: $('#user_time_tracking').is(':checked'),
        add_gtag_snippet: $('#add_gtag_snippet').is(':checked'),
        client_id_tracking: $('#client_id_tracking').is(':checked'),
        exception_tracking: $('#exception_tracking').is(':checked'),
        enhanced_link_attribution_tracking: $('#enhanced_link_attribution_tracking').is(':checked'),
        tvc_data:tvc_data,
        conversios_onboarding_nonce:conversios_onboarding_nonce
      };
      $.ajax({
        type: "POST",
        dataType: "json",
        url: tvc_ajax_url,
        data: data,
        beforeSend: function(){
          loaderSection(true);
        },
        success: function(response){
          loaderSection(false);
          if (response.error === false) {          
            add_message("success","Google Analytics successfully updated.");
            return true;
          }else{
            add_message("error","Error while updating Google Analytics.");
            return false;
          }
          
        }
      });
      
    }else{
      add_message("warning",msg);
      return false;
    }
  }else{
    add_message("warning","Missing value of subscription id.");
    return false;
  }  
}

function save_google_ads_data(google_ads_id, tvc_data, subscription_id, is_skip=false){
  var conversios_onboarding_nonce = $("#conversios_onboarding_nonce").val();
  if(google_ads_id || is_skip == true){
    loaderSection(true);
    var data = {
      action: "save_google_ads_data",
      subscription_id:subscription_id,
      google_ads_id: google_ads_id,
      remarketing_tags: $('#remarketing_tag').is(':checked'),
      dynamic_remarketing_tags: $('#dynamic_remarketing_tags').is(':checked'),
      google_ads_conversion_tracking: $("#google_ads_conversion_tracking").is(':checked'),
      link_google_analytics_with_google_ads: $("#link_google_analytics_with_google_ads").is(':checked'),
      tvc_data:tvc_data,
      conversios_onboarding_nonce:conversios_onboarding_nonce
    };
    $.ajax({
      type: "POST",
      dataType: "json",
      url: tvc_ajax_url,
      data: data,
      beforeSend: function () {        
      },
      success: function (response) {
        if(response.error === false) {
          add_message("success","Google Ads successfully updated.");
          //$("#ads-account").val(google_ads_id);
          let tracking_option = $('input:radio[name=analytic_tag_type]:checked').val();
          var s_tracking_option = tracking_option.toLowerCase();
          if(plan_id != 1){
            check_oradd_conversion_list(google_ads_id, tvc_data);
          }
          if ($("#link_google_analytics_with_google_ads").is(':checked')) {            
            if(tracking_option == "UA" || tracking_option == "BOTH"){
              var UalinkData = {
                  action: "link_analytic_to_ads_account",
                  type: "UA",
                  ads_customer_id: google_ads_id,
                  analytics_id: $("#"+s_tracking_option+"_web_property_id").find(':selected').data('accountid'),
                  web_property_id: $("#"+s_tracking_option+"_web_property_id").val(),
                  profile_id: $("#"+s_tracking_option+"_web_property_id").find(':selected').data('profileid'),
                  tvc_data:tvc_data,
                  conversios_onboarding_nonce:conversios_onboarding_nonce
              };
              //console.log(UalinkData);
              if(google_ads_id != ""){
                setTimeout(function(){      
                  link_analytic_to_ads_account(UalinkData);
                }, 1000); 
              }
              
            }
            if(tracking_option == "GA4" || tracking_option == "BOTH"){
              var Ga4linkData = {
                action: "link_analytic_to_ads_account",
                type: "GA4",
                ads_customer_id: google_ads_id,
                web_property_id: $("#"+s_tracking_option+"_web_measurement_id").val(),
                web_property: $("#"+s_tracking_option+"_web_measurement_id").find(':selected').data('name'),
                tvc_data:tvc_data,
                conversios_onboarding_nonce:conversios_onboarding_nonce
              };
              if(google_ads_id != ""){
                setTimeout(function(){
                  link_analytic_to_ads_account(Ga4linkData);
                }, 1500); 
              }
            }
            loaderSection(false);
            return true;
          }            
        }else{
          add_message("error","Error while updating Google Ads.");
        }
        loaderSection(false);
      }
    });
    return true;
  }else{
    $('#tvc_ads_skip_confirm').addClass('showpopup');
    $('body').addClass('scrlnone');
    //$('#tvc_ads_skip_confirm').modal('show');  
    return false;
  }
}
function save_merchant_data(google_merchant_center_id, merchant_id, tvc_data, subscription_id, plan_id, is_skip=fals){
  if(google_merchant_center_id || is_skip == true){
    var conversios_onboarding_nonce = $("#conversios_onboarding_nonce").val();
    var website_url = $("#url").val();
    var customer_id = $("#loginCustomerId").val();
    var data = {
      action: "save_merchant_data",
      subscription_id:subscription_id,
      google_merchant_center:google_merchant_center_id,
      merchant_id: merchant_id,
      website_url:website_url,
      customer_id:customer_id,
      tvc_data:tvc_data,
      conversios_onboarding_nonce:conversios_onboarding_nonce
    };
    $.ajax({
      type: "POST",
      dataType: "json",
      url: tvc_ajax_url,
      data: data,
      beforeSend: function () { 
        loaderSection(true);       
      },
      success: function (response) {
        let google_ads_id = $("#new_google_ads_id").text();
        if(google_ads_id ==null || google_ads_id ==""){
          google_ads_id = $('#ads-account').val();
        }
        
        if (response.error === false) {
          add_message("success","Google merchant center successfully updated.");
          //clearTimeout(tvc_time_out);
          var link_data = {
            action: "link_google_ads_to_merchant_center",
            account_id: google_merchant_center_id,
            merchant_id: merchant_id,
            adwords_id: google_ads_id,
            tvc_data:tvc_data,
            conversios_onboarding_nonce:conversios_onboarding_nonce
          };
          if(google_merchant_center_id != "" && google_ads_id != ""){
            link_google_Ads_to_merchant_center(link_data, tvc_data, subscription_id);
          }else{
            get_subscription_details(tvc_data, subscription_id); 
          }
        } else {         
          add_message("error","Error while updating Google merchant center.");
        }

        //loaderSection(false);        
      }
    });    
  }else{
    add_message("warning","Missing Google merchant center accountid.");
  }
}

/* get conversion list */
function check_oradd_conversion_list(google_ads_id, google_merchant_center_id, tvc_data){
  var conversios_onboarding_nonce = $("#conversios_onboarding_nonce").val();
  if(google_ads_id && google_merchant_center_id){
    var data = {
        action: "get_conversion_list",
        customer_id:google_ads_id,
        tvc_data:tvc_data,
        conversios_onboarding_nonce:conversios_onboarding_nonce
      };
    $.ajax({
      type: "POST",
      dataType: "json",
      url: tvc_ajax_url,
      data: data,
      success: function (response) {
        //console.log(response);
        clearTimeout(tvc_time_out);
        if(response.error === false){
          setTimeout(function(){
            add_message("success",response.message);
          }, 2000);
        }else{
          //const errors = JSON.parse(response.errors[0]);
          if(response.errors){
            setTimeout(function(){
              add_message("error",response.errors);
            }, 2000);
          }
          
        }
      }
    });
  }
}
/* link account code */
function link_analytic_to_ads_account(data) {
  $.ajax({
    type: "POST",
    dataType: "json",
    url: tvc_ajax_url,
    data: data,
    success: function (response) {
      clearTimeout(tvc_time_out);
      if(response.error === false){
        add_message("success","Google ananlytics and google ads linked successfully.");
      }else{
        const errors = JSON.parse(response.errors[0]);
        add_message("error",errors.message);
      }
    }
  });
}

function link_google_Ads_to_merchant_center(link_data, tvc_data, subscription_id){
  $.ajax({
    type: "POST",
    dataType: "json",
    url: tvc_ajax_url,
    data: link_data,
    beforeSend: function(){
      //loaderSection(true);
    },
    success: function (response) {
      clearTimeout(tvc_time_out);
      if(response.error === false){        
        add_message("success",response.data.message);
      }else if(response.error == true && response.errors != undefined){
        const errors = JSON.parse(response.errors[0]);
        add_message("error",errors.message);
      }else{
        add_message("error","There was an error while link account");
      }
      get_subscription_details(tvc_data, subscription_id);  
      //loaderSection(false);      
    }
  });
}
/* get subscription details */
function get_subscription_details(tvc_data, subscription_id) { 
  var conversios_onboarding_nonce = $("#conversios_onboarding_nonce").val(); 
  $.ajax({
    type: "POST",
    dataType: "json",
    url: tvc_ajax_url,
    data: {action: "get_subscription_details", tvc_data:tvc_data, subscription_id:subscription_id, conversios_onboarding_nonce:conversios_onboarding_nonce},
    beforeSend: function () {
    },
    success: function (response) {
      if (response.error === false) {
        $("#google_analytics_property_id_info").hide();
        $("#google_analytics_measurement_id_info").hide();
        $("#google_ads_info").hide();
        $("#google_merchant_center_info").hide();
        if(response.data.property_id != ""){
          $("#selected_google_analytics_property").text(response.data.property_id);
          $("#google_analytics_property_id_info").show();
        }
        if(response.data.measurement_id != ""){
          $("#selected_google_analytics_measurement").text(response.data.measurement_id);
          $("#google_analytics_measurement_id_info").show();
        }
        if(response.data.google_ads_id != ""){
          $("#selected_google_ads_account").text(response.data.google_ads_id);
          $("#google_ads_info").show();
        }
        if(response.data.google_merchant_center_id != ""){
          $("#selected_google_merchant_center").text(response.data.google_merchant_center_id);
          $("#google_merchant_center_info").show();
        } 
        $('#tvc_confirm_submite').addClass('showpopup');
        $('body').addClass('scrlnone');       
        //$('#tvc_confirm_submite').modal('show');
      } else {
        add_message("error","Error while fetching subscription data");
      } 
      loaderSection(false);
    }
  });
}
/* List function */
//call get list propertie function base on tracking_option
function call_list_analytics_web_properties(tracking_option, tvc_data){
  if (tracking_option == 'UA'){
    let web_property_id_length = $('#ua_web_property_id option').length;
    if(web_property_id_length < 2){
      list_analytics_web_properties("UA", tvc_data);
    }
  }else if (tracking_option == 'GA4'){
    let web_measurement_id_length = $('#ga4_web_measurement_id option').length;
    if(web_measurement_id_length < 2){
      list_analytics_web_properties("GA4", tvc_data);
    }       
  }else if (tracking_option == 'BOTH'){
    let web_property_id_length = $('#both_web_property_id option').length;
    let web_measurement_id_length = $('#both_web_measurement_id option').length;
    if(web_measurement_id_length < 2 || web_property_id_length < 2){
      list_analytics_web_properties("BOTH", tvc_data);
    }
  }
}
// get list properties dropdown options
function list_analytics_web_properties(type, tvc_data) {
  loaderSection(true);
  var conversios_onboarding_nonce = $("#conversios_onboarding_nonce").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: tvc_ajax_url,
    data: {action: "get_analytics_web_properties", type: type, tvc_data:tvc_data, conversios_onboarding_nonce:conversios_onboarding_nonce},
    success: function (response) {
      if (response.error === false) {
        if (type == "UA" || type == "BOTH") {
          //web_properties_dropdown
          var subscriptionPropertyId = $("#subscriptionPropertyId").val();
          var ga_view_id = $("#ga_view_id").val();
          var PropOptions = '<option value="">Select Property Id</option>';
          $.each(response.data.wep_properties, function (propKey, propValue) {
              var selected ="";              
              if (subscriptionPropertyId == propValue.webPropertyId) {
                if(ga_view_id != "" && ga_view_id == propValue.id){
                  selected = "selected='selected'";
                }else if(ga_view_id =="" ){
                  selected = "selected='selected'";
                }
                    
              }else{
                selected = "";
              }
              PropOptions = PropOptions + '<option value="' + propValue.webPropertyId + '" ' + selected + ' data-accountid="' + propValue.accountId + '" data-profileid="' + propValue.id + '"> ' + propValue.accountName + ' - ' + propValue.propertyName + ' - ' + propValue.name + '</option>';
          });
          $('#ua_web_property_id').html(PropOptions);
          $('#both_web_property_id').html(PropOptions);
        }
        if (type == "GA4" || type == "BOTH") {
          //web_measurement_dropdown
          var subscriptionMeasurementId = $("#subscriptionMeasurementId").val();
          var MeasOptions = '<option value="">Select Measurement Id</option>';
          $.each(response.data.wep_measurement, function (measKey, measValue) {
            if (subscriptionMeasurementId == measValue.measurementId) {
              var selected = "selected='selected'";
            } else {
              var selected = "";
            }
            var web_property = measValue.name.split("/");
            MeasOptions = MeasOptions + '<option value="' + measValue.measurementId + '" ' + selected + ' data-name="'+web_property[1] +'"'+ ' data-accountid="' + measValue.accountId + '"> ' + measValue.accountName + ' - ' + web_property[1] + ' - ' + measValue.measurementId + '</option>';
          });
          $('#ga4_web_measurement_id').html(MeasOptions);
          $('#both_web_measurement_id').html(MeasOptions);
        }
        $(".slect2bx").select2();
      }
      is_validate_step("step_1");
      loaderSection(false);
    }
  });
}
function call_list_googl_ads_account(tvc_data){
  let ads_account_length = $('#ads-account option').length;
  if(ads_account_length < 2){
    list_googl_ads_account(tvc_data);
  }
}
// get list google ads dropdown options
function list_googl_ads_account(tvc_data) {
  //loaderSection(true);
  var selectedValue = $("#subscriptionGoogleAdsId").val();
  var conversios_onboarding_nonce = $("#conversios_onboarding_nonce").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: tvc_ajax_url,
    data: {action: "list_googl_ads_account", tvc_data:tvc_data, conversios_onboarding_nonce:conversios_onboarding_nonce},
    success: function (response) {
      if (response.error === false) {
        $('#ads-account').empty();
        $('#ads-account').append($('<option>', {
            value: "",
            text: "Select Google Ads Account"
        }));
        if (response.data.length == 0) {
          add_message("warning","There are no Google ads accounts associated with email.");
        } else {
          $.each(response.data, function (key, value) {

            if (selectedValue == value) {
              $('#ads-account').append($('<option>', { value: value, text: value,selected: "selected"}));
            } else {
              if(selectedValue == "" && key == 0){                
                $('#ads-account').append($('<option>', { value: value, text: value,selected: "selected"}));
              }else{
                $('#ads-account').append($('<option>', { value: value, text: value,}));
              }
            }
          });
        }
      } else {
        add_message("warning","There are no Google ads accounts associated with email.");
      }
      //loaderSection(false);
    }
  });
}

function call_list_google_merchant_account(tvc_data){
  let mcc_account_length = $('#google_merchant_center_id option').length;
  if(mcc_account_length < 2){
    list_google_merchant_account(tvc_data);
  }
}
function list_google_merchant_account(tvc_data){
  var selectedValue = $("#subscriptionMerchantCenId").val();
  var conversios_onboarding_nonce = $("#conversios_onboarding_nonce").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: tvc_ajax_url,
    data: {action: "list_google_merchant_account", tvc_data:tvc_data, conversios_onboarding_nonce:conversios_onboarding_nonce},
    success: function (response) {
      if (response.error === false){
        $('#google_merchant_center_id').empty();
        $('#google_merchant_center_id').append($('<option>', {value: "", text: "Select Measurement Id"}));
        if (response.data.length > 0) {        
          $.each(response.data, function (key, value) {
            if(selectedValue == value.account_id){
              $('#google_merchant_center_id').append($('<option>', {value: value.account_id, "data-merchant_id": value.merchant_id, text: value.account_id,selected: "selected"}));
            }else{
              if(selectedValue == "" && key == 0){ 
                $('#google_merchant_center_id').append($('<option>', {value: value.account_id, "data-merchant_id": value.merchant_id, text: value.account_id,selected: "selected"}));
              }else{
                $('#google_merchant_center_id').append($('<option>', {value: value.account_id,"data-merchant_id": value.merchant_id, text: value.account_id, }));
              }
            }
          });
        }else{
          add_message("error","There are no Google merchant center accounts associated with email.");
        }
      }else{
        add_message("error","There are no Google merchant center accounts associated with email.");
      }       
    }
  });
  loaderSection(false);
}
/* Create function */
function create_google_ads_account(tvc_data){
  var conversios_onboarding_nonce = $("#conversios_onboarding_nonce").val();
  $.ajax({
    type: "POST",
    dataType: "json",
    url: tvc_ajax_url,
    data: {action: "create_google_ads_account", tvc_data:tvc_data, conversios_onboarding_nonce:conversios_onboarding_nonce},
    beforeSend: function () {
      loaderSection(true);
    },
    success: function (response) {
      if (response.error === false) {
        add_message("success",response.data.message);
        $("#new_google_ads_id").text(response.data.adwords_id);
        $("#tvc_ads_section").slideUp();
        $("#new_google_ads_section").slideDown();
        //localStorage.setItem("new_google_ads_id", response.data.adwords_id);
        //listGoogleAdsAccount();
      } else {
        add_message("error",response.data.message);
      }
      loaderSection(false);
    }
  });
}

function create_google_merchant_center_account(tvc_data){
  var conversios_onboarding_nonce = $("#conversios_onboarding_nonce").val();
  var is_valide = true;
  var website_url = $("#url").val();
  var email_address = $("#get-mail").val();
  var store_name = $("#store_name").val();
  var country = $("#selectCountry").val();
  var customer_id = $("#loginCustomerId").val();
  var adult_content = $("#adult_content").is(':checked');
  if(website_url == ""){
    add_message("error","Missing value of website url.");
    is_valide = false;
  }else if(email_address == ""){
    add_message("error","Missing value of email address.");
    is_valide = false;
  }else if(store_name == ""){
    add_message("error","Missing value of store name.");
    is_valide = false;
  }else if(country == ""){
    add_message("error","Missing value of country.");
    is_valide = false;
  } else if($('#terms_conditions').prop('checked') == false){
    add_message("error","Please I accept the terms and conditions.");
    is_valide = false;
  }
  if(is_valide == true){
    var data = {
      action: "create_google_merchant_center_account",
      website_url: website_url,
      email_address: email_address,
      store_name: store_name,
      country: country,
      concent: 1,
      customer_id: customer_id,
      adult_content:adult_content,
      tvc_data:tvc_data,
      conversios_onboarding_nonce:conversios_onboarding_nonce
    };
    $.ajax({
      type: "POST",
      dataType: "json",
      url: tvc_ajax_url,
      data: data,
      beforeSend: function () {
        loaderSection(true);
      },
      success: function (response, status) {
        if (response.error === false || response.merchant_id != undefined) {
          add_message("success","New merchant center created successfully.");              
          $("#new_merchant_id").text(response.account.id);
          $("#tvc_merchant_section").slideUp();
          $("#new_merchant_section").slideDown();
        } else if (response.error === true) {
          const errors = JSON.parse(response.errors[0]);
          add_message("error",errors.message);
        } else {
          add_message("error","There was error to create merchant center account");
        }
        $("#createmerchantpopup").removeClass('showpopup');
        $('body').removeClass('scrlnone');
        //$("#merchantconfirmModal").modal('hide');
        loaderSection(false);
      }
    });
    
  }
}