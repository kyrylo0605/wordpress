"use strict";
/*global tvc_feed_settings_form_vars */
/**
 * Returns the html code for a static input field
 *
 * @param {string} rowId            to which attribute row the field belongs
 * @param {string} queryLevel        to which query level the field belongs
 * @param {string} combinationLevel    in combined sources, to which combination level the field belongs
 * @param {string} selectedValue    the value of the field
 * @returns {string} the html code
 */
function tvc_staticInputField( rowId, queryLevel, combinationLevel, selectedValue ) {

	selectedValue = tvc_escapeHtml( selectedValue );

	return '<input type="text" name="static-input-field" id="static-input-field-' + rowId + '-' + queryLevel + '-' + combinationLevel
		+ '" class="static-input-field" value="' + selectedValue + '" onchange="tvc_staticValueChanged('
		+ rowId + ', ' + queryLevel + ', ' + combinationLevel + ')">';
}

function tvc_feedStaticValueSelector( fieldName, rowId, sourceLevel, level, value, channel ) {

	var restrictedFields = tvc_restrictedStaticFields( channel, fieldName );

	if ( restrictedFields.length > 0 ) {

		return tvc_displayCorrectStaticField( rowId, sourceLevel, level, channel, fieldName, value );
	} else {

		return tvc_staticInputField( rowId, sourceLevel, level, value );
	}
}

function tvc_staticInputSelect( rowId, level, combinationLevel, options, selected ) {

	var htmlCode = '<div class="static-value-control" id="static-value-control-' + rowId + '-' + level + '-' + combinationLevel + '">';

	htmlCode += '<select class="static-select-control input-select" id="static-condition-input-' + rowId + '-' + level + '-' + combinationLevel
		+ '" onchange="tvc_staticValueChanged(' + rowId + ', ' + level + ', ' + combinationLevel + ')">';

	for ( var i = 0; i < options.length; i ++ ) {

		// some channels use a key and value combination for the static values
		var key   = typeof options[ i ] !== 'object' ? options[ i ] : options[ i ][ 'id' ];
		var value = typeof options[ i ] !== 'object' ? options[ i ] : options[ i ][ 'value' ];

		if ( key !== selected ) {
			htmlCode += '<option value="' + key + '">' + value.replace( '_', ' ' ) + '</option>';
		} else {
			htmlCode += '<option value="' + key + '" selected>' + value.replace( '_', ' ' ) + '</option>';
		}
	}

	htmlCode += '</select></div>';

	return htmlCode;
}

function tvc_advisedSourceSelector( rowId, sourceCounter, advisedSource ) {


	return '<div class="advised-source">' + advisedSource + tvc_editSourceSelector( rowId, sourceCounter ) + '</div>';
}

function tvc_editSourceSelector( rowId, sourceCounter ) {

	var onClickString = 'tvc_editOutput( ' + rowId + ', ' + sourceCounter + ' )';

	return ' (<a class="edit-output tvc-btn tvc-btn-small" href="javascript:void(0)" onclick="' + onClickString + '">' + tvc_feed_settings_form_vars.edit + '</a>)';
}

function tvc_forAllProductsCondition( rowId, level, isVisible ) {

	var other_val = level > 0 ? tvc_feed_settings_form_vars.other + ' ' : '';

	return '<div class="colw col40w allproducts" id="condition-col-' + rowId + '-' + level + '" style="display:' + isVisible + '"> '
		+ tvc_feed_settings_form_vars.all_other_products.replace( '%other%', other_val )
		+ ' (<a class="edit-prod-query tvc-btn tvc-btn-small" href="javascript:void(0)" id="edit-prod-query-' + rowId + '" '
		+ 'onclick="tvc_addCondition(' + rowId + ', ' + level + ', 0, \'\')">'
		+ tvc_feed_settings_form_vars.edit + '</a>)'
		+ '</div>';
}

function tvc_editValueSpan( rowId, sourceLevel, valueEditorLevel, displayStyle ) {

	return '<div class="edit-value-control" id="value-editor-input-query-add-span-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel + '" style="display:' + displayStyle + '"><p>'
		+ '(<a class="edit-prod-query tvc-btn tvc-btn-small" href="javascript:void(0)" id="edit-row-value-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel + '" '
		+ 'onclick="tvc_addRowValueEditor(' + rowId + ', ' + sourceLevel + ', ' + valueEditorLevel + ', \'\')">'
		+ tvc_feed_settings_form_vars.edit_values + '</a>)'
		+ '</p></div>';
}

function tvc_requiresForAllOtherProductsField( mapping ) {
	return mapping.hasOwnProperty( 'c' );
}

function tvc_addFeedSourceRow( rowId, sourceLevel, sourceRowsData, channel, removable ) {

	var borderStyleClass      = sourceLevel > 0 ? ' dotted-top-line' : '';
	var showEditValuesControl = 'initial';
	var deleteValueControl    = removable ? tvc_removeOutputCntrl( rowId, sourceRowsData.fieldName ) : '';
	var requiresForAllOtherProductsField = sourceLevel + 1 === sourceRowsData.mapping.length ? tvc_requiresForAllOtherProductsField( sourceRowsData.mapping[sourceLevel] ) : false;

	if ( sourceRowsData.customCondition ) { // no edit value control for the Category item
		showEditValuesControl = 'none';
	}

	// source wrapper
	var htmlCode = '<div class="feed-source-row" id="source-' + rowId + '-' + sourceLevel + '">';

	// first column wrapper
	htmlCode += '<div class="add-to-feed-column colw col20w">';

	// first column (add to feed column)
	htmlCode += sourceLevel === 0 ? '<span class="output-field-label">' + sourceRowsData.fieldName + '</span>' + deleteValueControl : '&nbsp;';

	htmlCode += '</div>';

	// the source data and queries wrapper
	htmlCode += '<div class="source-data-column colw col80w' + borderStyleClass + '" id="source-data-' + rowId + '-' + sourceLevel + '">';

	htmlCode += tvc_addSourceDataAndQueriesColumn( sourceLevel, sourceRowsData );

	// close the source data and queries wrapper
	htmlCode += '</div>';

	if ( sourceLevel === 0 && sourceRowsData.changeValues.length === 0 ) {
		htmlCode += tvc_editValueSpan( sourceRowsData.rowId, sourceLevel, 0, showEditValuesControl );
	} else {

		// Aanzetten om fout 73 verder te onderzoeken
		//        console.log(JSON.stringify(sourceRowsData.changeValues));
		//        console.log(sourceRowsData.changeValues.length);
		//        console.log(sourceLevel);

		//        for ( var i = 0; i < sourceRowsData.changeValues.length; i++ ) {
		//
		//            if ( sourceRowsData.changeValues[sourceLevel] ) {
		//
		//                // add the change value editor fields
		//                htmlCode += tvc_valueEditor( sourceRowsData.rowId, sourceLevel, i, sourceRowsData.changeValues );
		//            }

		//        }
	}

	// close the source wrapper
	htmlCode += '</div>';

	if ( requiresForAllOtherProductsField ) {
		htmlCode += tvc_orSelectorRowCode( rowId, sourceLevel + 1, borderStyleClass );
	}

	return htmlCode;
}

function tvc_removeOutputCntrl( rowId, fieldName ) {
	var htmlCode = ' (';
	htmlCode    += '<a class="remove-output tvc-btn tvc-btn-small" href="javascript:void(0)" id="';
	htmlCode    += rowId + '" onclick="tvc_removeRow(' + rowId + ', \'' + fieldName + '\')">' + tvc_feed_settings_form_vars.remove + '</a>';
	htmlCode    += ') ';

	return htmlCode;
}

function tvc_conditionQueryCntrl( id, sourceLevel, conditionLevel, subConditionLevel, identifier, onChangeFunction, selectedValue ) {
	var queryOptions             = tvc_queryOptionsEng();
	var queryLevelString         = subConditionLevel !== - 1 ? '-' + subConditionLevel : '';
	var queryLevelFunctionString = subConditionLevel !== - 1 ? ', ' + subConditionLevel : '';

	var htmlCode = '<select class="select-control condition-query-select" id="' + identifier + '-'
		+ id + '-' + sourceLevel + '-' + conditionLevel + queryLevelString + '" onchange="' + onChangeFunction + '(' + id + ', ' + sourceLevel + ', ' + conditionLevel + queryLevelFunctionString + ')"> ';

	for ( var i = 0; i < queryOptions.length; i ++ ) {
		htmlCode += parseInt( selectedValue ) !== i ? '<option value = "' + i + '">' + queryOptions[ i ] + '</option>'
			: '<option value = "' + i + '" selected>' + queryOptions[ i ] + '</option>';
	}

	htmlCode += '</select>';

	return htmlCode;
}

function tvc_valueEditor( rowId, sourceLevel, valueEditorLevel, valueObject ) {
	var valueArray                = tvc_valueStringToValueObject( valueObject[ sourceLevel ] );
	var queryDisplay              = valueObject[ valueEditorLevel ] && valueObject[ valueEditorLevel ].q ? 'none' : 'initial';
	var value                     = tvc_countObjectItems( valueArray ) > 0 ? valueArray : tvc_makeCleanValueObject();
	var valueSelector             = tvc_feed_settings_form_vars.and_change_values + ' ';
	var html                      = '<div class="change-source-value-wrapper" id="edit-value-span-' + rowId + '-' + sourceLevel + '-0">';
	var removeValueEditorSelector = sourceLevel === 0 ? ' (<a class="remove-value-editor-query tvc-btn tvc-btn-small" href="javascript:void(0)" id="remove-value-editor-query-' + rowId + '-' + sourceLevel
		+ '-' + valueEditorLevel + '" onclick="tvc_removeValueEditor(' + rowId + ', ' + sourceLevel + ', ' + valueEditorLevel + ')">' + tvc_feed_settings_form_vars.remove_value_editor + '</a>)' : '';

	if ( sourceLevel > 0 ) {
		valueSelector = tvc_feed_settings_form_vars.and + ' ';
	}

	html += valueSelector;
	html += tvc_changeValueCntrl( rowId, sourceLevel, valueEditorLevel, value.condition );
	html += '<span id="value-editor-input-span-' + rowId + '-' + sourceLevel + '-0">';
	html += tvc_getCorrectValueSelector( rowId, sourceLevel, 0, value.condition, value.value, value.endValue );
	html += '</span>';
	html += '<span id="value-editor-selectors-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel + '">';
	html += tvc_forAllProductsAtChangeValuesSelector( rowId, sourceLevel, valueEditorLevel, queryDisplay );
	html += '<span id="value-editor-input-query-remove-span-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel + '">';
	html += removeValueEditorSelector;
	html += '</span>';
	html += '</span>';
	html += '<span id="value-editor-queries-' + rowId + '-' + sourceLevel + '-0">';

	if ( valueObject[ valueEditorLevel ] && valueObject[ valueEditorLevel ].q ) {
		for ( var i = 1; i < valueObject[ valueEditorLevel ].q.length + 1; i ++ ) {
			var queryArray = tvc_convertQueryStringToQueryObject( valueObject[ valueEditorLevel ].q[ i - 1 ][ i ] );
			var lastValue  = i >= valueObject[ valueEditorLevel ].q.length ? true : false;

			html += tvc_ifValueQuerySelector( rowId, sourceLevel, i, queryArray, lastValue );
		}
	}

	html += '</span></div>';

	return html;
}

function tvc_endrow( rowId ) {
	return '<div class="end-row" id="end-row-id-' + rowId + '">&nbsp;</div>';
}


function tvc_forAllProductsAtChangeValuesSelector( rowId, sourceLevel, valueEditorLevel, displayStatus ) {
	var other_val = sourceLevel > 0 ? tvc_feed_settings_form_vars.other + ' ' : '';

	return '<div class="colw col30w allproducts" id="value-editor-input-query-span-' + rowId + '-' + sourceLevel + '-0" style="display:' + displayStatus + ';float:right;">'
		+ tvc_feed_settings_form_vars.all_other_products.replace( '%other%', other_val )
		+ ' (<a class="edit-value-editor-query tvc-btn tvc-btn-small" href="javascript:void(0)" id="edit-value-editor-query-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel
		+ '" onclick="tvc_addValueEditorQuery(' + rowId + ', ' + sourceLevel + ', 0)">' + tvc_feed_settings_form_vars.edit + '</a>)'
		+ '</div>';
}

function tvc_valueOptionsSingleInput( rowId, sourceLevel, valueEditorLevel, value ) {
	return ' ' + tvc_feed_settings_form_vars.to + tvc_valueOptionsSingleInputValue( rowId, sourceLevel, valueEditorLevel, value );
}

function tvc_valueOptionsElementInput( rowId, sourceLevel, valueEditorLevel, value ) {
	return ' ' + tvc_feed_settings_form_vars.with_element_name + tvc_valueOptionsSingleInputValue( rowId, sourceLevel, valueEditorLevel, value );
}

function tvc_valueOptionsSingleInputValue( rowId, sourceLevel, valueEditorLevel, optionsSelectorValue ) {

	optionsSelectorValue = tvc_escapeHtml( optionsSelectorValue );

	return ' <input type="text" onchange="tvc_valueInputOptionsChanged(' + rowId + ', ' + sourceLevel
		+ ', ' + valueEditorLevel + ')" id="value-options-input-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel + '" value="' + optionsSelectorValue + '">';
}

function tvc_addFeedStatusChecker( feedId ) {
	return '<script type="text/javascript">var tvcStatusCheck_' + feedId + ' = null; '
		+ '(function(){ tvcStatusCheck_' + feedId + ' = window.setInterval( tvc_checkAndSetStatus_' + feedId + ', 10000, ' + feedId + ' ); })(); '
		+ 'function tvc_checkAndSetStatus_' + feedId + '( feedId ) {'
		+ 'tvc_getCurrentFeedStatus( feedId, function( result ) {'
		+ 'var data = JSON.parse( result );'
		+ 'tvc_resetFeedStatus( data );'
		+ 'if( data["status_id"] !== "3" && data["status_id"] !== "4" ) {' // status is not in processing or in queue
		+ 'window.clearInterval( tvcStatusCheck_' + feedId + ' );'
		+ '}'
		+ '} );'
		+ '}</script>';
}

function tvc_valueOptionsReplaceInput( rowId, sourceLevel, valueEditorLevel, startValue, endValue ) {

	startValue = tvc_escapeHtml( startValue );

	return '<input type="text" onchange="tvc_valueInputOptionsChanged(' + rowId + ', ' + sourceLevel + ', '
		+ valueEditorLevel + ' )" id="value-options-input-' + rowId + '-' + sourceLevel + '-' + valueEditorLevel
		+ '" value="' + startValue + '"> with <input type="text" onchange="tvc_valueInputOptionsChanged('
		+ rowId + ', ' + sourceLevel + ', ' + valueEditorLevel + ')" id="value-options-input-with-' + rowId + '-'
		+ sourceLevel + '-' + valueEditorLevel + '" value="' + endValue + '">';
}

function tvc_valueOptionsRecalculate( rowId, sourceLevel, valueEditorLevel, selectedValue, recalculateValue ) {
	var valueOptions = tvc_changeValuesRecalculateOptions();

	var htmlCode = '<select class="select-value-options" id="value-options-recalculate-options-' + rowId + '-' + sourceLevel + '-0">';

	for ( var i = 0; i < valueOptions.length; i ++ ) {

		htmlCode += valueOptions[ i ] !== selectedValue ? '<option value = "' + i + '">' + valueOptions[ i ] + '</option>'
			: '<option value = "' + i + '" selected>' + valueOptions[ i ] + '</option>';
	}

	htmlCode += '</select>';
	htmlCode += ' <input type="text" onchange="tvc_valueInputOptionsChanged(' + rowId + ', ' + sourceLevel + ', ' + valueEditorLevel + ')" id="value-options-input-'
		+ rowId + '-' + sourceLevel + '-' + valueEditorLevel + '" value="' + recalculateValue + '">';

	return htmlCode;
}

function tvc_changeValueCntrl( rowId, conditionLevel, valueEditorLevel, selectedValue ) {
	var valueOptions = tvc_changeValuesOptions();

	var htmlCode = '<select class="select-value-options" id="value-options-'
		+ rowId + '-' + conditionLevel + '-0" onchange="tvc_valueOptionChanged(' + rowId + ', ' + conditionLevel + ', 0)"> ';

	for ( var i = 0; i < valueOptions.length; i ++ ) {

		htmlCode += valueOptions[ i ] !== selectedValue ? '<option value = "' + i + '">' + valueOptions[ i ] + '</option>'
			: '<option value = "' + i + '" selected>' + valueOptions[ i ] + '</option>';
	}

	htmlCode += '</select>';

	return htmlCode;
}

function tvc_mapToDefaultCategoryElement( categoryId, category ) {
	var categoryText = '';
	var editable     = '';

	switch ( category ) {
		case 'default':
			categoryText = tvc_feed_settings_form_vars.map_to_default_category;
			break;

		case 'shopCategory':
			categoryText = tvc_feed_settings_form_vars.use_shop_category;
			break;

		default:
			categoryText = category;
			break;
	}

	if ( category !== 'shopCategory' ) {
		editable = ' (<a class="edit-feed-mapping tvc-btn tvc-btn-small" '
			+ 'href="javascript:void(0)" data-id="' + categoryId + '" id="edit-feed-mapping-' + categoryId
			+ '" onclick="tvc_editCategoryMapping(' + categoryId + ')">' + tvc_feed_settings_form_vars.edit + '</a>)';
	}

	return '<div class="feed-category-map-to-default" id="feed-category-map-to-default-' + categoryId
		+ '" style="display:initial"><span id="category-text-span-' + categoryId + '">' + categoryText
		+ '</span>' + editable + '</div>';
}

function tvc_mapToCategoryElement( categoryId, categoryString ) {

	return '<div class="feed-category-map" id="feed-category-map-' + categoryId
		+ '" style="display:initial"><span id="category-text-span-' + categoryId + '">' + categoryString
		+ '</span> (<a class="edit-feed-mapping tvc-btn tvc-btn-small" '
		+ 'href="javascript:void(0)" data-id="' + categoryId + '" id="edit-feed-mapping-' + categoryId
		+ '" onclick="tvc_editCategoryMapping(' + categoryId + ')">' + tvc_feed_settings_form_vars.edit + '</a>)</div>';
}

//function tvc_categorySource( rowId, sourceValue ) {
function tvc_categorySource() {
	return '<span id="category-source-string">' + tvc_feed_settings_form_vars.defined_by_category_mapping_tble + '</span>';
}

function tvc_freeCategoryInputCntrl( type, id, value ) {
	var valueString = value ? ' value="' + value + '"' : '';

	return '<input type="text" name="free-category" class="free-category-text-input custom-category-'
		+ type + '" id="free-category-text-input" onchange="tvc_freeCategoryChanged(\''
		+ type + '\', \'' + id + '\')"' + valueString + '>';
}

function tvc_inputFieldCntrl( rowId, sourceLevel, sourceValue, staticValue, advisedSource, combinedValue, isCustom ) {

	var hasAdvisedValueHtml   = advisedSource ? '<option value="advised" itemprop="basic">' + tvc_feed_settings_form_vars.use_advised_source + '</option>' : '';
	var staticSelectedHtml    = staticValue ? ' selected' : '';
	var prefix                = sourceLevel > 0 ? tvc_feed_settings_form_vars.or + ' ' : '';
	var hasCombinedOptionHtml = ! combinedValue ? '<option value="combined" itemprop="basic">' + tvc_feed_settings_form_vars.combined_source_fields + '</option>'
		: '<option value="combined" selected>' + tvc_feed_settings_form_vars.combined_source_fields + '</option>';
	var customCategoryMapping = isCustom ? '<option value="category_mapping" itemprop="basic">' + tvc_feed_settings_form_vars.category_mapping + '</option>' : '';

	return '<div class="select-control">' + prefix + '<select class="select-control input-select" id="input-field-cntrl-' + rowId + '-' + sourceLevel
		+ '" onchange="tvc_changedOutput(' + rowId + ', ' + sourceLevel + ', \'' + advisedSource + '\')"> '
		+ '<option value="select" itemprop="basic">-- ' + tvc_feed_settings_form_vars.select_a_source_field + ' --</option>'
		+ hasAdvisedValueHtml
		+ '<option value="static" itemprop="basic"'
		+ staticSelectedHtml
		+ '>' + tvc_feed_settings_form_vars.fill_with_static_value + '</option>'
		+ customCategoryMapping
		+ hasCombinedOptionHtml
		+ tvc_fixedSourcesList( sourceValue ) + '</select></div>';
}

function tvc_combinedInputFieldCntrl( rowId, sourceLevel, combinedLevel, selectedValue, fieldName, channel ) {

	var isStatic           = selectedValue && selectedValue.startsWith( 'static#' );
	var staticSelectedHtml = isStatic ? ' selected' : '';
	var staticInputHtml    = isStatic ? tvc_feedStaticValueSelector( fieldName, rowId, sourceLevel, combinedLevel, selectedValue.substring( 7 ), channel ) : '';

	return '<select class="select-control input-select align-left" id="combined-input-field-cntrl-' + rowId + '-' + sourceLevel + '-' + combinedLevel
		+ '" onchange="tvc_changedCombinedOutput(' + rowId + ', ' + sourceLevel + ', ' + combinedLevel + ')"> '
		+ '<option value="select" itemprop="basic">-- ' + tvc_feed_settings_form_vars.select_a_source_field + ' --</option>'
		+ '<option value="static" itemprop="basic"'
		+ staticSelectedHtml
		+ '>' + tvc_feed_settings_form_vars.fill_with_static_value + '</option>'
		+ tvc_fixedSourcesList( selectedValue ) + '</select>'
		+ '<div class="static-value-control" id="static-value-control-' + rowId + '-' + sourceLevel + '-' + combinedLevel + '">'
		+ staticInputHtml
		+ '</div>';
}

function tvc_combinedSeparatorCntrl( rowId, sourceLevel, combinedLevel, selectedValue ) {

	return '<select class="select-control input-select align-left" id="combined-separator-cntrl-' + rowId + '-' + sourceLevel + '-' + combinedLevel
		+ '" onchange="tvc_changedCombinationSeparator(' + rowId + ', ' + sourceLevel + ', ' + combinedLevel + ')"> '
		+ tvc_getCombinedSeparatorList( selectedValue )
		+ '</select>';
}

function tvc_alternativeInputFieldCntrl( id, selectedValue ) {

	var selectedValueHtml = selectedValue === 'static' ? ' selected' : '';

	return '<select class="select-control alternative-input-select" id="alternative-input-field-cntrl-' + id
		+ '" onchange="tvc_changedAlternativeSource(' + id + ')"> '
		+ '<option value="select">-- ' + tvc_feed_settings_form_vars.select_a_source_field + ' --</option>'
		+ '<option value="empty">-- ' + tvc_feed_settings_form_vars.an_empty_field + ' --</option>'
		+ '<option value="static"'
		+ selectedValueHtml
		+ '>' + tvc_feed_settings_form_vars.fill_with_static_value + '</option>'
		+ tvc_fixedSourcesList( selectedValue ) + '</select>';
}

function tvc_outputFieldCntrl( level ) {

	var outputLevelHtml = level === 3 ? '<option value="no-value">-- ' + tvc_feed_settings_form_vars.add_recommended_output + ' --</option>' :
		'<option value="no-value">-- ' + tvc_feed_settings_form_vars.add_optional_output + ' --</option>';

	return '<select class="select-control input-select" id="output-field-cntrl-' + level + '"> '
		+ outputLevelHtml
		+ tvc_getOutputFieldsList( level )
		+ '</select>';
}

function tvc_customOutputFieldCntrl() {
	return '<input type="text" name="custom-output-title" id="custom-output-title-input" placeholder="Enter an output title" onfocusout="tvc_changedCustomOutputTitle()">';
}

function tvc_conditionFieldCntrl( id, sourceLevel, conditionLevel, subConditionLevel, identifier, selectedValue, onChange ) {

	var subConditionLevelString = subConditionLevel !== - 1 ? '-' + subConditionLevel : '';
	var emptyOption             = identifier === 'or-field-cntrl' ? '<option value="empty">-- ' + tvc_feed_settings_form_vars.an_empty_field + ' --</option>' : '';
	var onChangeFunction        = onChange ? ' onchange="' + onChange + '"' : '';

	return '<select class="select-control input-select" id="' + identifier + '-' + id + '-' + sourceLevel + '-' + conditionLevel + subConditionLevelString + '"' + onChangeFunction + '> '
		+ '<option value="select">-- ' + tvc_feed_settings_form_vars.select_a_source_field + ' --</option>'
		+ emptyOption
		+ tvc_fixedSourcesList( selectedValue )
		+ '</select>';
}

function tvc_filterPreCntrl( feedId, filterLevel, selectedValue ) {

	var preString = '<select id="filter-pre-control-' + feedId + '-' + filterLevel + '" onchange="tvc_filterChanged(' + feedId + ', ' + filterLevel + ')">';

	if ( filterLevel > 1 ) {

		return selectedValue === '1'
			? preString + '<option value="2">' + tvc_feed_settings_form_vars.or + '</option><option value="1" selected>' + tvc_feed_settings_form_vars.and + '</option></select>'
			: preString + '<option value="2" selected>' + tvc_feed_settings_form_vars.or + '</option><option value="1">' + tvc_feed_settings_form_vars.and + '</option></select>';
	} else {

		return '';
	}

}

function tvc_filterSourceCntrl( feedId, filterLevel, selectedValue ) {

	return '<select class="select-control input-select" id="filter-source-control-' + feedId + '-' + filterLevel + '" onchange="tvc_filterChanged(' + feedId + ', ' + filterLevel + ')">'
		+ '<option value="select">-- ' + tvc_feed_settings_form_vars.select_a_source_field + ' --</option>'
		+ tvc_fixedSourcesList( selectedValue )
		+ '</select>';
}

function tvc_filterOptionsCntrl( feedId, filterLevel, selectedValue ) {

	var filterOptions = tvc_queryOptionsEng();

	var htmlCode = '<select class="select-control condition-query-select" id="filter-options-control-' + feedId + '-' + filterLevel;
	htmlCode    += '" onchange="tvc_filterChanged(' + feedId + ', ' + filterLevel + ')">';

	for ( var i = 0; i < filterOptions.length; i ++ ) {

		htmlCode += parseInt( selectedValue ) !== i ? '<option value = "' + i + '">' + filterOptions[ i ] + '</option>'
			: '<option value = "' + i + '" selected>' + filterOptions[ i ] + '</option>';
	}

	htmlCode += '</select>';

	return htmlCode;
}

function tvc_filterInputCntrl( feedId, filterLevel, inputLevel, value ) {
	var identString   = feedId + '-' + filterLevel + '-' + inputLevel;
	var andString     = inputLevel > 1 ? ' ' + tvc_feed_settings_form_vars.and + ' ' : '';
	var splitPosition = inputLevel === 1 ? 1 : 3;
	var splitValue    = '';

	if ( inputLevel > 1 ) {
		splitValue = value && value.includes( '#' ) ? value.split( '#' )[ splitPosition ] : '';
	} else {
		splitValue = value ? value : '';
	}

	var style = ! splitValue ? 'style="display:none"' : 'style="display:initial"';

	return '<span id="filter-input-span-' + identString + '"' + style + '>' + andString + '<input type="text" name="filter-value" id="filter-input-control-' + identString
		+ '" onchange="tvc_filterChanged(' + feedId + ', ' + filterLevel + ', ' + inputLevel + ')" value="' + splitValue + '"></span>';
}
