"use strict";
/*global tvc_feed_list_form_vars */
function tvc_fillFeedList() {
	var listHtml         = '';
	var feedListSelector = jQuery( '#tvc-feed-list' );

	tvc_getFeedList(
		function( result ) {
			var feedList                = JSON.parse( result );
			var list                    = feedList[ 'list' ];
			var specialFeedAddOnsActive = feedList[ 'special_feed_add_ons_active' ];

			if ( '0' !== list ) {
				// convert the data to html code
				listHtml = tvc_feedListTable( list, specialFeedAddOnsActive );
			} else {
				listHtml = tvc_emptyListTable();
			}

			feedListSelector.empty(); // first clear the feed list

			feedListSelector.append( listHtml );

			console.log( 'Feed List refreshed' );

			parent.location='admin.php?page=tvc-product-feed-manager&tab=feed-list';
		}
	);
}

function appendCategoryLists( channelId, language, isNew ) {
	var levelZeroSelector     = jQuery( '#lvl_0' );
	var categoryLevelSelector = jQuery( '#category-selector-lvl' );

	if ( isNew ) {
		tvc_getCategoryListsFromString(
			channelId,
			'',
			language,
			function( categories ) {

				var list = JSON.parse( categories )[ 0 ];

				if ( list && list.length > 0 ) {
					levelZeroSelector.html( tvc_categorySelectCntrl( list ) );
					levelZeroSelector.prop( 'disabled', false );
				} else {
					// as the user selected a free format, just show a text input control
					categoryLevelSelector.html( tvc_freeCategoryInputCntrl( 'default', '0', false ) );
					categoryLevelSelector.prop( 'disabled', false );
				}
			}
		);
	}
}

function tvc_resetFeedList() {
	tvc_fillFeedList();
}

function tvc_resetFeedStatus( feedData ) {
	tvc_checkNextFeedInQueue(
		function() {
			tvc_updateFeedRowStatus( feedData[ 'product_feed_id' ], parseInt( feedData[ 'status_id' ] ) );
			tvc_updateFeedRowData( feedData );
		}
	);
}

function tvc_feedListTable( list, specialFeedAddOnsActive ) {
	var htmlCode = '';

	if ( ! list ) {
		return htmlCode;
	}

	for ( var i = 0; i < list.length; i ++ ) {
		var status       = list [ i ] [ 'status' ];
		var feedId       = list [ i ] [ 'product_feed_id' ];
		var feedUrl      = list [ i ] [ 'url' ];
		var feedReady    = 'on_hold' === status || 'ok' === status;
		var nrProducts   = '';
		var statusString = tvc_list_status_text( status );

		if ( feedReady ) {
			nrProducts = list [ i ] [ 'products' ];
		} else if ( 'processing' === status ) {
			nrProducts = tvc_feed_list_form_vars.processing_the_feed;
		} else if ( 'failed_processing' === status || 'in_processing_queue' === status ) {
			nrProducts = tvc_feed_list_form_vars.unknown_text;
		}

		htmlCode += '<tr id="feed-row"';

		if ( i % 2 === 0 ) {
			htmlCode += ' class="alternate"';
		} // alternate background color per row

		htmlCode += '>';
		htmlCode += '<td id="title">' + list [ i ] [ 'title' ] + '</td>';
		htmlCode += '<td id="url">' + feedUrl + '</td>';
		htmlCode += '<td id="updated-' + feedId + '">' + list [ i ] [ 'updated' ] + '</td>';
		htmlCode += '<td id="products-' + feedId + '">' + nrProducts + '</td>';
		htmlCode += specialFeedAddOnsActive ? '<td id="type-' + feedId + '">' + list [ i ] [ 'feed_type_name' ] + '</td>' : '';
		htmlCode += '<td id="feed-status-' + feedId + '" value="' + status + '" style="color: ' + list [ i ] [ 'color' ] + '"><strong>';
		htmlCode += statusString;
		htmlCode += '</strong></td>';
		htmlCode += '<td id="actions-' + feedId + '">';

		if ( feedReady ) {
			htmlCode += feedReadyActions( feedId, feedUrl, status, list [ i ] [ 'title' ], list [ i ] [ 'feed_type_name' ] );
		} else {
			htmlCode += feedNotReadyActions( feedId, feedUrl, list [ i ] [ 'title' ], list [ i ] [ 'feed_type_name' ] );
		}

		htmlCode += '</td>';
	}


	return htmlCode;
}

function feedReadyActions( feedId, feedUrl, status, title, feedType ) {
	var fileExists   = 'No feed generated' !== feedUrl;
	var fileName     = feedUrl.lastIndexOf( '/' ) > 0 ? feedUrl.slice( feedUrl.lastIndexOf( '/' ) - feedUrl.length + 1 ) : title;
	var tabTitle     = feedType.replace( / /g, '-' ).toLowerCase();
	var actionId     = title.replace( / /g, '-' ).toLowerCase();
	var changeStatus = 'ok' === status ? tvc_feed_list_form_vars.list_deactivate : tvc_feed_list_form_vars.list_activate;

	var htmlCode = '<strong><a href="javascript:void(0);" id="tvc-edit-' + actionId + '-action" onclick="parent.location=\'admin.php?page=tvc-product-feed-manager&tab=' + tabTitle + '&id=' + feedId + '\'">' + tvc_feed_list_form_vars.list_edit + ' </a>';
	htmlCode    += fileExists ? ' | <a href="javascript:void(0);" id="tvc-view-' + actionId + '-action" onclick="tvc_viewFeed(\'' + feedUrl + '\')">' + tvc_feed_list_form_vars.list_view + '</a>' : '';
	htmlCode    += ' | <a href="javascript:void(0);" id="tvc-delete-' + actionId + '-action" onclick="tvc_deleteSpecificFeed(' + feedId + ', \'' + fileName + '\')">' + tvc_feed_list_form_vars.list_delete + '</a>';
	htmlCode    += fileExists ? '<a href="javascript:void(0);" id="tvc-deactivate-' + actionId + '-action" onclick="tvc_deactivateFeed(' + feedId + ')" id="feed-status-switch-' + feedId + '"> | ' + changeStatus + '</a>' : '';
	htmlCode    += tvcEndOfActionsCode( feedId, actionId, feedType, title );
	return htmlCode;
}

function feedNotReadyActions( feedId, feedUrl, title, feedType ) {
	var fileName     = feedUrl.lastIndexOf( '/' ) > 0 ? feedUrl.slice( feedUrl.lastIndexOf( '/' ) - feedUrl.length + 1 ) : title;
	var tabTitle     = feedType.replace( / /g, '-' ).toLowerCase();
	var actionId     = title.replace( / /g, '-' ).toLowerCase();

	var htmlCode = '<strong>';
	htmlCode    += '<a href="javascript:void(0);" id="tvc-edit-' + actionId + '-action" onclick="parent.location=\'admin.php?page=tvc-product-feed-manager&tab=' + tabTitle + '&id=' + feedId + '\'">' + tvc_feed_list_form_vars.list_edit + '</a>';
	htmlCode    += ' | <a href="javascript:void(0);" id="tvc-delete-' + actionId + '-action" onclick="tvc_deleteSpecificFeed(' + feedId + ', \'' + fileName + '\')"> ' + tvc_feed_list_form_vars.list_delete + '</a>';
	htmlCode    += tvcEndOfActionsCode( feedId, actionId, feedType, title );
	htmlCode    += tvc_addFeedStatusChecker( feedId );
	return htmlCode;
}

function tvcEndOfActionsCode( feedId, actionId, feedType, title ) {
	var htmlCode = ' | <a href="javascript:void(0);" id="tvc-duplicate-' + actionId + '-action" onclick="tvc_duplicateFeed(' + feedId + ', \'' + title + '\')">' + tvc_feed_list_form_vars.list_duplicate + '</a>';
	htmlCode += 'Product Feed' === feedType ? ' | <a href="javascript:void(0);" id="tvc-regenerate-' + actionId + '-action" onclick="tvc_regenerateFeed(' + feedId + ')">' + tvc_feed_list_form_vars.list_regenerate + '</a>' : '';
	htmlCode += '</strong>';

	return htmlCode;
}

function tvc_emptyListTable() {
	var htmlCode = '';

	htmlCode += '<tr>';
	htmlCode += '<td colspan = 4>' + tvc_feed_list_form_vars.no_data_found + '</td>';
	htmlCode += '</tr>';

	return htmlCode;
}

function tvc_updateFeedRowData( rowData ) {
	if ( rowData[ 'status_id' ] === '1' || rowData[ 'status_id' ] === '2' ) {
		var feedId = rowData[ 'product_feed_id' ];
		var status = rowData[ 'status_id' ] === '1' ? tvc_feed_list_form_vars.ok : tvc_feed_list_form_vars.other;

		jQuery( '#updated-' + feedId ).html( rowData[ 'updated' ] );
		jQuery( '#products-' + feedId ).html( rowData[ 'products' ] );
		jQuery( '#actions-' + feedId ).html( feedReadyActions( feedId, rowData[ 'url' ], status, rowData[ 'title' ], rowData[ 'feed_type_name' ] ) );
	}
}

function tvc_switchStatusAction( feedId, status ) {
	var feedName = jQuery( '#title-' + feedId ).html();
	var actionText = '';

	feedName = feedName.replace(/\s+/g, '-').toLowerCase();

	switch ( status ) {
		case '1':
			actionText = ' | Auto-off ';
			break;

		case '2':
			actionText = ' | Auto-on ';
			break;
	}

	jQuery( '#tvc-deactivate-' + feedName + '-action' ).html( actionText );
}

function tvc_list_status_text( status ) {
	switch ( status ) {
		case 'unknown':
			return tvc_feed_list_form_vars.unknown;

		case 'ok':
			return tvc_feed_list_form_vars.status_ok;

		case 'on_hold':
			return tvc_feed_list_form_vars.on_hold;

		case 'processing':
			return tvc_feed_list_form_vars.processing;

		case 'in_processing_queue':
			return tvc_feed_list_form_vars.processing_queue;

		case 'has_errors':
			return tvc_feed_list_form_vars.has_errors;

		case 'failed_processing':
			return tvc_feed_list_form_vars.failed_processing;
	}
}

function tvc_updateFeedRowStatus( feedId, status ) {
	var feedStatusSelector       = jQuery( '#feed-status-' + feedId );
	var feedStatusSwitchSelector = jQuery( '#feed-status-switch-' + feedId );
	var productsSelector         = jQuery( '#products-' + feedId );

	switch ( status ) {
		case 0: // unknown
			feedStatusSelector.html( '<strong>' + tvc_feed_list_form_vars.unknown + '</strong>' );
			feedStatusSelector.css( 'color', '#6549F7' );
			feedStatusSwitchSelector.html( '' );
			break;

		case 1: // OK
			feedStatusSelector.html( '<strong>' + tvc_feed_list_form_vars.ok + '</strong>' );
			feedStatusSelector.css( 'color', '#0073AA' );
			feedStatusSwitchSelector.html( ' | ' + tvc_feed_list_form_vars.list_deactivate + ' ' );
			break;

		case 2: // On hold
			feedStatusSelector.html( '<strong>' + tvc_feed_list_form_vars.on_hold + '</strong>' );
			feedStatusSelector.css( 'color', '#0173AA' );
			feedStatusSwitchSelector.html( ' | ' + tvc_feed_list_form_vars.list_activate + ' ' );
			break;

		case 3: // Processing
			feedStatusSelector.html( '<strong>' + tvc_feed_list_form_vars.processing + '</strong>' );
			feedStatusSelector.css( 'color', '#0000FF' );
			feedStatusSwitchSelector.html( '' );
			productsSelector.html( tvc_feed_list_form_vars.processing_the_feed );
			break;

		case 4: // In queue
			feedStatusSelector.html( '<strong>' + tvc_feed_list_form_vars.processing_queue + '</strong>' );
			feedStatusSelector.css( 'color', '#00CCFF' );
			feedStatusSwitchSelector.html( tvc_feed_list_form_vars.list_activate + ' ' );
			break;

		case 5: // Has errors
			feedStatusSelector.html( '<strong>' + tvc_feed_list_form_vars.has_errors + '</strong>' );
			feedStatusSelector.css( 'color', '#FF0000' );
			productsSelector.html( tvc_feed_list_form_vars.unknown );
			feedStatusSwitchSelector.html( tvc_feed_list_form_vars.list_activate + ' ' );
			break;

		case 6: // Failed processing
			feedStatusSelector.html( '<strong>' + tvc_feed_list_form_vars.processing_failed + '</strong>' );
			feedStatusSelector.css( 'color', '#FF3300' );
			productsSelector.html( tvc_feed_list_form_vars.unknown );
			feedStatusSwitchSelector.html( '' );
			break;
	}
}

/**
 * Document ready actions
 */
jQuery( document ).ready(
	function() {
		// No actions required at the moment.
	}
);
