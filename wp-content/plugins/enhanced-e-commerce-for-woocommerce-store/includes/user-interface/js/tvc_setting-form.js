"use strict";
/*global tvc_setting_form_vars */
function tvc_auto_feed_fix_changed() {
	tvc_auto_feed_fix_mode(
		jQuery( '#tvc_auto_feed_fix_mode' ).is( ':checked' ),
		function( response ) {
			console.log( 'Auto feed fix setting changed to ' + response );
		}
	);
}

function tvc_background_processing_mode_changed() {
	tvc_background_processing_mode(
		jQuery( '#tvc_background_processing_mode' ).is( ':checked' ),
		function( response ) {
			console.log( 'Background processing setting changed to ' + response );
		}
	);
}

function tvc_feed_logger_status_changed() {
	tvc_feed_logger_status(
		jQuery( '#tvc_process_logging_mode' ).is( ':checked' ),
		function( response ) {
			console.log( 'Feed process logger status changed to ' + response );
		}
	);
}

function tvc_show_product_identifiers_changed() {
	tvc_show_pi_status(
		jQuery( '#tvc_product_identifiers' ).is( ':checked' ),
		function( response ) {
			console.log( 'Show Product Identifiers setting changed to ' + response );
		}
	);
}

function tvc_third_party_attributes_changed() {
	tvc_change_third_party_attribute_keywords(
		jQuery( '#tvc_third_party_attr_keys' ).val(),
		function( response ) {
			console.log( 'Third party attributes changed to ' + response );
		}
	);
}

function tvc_notice_mailaddress_changed() {
	tvc_change_notice_mailaddress(
		jQuery( '#tvc_notice_mailaddress' ).val(),
		function( response ) {
			console.log( 'Notice recipient setting changed to ' + response );
		}
	);
}

function tvc_clear_feed_process() {
	tvc_showFeedSpinner();
	tvc_clear_feed_process_data(
		function( response ) {
			console.log( 'Clear feed process activated' );
			tvc_hideFeedSpinner();
		}
	);
}

function tvc_reinitiate() {
	tvc_showFeedSpinner();
	tvc_reinitiate_plugin(
		function( response ) {
			console.log( 'Re-initialization initiated ' + response );
			tvc_hideFeedSpinner();
		}
	);
}

function tvc_backup() {
	var backupFileNameElement = jQuery( '#tvc_backup-file-name' );

	if ( backupFileNameElement.val() !== '' ) {
		jQuery( '#tvc_backup-wrapper' ).hide();

		tvc_initiateBackup(
			backupFileNameElement.val(),
			function( response ) {
				tvc_resetBackupsList();

				if ( response !== '1' ) {
					tvc_show_error_message( 'New backup file made ' + response );
				}
			}
		);
	} else {
		alert( tvc_setting_form_vars.first_enter_file_name );
	}
}

function tvc_deleteBackupFile( fileName ) {
	var userInput = confirm( tvc_setting_form_vars.confirm_file_deletion.replace( '%backup_file_name%', fileName ) );

	if ( userInput === true ) {
		tvc_deleteBackup(
			fileName,
			function( response ) {
				tvc_show_success_message( tvc_setting_form_vars.file_deleted.replace( '%backup_file_name%', fileName ) );
				tvc_resetBackupsList();
				console.log( 'Backup file deleted ' + response );
			}
		);
	}
}

function tvc_restoreBackupFile( fileName ) {

	var userInput = confirm( tvc_setting_form_vars.confirm_file_restoring.replace( '%backup_file_name%', fileName ) );

	if ( userInput === true ) {

		tvc_restoreBackup(
			fileName,
			function( response ) {

				if ( response === '1' ) {
					tvc_show_success_message( tvc_setting_form_vars.file_restored.replace( '%backup_file_name%', fileName ) );
					tvc_resetOptionSettings();
					console.log( 'Backup file restored ' + response );
				} else {
					tvc_show_error_message( response );
				}
			}
		);
	}
}

function tvc_duplicateBackupFile( fileName ) {

	tvc_duplicateBackup(
		fileName,
		function( response ) {

			if ( response === '1' ) {
				tvc_show_success_message( tvc_setting_form_vars.file_duplicated.replace( '%backup_file_name%', fileName ) );
				console.log( 'Backup file duplicated' + response );
			} else {
				tvc_show_error_message( response );
			}
			tvc_resetBackupsList();
		}
	);
}