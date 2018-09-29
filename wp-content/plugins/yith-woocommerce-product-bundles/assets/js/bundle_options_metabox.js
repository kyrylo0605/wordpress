jQuery( function ( $ ) {
    var bundled_items_cont             = $( '#yith_bundled_product_data .yith-wcpb-bundled-items' ),
        add_bundled_product_btn        = $( '#yith-wcpb-add-bundled-product' ),
        block_params                   = {
            message   : null,
            overlayCSS: {
                background: '#fff url(' + woocommerce_admin_meta_boxes.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center',
                opacity   : 0.6
            }
        },
        b_prod_id                      = $( '#yith-wcpb-bundled-product' ),
        remove_bundled_product_btn     = $( '.yith-wcpb-remove-bundled-product-item' ),
        items_count                    = $( '#yith_bundled_product_data .yith-wcpb-bundled-items .yith-wcpb-bundled-item' ).size(),
        bundled_product_data_container = $( '#yith_bundled_product_data' ),
        add_action_to_remove_btn       = function () {
            remove_bundled_product_btn = $( '.yith-wcpb-remove-bundled-product-item' );
            remove_bundled_product_btn.on( 'click', function () {
                $( this ).parent().parent().remove();
                //items_count--;
            } );
        };


    items_count++;
    add_bundled_product_btn.on( 'click', function () {
        if ( b_prod_id.val() == 0 ) {
            return
        }

        bundled_product_data_container.block( block_params );
        var data = {
            action     : 'yith_wcpb_add_product_in_bundle',
            open_closed: 'open',
            post_id    : woocommerce_admin_meta_boxes.post_id,
            id         : items_count,
            product_id : b_prod_id.val(),
        };

        $.post( woocommerce_admin_meta_boxes.ajax_url, data, function ( response ) {
            if ( response == 'notsimple' ) {
                alert( ajax_object.free_not_simple );
                bundled_product_data_container.unblock();
                return;
            }
            bundled_items_cont.append( response );
            bundled_items_cont.find( '.help_tip' ).tipTip();
            add_action_to_remove_btn();
            bundled_product_data_container.unblock();
            b_prod_id.val( 0 );
            items_count++;
        } );
    } );

    add_action_to_remove_btn();

    /*$('select#product-type').on('change', function(){
     alert($(this).val());
     });*/

    $( 'body' ).on( 'woocommerce-product-type-change', function ( event, select_val, select ) {

        if ( select_val == 'yith_bundle' ) {

            $( 'input#_downloadable' ).prop( 'checked', false );
            $( 'input#_virtual' ).removeAttr( 'checked' );

            $( '.show_if_external' ).hide();
            $( '.show_if_simple' ).show();
            $( '.show_if_bundle' ).show();

            $( 'input#_downloadable' ).closest( '.show_if_simple' ).hide();
            $( 'input#_virtual' ).closest( '.show_if_simple' ).hide();

            $( 'input#_manage_stock' ).change();
            $( 'input#_per_product_pricing_active' ).change();
            $( 'input#_per_product_shipping_active' ).change();

            $( '#_nyp' ).change();

            $( '.pricing' ).show();
            $( '.product_data_tabs' ).find( 'li.general_options' ).show();
        } else {
            $( '.show_if_bundle' ).hide();
        }

    } );

    $( 'select#product-type' ).change();

    $( '#_regular_price' ).closest( 'div.options_group' ).addClass( 'show_if_yith_bundle' );



    /* SORTING */
    var bundled_items = $( '.yith-wcpb-bundled-items' ).find( '.yith-wcpb-bundled-item' ).get();

    bundled_items.sort( function ( a, b ) {
        var compA = parseInt( $( a ).attr( 'rel' ) );
        var compB = parseInt( $( b ).attr( 'rel' ) );
        return ( compA < compB ) ? -1 : ( compA > compB ) ? 1 : 0;
    } );

    $( bundled_items ).each( function ( idx, itm ) {
        $( '.yith-wcpb-bundled-items' ).append( itm );
    } );
    //ordering
    $( '.yith-wcpb-bundled-items' ).sortable( {
                                                  items               : '.yith-wcpb-bundled-item',
                                                  cursor              : 'move',
                                                  axis                : 'y',
                                                  handle              : 'h3',
                                                  scrollSensitivity   : 40,
                                                  forcePlaceholderSize: true,
                                                  helper              : 'clone',
                                                  opacity             : 0.65,
                                                  placeholder         : 'wc-metabox-sortable-placeholder',
                                                  start               : function ( event, ui ) {
                                                      ui.item.css( 'background-color', '#f6f6f6' );
                                                  },
                                                  stop                : function ( event, ui ) {
                                                      ui.item.removeAttr( 'style' );
                                                  }
                                              } );
} );