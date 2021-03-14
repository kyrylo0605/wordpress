"use strict";
function tvcAddToQueueString( idToAdd ) {
	var listElement = jQuery( '#tvc-feed-list-feeds-in-queue' );

	if ( tvcQueueStringIsEmpty() ) {
		listElement.text( idToAdd );
	} else {
		listElement.text( listElement.text() + ',' + idToAdd );
	}
}

function tvcRemoveFromQueueString( idToRemove ) {
	var listElement = jQuery( '#tvc-feed-list-feeds-in-queue' );
	var currentString = listElement.text();

	if ( currentString.indexOf( ',' ) > -1 ) {
		currentString = currentString.endsWith( idToRemove ) ? currentString.replace( idToRemove, '' ) : currentString.replace( idToRemove + ',', '' );
		listElement.text( currentString );
	} else {
		tvcClearQueueString();
	}
}

function tvcQueueStringIsEmpty() {
	return jQuery( '#tvc-feed-list-feeds-in-queue' ).text().length < 1;
}

function tvcClearQueueString() {
	jQuery( '#tvc-feed-list-feeds-in-queue' ).text( '' );
}
