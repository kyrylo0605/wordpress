<?php

/**
 * Wp in Progress
 * 
 * @package Wordpress
 * @theme Sueva
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * It is also available at this URL: http://www.gnu.org/licenses/gpl-3.0.txt
 */

/*-----------------------------------------------------------------------------------*/
/* IS WOOCOMMERCE ACTIVE */
/*-----------------------------------------------------------------------------------*/ 

if ( ! function_exists( 'is_woocommerce_active' ) ) {
	
	function is_woocommerce_active( $type = "" ) {
	
        global $woocommerce;

        if ( isset( $woocommerce ) ) {
			
			if ( !$type || call_user_func($type) ) {
            
				return true;
			
			}
			
		}
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* SETTINGS */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('wip_woocarousel_lite_setting')) {

	function wip_woocarousel_lite_setting($id, $default = "" ) {
	
		$wip_woocarousel_setting = get_option("wip_woocarousel_settings");
		
		if(isset($wip_woocarousel_setting[$id])):
		
			return $wip_woocarousel_setting[$id];
		
		else:
		
			return $default;
		
		endif;
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* POSTMETA */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('wip_woocarousel_lite_postmeta')) {

	function wip_woocarousel_lite_postmeta($id, $default = "" ) {
	
		global $post, $wp_query;
		
		$content_ID = $post->ID;
	
		if( is_woocommerce_active('is_shop') ) {
	
			$content_ID = get_option('woocommerce_shop_page_id');
	
		}

		$val = get_post_meta( $content_ID , $id, TRUE);
		
		if(isset($val)) {
			
			return $val;
			
		} else {
				
			return '';
			
		}
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* RANDOM BANNER */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('wip_woocarousel_lite_random_banner')) {

	function wip_woocarousel_lite_random_banner() {
		
		$plugin1  = '<h1>'. __( 'WIP WooCarousel Lite.', 'wip-woocarousel-lite') . '</h1>';
		$plugin1 .= '<p>'. __( 'Upgrade to the premium version of WooCarousel, to enable 5 different layouts, 600+ Google Fonts, unlimited colors and much more.', 'wip-woocarousel-lite') . '</p>';
		$plugin1 .= '<div class="big-button">';		
		$plugin1 .= '<a href="'.esc_url( 'https://www.themeinprogress.com/wip-woocarousel-woocommerce-slider-carousel/?ref=2&campaign=wip-woocarousel-panel/?aff=panel').'" target="_blank">'.__( 'Upgrade to the premium version', 'wip-woocarousel-lite').'</a>';	
		$plugin1 .= '</div>';
		
		$plugin2  = '<h1>'. __( 'Internal Linking of Related Contents', 'wip-woocarousel-lite') . '</h1>';
		$plugin2 .= '<p>'. __( '<strong>Internal Linking of Related Contents</strong> WordPress plugin allow you to automatically insert related articles inside your WordPress posts.', 'wip-woocarousel-lite') . '</p>';
		$plugin2 .= '<div class="big-button">';		
		$plugin2 .= '<a href="'.esc_url( 'https://www.themeinprogress.com/internal-linking-of-related-contents-pro/?aff=wcl-panel').'" target="_blank">'.__( 'Download the free version, no email required', 'wip-woocarousel-lite').'</a>';	
		$plugin2 .= '</div>';

		$plugin3  = '<h1>'. __( 'Chatbox Manager', 'wip-woocarousel-lite') . '</h1>';
		$plugin3 .= '<p>'. __( '<strong>Chatbox Manager</strong> WordPress plugin allow you to display multiple WhatsApp buttons on your website.', 'wip-woocarousel-lite') . '</p>';
		$plugin3 .= '<div class="big-button">';		
		$plugin3 .= '<a href="'.esc_url( 'https://www.themeinprogress.com/chatbox-manager-pro/?aff=wcl-panel').'" target="_blank">'.__( 'Download the free version, no email required', 'wip-woocarousel-lite').'</a>';	
		$plugin3 .= '</div>';

		$plugin4  = '<h1>'. __( 'Content Snippet Manager', 'wip-woocarousel-lite') . '</h1>';
		$plugin4 .= '<p>'. __( '<strong>Content Snippet Manager</strong> WordPress plugin allow you to include every kind of code inside your Wordpress website.', 'wip-woocarousel-lite') . '</p>';
		$plugin4 .= '<div class="big-button">';		
		$plugin4 .= '<a href="'.esc_url( 'https://www.themeinprogress.com/content-snippet-manager/?aff=wcl-panel').'" target="_blank">'.__( 'Download the free version, no email required', 'wip-woocarousel-lite').'</a>';	
		$plugin4 .= '</div>';

		$banner = array($plugin1,$plugin2,$plugin3,$plugin4);
		echo $banner[array_rand($banner)];
	
	}

}

?>