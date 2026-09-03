/**
 * Tag Black Widgets in the Elementor panel (category + search) so dark tile styles apply.
 * Search results leave the category container, so CSS alone on #...-black_widgets is not enough.
 */
( function ( $ ) {
	'use strict';

	var BW_NAME = /^b_/;

	function widgetTypeFromEl( el ) {
		var $el = $( el );
		var type =
			$el.attr( 'data-widget_type' ) ||
			$el.attr( 'data-widget-type' ) ||
			$el.attr( 'data-widget' ) ||
			'';
		// Elementor often stores "b_title.default".
		return String( type ).split( '.' )[ 0 ];
	}

	function tagElement( el ) {
		var name = widgetTypeFromEl( el );
		if ( BW_NAME.test( name ) ) {
			el.classList.add( 'bw-panel-widget' );
		}
	}

	function tagAll( root ) {
		var scope = root && root.querySelectorAll ? root : document;
		scope.querySelectorAll( '#elementor-panel .elementor-element' ).forEach( tagElement );
	}

	function bind() {
		var panel = document.getElementById( 'elementor-panel' );
		if ( ! panel || panel.dataset.bwPanelTagged === '1' ) {
			return;
		}
		panel.dataset.bwPanelTagged = '1';

		tagAll( panel );

		var observer = new MutationObserver( function ( mutations ) {
			mutations.forEach( function ( mutation ) {
				mutation.addedNodes.forEach( function ( node ) {
					if ( node.nodeType !== 1 ) {
						return;
					}
					if ( node.classList && node.classList.contains( 'elementor-element' ) ) {
						tagElement( node );
					}
					if ( node.querySelectorAll ) {
						node.querySelectorAll( '.elementor-element' ).forEach( tagElement );
					}
				} );
			} );
		} );

		observer.observe( panel, { childList: true, subtree: true } );
	}

	function boot() {
		bind();
		// Panel mounts asynchronously; retry briefly.
		var tries = 0;
		var timer = setInterval( function () {
			tries += 1;
			bind();
			tagAll( document );
			if ( document.getElementById( 'elementor-panel' ) && tries > 20 ) {
				clearInterval( timer );
			}
		}, 250 );
	}

	$( window ).on( 'elementor:init', boot );
	$( boot );
} )( jQuery );
