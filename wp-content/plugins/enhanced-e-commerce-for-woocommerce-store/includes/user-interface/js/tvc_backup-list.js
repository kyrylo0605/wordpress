"use strict";
/*global tvc_backup_list_form_vars */

function tvc_resetBackupsList() {
	var backupListData    = null;
	var listHtml          = '';
	var backupListElement = jQuery( '#tvc-backups-list' );

	tvc_getBackupsList(
		function( list ) {
			if ( '0' !== list ) {
				backupListData = JSON.parse( list );

				// convert the data to html code
				listHtml = tvc_backupsTable( backupListData );
			} else {
				listHtml = tvc_emptyBackupsTable();
			}

			backupListElement.empty(); // first clear the feedlist

			backupListElement.append( listHtml );
		}
	);
}

/**
 * Restores the options on the settings page
 */
function tvc_resetOptionSettings() {
	tvc_getSettingsOptions(
		function( optionsString ) {

			if ( optionsString ) {
				var options = JSON.parse( optionsString );

				jQuery( '#tvc_auto_feed_fix_mode' ).prop( 'checked', options[ 0 ] === 'true' );
				jQuery( '#tvc_background_processing_mode' ).prop( 'checked', options[ 3 ] === 'true' );

				jQuery( '#tvc_third_party_attr_keys' ).val( options[ 1 ] );
				jQuery( '#tvc_notice_mailaddress' ).val( options[ 2 ] );
			}
		}
	);
}

function tvc_backupsTable( list ) {
	var htmlCode = '';

	for ( var i = 0; i < list.length; i ++ ) {

		var backup   = list[ i ].split( '&&' );
		var fileName = backup[ 0 ];
		var fileDate = backup[ 1 ];

		htmlCode += '<tr id="feed-row"';
		if ( i % 2 === 0 ) {
			htmlCode += ' class="alternate"';
		} // alternate background color per row
		htmlCode += '>';
		htmlCode += '<td id="file-name" value="' + fileName + '">' + fileName + '</td>';
		htmlCode += '<td id="file-date">' + fileDate + '</td>';
		htmlCode += '<td id="actions"><strong><a href="javascript:void(0);" id="tvc-delete-' + fileName.replace('.', '-') + '-backup-action" onclick="tvc_deleteBackupFile(\'' + fileName + '\')">' + tvc_backup_list_form_vars.list_delete + ' </a>';
		htmlCode += '| <a href="javascript:void(0);" id="tvc-restore-' + fileName.replace('.', '-') + '-backup-action" onclick="tvc_restoreBackupFile(\'' + fileName + '\')">' + tvc_backup_list_form_vars.list_restore + ' </a>';
		htmlCode += '| <a href="javascript:void(0);" id="tvc-duplicate-' + fileName.replace('.', '-') + '-backup-action" onclick="tvc_duplicateBackupFile(\'' + fileName + '\')">' + tvc_backup_list_form_vars.list_duplicate + ' </a></strong></td>';
		htmlCode += '</tr>';
	}
	return htmlCode;
}

function tvc_emptyBackupsTable() {

	var htmlCode = '';
	htmlCode += '<tr>';
	htmlCode += '<td colspan = 4>' + tvc_backup_list_form_vars.no_backup + '</td>';
	htmlCode += '</tr>';

	return htmlCode;
}

/**
 * Document ready actions
 */
jQuery( document ).ready(
	function() {
		// fill the backups list
		tvc_resetBackupsList();
	}
);
