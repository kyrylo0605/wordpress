/**
 * wacp-frontend.js
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Added to Cart Popup
 * @version 1.0.0
 */

jQuery(document).ready(function($) {
    "use strict";

    var popup       = $('#yith-wacp-popup'),
        overlay     = popup.find( '.yith-wacp-overlay'),
        close       = popup.find( '.yith-wacp-close'),
        wrapper     = popup.find( '.yith-wacp-wrapper'),
        wrapper_w   = wrapper.width(),
        wrapper_h   = wrapper.height(),
        close_popup = function(){
            // remove class open
            popup.removeClass( 'open' );
            // after 2 sec remove content
            setTimeout(function () {
                popup.find('.yith-wacp-content').html('');
            }, 1000);

            $(document).trigger( 'yith_wacp_popup_after_closing' );
        },
        // center popup function
        center_popup = function () {
            var window_w = $(window).width(),
                window_h = $(window).height(),
                width    = ( ( window_w - 60 ) > wrapper_w ) ? wrapper_w : ( window_w - 60 ),
                height   = ( ( window_h - 120 ) > wrapper_h ) ? wrapper_h : ( window_h - 120 );

            wrapper.css({
                'left' : (( window_w/2 ) - ( width/2 )),
                'top' : (( window_h/2 ) - ( height/2 )),
                'width'     : width + 'px',
                'height'    : height + 'px'
            });
        };

    $( window ).on( 'resize', center_popup );

    $('body').on( 'added_to_cart', function( ev, fragmentsJSON, cart_hash, button ){

        if( typeof fragmentsJSON == 'undefined' )
            fragmentsJSON = $.parseJSON( sessionStorage.getItem( wc_cart_fragments_params.fragment_name ) );

        $.each( fragmentsJSON, function( key, value ) {

            if ( key == 'yith_wacp_message' ) {

                popup.find('.yith-wacp-content').html( value );

                // position popup
                center_popup();

                popup.addClass('open');

                popup.find( 'a.continue-shopping' ).on( 'click', function (e) {
                    e.preventDefault();
                    close_popup();
                });

                return false;
            }
        });
    });

    // Close box by click close button
    close.on( 'click', function(ev){
        ev.preventDefault();

        close_popup();
    });
});