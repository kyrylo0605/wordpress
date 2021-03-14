"use strict";
function tvc_activateFeedCategoryMapping( id ) {
	var feedSelectorElement = jQuery( '#feed-selector-' + id );
	var children            = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];

	feedSelectorElement.prop( 'checked', true );

	tvc_activateFeedCategorySelector( id );

	for ( var i = 0; i < children.length; i ++ ) {
		tvc_activateFeedCategorySelector( children[ i ] );
	}
}

function tvc_activateFeedCategorySelection( id ) {
	var feedSelectorElement = jQuery( '#feed-selector-' + id );
	var children            = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];

	feedSelectorElement.prop( 'checked', true );

	_feedHolder.activateCategory( id, true );

	for ( var i = 0; i < children.length; i ++ ) {
		tvc_activateFeedCategorySelection( children[ i ] );
	}
}

function tvc_activateAllFeedCategoryMapping() {
	var tableType = 0 !== document.getElementsByClassName( 'tvc-category-mapping-selector' ).length ? 'category_mapping_table' : 'category_selection_table';
	var idCollection = 'category_mapping_table' === tableType
		? document.getElementsByClassName( 'tvc-category-mapping-selector' ) // category mapping table
		: document.getElementsByClassName( 'tvc-category-selector' ); // category selection table

	for ( var j = 0; j < idCollection.length; j ++ ) {
		if ( 'category_mapping_table' === tableType ) {
			tvc_activateFeedCategorySelector(idCollection[ j ].value);
		} else {
			tvc_activateFeedCategorySelection(idCollection[ j ].value);
		}
	}
}

function tvc_activateFeedCategorySelector( id ) {

	// some channels use your own shop's categories
	var usesOwnCategories   = tvc_channelUsesOwnCategories( _feedHolder[ 'channel' ] );
	var feedCategoryText    = usesOwnCategories ? 'shopCategory' : 'default';
	var feedSelectorElement = jQuery( '#feed-selector-' + id );
	var feedCategoryElement = jQuery( '#feed-category-' + id );

	// activate the category in the feedHolder
	_feedHolder.activateCategory( id, usesOwnCategories );

	// get the children of this selector if any
	var children = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];

	if ( feedCategoryElement.html() === '' ) {
		feedCategoryElement.html( tvc_mapToDefaultCategoryElement( id, feedCategoryText ) );
	}

	feedSelectorElement.prop( 'checked', true );

	for ( var i = 0; i < children.length; i ++ ) {
		tvc_activateFeedCategorySelector( children[ i ] );
	}
}

function tvc_deactivateFeedCategorySelection( id ) {
	var feedSelectorElement = jQuery( '#feed-selector-' + id );
	var children            = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];

	feedSelectorElement.prop( 'checked', false );

	_feedHolder.deactivateCategory( id );

	for ( var i = 0; i < children.length; i ++ ) {
		tvc_deactivateFeedCategorySelection( children[ i ] );
	}
}

function tvc_deactivateFeedCategoryMapping( id ) {
	var feedSelectorElement = jQuery( '#feed-selector-' + id );

	tvc_deactivateFeedCategorySelector( id, true );

	var children = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];

	for ( var i = 0; i < children.length; i ++ ) {
		tvc_deactivateFeedCategorySelector( children[ i ], false );
	}
}

function tvc_deactivateAllFeedCategoryMapping() {
	var idCollection = 0 !== document.getElementsByClassName( 'tvc-category-mapping-selector' ).length
		? document.getElementsByClassName( 'tvc-category-mapping-selector' ) // category mapping table
		: document.getElementsByClassName( 'tvc-category-selector' ); // category selection table

	for ( var j = 0; j < idCollection.length; j ++ ) {
		tvc_deactivateFeedCategorySelector( idCollection[j].value );
	}
}

function tvc_contains_special_characters( string ) {
	var specialChars = '%^#<>{}[]\/~`@?:;=&';

	for ( var i = 0; i < specialChars.length; i ++ ) {
		if ( string.indexOf( specialChars[ i ] ) > - 1 ) {
			return true;
		}
	}

	return false;
}

function tvc_feedFileNameExists( fileName ) {
	console.log(fileName);

	return false;
}

function tvc_deactivateFeedCategorySelector( id, parent ) {
	var feedSelectorElement = jQuery( '#feed-selector-' + id );

	_feedHolder.deactivateCategory( id );

	jQuery( '#feed-category-' + id ).html( '' );
	jQuery( '#category-selector-catmap-' + id ).hide();

	feedSelectorElement.prop( 'checked', false );

	if ( ! parent ) {
		var children = feedSelectorElement.attr( 'data-children' ) ? JSON.parse( feedSelectorElement.attr( 'data-children' ) ) : [];
		for ( var i = 0; i < children.length; i ++ ) {
			tvc_deactivateFeedCategorySelector( children[ i ], false );
		}
	}
}

/**
 * Shows and hides the category sub level selectors depending on the selected level
 *
 * @param {string} currentLevelId
 */
function tvc_hideSubs( currentLevelId ) {

	// identify the level from the level id
	var level    = currentLevelId.match( /(\d+)$/ )[ 0 ];
	var idString = currentLevelId.substring( 0, currentLevelId.length - level.length );

	// only show sub fields that are at or before the selected level. Hide the rest
	for ( var i = 7; i > level; i -- ) {
		var categorySubLevelSelector = jQuery( '#' + idString + i );
		categorySubLevelSelector.css( 'display', 'none' );
		categorySubLevelSelector.empty();
	}
}

/**
 * Replaces special html characters to HTML entities.
 *
 * @param text
 * @returns {string}
 */
function tvc_escapeHtml( text ) {
	text = text || '';
	text = text.replace( /&([^#])(?![a-z1-4]{1,8};)/gi, '&#038;$1' );
	return text.replace( /</g, '&lt;' ).replace( />/g, '&gt;' ).replace( /"/g, '&quot;' ).replace( /'/g, '&#039;' );
}

/**
 * Takes a field string from a source input string and splits it up even when a pipe character
 * is used in a combined source input string
 *
 * @since 2.3.0
 * @param {string} fieldString
 * @returns {array}
 */
function tvc_splitCombinedFieldElements( fieldString ) {
	if ( ! fieldString ) {
		return [];
	}

	var reg        = /\|[0-9]/; // pipe splitter plus a number directly after it
	var result     = [];
	var sliceStart = 0;
	var match;

	// fetch the separate field strings and put them in the result array
	while (( match = reg.exec(fieldString) ) !== null) {
		var ind = match.index;
		result.push(fieldString.substring(sliceStart, ind));
		fieldString = fieldString.slice(ind + 1);
	}

	// then add the final field string to the result array
	result.push( fieldString );

	return result;
}

function tvc_showFeedSpinner() {
	jQuery( '#feed-spinner' ).show();
	jQuery( 'body' ).css( 'cursor', 'wait' );
}

function tvc_hideFeedSpinner() {
	jQuery( '#feed-spinner' ).hide();
	jQuery( 'body' ).css( 'cursor', 'default' );
}

function tvc_enableFeedActionButtons() {
	// enable the Generate and Save button
	jQuery( '[name=generate-top]' ).prop( 'disabled', false );
	jQuery( '[name=generate-bottom]' ).prop( 'disabled', false );
	jQuery( '[name=save-top]' ).prop( 'disabled', false );
	jQuery( '[name=save-bottom]' ).prop( 'disabled', false );
	jQuery( '[name=new]' ).prop( 'disabled', false );
	jQuery( '[name=view-top]' ).prop( 'disabled', false );

	if ( '' !== jQuery( '#tvc-feed-url' ).text() ) {
		tvc_enableViewFeedButtons();
	}
}

function disableFeedActionButtons() {
	// keep the Generate and Save buttons disabled
	jQuery( '[name=generate-top]' ).prop( 'disabled', true );
	jQuery( '[name=generate-bottom]' ).prop( 'disabled', true );
	jQuery( '[name=save-top]' ).prop( 'disabled', true );
	jQuery( '[name=save-bottom]' ).prop( 'disabled', true );
	jQuery( '[name=new]' ).prop( 'disabled', true );
	tvc_disableViewFeedButtons();
}

function tvc_enableViewFeedButtons() {
	jQuery('[name=view-top]').prop('disabled', false);
	jQuery('[name=view-bottom]').prop('disabled', false);
	jQuery( '[name=new]' ).prop( 'disabled', false );
	jQuery( '[name=open-bottom]' ).prop( 'disabled', false );
}

function tvc_disableViewFeedButtons() {
	jQuery( '[name=view-top]' ).prop( 'disabled', true );
	jQuery( '[name=view-bottom]' ).prop( 'disabled', true );
	jQuery( '[name=new]' ).prop( 'disabled', true );
	jQuery( '[name=open-bottom]' ).prop( 'disabled', true );
}

function tvc_show_error_message( message ) {
	var errorMessageSelector = jQuery( '#error-message' );
	errorMessageSelector.empty();
	errorMessageSelector.append( '<p>' + message + '</p>' );
	errorMessageSelector.show();
}

function tvc_show_success_message( message ) {
	var successMessageSelector = jQuery( '#success-message' );
	successMessageSelector.empty();
	successMessageSelector.append( '<p>' + message + '</p>' );
	successMessageSelector.show();
}