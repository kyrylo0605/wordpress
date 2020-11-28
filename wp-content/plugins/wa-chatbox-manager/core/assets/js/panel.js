jQuery.noConflict()(function($){

	"use strict";

/* ===============================================
   AjaxSelect2
   =============================================== */

	$('.cmAjaxSelect2').select2({

		ajax: {

			url: ajaxurl,
			dataType: 'json',

			delay: 250,

			data: function (params) {

				return {
					action: 'chatbox_manager_list_posts',
					chatbox_manager_post_type: $(this).attr('data-type'),
					chatbox_manager_post_filter: $(this).attr('data-filter'),
					q: params.term,
					page: params.page

				};

			},

		processResults: function (data, params) {

			params.page = params.page || 1;

				return {
					results: data.items,
					more: false,
				};

			},

			cache: true

		},

		placeholder: 'Type here...',
		minimumInputLength: 3,
		width: '98%'
	});


/* ===============================================
   AjaxSelect2
   =============================================== */

	$('.cmAjaxSelect2Tax').select2({

		ajax: {

			url: ajaxurl,
			dataType: 'json',

			delay: 250,

			data: function (params) {

				return {
					action: 'chatbox_manager_list_taxonomy',
					chatbox_manager_taxonomy_type: $(this).attr('data-type'),
					chatbox_manager_taxonomy_filter: $(this).attr('data-filter'),
					q: params.term,
					page: params.page

				};

			},

		processResults: function (data, params) {

			params.page = params.page || 1;

				return {
					results: data.items,
					more: false,
				};

			},

			cache: true

		},

		placeholder: 'Type here...',
		minimumInputLength: 3,
		width: '98%'

	});

/* ===============================================
   Message, after save options
   =============================================== */

	$('.chatbox_manager_panel_message').delay(1000).fadeOut(1000);

/* ===============================================
   On off
   =============================================== */

	$('.on-off').on("change",function() {

		if ($(this).val() === "on" ) {
			$('.hidden-element').css({'display':'none'});
		}
		else {
			$('.hidden-element').slideDown("slow");
		}

	});

	$('input[type="checkbox"].on_off').on("change",function() {

		if (!this.checked) {
			$(this).parent('.iPhoneCheckContainer').parent('.chatbox_manager_panel_box').next('.hidden-element').slideUp("slow");
		} else {
			$(this).parent('.iPhoneCheckContainer').parent('.chatbox_manager_panel_box').next('.hidden-element').slideDown("slow");
		}

	});

/* ===============================================
   Option panel
   =============================================== */

	$('.chatbox_manager_panel_container .chatbox_manager_panel_mainbox').css({'display':'none'});
	$('.chatbox_manager_panel_container .inactive').next('.chatbox_manager_panel_mainbox').css({'display':'block'});

	$('.chatbox_manager_panel_container h5.element').each(function(){

		if($(this).next('.chatbox_manager_panel_mainbox').css('display') === 'none') {
			$(this).next('input[name="element-opened"]').remove();
		}

		else {
			$(this).next().append('<input type="hidden" name="element-opened" value="'+$(this).attr('id')+'" />');
		}

	});

	$('.chatbox_manager_panel_container h5.element').on("click", function(){

		if($(this).next('.chatbox_manager_panel_mainbox').css('display') === 'none') {

			$(this).parent('.chatbox_manager_panel_container').addClass('unsortableItem');

			$(this).addClass('inactive');
			$(this).children('img').addClass('inactive');
			$('input[name="element-opened"]').remove();
			$(this).next().append('<input type="hidden" name="element-opened" value="'+$(this).attr('id')+'" />');
		}

		else {

			$(this).parent('.chatbox_manager_panel_container').removeClass('unsortableItem');

			$(this).removeClass('inactive');
			$(this).children('img').removeClass('inactive');
			$(this).next('input[name="element-opened"]').remove();

		}

		$(this).next('.chatbox_manager_panel_mainbox').stop(true,false).slideToggle('slow');

	});

/* ===============================================
   CHOOSE SCRIPT POSITION
   =============================================== */

	function chooseChatboxPosition (type, value, chatbox) {

		var parent = '#' + chatbox;

		if ( value === 'include' ) {

			$( parent + ' .' + type + 'Cpt.MatchValue').css({'display':'block'});
			$( parent + ' .include.' + type + 'cpt').css({'display':'block'});
			$( parent + ' .exclude.' + type + 'cpt').css({'display':'none'});

		} else if ( value === 'exclude' ) {

			$( parent + ' .' + type + 'Cpt.MatchValue').css({'display':'block'});
			$( parent + ' .include.' + type + 'cpt').css({'display':'none'});
			$( parent + ' .exclude.' + type + 'cpt').css({'display':'block'});

		}

	}

	$('.selectValue').on('change', function() {

		var chatbox = $(this).closest('.chatbox_manager_panel_container').attr('id');
		var type = $(this).attr('data-type');
		var value = $(this).val();
		chooseChatboxPosition(type, value, chatbox);

	});

	$('.selectValue').each(function() {

		var chatbox = $(this).closest('.chatbox_manager_panel_container').attr('id');
		var type = $(this).attr('data-type');
		var value = $(this).val();
		chooseChatboxPosition(type, value, chatbox);

	});

/* ===============================================
   WHOLE WEBSITE OPTION
   =============================================== */

	function wholeWebsiteSelection (value, chatbox) {

		var parent = '#' + chatbox;

		if ( value === 'on' ) {

			$( parent + ' .wholewebsite_warning').css({'display':'block'});
			$( parent + ' .MatchValueBox').css({'display':'none'});

		} else if ( value === 'off' ) {

			$(parent + ' .wholewebsite_warning').css({'display':'none'});
			$(parent + ' .MatchValueBox').css({'display':'block'});

		}

	}

	$('.wholeWebsite').on('click', function() {
		var chatbox = $(this).closest('.chatbox_manager_panel_container').attr('id');
		var value = $(this).children('.on-off').val();
		wholeWebsiteSelection(value, chatbox);
	});

	$('.wholeWebsite').each(function() {
		var chatbox = $(this).closest('.chatbox_manager_panel_container').attr('id');
		var value = $(this).children('.on-off').val();
		wholeWebsiteSelection(value, chatbox);
	});

/* ===============================================
   Position OPTION
   =============================================== */

	function positionSelection (value, chatbox) {

		var parent = '#' + chatbox;
		var result = value.split('-');
		$( parent + ' .positionInput').css({'display':'none'});
		$( parent + ' .positionInput + p').css({'display':'none'});
		$( parent + ' .' + result[0] + 'Input').css({'display':'inline-block'});
		$( parent + ' .' + result[1] + 'Input').css({'display':'inline-block'});
		$( parent + ' .' + result[0] + 'Input + p').css({'display':'inline-block'});
		$( parent + ' .' + result[1] + 'Input + p').css({'display':'inline-block'});

	}

	$('.filterPosition').on('change', function() {
		var chatbox = $(this).closest('.chatbox_manager_panel_container').attr('id');
		var value = $(this).val();
		positionSelection(value, chatbox);
	});

	$('.filterPosition').each(function() {
		var chatbox = $(this).closest('.chatbox_manager_panel_container').attr('id');
		var value = $(this).val();
		positionSelection(value, chatbox);
	});

/* ===============================================
   Layout filter
   =============================================== */

	function layoutSelection (value, chatbox) {

		var parent = '#' + chatbox;

		console.log(value);

		if ( value === 'layout-3' || value === 'layout-4' || value === 'layout-5' ) {

			$( parent + ' select.filterIcon option[value="none"]').attr('disabled', true).attr('selected', false);

		} else {

			$( parent + ' select.filterIcon option[value="none"]').attr('disabled', false);

		}

	}

	$('.filterLayout').on('change', function() {
		var chatbox = $(this).closest('.chatbox_manager_panel_container').attr('id');
		var value = $(this).val();
		layoutSelection(value, chatbox);
	});

	$('.filterLayout').each(function() {
		var chatbox = $(this).closest('.chatbox_manager_panel_container').attr('id');
		var value = $(this).val();
		layoutSelection(value, chatbox);
	});

/* ===============================================
   Restore warning
   =============================================== */

	$('.chatbox_manager_restore_settings').on("click", function(){

    	if (!window.confirm('Do you want to restore the plugin settings？')) {
			return false;
		}

	});

/* ===============================================
   Delete warning
   =============================================== */

	$('.chatbox_manager_delete_chatbox').on("click", function() {

    	if (!window.confirm('Do you want to delete this chatbox？')) {
			return false;
		}

	});

/* ===============================================
   Update warning
   =============================================== */

	$('.chatbox_manager_update_chatbox').on("click", function() {

		if ($('#chatbox_manager_check_number').val() === '') {
			alert('Please enter a valid WhatsApp number');
			return false;
		}

    	if (!window.confirm('Do you want to update this chatbox？')) {
			return false;
		}

	});

});
