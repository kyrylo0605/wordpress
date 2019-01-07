<?php


/*-----------------------------------------------------------------------------------*/
/* Woocommerce Hooks */
/*-----------------------------------------------------------------------------------*/ 
	
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

/*-----------------------------------------------------------------------------------*/
/* Woocommerce remove breadcrumbs */
/*-----------------------------------------------------------------------------------*/ 

if ( ! function_exists( 'bazaarlite_remove_breadcrumbs' ) ) {

	function bazaarlite_remove_breadcrumbs() {
    	
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
	
	}

	add_action( 'init', 'bazaarlite_remove_breadcrumbs' );

}

/*-----------------------------------------------------------------------------------*/
/* Woocommerce header cart */
/*-----------------------------------------------------------------------------------*/ 

if ( ! function_exists( 'bazaarlite_header_cart' ) ) {

	function bazaarlite_header_cart() { ?>

        <section class="header-cart">
        
            <a class="cart-contents" href="<?php echo esc_url(WC()->cart->get_cart_url()); ?>" title="<?php esc_attr_e( 'View your shopping cart','bazaar-lite' ); ?>">
                
                <i class="fa fa-shopping-cart"></i> 
                <span class="cart-count"><?php echo sprintf ( _n( '%d', '%d', WC()->cart->cart_contents_count, 'bazaar-lite' ), WC()->cart->cart_contents_count ); ?></span>
    
            </a>
                        
            <div class="header-cart-widget">
            
                <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
            
            </div>
            
        </section>
    
<?php

	}
	
}

if ( ! function_exists( 'bazaarlite_cart_link_fragment' ) ) {

	function bazaarlite_cart_link_fragment( $fragments ) {
	
		ob_start();

?>
		<a class="cart-contents" href="<?php echo esc_url(WC()->cart->get_cart_url()); ?>" title="<?php esc_attr_e( 'View your shopping cart','bazaar-lite' ); ?>">
            
			<i class="fa fa-shopping-cart"></i> 
			<span class="cart-count"><?php echo sprintf ( _n( '%d', '%d', WC()->cart->cart_contents_count, 'bazaar-lite' ), WC()->cart->cart_contents_count ); ?></span>

		</a>
        
<?php

		$fragments['a.cart-contents'] = ob_get_clean();
		
		return $fragments;
	
	}
	
	add_filter( 'woocommerce_add_to_cart_fragments', 'bazaarlite_cart_link_fragment' );

}

/*-----------------------------------------------------------------------------------*/
/* Woocommerce template */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('bazaarlite_woocommerce_before_main_content')) {

	function bazaarlite_woocommerce_before_main_content() { 
	
		if ( is_product() ) {
			
			$classes = "product-wrapper" ;
	
		} else {
	
			$classes = "product-wrapper products-list" ;
	
		}

		if ( (is_shop() || is_product()) && (bazaarlite_postmeta('wip_header_sidebar') && bazaarlite_postmeta('wip_header_sidebar') <> "none") ):

			do_action('bazaarlite_header_sidebar', bazaarlite_postmeta('wip_header_sidebar'));
		
		endif;
	
?>
	
	<div class="container">
	
		<div class="row">
		
			<div class="<?php echo bazaarlite_template('span') . " " . bazaarlite_template('sidebar') . " " . $classes; ?>" >
	
<?php
	
	}
	
	add_action('woocommerce_before_main_content', 'bazaarlite_woocommerce_before_main_content', 10);

}

/*-----------------------------------------------------------------------------------*/
/* Woocommerce template */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('bazaarlite_woocommerce_after_main_content')) {
	
	function bazaarlite_woocommerce_after_main_content() { ?>
	
			</div>
			
			<?php 
			
				if ( bazaarlite_template('span') == "col-md-8" ) :

					do_action('bazaarlite_side_sidebar', 'side_sidebar_area'); 
					
				endif;
				
			?>
	
		</div>
		
	</div>
    
<?php
	
		do_action('bazaarlite_masonry_script');

		if ( (is_shop() || is_product()) && (bazaarlite_postmeta('wip_bottom_sidebar') && bazaarlite_postmeta('wip_bottom_sidebar') <> "none") ):

			do_action('bazaarlite_header_sidebar', bazaarlite_postmeta('wip_bottom_sidebar'));
		
		endif;

	}
	
	add_action('woocommerce_after_main_content', 'bazaarlite_woocommerce_after_main_content', 10);

}

/*-----------------------------------------------------------------------------------*/
/* Replace wc_get_gallery_image_html function */ 
/*-----------------------------------------------------------------------------------*/ 

if ( ! function_exists( 'bazaarlite_wc_get_gallery_image_html' ) ) {
	
	function bazaarlite_wc_get_gallery_image_html( $attachment_id, $main_image = false ) {
		$flexslider        = (bool) apply_filters( 'woocommerce_single_product_flexslider_enabled', get_theme_support( 'wc-product-gallery-slider' ) );
		$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
		$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
		$image_size        = apply_filters( 'woocommerce_gallery_image_size', $flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size );
		$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
		$full_src          = wp_get_attachment_image_src( $attachment_id, $full_size );
		$image             = wp_get_attachment_image( $attachment_id, $image_size, false, array(
			'title'                   => get_post_field( 'post_title', $attachment_id ),
			'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
			'data-src'                => $full_src[0],
			'data-large_image'        => $full_src[0],
			'data-large_image_width'  => $full_src[1],
			'data-large_image_height' => $full_src[2],
			'class'                   => $main_image ? 'wp-post-image' : '',
		) );
	
		return '<div data-thumb="' . esc_url( $thumbnail_src[0] ) . '" class="product-thumbnail woocommerce-product-gallery__image"><a class="swipebox" data-rel="[product-gallery]" href="' . esc_url( $full_src[0] ) . '">' . $image . '</a></div>';
	}

}

?>