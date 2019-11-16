<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if( !class_exists( 'chatbox_manager_chatboxes' ) ) {

	class chatbox_manager_chatboxes {

		/**
		 * Constructor
		 */
		 
		public function __construct() {

			add_action('wp_footer', array(&$this, 'floating'));
			add_filter('the_content', array(&$this, 'afterContent'));

		}

		/**
		* Get current page
		*/
			 
		private function get_current_page() {
		
			if ( is_home() ) {
				$current = 'home';
			} elseif ( is_single() ) {
				$current = 'single';
			} elseif ( is_page() ) {
				$current = 'page';
			} elseif ( chatbox_manager_is_woocommerce_active('is_shop')) {
				$current = 'shop';
			} elseif ( is_category() ) {
				$current = 'category';
			} elseif ( is_tag() ) {
				$current = 'tag';
			} elseif ( is_tax() ) {
				$current = 'tax';
			} elseif ( is_search() ) {
				$current = 'search';
			}

			return isset($current) ? $current : false;
		
		}

		/**
		* Check chatbox
		*/
			 
		private function isLoadableChatbox( $ID, $type, $settings, $function, $control ) {
			
			$loadChatbox = FALSE;
			
			if ( call_user_func($function, $type) ) :
				
				if ( is_array($settings) && in_array('-1', $settings) && $control == 'in_array') {
			
					$loadChatbox = TRUE;
			
				} elseif ( is_array($settings) ) {

					switch ( $control ) {
						
						case 'in_array':
							if ( in_array($ID, $settings) )
								$loadChatbox = TRUE;
						break;
					
						case 'not_in_array':
							if ( !in_array($ID, $settings) && !in_array('-1', $settings) )
								$loadChatbox = TRUE;
						break;
					
					}
			
				}
									
			endif;
			
			return $loadChatbox;
		
		}

		/**
		* Floating chatbox
		*/
			 
		public function floating() {

			global $conversionChatbox;

			ob_start();
			$this->loadChatboxes('floating');
			$output = ob_get_contents();
			ob_end_clean();
			
			echo (!empty($output)) ? '<div class="floating-chatbox">' . $output . '</div>' : '';

		}

		/**
		* After content chatbox
		*/
			 
		public function afterContent($content) {

			global $conversionChatbox;

			ob_start();
			$this->loadChatboxes('afterContent');
			$output = ob_get_contents();
			ob_end_clean();
			
			return (!empty($output)) ?  $content . '<div class="inline-chatbox">' . $output . '</div>' : $content;

		}

		/**
		* Load Chatboxes
		*/
			 
		public function loadChatboxes($chatboxType) {

			global $post;

			$chatbox_manager_setting = chatbox_manager_setting('chatbox_manager_chatboxes');

			if ( is_array($chatbox_manager_setting) ) {

				$count = 0;

				foreach ( $chatbox_manager_setting as $chatboxID => $chatbox ) {
					
					$count++;

					$loadChatbox = FALSE;
		
					switch ( $this->get_current_page() ) {
							
						case 'home' :
							
							switch(true) {
								
								case (isset($chatbox['include_home']) && $chatbox['include_home'] == 'on') :
										
									$loadChatbox = TRUE;
									
								break;
									
								default:
										
									$loadChatbox = FALSE;
								
							} 
			
						break;
							
						case 'search' :
							
							switch(true) {
								
								case (isset($chatbox['include_search']) && $chatbox['include_search'] == 'on') :
										
									$loadChatbox = TRUE;
									
								break;
									
								default:
										
									$loadChatbox = FALSE;
								
							} 
			
						break;
							
						case 'single':
						case 'page':
						
							if ( isset($chatbox['include_whole_website']) && $chatbox['include_whole_website'] == 'on' ) :
								
								$loadChatbox = TRUE;
								
							else :
	
								$postID = $post->ID;
								$postType = get_post_type();
									
								switch(true) {

									case (isset($chatbox[$postType . '_matchValue']) && isset($chatbox['include_' . $postType]) && $chatbox[$postType . '_matchValue'] == 'include') :
											
										if ( is_singular($postType) ) :
											$loadChatbox = $this->isLoadableChatbox( $postID, $postType, $chatbox['include_' . $postType], 'is_singular', 'in_array' );
										endif;
		
									break;

									case (isset($chatbox[$postType . '_matchValue']) && isset($chatbox['exclude_' . $postType]) && $chatbox[$postType . '_matchValue'] == 'exclude') :
		
										if ( is_singular($postType) ) :
											$loadChatbox = $this->isLoadableChatbox( $postID, $postType, $chatbox['exclude_' . $postType], 'is_singular', 'not_in_array' );
										endif;
											
									break;
										
									default:
											
										$loadChatbox = FALSE;
									
									}
		
								endif;
	
						break;
					
						case 'shop':
	
							if ( isset($chatbox['include_whole_website']) && $chatbox['include_whole_website'] == 'on' ) {
								
								$loadChatbox = TRUE;
								
							} else {
	
								$shopID = wc_get_page_id('shop');

								switch(true) {

									case (isset($chatbox['page_matchValue']) && isset($chatbox['include_page']) && $chatbox['page_matchValue'] == 'include') :
											
										$loadChatbox = $this->isLoadableChatbox( $shopID, FALSE, $chatbox['include_page'], 'is_shop', 'in_array' );
									
									break;

									case (isset($chatbox['page_matchValue']) && isset($chatbox['exclude_page']) && $chatbox['page_matchValue'] == 'exclude') :
		
										$loadChatbox = $this->isLoadableChatbox( $shopID, FALSE, $chatbox['exclude_page'], 'is_shop', 'not_in_array' );
									
									break;
										
									default:
	
										$loadChatbox = FALSE;
									
								}
								
							}
	
						break;
							
						case 'category':
						case 'tag':
	
							if ( isset($chatbox['include_whole_website']) && $chatbox['include_whole_website'] == 'on' ) :
								
								$loadChatbox = TRUE;
								
							else :
		
								$catID = get_queried_object()->term_id;
								$catSlug = get_queried_object()->taxonomy;
								$catType = str_replace('post_', '', get_queried_object()->taxonomy);
									
								switch(true) {

									case (isset($chatbox[$catSlug . '_matchValue']) && isset($chatbox['include_' . $catSlug])  && $chatbox[$catSlug . '_matchValue'] == 'include') :
											
										$loadChatbox = $this->isLoadableChatbox( $catID, FALSE, $chatbox['include_' . $catSlug], 'is_' . $catType, 'in_array' );
									
									break;

									case (isset($chatbox[$catSlug . '_matchValue']) && isset($chatbox['exclude_' . $catSlug])  && $chatbox[$catSlug . '_matchValue'] == 'exclude') :
		
										$loadChatbox = $this->isLoadableChatbox( $catID, FALSE, $chatbox['exclude_' . $catSlug], 'is_' . $catType, 'not_in_array' );
									
									break;
										
									default:
										
										$loadChatbox = FALSE;
									
								}
									
								endif;
								
						break;
							
						case 'tax':
	
							if ( isset($chatbox['include_whole_website']) && $chatbox['include_whole_website'] == 'on' ) :
								
								$loadChatbox = TRUE;
								
							else :
			
								$taxID = get_queried_object()->term_id;
								$taxType = get_queried_object()->taxonomy;
		
								switch(true) {

									case (isset($chatbox[$taxType . '_matchValue']) && isset($chatbox['include_' . $taxType])  && $chatbox[$taxType . '_matchValue'] == 'include') :
											
											$loadChatbox = $this->isLoadableChatbox( $taxID, $taxType, $chatbox['include_' . $taxType], 'is_tax', 'in_array' );
									
									break;

									case (isset($chatbox[$taxType . '_matchValue']) && isset($chatbox['exclude_' . $taxType])  && $chatbox[$taxType . '_matchValue'] == 'exclude') :
		
											$loadChatbox = $this->isLoadableChatbox( $taxID, $taxType, $chatbox['exclude_' . $taxType], 'is_tax', 'not_in_array' );
									
									break;
										
									default:
											
										$loadChatbox = FALSE;
									
								}
									
							endif;
	
						break;

					}
					
					if ( $loadChatbox == TRUE && isset($chatbox['chatbox-type']) && $chatboxType == $chatbox['chatbox-type']) :

						$fixedPosition = str_replace('chatbox-wrapper', '', $chatbox['position']);
						$fixedPosition = explode('-', $fixedPosition);
						
						$shortcode  = '[chatbox_manager_button';
						$shortcode .= ' position="' . $chatbox['position'] . '" ';
						$shortcode .= ' layout="' . $chatbox['layout'] . '" ';
						$shortcode .= (isset($chatbox[$fixedPosition[0]])) ? ' position1="' . $chatbox[$fixedPosition[0]] . '" ' : '';
						$shortcode .= (isset($chatbox[$fixedPosition[1]])) ? ' position2="' . $chatbox[$fixedPosition[1]] . '" ' : '';
						$shortcode .= ' chatboxtype="' . $chatbox['chatbox-type'] . '" ';
						$shortcode .= ' chatboxid="' . $chatboxID . '" ';
						$shortcode .= ' size="' . $chatbox['size'] . '" ';
						$shortcode .= ' icon="' . $chatbox['icon'] . '" ';
						$shortcode .= ' text="' . $chatbox['text'] . '" ';
						$shortcode .= ' number="' . $chatbox['number'] . '" ';
						$shortcode .= ' prefilledmessage="' . $chatbox['prefilled-message'] . '" ';
						$shortcode .= ']';

						echo do_shortcode($shortcode);

					endif;

				}
				
			}

		}

	}

}

?>