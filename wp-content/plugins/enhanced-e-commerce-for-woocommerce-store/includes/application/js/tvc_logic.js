
"use strict";
/*global tvc_feed_settings_form_vars, tvc_manage_channels_vars, tvc_feed_list_form_vars */
var _feedHolder;

function tvc_editCategories() {
	var cat_lvl_selector = jQuery( '#category-selector-lvl' );

	if ( ! tvc_isCustomChannel( _feedHolder[ 'channel' ] ) ) {
		var currentCategories = _feedHolder[ 'mainCategory' ].split( ' > ' );
		var cat_length        = currentCategories.length;
		var cat_selectors     = jQuery( '#lvl_' + ( cat_length ) ).html() !== '' ? cat_length + 1 : cat_length;

		jQuery( '#selected-categories' ).hide();
		jQuery( '#lvl_0' ).prop( 'disabled', false );

		for ( var i = 0; i < cat_selectors; i ++ ) {
			var levelElement = jQuery( '#lvl_' + i );

			if ( ! currentCategories[ i ] ) {
				levelElement.val( '0' );
			}
			levelElement.show();
		}
	} else {
		// as the user selected a free format, just show a text input control
		cat_lvl_selector.html(
			tvc_freeCategoryInputCntrl(
				'default',
				_feedHolder[ 'feedId' ],
				_feedHolder[ 'mainCategory' ]
			)
		);
		cat_lvl_selector.prop( 'disabled', false );
	}
}

function tvc_generateFeed() {
	if ( jQuery( '#file-name' ).val() !== '' ) {
		if ( _feedHolder[ 'categoryMapping' ] && _feedHolder[ 'categoryMapping' ].length > 0 ) {
			disableFeedActionButtons();
			tvc_generateAndSaveFeed();
		} else {
			var userInput = confirm(
				tvc_feed_settings_form_vars.no_category_selected
			);

			if ( userInput === true ) {
				disableFeedActionButtons();
				tvc_generateAndSaveFeed();
			}
		}
	} else {
		jQuery( '#alert-message' ).
		html( '<p>' + tvc_feed_settings_form_vars.file_name_required + '</p>' );
		jQuery( '#success-message' ).show();
	}
}

function tvc_saveFeedData() {
	if ( jQuery( '#file-name' ).val() !== '' ) {
		tvc_saveFeed();
	} else {
		jQuery( '#alert-message' ).
		html( '<p>' + tvc_feed_settings_form_vars.file_name_required + '</p>' );
		jQuery( '#success-message' ).show();
	}
}

function getCombinedValue( rowId, sourceLevel ) {
	var c             = 1;
	var combinedValue = '';
	var oldValue      = _feedHolder.getCombinedOutputValue( rowId, sourceLevel );

	while ( jQuery( '#combined-input-field-cntrl-' + rowId + '-' + sourceLevel + '-' + c ).
	val() ) {
		var idString = rowId + '-' + sourceLevel + '-' + c;

		var selectedValue = jQuery( '#combined-input-field-cntrl-' + idString ).
		val();

		combinedValue += c > 1 ?
			jQuery( '#combined-separator-cntrl-' + idString ).val() + '#' :
			'';

		if ( selectedValue !== 'static' ) {
			combinedValue += selectedValue !== 'select' ?
				selectedValue + '|' :
				'';
		} else if ( jQuery( '#static-input-field-' + idString ).val() ) {
			combinedValue += selectedValue + '#' + jQuery( '#static-input-field-' + idString ).val() + '|';
		} else {
			combinedValue = oldValue + '|';
			break; // if one of the static input fields is still empty, return the old value
		}

		c ++;
	}

	combinedValue = combinedValue.substring( 0, combinedValue.length - 1 ); // remove the last |

	return c > 1 ? combinedValue : false; // need at least two fields to be valid
}

function tvc_staticValueChanged( id, level, combinationLevel ) {
	if ( combinationLevel > 0 ) { // the static field resides in a combination source
		tvc_changedCombinedOutput( id, level, combinationLevel );
	} else {
		// store the change in the feed
		tvc_setStaticValue( id, level, combinationLevel );

		// when the identifier_exists static value has changed, the level of a few attributes should be changed
		if ( id === 34 ) {
			tvc_setIdentifierExistsDependancies();
		}
	}
}

function tvc_changedOutputSelection( level ) {
	var outputFieldControlElement = jQuery( '#output-field-cntrl-' + level );

	if ( outputFieldControlElement.val() !== 'no-value' ) {
		tvc_activateOptionalFieldRow( level, outputFieldControlElement.val() );
	}
}

function tvc_hasExtraSourceRow( nrOfSources, value ) {
	if ( value.length > 0 ) {
		return value[ nrOfSources - 1 ].hasOwnProperty( 'c' );
	} else {
		return false;
	}
}

function tvc_changedCustomOutputTitle() {
	var title = jQuery( '#custom-output-title-input' ).val();

	if ( title ) {
		tvc_activateCustomFieldRow( title );
	}
}

function tvc_deleteSpecificFeed( id, title ) {
	var userInput = confirm( tvc_feed_list_form_vars.confirm_delete_feed.replace( '%feedname%', title ) );

	if ( userInput === true ) {
		tvc_deleteFeed( id, title );
		console.log( 'File ' + title + ' removed from server.' );
		tvc_show_success_message( tvc_feed_list_form_vars.feed_removed.replace( '%feedname%', title ) );
		parent.location='admin.php?page=tvc-product-feed-manager&tab=feed-list';
	}
}

function tvc_alertRemoveChannel() {
	var userInput = confirm( tvc_manage_channels_vars.confirm_removing_channel );
	if ( true !== userInput ) {
		return false;
	}
}

function tvc_valueOptionChanged( rowId, sourceLevel, valueEditorLevel ) {
	var type = jQuery( '#value-options-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel ).val();

	//var selectorCode = tvc_getCorrectValueSelector( rowId, sourceLevel, valueEditorLevel, type, '', '' );
	var selectorCode = tvc_getCorrectValueSelector( rowId, sourceLevel, 0, type, '', '' );

	jQuery( '#value-editor-input-span-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel ).html( selectorCode );
}

function tvc_getCorrectValueSelector(
	rowId, sourceLevel, valueEditorLevel, type, value, endValue ) {
	var selectorCode = '';

	// TODO: the type is now based on the value and on the text. Should be only value as this makes it
	// easier to work with different languages
	switch ( type ) {
		case '0':
		case 'change nothing':
			tvc_valueInputOptionsChanged( rowId, sourceLevel, valueEditorLevel ); // save the value in meta as there is no input field required
			selectorCode = '';
			break;

		case '1':
		case 'overwrite':
			selectorCode = tvc_valueOptionsSingleInput( rowId, sourceLevel, valueEditorLevel, value );
			break;

		case '2':
		case 'replace':
			selectorCode = tvc_valueOptionsReplaceInput( rowId, sourceLevel, valueEditorLevel, value, endValue );
			break;

		case '3':
		case 'remove':
		case '4':
		case 'add prefix':
		case '5':
		case 'add suffix':
			selectorCode = tvc_valueOptionsSingleInputValue( rowId, sourceLevel, valueEditorLevel, value );
			break;

		case '6':
		case 'recalculate':
			selectorCode = tvc_valueOptionsRecalculate( rowId, sourceLevel, valueEditorLevel, value, endValue );
			break;

		case '7':
		case 'convert to child-element':
			selectorCode = tvc_valueOptionsElementInput( rowId, sourceLevel, valueEditorLevel, value );
			break;

		default:
			selectorCode = tvc_valueOptionsSingleInput( rowId, sourceLevel, valueEditorLevel, value );
			break;
	}

	return selectorCode;
}

function tvc_deactivateFeed( id ) {
	tvc_switchFeedStatus(
		id,
		function( result ) {
			tvc_updateFeedRowStatus( id, parseInt( result ) );
		}
	);
}

function tvc_duplicateFeed( id, feedName ) {
	tvc_duplicateExistingFeed(
		id,
		function( result ) {
			if ( result ) {
				tvc_show_success_message( tvc_feed_list_form_vars.added_feed_copy.replace( '%feedname%', feedName ) );
			}
		}
	);
}

function tvc_regenerateFeed( feedId ) {
	// when there's already a feed processing, then the status should be "in queue", else status should set to "processing"
	var feedStatus = tvcQueueStringIsEmpty() ? 3 : 4;

	tvcAddToQueueString( feedId );

	tvc_showFeedSpinner();

	tvc_updateFeedRowStatus( feedId, feedStatus );

	console.log( 'Started regenerating feed ' + feedId );

	tvc_updateFeedFile( feedId, function( xmlResult ) {

		tvc_hideFeedSpinner();

		console.log(xmlResult);

		// activate the feed list status checker to update the feed list when a status changes
		var checkStatus = setInterval( function(){
			tvc_getCurrentFeedStatus( feedId, function( statResult ) {
				var data = JSON.parse( statResult );
				if ('3' !== data[ 'status_id' ] && '4' !== data[ 'status_id' ]) {
					console.log( data );
					tvc_resetFeedStatus( data );
					tvc_resetFeedList();
					clearInterval( checkStatus );
					tvcRemoveFromQueueString( feedId );
				}
			} );
		}, 10000 );
	})
}

function tvc_viewFeed( url ) {
	if ( -1 !== url.indexOf( 'http' ) ) { // Filter out duplicate feeds that have not been generated yet.
		window.open(url);
	} else {
		alert( tvc_feed_list_form_vars.feed_not_generated );
	}
}

function tvc_addRowValueEditor(
	rowId, sourceLevel, valueEditorLevel, values ) {
	// add the change values controls
	jQuery( '#end-row-id-' + rowId ).remove();
	jQuery( '#row-' + rowId ).
	append( tvc_valueEditor( rowId, sourceLevel, valueEditorLevel, values ) + tvc_endrow( rowId ) );

	// and remove the edit values control
	jQuery( '#value-editor-input-query-add-span-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel ).remove();
}

/**
 * Takes an array of words and puts them together in a camel structure way.
 *
 * @param {array}    stringArray     contains the words from which the string should be generated
 *
 * @returns {string} camel structured string
 */
function tvc_convertToCamelCase( stringArray ) {
	// first word should remain lowercase
	var result = stringArray[ 0 ].toLowerCase();

	for ( var i = 1; i < stringArray.length; i++ ) {
		result += stringArray[ i ].charAt( 0 ).toUpperCase() + stringArray[ i ].slice( 1 );
	}

	return result;
}

function tvc_addValueEditorQuery( rowId, sourceLevel, conditionLevel ) {
	if ( tvc_changeValueIsFilled( rowId, sourceLevel, conditionLevel ) ) {
		if ( tvc_queryIsFilled(
			rowId,
			(
				sourceLevel - 1
			),
			1
		)
		) {
			tvc_showEditValueQuery( rowId, sourceLevel, conditionLevel, true );
		} else {
			alert( tvc_feed_settings_form_vars.query_requirements );
		}
	} else {
		alert( tvc_feed_settings_form_vars.first_fill_in_change_value );
	}
}

function tvc_queryStringToQueryObject( queryString ) {
	var queryObject = {};

	if ( queryString ) {
		for ( var key in queryString ) {
			queryObject = tvc_convertQueryStringToQueryObject( queryString[ key ] );
		}
	}

	return queryObject;
}

function tvc_valueStringToValueObject( valueString ) {
	var valueObject = {};

	if ( valueString ) {
		for ( var key in valueString ) {
			// do not process the query part of the string
			if ( key !== 'q' ) {
				valueObject = tvc_convertValueStringToValueObject( valueString[ key ] );
			}
		}
	}

	return valueObject;
}

function tvc_convertQueryStringToQueryObject( queryString ) {
	var queryObject = {};

	var stringSplit = queryString.split( '#' );

	if ( stringSplit[ 0 ] === '1' || stringSplit[ 0 ] === '2' ) {
		queryObject.preCondition = stringSplit[ 0 ];
	} else {
		queryObject.preCondition = '0';
	}

	queryObject.source    = stringSplit[ 1 ];
	queryObject.condition = stringSplit[ 2 ];
	queryObject.value     = stringSplit[ 3 ] ? stringSplit[ 3 ] : '';
	queryObject.endValue  = stringSplit[ 5 ] ? stringSplit[ 5 ] : '';

	return queryObject;
}

function tvc_resortObject( object ) {
	var result = [];
	var i      = 1;

	// re-sort the conditions
	for ( var element in object ) {
		var o = {};
		for ( var key in object[ element ] ) {
			if ( key !== 'q' ) { // exclude q as key
				o[ i ] = object[ element ][ key ];
				result.push( o );
			} else {
				result[ i - 1 ].q = object[ element ][ key ];
			}
		}

		i ++;
	}

	// don't return an empty {} string
	return i > 1 ? result : '';
}

function tvc_convertValueStringToValueObject( valueString ) {
	var valueObject = {};
	var valueSplit  = valueString.split( '#' );

	valueObject.preCondition = valueSplit[ 0 ];
	valueObject.condition    = valueSplit[ 1 ];
	valueObject.value        = valueSplit[ 2 ];
	valueObject.endValue     = valueSplit[ 3 ] ? valueSplit[ 3 ] : '';

	return valueObject;
}

function tvc_makeCleanQueryObject() {
	var queryObject = {};

	queryObject.preCondition = 'if';
	queryObject.source       = 'select';
	queryObject.condition    = '';
	queryObject.value        = '';
	queryObject.endValue     = '';

	return queryObject;
}

function tvc_makeCleanValueObject() {
	var valueObject = {};

	valueObject.preCondition = 'change';
	valueObject.condition    = 'overwrite';
	valueObject.value        = '';
	valueObject.endValue     = '';

	return valueObject;
}

function tvc_addNewItemToCategoryString(
	level, oldString, newValue, separator ) {
	var categoryLevel = oldString.split( separator ).length;

	if ( oldString === tvc_feed_settings_form_vars.map_to_default_category || level === '0' ) {
		return newValue;
	} else {
		if ( categoryLevel <= level ) {
			return oldString + separator + newValue;
		} else {
			var pos = 0;

			for ( var i = 0; i < level; i ++ ) {
				pos         = oldString.indexOf( separator, pos + 1 );
				var oldPart = oldString.substring( 0, pos );
			}

			return oldPart + separator + newValue;
		}
	}
}