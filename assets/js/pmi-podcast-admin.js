/**
 * PMI Podcast – admin repeater for "Altri link".
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var wrapper  = document.getElementById( 'pmi-podcast-extra-links' );
		var addBtn   = document.getElementById( 'pmi-podcast-add-link' );
		var template = document.getElementById( 'pmi-podcast-extra-link-template' );

		if ( ! wrapper || ! addBtn || ! template ) {
			return;
		}

		var nextIndex = wrapper.querySelectorAll( '.pmi-podcast-extra-link-row' ).length;

		addBtn.addEventListener( 'click', function () {
			var html = template.innerHTML.replace( /__INDEX__/g, String( nextIndex ) );
			var temp = document.createElement( 'div' );
			temp.innerHTML = html.trim();

			var row = temp.firstElementChild;
			wrapper.appendChild( row );
			nextIndex++;

			var firstInput = row.querySelector( 'input' );
			if ( firstInput ) {
				firstInput.focus();
			}
		} );

		wrapper.addEventListener( 'click', function ( event ) {
			var removeBtn = event.target.closest( '.pmi-podcast-remove-link' );

			if ( ! removeBtn ) {
				return;
			}

			event.preventDefault();
			var row = removeBtn.closest( '.pmi-podcast-extra-link-row' );

			if ( row ) {
				row.remove();
			}
		} );
	} );
} )();
