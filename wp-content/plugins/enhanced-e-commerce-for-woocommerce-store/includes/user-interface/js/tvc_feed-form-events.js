"use strict";
jQuery( document ).ready(
	function( jQuery ) {
		var fileNameElement  = jQuery( '#file-name' );
		var merchantsElement = jQuery( '#tvc-merchants-selector' );
		var countriesElement = jQuery( '#tvc-countries-selector' );
		var level0Element    = jQuery( '#lvl_0' );

		// monitor the four main feed settings and react when they change
		fileNameElement.on(
			'focusout',
			function() {
				if ( '' !== fileNameElement.val() ) {
					countriesElement.prop( 'disabled', false );
					level0Element.prop( 'disabled', false );
					if ( false === tvc_validateFileName( fileNameElement.val() ) ) {
						fileNameElement.val( '' );
					}

					if ( '0' !== merchantsElement.val() ) {
						tvc_showChannelInputs( merchantsElement.val(), true );
						tvc_mainInputChanged( false );
					} else {
						tvc_hideFeedFormMainInputs();
					}
				} else {
					countriesElement.prop( 'disabled', true );
					level0Element.prop( 'disabled', true );
				}
			}
		);

		fileNameElement.on(
			'keyup',
			function() {

				if ( '' !== fileNameElement.val() ) {
					countriesElement.prop( 'disabled', false );
					level0Element.prop( 'disabled', false );
				} else {
					countriesElement.prop( 'disabled', true );
					level0Element.prop( 'disabled', true );
				}
			}
		);

		countriesElement.on(
			'change',
			function() {
				if ( '0' !== countriesElement.val() ) {
					level0Element.prop( 'disabled', false );
				}

				tvc_mainInputChanged( false );
			}
		);

		jQuery( '#tvc-feed-language-selector' ).on(
			'change',
			function() {
				tvc_setGoogleFeedLanguage( jQuery( '#tvc-feed-language-selector' ).val() );

				if ( tvc_requiresLanguageInput ) {
					tvc_mainInputChanged( false );
				}
			}
		);

		jQuery( '#google-feed-title-selector' ).on(
			'change',
			function() {
				tvc_setGoogleFeedTitle( jQuery( '#google-feed-title-selector' ).val() );
			}
		);

		jQuery( '#google-feed-description-selector' ).on(
			'change',
			function() {
				tvc_setGoogleFeedDescription( jQuery( '#google-feed-description-selector' ).val() );
			}
		);

		merchantsElement.on(
			'change',
			function() {
				if ( '0' !== merchantsElement.val() && '' !== jQuery( '#file-name' ).val() ) {
					tvc_showChannelInputs( jQuery( '#tvc-merchants-selector' ).val(), true );
					tvc_mainInputChanged( false );
				} else {
					tvc_hideFeedFormMainInputs();
				}
			}
		);

		jQuery( '#variations' ).on(
			'change',
			function() {
				tvc_variation_selection_changed();
			}
		);

		jQuery( '#aggregator' ).on(
			'change',
			function() {
				tvc_aggregatorChanged();
				tvc_drawAttributeMappingSection(); // reset the attribute mapping
			}
		);

		level0Element.on(
			'change',
			function() {
				tvc_mainInputChanged( true );
			}
		);

		jQuery( '.tvc-cat-selector' ).on(
			'change',
			function() {
				tvc_nextCategory( this.id );
			}
		);

		jQuery( '#tvc-generate-feed-button-top' ).on(
			'click',
			function() {
				tvc_generateFeed();
			}
		);

		jQuery( '#tvc-generate-feed-button-bottom' ).on(
			'click',
			function() {
				tvc_generateFeed();
			}
		);

		jQuery( '#tvc-save-feed-button-top' ).on(
			'click',
			function() {
				tvc_saveFeedData();
			}
		);

		jQuery( '#tvc-view-feed-button-top' ).on(
			'click',
			function() {
				tvc_viewFeed( jQuery( '#tvc-feed-url' ).text() );
			}
		);

		jQuery( '#tvc-view-feed-button-bottom' ).on(
			'click',
			function() {
				tvc_viewFeed( jQuery( '#tvc-feed-url' ).text() );
			}
		);

		jQuery( '#tvc-save-feed-button-bottom' ).on(
			'click',
			function() {
				tvc_saveFeedData();
			}
		);

		jQuery( '#days-interval' ).on(
			'change',
			function() {
				tvc_saveUpdateSchedule();
			}
		);

		jQuery( '#update-schedule-hours' ).on(
			'change',
			function() {
				tvc_saveUpdateSchedule();
			}
		);

		jQuery( '#update-schedule-minutes' ).on(
			'change',
			function() {
				tvc_saveUpdateSchedule();
			}
		);

		jQuery( '#update-schedule-frequency' ).on(
			'change',
			function() {
				tvc_saveUpdateSchedule();
			}
		);

		jQuery( '#tvc_auto_feed_fix_mode' ).on(
			'change',
			function() {
				tvc_auto_feed_fix_changed();
			}
		);

		jQuery( '#tvc_background_processing_mode' ).on(
			'change',
			function() {
				tvc_clear_feed_process();
				tvc_background_processing_mode_changed();
			}
		);

		jQuery( '#tvc_process_logging_mode' ).on(
			'change',
			function() {
				tvc_feed_logger_status_changed();
			}
		);

		jQuery( '#tvc_product_identifiers' ).on(
			'change',
			function() {
				tvc_show_product_identifiers_changed();
			}
		);

		jQuery( '#tvc_third_party_attr_keys' ).on(
			'focusout',
			function() {
				tvc_third_party_attributes_changed();
			}
		);

		jQuery( '#tvc_notice_mailaddress' ).on(
			'focusout',
			function() {
				tvc_notice_mailaddress_changed();
			}
		);

		jQuery( '#tvc-clear-feed-process-button' ).on(
			'click',
			function() {
				tvc_clear_feed_process();
			}
		);

		jQuery( '#tvc-reinitiate-plugin-button' ).on(
			'click',
			function() {
				tvc_reinitiate();
			}
		);

		jQuery( '.tvc-category-mapping-selector' ).on( // on activation of a category selector in the Category Mapping table
			'change',
			function() {
				if ( jQuery( this ).is( ':checked' ) ) {
					console.log( 'category ' + jQuery( this ).val() + ' selected' );
					tvc_activateFeedCategoryMapping( jQuery( this ).val() );
				} else {
					console.log( 'category ' + jQuery( this ).val() + ' deselected' );
					tvc_deactivateFeedCategoryMapping( jQuery( this ).val() );
				}
			}
		);

		jQuery( '.tvc-category-selector' ).on( // on activation of a category selector in the Category Selector table
			'change',
			function() {
				if ( jQuery( this ).is( ':checked' ) ) {
					console.log( 'category ' + jQuery( this ).val() + ' selected' );
					tvc_activateFeedCategorySelection( jQuery( this ).val() );
				} else {
					console.log( 'category ' + jQuery( this ).val() + ' deselected' );
					tvc_deactivateFeedCategorySelection( jQuery( this ).val() );
				}
			}
		);

		jQuery( '#tvc-categories-select-all' ).on( // on activation of the 'all' selector in the Category Mapping and Category Selector table
			'change',
			function() {
				if ( jQuery( this ).is( ':checked' ) ) {
					tvc_activateAllFeedCategoryMapping();
				} else {
					tvc_deactivateAllFeedCategoryMapping();
				}
			}
		);

		jQuery( '#tvc_accept_eula' ).on(
			'change',
			function() {
				if ( jQuery( this ).is( ':checked' ) ) {
					jQuery( '#tvc_license_activate' ).prop( 'disabled', false );
				} else {
					jQuery( '#tvc_license_activate' ).prop( 'disabled', true );
				}
			}
		);


		jQuery( '#tvc_prepare_backup' ).on(
			'click',
			function() {
				jQuery( '#tvc_backup-file-name' ).val( '' );
				jQuery( '#tvc_backup-wrapper' ).show();
			}
		);

		jQuery( '#tvc_make_backup' ).on(
			'click',
			function() {
				tvc_backup();
			}
		);

		jQuery( '#tvc_cancel_backup' ).on(
			'click',
			function() {
				jQuery( '#tvc_backup-wrapper' ).hide();
			}
		);

		jQuery( '#tvc_backup-file-name' ).on(
			'keyup',
			function() {
				if ( '' !== jQuery( '#tvc_backup-file-name' ).val ) {
					jQuery( '#tvc_make_backup' ).attr( 'disabled', false );
				}
			}
		);
	}
);
