<?php

/**
 * Wp in Progress
 * 
 * @package Wordpress
 * @theme Sueva
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * It is also available at this URL: http://www.gnu.org/licenses/gpl-3.0.txt

/*-----------------------------------------------------------------------------------*/
/* WooCommerce is active */
/*-----------------------------------------------------------------------------------*/ 

if ( ! function_exists( 'chatbox_manager_is_woocommerce_active' ) ) {
	
	function chatbox_manager_is_woocommerce_active( $type = '' ) {
	
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

if (!function_exists('chatbox_manager_setting')) {

	function chatbox_manager_setting($id, $default = '' ) {
	
		$settings = get_option('chatbox_manager_settings');
		
		if(isset($settings[$id]) && !empty($settings[$id])):
		
			return $settings[$id];
		
		else:
		
			return $default;
		
		endif;
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* AJAX POSTS LIST */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('chatbox_manager_get_ajax_list_posts')) {

	function chatbox_manager_get_ajax_list_posts() {
	
		global $wpdb;
	
		$result = array();
	
		$search = strip_tags(trim($_GET['q'])); 
		$post_type = sanitize_text_field($_REQUEST['chatbox_manager_post_type']);
		$post_filter = sanitize_text_field($_REQUEST['chatbox_manager_post_filter']);

		if (strpos($search, '[Al') !== false || strpos($search, '[al') !== false) {
		
			$result[] = array(
				'text' => '[All]',
				'id' => '-1',
			);
		
		} else {
			
			/* Code to hide the pages already choosed
			$chatbox_manager_settings = get_option('chatbox_manager_settings');
			
			foreach( $chatbox_manager_settings['chatbox_manager_chatboxes'] as $v) {
				foreach( $v[$post_filter . '_' . $post_type] as $ID) {
					$filter[] = $ID;
				}
			}
			*/

			add_filter('posts_where', function( $where ) use ($search) {
				$where .= (" AND post_title LIKE '%" . $search . "%'");
				return $where;
			});
			
			$query = array(
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'post_type' => $post_type,
				'order' => 'ASC',
				'orderby' => 'title',
				'suppress_filters' => false,
				//'post__not_in' => $filter,
			);
			
			$posts = get_posts( $query );
		
			foreach ($posts as $this_post) {
					
				$post_title = $this_post->post_title;
				$id = $this_post->ID;
			
				$result[] = array(
					'text' => $post_title,
					'id' => $id,
				);
					
			}
			
		}
		
		$posts['items'] = $result;
		echo json_encode($posts);
		die();
	
	}
	
	add_action( 'wp_ajax_chatbox_manager_list_posts', 'chatbox_manager_get_ajax_list_posts' );

}

/*-----------------------------------------------------------------------------------*/
/* AJAX TAXONOMY LIST */
/*-----------------------------------------------------------------------------------*/ 

if (!function_exists('chatbox_manager_get_ajax_list_taxonomy')) {

	function chatbox_manager_get_ajax_list_taxonomy() {
	
		global $wpdb;
	
		$result = array();
		$search = strip_tags(trim($_GET['q'])); 
		$tax_type = sanitize_text_field($_REQUEST['chatbox_manager_taxonomy_type']);
		$tax_filter = sanitize_text_field($_REQUEST['chatbox_manager_taxonomy_filter']);

		if (strpos($search, '[Al') !== false || strpos($search, '[al') !== false) {
		
			$result[] = array(
				'text' => '[All]',
				'id' => '-1',
			);
		
		} else {

			/* Code to hide the taxonomies already choosed
			$chatbox_manager_settings = get_option('chatbox_manager_settings');
			
			foreach( $chatbox_manager_settings['chatbox_manager_chatboxes'] as $v) {
				foreach( $v[$tax_filter . '_' . $tax_type] as $ID) {
					$filter[] = $ID;
				}
			}
			*/

			$args = array(
				'taxonomy' => $tax_type,
				'hide_empty' => false,
				//'exclude' => $filter,
				'name__like' => $search
			);
		
			foreach ( get_terms($args) as $cat) {
				$result[] = array(
					'text' => $cat->name,
					'id' => $cat->term_id,
				);
			}
	
		}
		
		$terms['items'] = $result;
		echo json_encode($terms);
		die();
	
	}
	
	add_action( 'wp_ajax_chatbox_manager_list_taxonomy', 'chatbox_manager_get_ajax_list_taxonomy' );

}

/*-----------------------------------------------------------------------------------*/
/* GET CUSTOM POST LIST */
/*-----------------------------------------------------------------------------------*/ 

if ( ! function_exists( 'chatbox_manager_get_custom_post_list' ) ) {
	
	function chatbox_manager_get_custom_post_list() {

		return get_post_types(array('public' => TRUE));
	
	}

}

/*-----------------------------------------------------------------------------------*/
/* GET TAXONOMIES LIST */
/*-----------------------------------------------------------------------------------*/ 

if ( ! function_exists( 'chatbox_manager_get_taxonomies_list' ) ) {
	
	function chatbox_manager_get_taxonomies_list() {

		return get_taxonomies(array('public' => TRUE));
	
	}

}

?>