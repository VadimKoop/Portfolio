/**
 * Variation Swatches Admin
 *
 * @package Woostify Pro
 */

/* global woostify_variation_swatches_admin */

'use strict';

var frame, woostifyData = woostify_variation_swatches_admin || {};

document.addEventListener(
	'DOMContentLoaded',
	function() {
		var wp   = window.wp,
			body = jQuery( 'body' );

		jQuery( '#term-color' ).wpColorPicker();

		// Update attribute image.
		body.on(
			'click',
			'.woostify-variation-swatches-upload-image-button',
			function( event ) {
				event.preventDefault();

				var button = jQuery( this );

				// If the media frame already exists, reopen it.
				if ( frame ) {
					frame.open();
					return;
				}

				// Create the media frame.
				frame = wp.media.frames.downloadable_file = wp.media(
					{
						title   : woostifyData.i18n.mediaTitle,
						button  : {
							text: woostifyData.i18n.mediaButton
						},
						multiple: false
					}
				);

				// When an image is selected, run a callback.
				frame.on(
					'select',
					function() {
						var attachment = frame.state().get( 'selection' ).first().toJSON();

						button.siblings( 'input.woostify-variation-swatches-term-image' ).val( attachment.id );
						button.siblings( '.woostify-variation-swatches-remove-image-button' ).show();
						button.parent().prev( '.woostify-variation-swatches-term-image-thumbnail' ).find( 'img' ).attr( 'src', attachment.sizes.thumbnail.url );
					}
				);

				// Finally, open the modal.
				frame.open();
			}
		).on(
			'click',
			'.woostify-variation-swatches-remove-image-button',
			function() {
				var button = jQuery( this );

				button.siblings( 'input.woostify-variation-swatches-term-image' ).val( '' );
				button.siblings( '.woostify-variation-swatches-remove-image-button' ).show();
				button.parent().prev( '.woostify-variation-swatches-term-image-thumbnail' ).find( 'img' ).attr( 'src', woostifyData.placeholder );

				return false;
			}
		);

		// Toggle add new attribute term modal.
		var modal   = jQuery( '#woostify-variation-swatches-modal-container' ),
			spinner = modal.find( '.spinner' ),
			msg     = modal.find( '.message' ),
			metabox = null;

		body.on(
			'click',
			'.variation_swatches_add_new_attribute',
			function( e ) {
				e.preventDefault();

				var button           = jQuery( this ),
					taxInputTemplate = wp.template( 'woostify-variation-swatches-input-tax' ),
					data             = {
						type: button.data( 'type' ),
						tax : button.closest( '.woocommerce_attribute' ).data( 'taxonomy' )
				};

				// Insert input.
				modal.find( '.woostify-variation-swatches-term-swatch' ).html( jQuery( '#tmpl-woostify-variation-swatches-input-' + data.type ).html() );
				modal.find( '.woostify-variation-swatches-term-tax' ).html( taxInputTemplate( data ) );

				if ( 'color' == data.type ) {
					modal.find( 'input.woostify-variation-swatches-input-color' ).wpColorPicker();
				}

				metabox = button.closest( '.woocommerce_attribute.wc-metabox' );
				modal.show();
			}
		).on(
			'click',
			'.woostify-variation-swatches-modal-close, .woostify-variation-swatches-modal-backdrop',
			function( e ) {
				e.preventDefault();
				closeModal();
			}
		);

		// Send ajax request to add new attribute term.
		body.on(
			'click',
			'.woostify-variation-swatches-new-attribute-submit',
			function( e ) {
				e.preventDefault();

				var button = jQuery( this ),
					type   = button.data( 'type' ),
					error  = false,
					data   = {};

				// Validate.
				modal.find( '.woostify-variation-swatches-input' ).each(
					function() {
						var t = jQuery( this );

						if ( 'slug' != t.attr( 'name' ) && ! t.val() ) {
							t.addClass( 'error' );
							error = true;
						} else {
							t.removeClass( 'error' );
						}

						data[ t.attr( 'name' ) ] = t.val();
					}
				);

				if ( error ) {
					return;
				}

				// Send ajax request.
				spinner.addClass( 'is-active' );
				msg.hide();
				wp.ajax.send(
					'variation_swatches_add_new_attribute',
					{
						data: data,
						error: function( res ) {
							spinner.removeClass( 'is-active' );
							msg.addClass( 'error' ).text( res ).show();
						},
						success: function( res ) {
							spinner.removeClass( 'is-active' );
							msg.addClass( 'success' ).text( res.msg ).show();

							metabox.find( 'select.attribute_values' ).append( '<option value="' + res.id + '" selected="selected">' + res.name + '</option>' );
							metabox.find( 'select.attribute_values' ).change();

							closeModal();
						}
					}
				);
			}
		);

		// Close modal.
		function closeModal() {
			modal.find( '.woostify-variation-swatches-term-name input, .woostify-variation-swatches-term-slug input' ).val( '' );
			spinner.removeClass( 'is-active' );
			msg.removeClass( 'error success' ).hide();
			modal.hide();
		}
	}
);
