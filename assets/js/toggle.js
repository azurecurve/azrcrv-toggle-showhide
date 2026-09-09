/**
 * Front-end toggle interaction - vanilla JS, no jQuery dependency.
 *
 * Replaces the pre-2.0.0 plugin's assets/jquery/jquery.js (despite its
 * filename, that was this plugin's own ~30-line interaction script, not a
 * copy of the jQuery library itself - it just required jQuery core, loaded
 * via WP's bundled 'jquery' handle, to run). This file replicates the same
 * three behaviours with no library dependency at all:
 *
 *   1. Style 1, closed by default: click the title to slide the content
 *      open/closed.
 *   2. Style 1, expand="1" (open by default): same, starting open.
 *   3. Style 2: click the "read more" button to reveal the rest of the
 *      content, swapping the button's own text between its read-more/
 *      read-less labels (read from its own data attributes, which are set
 *      per-instance from the plugin's saved settings - see
 *      display_toggle_style2() in includes/functions-shortcodes.php).
 */
document.addEventListener( 'DOMContentLoaded', function () {
	'use strict';

	/**
	 * A small dependency-free stand-in for jQuery's slideToggle(): animates
	 * an element's height between 0 and its natural content height.
	 *
	 * @param {HTMLElement} el       The element to show/hide.
	 * @param {number}      duration Animation duration in milliseconds.
	 */
	function slideToggle( el, duration ) {

		var isHidden = window.getComputedStyle( el ).display === 'none';

		el.style.overflow    = 'hidden';
		el.style.transition  = 'height ' + duration + 'ms ease';

		if ( isHidden ) {
			el.style.display = 'block';
			var targetHeight = el.scrollHeight + 'px';
			el.style.height  = '0px';

			window.requestAnimationFrame( function () {
				el.style.height = targetHeight;
			} );

			window.setTimeout( function () {
				el.style.removeProperty( 'height' );
				el.style.removeProperty( 'overflow' );
				el.style.removeProperty( 'transition' );
			}, duration );
		} else {
			el.style.height = el.scrollHeight + 'px';

			window.requestAnimationFrame( function () {
				el.style.height = '0px';
			} );

			window.setTimeout( function () {
				el.style.display = 'none';
				el.style.removeProperty( 'height' );
				el.style.removeProperty( 'overflow' );
				el.style.removeProperty( 'transition' );
			}, duration );
		}
	}

	// --- Style 1, closed by default. ---
	document.querySelectorAll( '.azrcrv-tsh-toggle-container' ).forEach( function ( container ) {
		container.style.display = 'none';
	} );

	document.querySelectorAll( '.azrcrv-tsh-toggle' ).forEach( function ( title ) {
		title.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			title.classList.toggle( 'azrcrv-tsh-toggle-active' );
			var container = title.nextElementSibling;
			if ( container ) {
				slideToggle( container, 200 );
			}
		} );
	} );

	// --- Style 1, expand="1" (open by default). ---
	document.querySelectorAll( '.azrcrv-tsh-toggle-container-open' ).forEach( function ( container ) {
		container.style.display = 'block';
	} );

	document.querySelectorAll( '.azrcrv-tsh-toggle-open-active' ).forEach( function ( title ) {
		title.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			title.classList.toggle( 'azrcrv-tsh-toggle-open' );
			var container = title.nextElementSibling;
			if ( container ) {
				slideToggle( container, 200 );
			}
		} );
	} );

	// --- Style 2, read more / read less. ---
	document.querySelectorAll( '.azrcrv-tsh-readmore' ).forEach( function ( panel ) {
		panel.style.display = 'none';
	} );

	document.querySelectorAll( '.azrcrv-tsh-readmore-button' ).forEach( function ( button ) {
		button.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			button.classList.toggle( 'azrcrv-tsh-readmore-active' );

			var panel = button.previousElementSibling;
			if ( panel ) {
				slideToggle( panel, 400 );
			}

			var readMoreText = button.getAttribute( 'data-read-more' );
			var readLessText = button.getAttribute( 'data-read-less' );

			if ( readMoreText && readLessText ) {
				button.textContent = ( button.textContent === readMoreText ) ? readLessText : readMoreText;
			}
		} );
	} );

} );
