<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if( !class_exists( 'chatbox_manager_panel' ) ) {

	class chatbox_manager_panel {
	
		/**
		 * Constructor
		 */

		public function __construct( $fields = array() ) {

			$this->panel_fields = $fields;

			$this->plugin_slug = 'chatbox_manager_panel_';
			$this->plugin_optionname = 'chatbox_manager_settings';

			add_action('admin_menu', array(&$this, 'admin_menu') ,11);
			add_action('admin_init', array(&$this, 'add_script') ,11);
			add_action('admin_init', array(&$this, 'save_option') ,11);

		}

		/**
		 * Create option panel menu
		 */

		public function admin_menu() {

			global $admin_page_hooks;
			
            if ( !isset( $admin_page_hooks['tip_plugins_panel']) ) :

				add_menu_page(
					esc_html__('TIP Plugins', 'chatbox-manager'),
					esc_html__('TIP Plugins', 'chatbox-manager'),
					'manage_options',
					'tip_plugins_panel',
					NULL,
					plugins_url('/assets/images/tip-icon.png', dirname(__FILE__)),
					64
				);

			endif;

			add_submenu_page(
				'tip_plugins_panel',
				esc_html__('Chatbox Manager', 'chatbox-manager'),
				esc_html__('Chatbox Manager', 'chatbox-manager'),
				'manage_options',
				'chatbox_manager_panel',
				array(&$this, 'chatbox_manager_panel')
			);

			if ( isset( $admin_page_hooks['tip_plugins_panel'] ) )
				remove_submenu_page( 'tip_plugins_panel', 'tip_plugins_panel' );

		}

		/**
		 * Loads the plugin scripts and styles
		 */
		 
		public function add_script() {
			
			 global $wp_version, $pagenow;
		
			 if ( $pagenow == 'admin.php' ) {

				$file_dir = plugins_url('/assets/', dirname(__FILE__));

				wp_enqueue_style ( 'chatbox_manager_panel', $file_dir.'css/panel.css' ); 
				wp_enqueue_style ( 'chatbox_manager_free_pro_table', $file_dir.'css/free_pro_table.css' );
				wp_enqueue_style ( 'chatbox_manager_panel_on_off', $file_dir.'css/on_off.css' );
				wp_enqueue_style ( 'chatbox_manager_panel_googlefonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i');
				wp_enqueue_style ( 'chatbox_manager_panel_select2', $file_dir.'css/select2.min.css' );
				
				wp_enqueue_script( 'jquery');
				wp_enqueue_script( 'jquery-ui-core', array('jquery'));
				wp_enqueue_script( 'jquery-ui-tabs', array('jquery'));

				wp_enqueue_script( 'chatbox_manager_panel_on_off', $file_dir.'js/on_off.js','3.5', '', TRUE); 
				wp_enqueue_script( 'chatbox_manager_panel_select2', $file_dir.'js/select2.min.js','3.5', '', TRUE); 
	
				wp_enqueue_script( 'chatbox_manager_panel', $file_dir.'js/panel.js',array('jquery','thickbox'),'1.0',TRUE ); 
			 
			 }
			 
		}

		/**
		 * Message after the options saving
		 */
		 
		public function save_message() {
			
			global $chatbox_manager_message;
			$plugin_slug = $this->plugin_slug;
			
			if (isset($chatbox_manager_message))
				echo '<div id="message" class="updated fade message_save ' . $plugin_slug . 'message"><p><strong> ' . $chatbox_manager_message . '</strong></p></div>';
			
		}

		/**
		 * Sanitize icon function
		 */
		 
		public function sanitize_icon_function($k) {
			
			$iconArray = array(
				'icon-1',
				'icon-2',
				'none'
			);
		
			if ( in_array($k, $iconArray)) {
			
				return $k;
			
			} else {
			
				return 'icon-1';
			
			}
		
		}

		/**
		 * Sanitize layout function
		 */
		 
		public function sanitize_layout_function($k) {
			
			$layoutArray = array(
				'layout-1',
				'layout-2',
				'layout-3',
				'layout-4',
				'layout-5'
			);
		
			if ( in_array($k, $layoutArray)) {
			
				return $k;
			
			} else {
			
				return 'layout-2';
			
			}
		
		}

		/**
		 * Sanitize position function
		 */
		 
		public function sanitize_position_function($k) {
			
			$positionArray = array(
				'bottom-right',
				'bottom-left',
				'bottom-center',
				'top-right',
				'top-left',
				'top-center'
			);
		
			if ( in_array($k, $positionArray)) {
			
				return $k;
			
			} else {
			
				return 'bottom-right';
			
			}
		
		}

		/**
		 * Sanitize type function
		 */
		 
		public function sanitize_type_function($k) {
			
			$typeArray = array(
				'floating',
				'afterContent'
			);
		
			if ( in_array($k, $typeArray)) {
			
				return $k;
			
			} else {
			
				return 'floating';
			
			}
		
		}

		/**
		 * Sanitize boolean function
		 */
		 
		public function sanitize_bool_function($k) {
			
			return ( $k == 'on' ) ? 'on' : 'off';
		
		}

		/**
		 * Sanitize matchValue function
		 */
		 
		public function sanitize_matchValue_function($k) {
			
			return ( $k == 'include' ) ? 'include' : 'exclude';
		
		}

		/**
		 * Sanitize matchValue function
		 */
		 
		public function sanitize_postID_function($k) {
			
			if ( is_array($k) ) :

				if (in_array('-1', $k)) {
				
					return array('-1');
				
				} else {
					
					foreach ($k as $v) {
						
						$postTitle = get_the_title($v);
						
						if ( true == post_exists($postTitle) ) {
							
							$error = false;
						
						} elseif ( false == post_exists($postTitle) ) {
						
							$error = true;
						
						}

					}
					
					return ($error == false ) ? $k : array('-1');
				
				}

			else:
			
				return array('-1');
			
			endif;
		
		}

		/**
		 * Get sanitize function
		 */
		 
		public function get_sanitize_function($key) {

			$cptMatchValue = array();
			$cptInclude = array();
			$cptExclude = array();
			$taxMatchValue = array();
			$taxInclude = array();
			$taxExclude = array();
			$native_functions = array();
			$sanize_function = array();

			foreach (chatbox_manager_get_custom_post_list() as $v ) {
				$cptMatchValue[$v . '_matchValue'] = 'chatbox_manager_panel::sanitize_matchValue_function';
				$cptInclude['include_' . $v] = 'chatbox_manager_panel::sanitize_postID_function';
				$cptExclude['exclude_' . $v] = 'chatbox_manager_panel::sanitize_postID_function';
			}
			
			foreach (chatbox_manager_get_taxonomies_list() as $v ) {
				$taxMatchValue[$v . '_matchValue'] = 'chatbox_manager_panel::sanitize_matchValue_function';
				$taxInclude['include_' . $v] = 'chatbox_manager_panel::sanitize_postID_function';
				$taxExclude['exclude_' . $v] = 'chatbox_manager_panel::sanitize_postID_function';
			}
			
			$native_functions = array(  
				'name' => 'sanitize_text_field',
				'text' => 'sanitize_text_field',
				'number' => 'sanitize_text_field',
				'top' => 'sanitize_text_field',
				'left' => 'sanitize_text_field',
				'right' => 'sanitize_text_field',
				'bottom' => 'sanitize_text_field',
				'size' => 'sanitize_text_field',
				'prefilled-message' => 'sanitize_text_field',
				'include_home' => 'chatbox_manager_panel::sanitize_bool_function',
				'include_search' => 'chatbox_manager_panel::sanitize_bool_function',
				'include_whole_website' => 'chatbox_manager_panel::sanitize_bool_function',
				'icon' => 'chatbox_manager_panel::sanitize_icon_function',
				'layout' => 'chatbox_manager_panel::sanitize_layout_function',
				'position' => 'chatbox_manager_panel::sanitize_position_function',
				'chatbox-type' => 'chatbox_manager_panel::sanitize_type_function'
			);
			
			$sanize_function = array_merge(
				$native_functions,
				$cptMatchValue,
				$cptInclude,
				$cptExclude,
				$taxMatchValue,
				$taxInclude,
				$taxExclude
			);
			
			return $sanize_function[$key];
		
		}

		/**
		 * Save options function
		 */
		 
		public function save_option() {
			
			global $chatbox_manager_message;

			$chatbox_manager_setting = get_option($this->plugin_optionname);

			if ( $chatbox_manager_setting != false ) :
					
				$chatbox_manager_setting = maybe_unserialize( get_option( $this->plugin_optionname ) );
								
			else :
				
				$chatbox_manager_setting = array();
			
			endif;   

			if ( $this->chatbox_manager_request('chatbox_manager_action') == 'New chatbox' ) {
				
				$lastChatbox = key(array_slice($chatbox_manager_setting['chatbox_manager_chatboxes'], -1, 1, true));
				$newChatbox = str_replace('chatbox', '', $lastChatbox) + 1;
				$chatbox_manager_setting['chatbox_manager_chatboxes']['chatbox' . $newChatbox] = array('name' => 'Undefined chatbox ' . $newChatbox);
				update_option($this->plugin_optionname, $chatbox_manager_setting);
				wp_redirect(admin_url('admin.php?page=chatbox_manager_panel&tab=Chatbox_Generator&chatboxID=' . $newChatbox));

			}
			
			if ( $this->chatbox_manager_request('chatbox_manager_action') == 'Delete' ) {
				
				unset($chatbox_manager_setting['chatbox_manager_chatboxes']['chatbox' . sanitize_text_field($_POST['chatbox_manager_chatbox_id'])]);
				update_option($this->plugin_optionname, $chatbox_manager_setting);

			}
			
			if ( $this->chatbox_manager_request('chatbox_manager_action') == 'Save' ) {
				
				foreach ( $this->panel_fields as $element ) {
					
					if ( isset($element['tab']) && $element['tab'] == $_GET['tab'] ) {
							
						foreach ($element as $value ) {

							if ( isset($value['id']) && ( $value['id'] == 'chatbox_manager_chatboxes' ) ) {
								
								if ( isset($_POST[$value['id']]) ) {

									$newValues = $_POST[$value["id"]];

									foreach ( $newValues['chatbox' . $_POST['chatbox_manager_chatbox_id']] as $k => $v) { 
										$toSave[$k] = call_user_func($this->get_sanitize_function($k), $v);
									}

									$chatbox_manager_setting[$value['id']]['chatbox'. $_POST['chatbox_manager_chatbox_id']] = $toSave;
									update_option($this->plugin_optionname, $chatbox_manager_setting );
									
								}
								
							}
	
							$chatbox_manager_message = esc_html__('Options saved successfully.', 'chatbox-manager' );
	
						}
		
					}
	
				}
	
			}
	
		}
		
		/**
		 * Request function
		 */
		 
		public function chatbox_manager_request($id) {
			
			if (isset($_REQUEST[$id]))
				return sanitize_text_field($_REQUEST[$id]);	
			
		}
		
		/**
		 * Option panel
		 */
		 
		public function chatbox_manager_panel() {

			global $chatbox_manager_message;

			$chatboxManagerForm = new chatbox_manager_form();
			$plugin_slug =  $this->plugin_slug;
						
			if (!isset($_GET['tab'])) 
				$_GET['tab'] = "Chatbox_Generator"; 

			foreach ( $this->panel_fields as $element) {
	
				if (isset($element['type'])) : 
	
					switch ( $element['type'] ) { 
	
						case 'navigation':
						
							echo $chatboxManagerForm->elementStart('div', $plugin_slug . 'tabs', FALSE );
								
								echo $chatboxManagerForm->elementStart('div', $plugin_slug . 'header', FALSE );
										
									echo $chatboxManagerForm->elementStart('div', FALSE, 'left plugin_description' );
										
										echo $chatboxManagerForm->element('h2', FALSE, 'maintitle', esc_html__( 'Chatbox Manager','chatbox-manager'));
										echo $chatboxManagerForm->element('span', FALSE, FALSE, esc_html__( 'Version: ','chatbox-manager') . CM_VERSION);
										echo $chatboxManagerForm->link(esc_url('https://www.themeinprogress.com'), FALSE, FALSE, '_blank', FALSE, esc_html__( 'by ThemeinProgress','chatbox-manager') );
										echo $chatboxManagerForm->link(esc_url(CM_DEMO_PAGE), FALSE, FALSE, '_blank', FALSE, esc_html__( ' - Documentation','chatbox-manager') );

									echo $chatboxManagerForm->elementEnd('div');

									echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);

								echo $chatboxManagerForm->elementEnd('div');

								$this->save_message();

								echo $chatboxManagerForm->htmlList('ul', FALSE, $plugin_slug . 'navigation', $element['item'], esc_attr($_GET['tab']));
							
						break;
						
						case 'end-tab':
						
								echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
						
							echo $chatboxManagerForm->elementEnd('div');
						
						break;
						
					}
				
				endif;
			
			if (isset($element['tab'])) : 
			
				switch ( $element['tab'] ) { 
			
					case esc_attr($_GET['tab']):  
			
						foreach ($element as $value) {
						
							if (isset($value['type'])) :
							
								switch ( $value['type'] ) { 
							
								case 'start-form':
									
									echo $chatboxManagerForm->elementStart('div', str_replace(' ', '', $value['name']), FALSE );
										
										echo $chatboxManagerForm->formStart('post', '?page=chatbox_manager_panel&tab=' . esc_attr($_GET['tab']) );
								
								break;
								
								case 'end-form':

										echo $chatboxManagerForm->formEnd();
									
									echo $chatboxManagerForm->elementEnd('div');

								break;
									
								case 'start-container':

									$class = ( 'Save' == $this->chatbox_manager_request('chatbox_manager_action') && $value['val'] == $this->chatbox_manager_request('element-opened') ) ? ' inactive' : '';

									echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'container' );
										
										echo $chatboxManagerForm->element('h5', $value['val'], 'element ' . $class, $value['name'] );

										echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'mainbox' );

								break;
						
								case 'start-open-container':
								
									echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'container' );
										
										echo $chatboxManagerForm->element('h5', FALSE, 'element-open', $value['name'] );

										echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'mainbox chatbox_manager_openbox' );

								break;
														
								case 'end-container':
								
										echo $chatboxManagerForm->elementEnd('div');
										
									echo $chatboxManagerForm->elementEnd('div');
								
								break;
					
								case 'checkbox':
								
									echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box');
										
										echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
										
											echo $chatboxManagerForm->label($value['id'], $value['name']);
										
										echo $chatboxManagerForm->elementEnd('div');

										echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
											
											echo $chatboxManagerForm->checkbox($value['id'], $value['options'], chatbox_manager_setting($value['id']));
											echo $chatboxManagerForm->element('em', FALSE, FALSE, $value['desc']);

										echo $chatboxManagerForm->elementEnd('div');

										echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
										
									echo $chatboxManagerForm->elementEnd('div');
								
								break;
									
								case 'text':
								
									echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
										
										echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
										
											echo $chatboxManagerForm->label($value['id'], $value['name']);
										
										echo $chatboxManagerForm->elementEnd('div');

										echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

											echo $chatboxManagerForm->input($value['id'], $value['id'], FALSE, $value['type'], esc_attr(chatbox_manager_setting($value['id']), FALSE, $value['std']));
											echo $chatboxManagerForm->element('em', FALSE, FALSE, $value['desc']);

										echo $chatboxManagerForm->elementEnd('div');

										echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
										
									echo $chatboxManagerForm->elementEnd('div');
								
								break;
									
								case 'textarea':
								
									echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box');
										
										echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
										
											echo $chatboxManagerForm->label($value['id'], $value['name']);
										
										echo $chatboxManagerForm->elementEnd('div');

										echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
											
											echo $chatboxManagerForm->textarea($value['id'], $value['id'], FALSE, esc_html(chatbox_manager_setting($value['id']), $value['std']), FALSE);
											echo $chatboxManagerForm->element('em', FALSE, FALSE, $value['desc']);

										echo $chatboxManagerForm->elementEnd('div');

										echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
										
									echo $chatboxManagerForm->elementEnd('div');
																
								break;
					 
								case 'on-off': 
								
									echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box');
										
										echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
										
											echo $chatboxManagerForm->label($value['id'], $value['name']);
										
										echo $chatboxManagerForm->elementEnd('div');

										echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

											echo $chatboxManagerForm->elementStart('div', FALSE,  $plugin_slug . 'slider ' . $this->sanitize_bool_function(chatbox_manager_setting($value['id']), $value['std']) );
											
												echo $chatboxManagerForm->elementStart('div', FALSE, 'inset' );
												echo $chatboxManagerForm->element('div', FALSE, 'control', FALSE);
												echo $chatboxManagerForm->elementEnd('div');
											
												echo $chatboxManagerForm->input($value['id'], $value['id'], 'on-off', 'hidden', $this->sanitize_bool_function(chatbox_manager_setting($value['id']), $value['std']));
											
											echo $chatboxManagerForm->elementEnd('div');
												
											echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
											echo $chatboxManagerForm->element('p', FALSE, FALSE, $value['desc'] );

										echo $chatboxManagerForm->elementEnd('div');

										echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
										
									echo $chatboxManagerForm->elementEnd('div');
																
								break;

								case "save-button": 
								
									echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box WIP_plugin_save_box');
										
										echo $chatboxManagerForm->input($value['action'], FALSE, 'button', 'submit', $value['value']);
									
									echo $chatboxManagerForm->elementEnd('div');
									
								break;

								case "activation-button": 
								
									echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box WIP_plugin_save_box');
										
										echo $chatboxManagerForm->input( CM_ITEM_SLUG . '_activate_license', FALSE, 'button', 'submit', 'Activate');
										echo $chatboxManagerForm->input( CM_ITEM_SLUG . '_deactivate_license', FALSE, 'button', 'submit', 'Deactivate');
									
									echo $chatboxManagerForm->elementEnd('div');
									
								break;

								case 'color':
								
									echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
										
										echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
										
											echo $chatboxManagerForm->label($value['id'], $value['name']);
										
										echo $chatboxManagerForm->elementEnd('div');

										echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

											echo $chatboxManagerForm->color($value['id'], $value['id'], $plugin_slug . 'color', 'text', esc_attr(chatbox_manager_setting($value['id']), $value['std']));
											echo $chatboxManagerForm->element('em', FALSE, FALSE, $value['desc']);

										echo $chatboxManagerForm->elementEnd('div');

										echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
										
									echo $chatboxManagerForm->elementEnd('div');
								
								break;
									
								case 'free_vs_pro':

								echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );

									echo $chatboxManagerForm->tableStart(FALSE, $plugin_slug . ' card table free-pro', 0, 0 );

									echo $chatboxManagerForm->tableElementStart('tbody', FALSE, 'table-body');

										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'table-head');

											echo $chatboxManagerForm->tableElement('th', FALSE, 'large');

											echo $chatboxManagerForm->tableElementStart('th', FALSE, 'indicator');
												echo esc_html__('Free', 'chatbox-manager');
											echo $chatboxManagerForm->tableElementEnd('th');

											echo $chatboxManagerForm->tableElementStart('th', FALSE, 'indicator');
												echo esc_html__('Premium', 'chatbox-manager');
											echo $chatboxManagerForm->tableElementEnd('th');

										echo $chatboxManagerForm->tableElementEnd('tr');

										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'large');

												echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $chatboxManagerForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Pre-filled message', 'chatbox-manager');

													echo $chatboxManagerForm->elementEnd('h4');

												echo $chatboxManagerForm->elementEnd('div');

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

										echo $chatboxManagerForm->tableElementEnd('tr');

										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'large');

												echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $chatboxManagerForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Chatbox position', 'chatbox-manager');

													echo $chatboxManagerForm->elementEnd('h4');

												echo $chatboxManagerForm->elementEnd('div');

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

										echo $chatboxManagerForm->tableElementEnd('tr');

										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'large');

												echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $chatboxManagerForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Add the chatbox on whole website', 'chatbox-manager');

													echo $chatboxManagerForm->elementEnd('h4');

												echo $chatboxManagerForm->elementEnd('div');

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

										echo $chatboxManagerForm->tableElementEnd('tr');
										
										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'large');

												echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $chatboxManagerForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Add the chatbox on specific content', 'chatbox-manager');

													echo $chatboxManagerForm->elementEnd('h4');

												echo $chatboxManagerForm->elementEnd('div');

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE );

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

										echo $chatboxManagerForm->tableElementEnd('tr');

										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'large');

												echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $chatboxManagerForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Layouts', 'chatbox-manager');

													echo $chatboxManagerForm->elementEnd('h4');

												echo $chatboxManagerForm->elementEnd('div');

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo esc_html__('5', 'chatbox-manager');

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo esc_html__('6', 'chatbox-manager');

											echo $chatboxManagerForm->tableElementEnd('td');

										echo $chatboxManagerForm->tableElementEnd('tr');

										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'large');

												echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $chatboxManagerForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Unlimited chatboxes', 'chatbox-manager');

													echo $chatboxManagerForm->elementEnd('h4');

													echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $chatboxManagerForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $chatboxManagerForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('You can generate unlimited chatboxes with different numbers.', 'chatbox-manager');

														echo $chatboxManagerForm->elementEnd('span');

													echo $chatboxManagerForm->elementEnd('div');

												echo $chatboxManagerForm->elementEnd('div');

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

										echo $chatboxManagerForm->tableElementEnd('tr');

										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'large');

												echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $chatboxManagerForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Device selection', 'chatbox-manager');

													echo $chatboxManagerForm->elementEnd('h4');

													echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $chatboxManagerForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $chatboxManagerForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('Select the device where you want to display the WhatsApp button.', 'chatbox-manager');

														echo $chatboxManagerForm->elementEnd('span');

													echo $chatboxManagerForm->elementEnd('div');

												echo $chatboxManagerForm->elementEnd('div');

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

										echo $chatboxManagerForm->tableElementEnd('tr');

										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'large');

												echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $chatboxManagerForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Shake animation', 'chatbox-manager');

													echo $chatboxManagerForm->elementEnd('h4');

													echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $chatboxManagerForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $chatboxManagerForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('Capture Users’ Attention thanks to the shake animation.', 'chatbox-manager');

														echo $chatboxManagerForm->elementEnd('span');

													echo $chatboxManagerForm->elementEnd('div');

												echo $chatboxManagerForm->elementEnd('div');

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

										echo $chatboxManagerForm->tableElementEnd('tr');

										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'large');

												echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $chatboxManagerForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Backup section', 'chatbox-manager');

													echo $chatboxManagerForm->elementEnd('h4');

													echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $chatboxManagerForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $chatboxManagerForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('You can create a backup of plugin settings, import an existing backuo or restore the default settings.', 'chatbox-manager');

														echo $chatboxManagerForm->elementEnd('span');

													echo $chatboxManagerForm->elementEnd('div');

												echo $chatboxManagerForm->elementEnd('div');

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

										echo $chatboxManagerForm->tableElementEnd('tr');

										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'feature-row');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'large');

												echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-wrap' );

													echo $chatboxManagerForm->elementStart('h4', FALSE, FALSE );

														echo esc_html__('Dynamic values on the pre-filled message', 'chatbox-manager');

													echo $chatboxManagerForm->elementEnd('h4');

													echo $chatboxManagerForm->elementStart('div', FALSE, 'feature-inline-row' );

														echo $chatboxManagerForm->element('span', FALSE, 'info-icon dashicon dashicons dashicons-info', FALSE );

														echo $chatboxManagerForm->elementStart('span', FALSE, 'feature-description' );

															echo esc_html__('Include the dynamic values inside the pre-filled message to add the TITLE and URL of current post/page.', 'chatbox-manager');

														echo $chatboxManagerForm->elementEnd('span');

													echo $chatboxManagerForm->elementEnd('div');

												echo $chatboxManagerForm->elementEnd('div');

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-no-alt', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->element('span', FALSE, 'dashicon dashicons dashicons-yes', FALSE);

											echo $chatboxManagerForm->tableElementEnd('td');

										echo $chatboxManagerForm->tableElementEnd('tr');

										echo $chatboxManagerForm->tableElementStart('tr', FALSE, 'upsell-row');

											echo $chatboxManagerForm->tableElement('td', FALSE, FALSE);
											echo $chatboxManagerForm->tableElement('td', FALSE, FALSE);

											echo $chatboxManagerForm->tableElementStart('td', FALSE, 'indicator');

												echo $chatboxManagerForm->link(esc_url(CM_SALE_PAGE . '/?ref=2&campaign=cm-freepro'), FALSE, 'button button-primary', '_blank', FALSE, esc_html__( 'Upgrade to Premium','chatbox-manager') );

											echo $chatboxManagerForm->tableElementEnd('td');

										echo $chatboxManagerForm->tableElementEnd('tr');

									echo $chatboxManagerForm->tableElementEnd('tbody');

									echo $chatboxManagerForm->tableEnd();

								echo $chatboxManagerForm->elementEnd('div');

								break;

								case 'chatboxGenerator':

									$chatbox_manager_chatboxes = chatbox_manager_setting('chatbox_manager_chatboxes');
			
									echo $chatboxManagerForm->elementStart('div', FALSE, 'chatbox_manager_chatboxGenerator' );
										
										echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'chatbox_order' );
										
										if (!isset($_REQUEST['chatboxID'])) {
	
											if ($chatbox_manager_chatboxes) { 
												
												foreach ( $chatbox_manager_chatboxes as $k => $v) {

													echo $chatboxManagerForm->elementStart('div', 'chatbox', $plugin_slug . 'container ' . 'chatbox' );
										
													echo $chatboxManagerForm->element('h5', FALSE, 'element linkable-element', '<a href="?page=chatbox_manager_panel&tab=' . esc_attr($_GET['tab']) .'&chatboxID='.str_replace('chatbox','', esc_attr($k)).'">'.esc_html($v['name']).'</a>' );

													echo $chatboxManagerForm->elementEnd('div');
													
												}
												
											}
	
											echo $chatboxManagerForm->elementStart('div', FALSE, FALSE );
										   
												$chatbox_manager_settings = get_option('chatbox_manager_settings');

												if ( 
													isset($chatbox_manager_settings['chatbox_manager_chatboxes']) && 
													is_array($chatbox_manager_settings['chatbox_manager_chatboxes']) && 
													count($chatbox_manager_settings['chatbox_manager_chatboxes']) >= 3
												) :
												
													echo $chatboxManagerForm->element('span', FALSE, FALSE, _e('<span>You have reached the limit of 3 chatboxes. You can unlock this limit visiting this LINK</span> ','chatbox-manager'));

													echo $chatboxManagerForm->link(esc_url(CM_SALE_PAGE . 'cm-panel'), FALSE, FALSE, '_blank', FALSE, esc_html__( 'Upgrade to Chatbox Manager premium','chatbox-manager') );
												
												else:
													
													echo $chatboxManagerForm->input('chatbox_manager_action', FALSE, 'button', 'submit', 'New chatbox');

												endif;
	
											echo $chatboxManagerForm->elementEnd('div');

										} else {

											$chatboxID = esc_attr($_REQUEST['chatboxID']);

											if (!array_key_exists('chatbox' . $chatboxID, $chatbox_manager_chatboxes)) {
												wp_redirect(admin_url('admin.php?page=chatbox_manager_panel&tab=Chatbox_Generator'));
											}
											
											echo $chatboxManagerForm->input('chatbox_manager_chatbox_id', FALSE, FALSE, 'hidden', $chatboxID);

											echo $chatboxManagerForm->elementStart('div', 'chatbox' . $chatboxID, $plugin_slug . 'container ' . 'chatbox' . $chatboxID);
										
											echo $chatboxManagerForm->element('h5', FALSE, 'element-open', ($chatbox_manager_chatboxes['chatbox' . $chatboxID]['name']) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['name']) : esc_html__( "Undefined chatbox","chatbox-manager"));
									
											echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'mainbox-open');
														
												echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
													
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
														echo $chatboxManagerForm->label(FALSE, esc_html__( 'Chatbox name.', 'chatbox-manager'));
																
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
						
														echo $chatboxManagerForm->input($value['id'] . '[chatbox' . $chatboxID . '][name]', FALSE, FALSE, 'text', ($chatbox_manager_chatboxes['chatbox' . $chatboxID]['name']) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['name']) : esc_html__( "Undefined chatbox","chatbox-manager"));
														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Add the name of this chatbox.','chatbox-manager'));
						
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
												echo $chatboxManagerForm->elementEnd('div');

												echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
													
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
														echo $chatboxManagerForm->label(FALSE, esc_html__( 'WhatsApp number', 'chatbox-manager'));
																
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
						
														echo $chatboxManagerForm->input($value['id'].'[chatbox' . $chatboxID . '][number]','chatbox_manager_check_number', FALSE, 'text',(isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['number'])) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['number']) : '');
																
														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Add the WhatsApp number of this chatbox including the country code, for example +393331234567','chatbox-manager'));

														echo $chatboxManagerForm->link(esc_url('https://faq.whatsapp.com/en/general/21016748'), FALSE, FALSE, '_blank', FALSE, esc_html__( 'How to include properly the phone number','chatbox-manager') );
						
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
												echo $chatboxManagerForm->elementEnd('div');
												
												echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
													
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
														echo $chatboxManagerForm->label(FALSE, esc_html__( 'Text to display', 'chatbox-manager'));
																
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
						
														echo $chatboxManagerForm->input($value['id'].'[chatbox' . $chatboxID . '][text]', 'chatbox_manager_check_number', FALSE, 'text', (isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['text'])) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['text']) : esc_html__( 'WhatsApp us', 'chatbox-manager'));
																
														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Add the text of this chatbox.','chatbox-manager'));
						
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
												echo $chatboxManagerForm->elementEnd('div');
												
												echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
													
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
														echo $chatboxManagerForm->label(FALSE, esc_html__( 'Pre-filled message', 'chatbox-manager'));
																
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
						
														echo $chatboxManagerForm->input($value['id'].'[chatbox' . $chatboxID . '][prefilled-message]', 'chatbox_manager_check_number', FALSE, 'text', (isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['prefilled-message'])) ? esc_attr(stripslashes(esc_html($chatbox_manager_chatboxes['chatbox' . $chatboxID]['prefilled-message']))) : esc_html__( 'Hello', 'chatbox-manager'));

														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Add the pre-filled message of this chatbox.','chatbox-manager'));
						
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
												echo $chatboxManagerForm->elementEnd('div');
												
												echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
													
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
														echo $chatboxManagerForm->label(FALSE, esc_html__( 'Fixed position', 'chatbox-manager'));
																
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

														$fixedPosition = (isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['position'])) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['position']) : 'bottom-right';

														$options = array( 
															'bottom-right' => esc_html__('Bottom right','chatbox-manager'),
															'bottom-left' => esc_html__('Bottom left','chatbox-manager'),
															'bottom-center' => esc_html__('Bottom center','chatbox-manager'),
															'top-right' => esc_html__('Top right','chatbox-manager'),
															'top-left' => esc_html__('Top left','chatbox-manager'),
															'top-center' => esc_html__('Top center','chatbox-manager'),
														);
														
														echo $chatboxManagerForm->select($value['id'].'[chatbox' . $chatboxID . '][position]', FALSE, 'filterPosition', $options, $fixedPosition, FALSE);
														
														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Set the fixed position where you want to place this chatbox.','chatbox-manager'));
						
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
												echo $chatboxManagerForm->elementEnd('div');

												echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
													
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
														echo $chatboxManagerForm->label(FALSE, esc_html__( 'Position', 'chatbox-manager'));
																
													echo $chatboxManagerForm->elementEnd('div');

													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

														echo $chatboxManagerForm->input($value['id'].'[chatbox' . $chatboxID . '][top]', FALSE, 'positionInput topInput', 'number', (isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['top'])) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['top']) : '20', FALSE, esc_html__('Top position','chatbox-manager') );
														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Insert the top position, please do not include "px"','chatbox-manager'));

													echo $chatboxManagerForm->elementEnd('div');

													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

														echo $chatboxManagerForm->input($value['id'].'[chatbox' . $chatboxID . '][right]', FALSE, 'positionInput rightInput', 'number', (isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['right'])) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['right']) : '20', FALSE, esc_html__('Right position.','chatbox-manager') );

														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Insert the right position, please do not include "px"','chatbox-manager'));

													echo $chatboxManagerForm->elementEnd('div');

													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

echo $chatboxManagerForm->input($value['id'].'[chatbox' . $chatboxID . '][bottom]', FALSE, 'positionInput bottomInput', 'number', (isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['bottom'])) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['bottom']) : '20', FALSE, esc_html__('Bottom position.','chatbox-manager'));

														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Insert the bottom position, please do not include "px"','chatbox-manager'));

													echo $chatboxManagerForm->elementEnd('div');

													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

														echo $chatboxManagerForm->input($value['id'].'[chatbox' . $chatboxID . '][left]', FALSE, 'positionInput leftInput', 'number', (isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['left'])) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['left']) : '20', FALSE, esc_html__('Left position.','chatbox-manager'));

														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Insert the left position, please do not include "px"','chatbox-manager'));

													echo $chatboxManagerForm->elementEnd('div');
												
													echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
												
												echo $chatboxManagerForm->elementEnd('div');
												
												echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
													
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
														echo $chatboxManagerForm->label(FALSE, esc_html__( 'Size', 'chatbox-manager'));
																
													echo $chatboxManagerForm->elementEnd('div');

													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

														echo $chatboxManagerForm->input($value['id'].'[chatbox' . $chatboxID . '][size]', FALSE, FALSE, 'number', (isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['size'])) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['size']) : '50');

														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Insert the size of this icon, please do not include "px"','chatbox-manager'));

													echo $chatboxManagerForm->elementEnd('div');
												
													echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
												
												echo $chatboxManagerForm->elementEnd('div');
												
												echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
													
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
														echo $chatboxManagerForm->label(FALSE, esc_html__( 'Icon', 'chatbox-manager'));
																
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

														$defaultPosition = (isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['icon'])) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['icon']) : 'icon-1';

														$options = array( 
															'icon-1' => esc_html__('Icon 1','chatbox-manager'),
															'icon-2' => esc_html__('Icon 2','chatbox-manager'),
															'none' => esc_html__('None','chatbox-manager'),
														);

														echo $chatboxManagerForm->select($value['id'].'[chatbox' . $chatboxID . '][icon]', FALSE, 'filterIcon', $options, $defaultPosition, FALSE);
														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Select a icon for this chatbox.','chatbox-manager'));
						
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
												echo $chatboxManagerForm->elementEnd('div');
												
												echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
													
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
														echo $chatboxManagerForm->label(FALSE, esc_html__( 'Layout', 'chatbox-manager'));
																
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

														$defaultPosition = (isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['layout'])) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['layout']) : 'layout-2';

														$options = array( 
															'layout-1' => esc_html__('Layout 1','chatbox-manager'),
															'layout-2' => esc_html__('Layout 2','chatbox-manager'),
															'layout-3' => esc_html__('Layout 3','chatbox-manager'),
															'layout-4' => esc_html__('Layout 4','chatbox-manager'),
															'layout-5' => esc_html__('Layout 5','chatbox-manager'),
														);

														echo $chatboxManagerForm->select($value['id'].'[chatbox' . $chatboxID . '][layout]', FALSE, 'filterLayout', $options, $defaultPosition, FALSE);
														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Select a layout for this chatbox.','chatbox-manager'));
						
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
												echo $chatboxManagerForm->elementEnd('div');

												echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box' );
													
													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
														echo $chatboxManagerForm->label(FALSE, esc_html__( 'Chatbox type', 'chatbox-manager'));
																
													echo $chatboxManagerForm->elementEnd('div');

													echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );

														$defaultPosition = (isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['chatbox-type'])) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID]['chatbox-type']) : 'floating';

														$options = array( 
															'floating' => esc_html__('Floating button','chatbox-manager'),
															'afterContent' => esc_html__('After content','chatbox-manager'),
														);

														echo $chatboxManagerForm->select($value['id'].'[chatbox' . $chatboxID . '][chatbox-type]', FALSE, FALSE, $options, $defaultPosition, FALSE);
														echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Which type of chatbox you want to display?','chatbox-manager'));
						
													echo $chatboxManagerForm->elementEnd('div');
						
													echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
												echo $chatboxManagerForm->elementEnd('div');

												echo $chatboxManagerForm->elementStart('div', FALSE, 'switchSection');
												
													$includeHome = (!isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['include_home'])||$chatbox_manager_chatboxes['chatbox' . $chatboxID]['include_home'] == 'on') ? 'on' : 'off';
														
													echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box');
																
														echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
															echo $chatboxManagerForm->label('Homepage.', esc_html__( 'Homepage', 'chatbox-manager'));
																
														echo $chatboxManagerForm->elementEnd('div');
						
														echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
						
															echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'slider ' . $includeHome );
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'inset' );
																	echo $chatboxManagerForm->element('div', FALSE, 'control', FALSE);
																echo $chatboxManagerForm->elementEnd('div');
																	
																echo $chatboxManagerForm->input($value['id'].'[chatbox' . $chatboxID . '][include_home]', FALSE, 'on-off', 'hidden', $includeHome );
																	
															echo $chatboxManagerForm->elementEnd('div');
															
															echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
															echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Do you want to load this chatbox on the homepage?','chatbox-manager'));

														echo $chatboxManagerForm->elementEnd('div');
						
														echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
													echo $chatboxManagerForm->elementEnd('div');
													
													echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box');

														$includeSearch = (!isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['include_search']) || $chatbox_manager_chatboxes['chatbox' . $chatboxID]['include_search'] == 'on') ? 'on' : 'off';
														
														echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
															echo $chatboxManagerForm->label('Search result pages.', esc_html__( 'Search', 'chatbox-manager'));
																
														echo $chatboxManagerForm->elementEnd('div');
						
														echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
						
															echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'slider ' . $includeSearch );
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'inset' );
																	
																	echo $chatboxManagerForm->element('div', FALSE, 'control', FALSE);
																
																echo $chatboxManagerForm->elementEnd('div');
																		
																echo $chatboxManagerForm->input($value['id'].'[chatbox' . $chatboxID . '][include_search]', FALSE, 'on-off', 'hidden', $includeSearch );
																		
															echo $chatboxManagerForm->elementEnd('div');
																			
															echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
															echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Do you want to load this chatbox on the search result pages?','chatbox-manager'));
	
														echo $chatboxManagerForm->elementEnd('div');
							
														echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
	
													echo $chatboxManagerForm->elementEnd('div');

													$includeWholeWebsite = (!isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['include_whole_website']) || $chatbox_manager_chatboxes['chatbox' . $chatboxID]['include_whole_website'] == 'on') ? 'on' : 'off';

													echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box');
																
														echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																
															echo $chatboxManagerForm->label('wholeWebsite', esc_html__( 'Whole website (posts, pages, taxonomies)', 'chatbox-manager'));
																
														echo $chatboxManagerForm->elementEnd('div');
						
														echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
						
															echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'slider wholeWebsite ' . $includeWholeWebsite );
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'inset' );
																	echo $chatboxManagerForm->element('div', FALSE, 'control', FALSE);
																echo $chatboxManagerForm->elementEnd('div');
																	
																echo $chatboxManagerForm->input($value['id'].'[chatbox' . $chatboxID . '][include_whole_website]', FALSE, 'on-off', 'hidden', $includeWholeWebsite );
																	
															echo $chatboxManagerForm->elementEnd('div');
																		
															echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
															echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Do you want to load this chatbox on whole website?','chatbox-manager'));
														
														echo $chatboxManagerForm->elementEnd('div');
						
																echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
															echo $chatboxManagerForm->elementEnd('div');
															
														echo $chatboxManagerForm->elementEnd('div');

														echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box MatchValueBox' );
						
															echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
						
																echo $chatboxManagerForm->element('strong', FALSE, FALSE, esc_html__('Custom post types.','chatbox-manager'));
																echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Below each available custom post type.','chatbox-manager'));
						
															echo $chatboxManagerForm->elementEnd('div');
						
														echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
													echo $chatboxManagerForm->elementEnd('div');

													foreach ( chatbox_manager_get_custom_post_list() as $cpt ) { 
	
														echo $chatboxManagerForm->elementStart('div', FALSE, 'MatchValueBox ' . $cpt . 'Type');
	
															echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box ' . $cpt . 'Cpt MatchValue');
															
															echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																	
																	echo $chatboxManagerForm->label(FALSE, ucfirst($cpt) . esc_html__( ' match value.', 'chatbox-manager'));
																	
																echo $chatboxManagerForm->elementEnd('div');
							
																echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
	
																	$postMatch = isset($chatbox_manager_chatboxes['chatbox' . $chatboxID][$cpt . '_matchValue']) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID][$cpt . '_matchValue']) : array();
	
																	$options = array( 
																		'include' => esc_html__('Include','chatbox-manager'),
																		'exclude' => esc_html__('Exclude','chatbox-manager')
																	);
	
																	echo $chatboxManagerForm->select($value['id'].'[chatbox'.$chatboxID.']['.$cpt.'_matchValue]', FALSE, 'selectValue', $options, $postMatch, 'data-type="'.$cpt.'"');

																	echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Do you want to include or exclude this custom post type?','chatbox-manager'));
							
																echo $chatboxManagerForm->elementEnd('div');
							
																echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																	
															echo $chatboxManagerForm->elementEnd('div');
																
															echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box include ' . $cpt . 'cpt');
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																	
																	echo $chatboxManagerForm->label('Include', esc_html__( 'Include these items.', 'chatbox-manager'));
																	
																echo $chatboxManagerForm->elementEnd('div');
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
																	
																	$includeValues = isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['include_' . $cpt]) ? $this->sanitize_postID_function($chatbox_manager_chatboxes['chatbox' . $chatboxID]['include_' . $cpt]) : array('-1');
	
echo $chatboxManagerForm->ajaxSelect($value['id'].'[chatbox'.$chatboxID.'][include_'.$cpt.']', FALSE, 'cmAjaxSelect2', $includeValues, $cpt, 'include');
	
																	echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Type [All] to include all items.','chatbox-manager'));
							
																echo $chatboxManagerForm->elementEnd('div');
							
																echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																	
															echo $chatboxManagerForm->elementEnd('div');
	
															echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box exclude ' . $cpt . 'cpt');
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
															
																	echo $chatboxManagerForm->label('Exclude', esc_html__( 'Exclude these items.', 'chatbox-manager'));
																	
																echo $chatboxManagerForm->elementEnd('div');
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
																	
																	$excludeValues = isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['exclude_' . $cpt]) ? $this->sanitize_postID_function($chatbox_manager_chatboxes['chatbox' . $chatboxID]['exclude_' . $cpt]) : array('-1');
	
																	echo $chatboxManagerForm->ajaxSelect($value['id'].'[chatbox'.$chatboxID.'][exclude_'.$cpt.']', FALSE, 'cmAjaxSelect2', $excludeValues, $cpt, 'exclude');
	
																	echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Type [All] to exclude all items.','chatbox-manager'));
							
																echo $chatboxManagerForm->elementEnd('div');
							
																echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																	
															echo $chatboxManagerForm->elementEnd('div');
															
														echo $chatboxManagerForm->elementEnd('div');
	
													} 

													echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box MatchValueBox' );
						
														echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
						
															echo $chatboxManagerForm->element('strong', FALSE, FALSE, esc_html__('Custom taxonomies.','chatbox-manager'));
															echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Below each available Custom taxonomy.','chatbox-manager'));
						
														echo $chatboxManagerForm->elementEnd('div');
						
														echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																
													echo $chatboxManagerForm->elementEnd('div');

													foreach ( chatbox_manager_get_taxonomies_list() as $tax ) { 
	
														echo $chatboxManagerForm->elementStart('div', FALSE, 'MatchValueBox ' . $tax . 'Type');
	
															echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box ' . $tax . 'Cpt MatchValue');
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																	
																	echo $chatboxManagerForm->label(FALSE, ucfirst($tax) . esc_html__( ' match value.', 'chatbox-manager'));
																	
																echo $chatboxManagerForm->elementEnd('div');
							
																echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
	
																	$taxMatch = isset($chatbox_manager_chatboxes['chatbox' . $chatboxID][$tax . '_matchValue']) ? esc_attr($chatbox_manager_chatboxes['chatbox' . $chatboxID][$tax . '_matchValue']) : array();
	
																	$current = array( 
																		'include' => esc_html__('Include','chatbox-manager'),
																		'exclude' => esc_html__('Exclude','chatbox-manager')
																	);
	
																	echo $chatboxManagerForm->select($value['id'].'[chatbox'.$chatboxID.']['.$tax.'_matchValue]', FALSE, 'selectValue', $current, $taxMatch, 'data-type="'.$tax.'"');

																	echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Do you want to include or exclude this custom taxonomy?','chatbox-manager'));
							
																echo $chatboxManagerForm->elementEnd('div');
							
																echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																	
															echo $chatboxManagerForm->elementEnd('div');
																
															echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box include ' . $tax . 'cpt');
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																	
																	echo $chatboxManagerForm->label('Include', esc_html__( 'Include these items. ', 'chatbox-manager'));

																echo $chatboxManagerForm->elementEnd('div');
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
																	
																	$includeValues = isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['include_' . $tax]) ? $this->sanitize_postID_function($chatbox_manager_chatboxes['chatbox' . $chatboxID]['include_' . $tax]) : array('-1');
	
																	echo $chatboxManagerForm->ajaxSelect($value['id'].'[chatbox'.$chatboxID.'][include_'.$tax.']', FALSE, 'cmAjaxSelect2Tax', $includeValues, $tax, 'include');
	
																	echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Type [All] to include all items.','chatbox-manager'));
							
																echo $chatboxManagerForm->elementEnd('div');
							
																echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																	
															echo $chatboxManagerForm->elementEnd('div');
	
															echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box exclude ' . $tax . 'cpt');
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'input-left' );
																	
																	echo $chatboxManagerForm->label('Exclude', esc_html__( 'Exclude these items. ', 'chatbox-manager'));
																	
																echo $chatboxManagerForm->elementEnd('div');
																	
																echo $chatboxManagerForm->elementStart('div', FALSE, 'input-right' );
																	
																	$excludeValues = isset($chatbox_manager_chatboxes['chatbox' . $chatboxID]['exclude_' . $tax]) ? $this->sanitize_postID_function($chatbox_manager_chatboxes['chatbox' . $chatboxID]['exclude_' . $tax]) : array('-1');
	
																	echo $chatboxManagerForm->ajaxSelect($value['id'].'[chatbox'.$chatboxID.'][exclude_'.$tax.']', FALSE, 'cmAjaxSelect2Tax', $excludeValues, $tax, 'exclude');
	
																	echo $chatboxManagerForm->element('p', FALSE, FALSE, esc_html__('Type [All] to exclude all items.','chatbox-manager'));
							
																echo $chatboxManagerForm->elementEnd('div');
							
																echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);
																	
															echo $chatboxManagerForm->elementEnd('div');
																
														echo $chatboxManagerForm->elementEnd('div');
	
													} 
														
													echo $chatboxManagerForm->elementStart('div', FALSE, $plugin_slug . 'box deleteSlot' );
											
														echo $chatboxManagerForm->input('chatbox_manager_action', FALSE, 'chatbox_manager_update_chatbox button', 'submit', 'Save');
														echo $chatboxManagerForm->input('chatbox_manager_action', FALSE, 'chatbox_manager_delete_chatbox button', 'submit', 'Delete');

														echo $chatboxManagerForm->element('div', FALSE, 'clear', FALSE);

													echo $chatboxManagerForm->elementEnd('div');

												echo $chatboxManagerForm->elementEnd('div');
                                                    
											echo $chatboxManagerForm->elementEnd('div');
										
										}

                                        echo $chatboxManagerForm->elementEnd('div');

									echo $chatboxManagerForm->elementEnd('div');

								break;

								}
							
							endif;
						
						}
					
					}	
				
				endif;	
		
			}

		}
	
	}

}

?>