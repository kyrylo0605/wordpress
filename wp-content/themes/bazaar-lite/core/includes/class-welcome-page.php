<?php

/**
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if( !class_exists( 'bazaar_lite_welcome' ) ) {

	class bazaar_lite_welcome {

		public function __construct( $fields = array() ) {
	
			$this->theme_fields = $fields;

			add_action ('admin_init' , array( &$this, 'admin_scripts' ) );
			add_action('admin_menu',array( &$this, 'welcome_page_menu'));

		}

		public function admin_scripts() {
	
			global $pagenow;

			$file_dir = get_template_directory_uri() . '/core/admin/assets/';

			if ( $pagenow == 'themes.php' && isset($_GET['page']) && $_GET['page'] == 'bazaar-lite-welcome-page') {
				
				wp_enqueue_style (
					'bazaar-lite-welcome-page-style',
					$file_dir . 'css/welcome-page.css',
					array(), '1.0.0'
				);
				 
			}

		}
		
        public function welcome_page_menu() {
            
			add_theme_page(
				esc_html__('About Bazaar Lite', 'bazaar-lite'),
				esc_html__('About Bazaar Lite', 'bazaar-lite'), 
				'edit_theme_options', 
				'bazaar-lite-welcome-page', 
				array( &$this, 'welcome_page' )
			);
		
		}
		
        public function check_installed_plugin($slug, $filename) {
			return file_exists( ABSPATH . 'wp-content/plugins/' . $slug . '/' . $filename . '.php' ) ? true : false;
		}

		private function call_plugin_api( $slug ) {
			
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	
			$call_api = get_transient( 'bazaar_lite_plugin_info_' . $slug );
	
			if ( false === $call_api ) {
				$call_api = plugins_api(
					'plugin_information',
					array(
						'slug'   => $slug,
						'fields' => array(
							'downloaded'        => false,
							'rating'            => false,
							'description'       => false,
							'short_description' => true,
							'donate_link'       => false,
							'tags'              => false,
							'sections'          => true,
							'homepage'          => true,
							'added'             => false,
							'last_updated'      => false,
							'compatibility'     => false,
							'tested'            => false,
							'requires'          => false,
							'downloadlink'      => false,
							'icons'             => true,
							'banners'           => true,
						),
					)
				);
				set_transient( 'bazaar_lite_plugin_info_' . $slug, $call_api, 30 * MINUTE_IN_SECONDS );
			}
	
			return $call_api;
		}

        public function welcome_page() {

            $tabs = array(
                'getting_started' => esc_html__('Getting Started', 'bazaar-lite'),
                'recommended_plugins' => esc_html__('Recommended Plugins', 'bazaar-lite'),
                'free_pro' => esc_html__('Free VS Pro', 'bazaar-lite'),
                'support' => esc_html__('Support', 'bazaar-lite'),
            );
			
        ?>
            
            <div class="wrap about-wrap access-wrap">
                <div class="abt-promo-wrap clearfix">
                    <div class="abt-theme-wrap">
                        
                        <h1><?php printf(esc_html__('Welcome to %1$s - Version %2$s', 'bazaar-lite'), 'Bazaar Lite', '1.7.3'); ?></h1>
                        
                        <div class="about-text">
							<?php echo esc_html__('Bazaar Lite is our new creative and minimal ecommerce WordPress theme. Perfect to manage a shop using WooCommerce plugin or a blog, it offers a fully responsive layout to be displayed on every kind of device. With the latest version, you can display an optional slideshow on homepage.', 'bazaar-lite'); ?>
                        </div>
                    
                    </div>
                
                </div>

                <div class="nav-tab-wrapper clearfix">
                    
					<?php 

						$tabHTML = '';

						foreach ($tabs as $id => $label) :

							$target = '';
							$nav_class = 'nav-tab';
							$section = isset($_GET['section']) ? $_GET['section'] : 'getting_started';
							
							if ($id == $section) {
								$nav_class .= ' nav-tab-active';
							}

							switch ($id) {
								
								case 'support':
									$target = 'target="_blank"';
									$url = esc_url('https://wordpress.org/support/theme/bazaar-lite/');
								break;

								case 'getting_started':
									$url = esc_url(admin_url('themes.php?page=bazaar-lite-welcome-page'));
								break;

								default:
									$url = esc_url(admin_url('themes.php?page=bazaar-lite-welcome-page&section=' . $id));
								break;

							}

							$tabHTML .= '<a ';
							$tabHTML .= $target;
							$tabHTML .= ' href="' . $url. '"';
							$tabHTML .= ' class="' . esc_attr($nav_class). '"';
							$tabHTML .= '>';
							$tabHTML .= esc_html($label);
							$tabHTML .= '</a>';
					
						endforeach;
						
						echo $tabHTML;
						
					?>
                    
                </div>

                <div class="welcome-section-wrapper">
                    
                    <div class="welcome-section getting_started clearfix">

                    	<?php
						
							$section = isset($_GET['section']) ? $_GET['section'] : 'getting_started';
							
							switch ($section) {
								
								case 'free_pro':
									$this->free_pro();
								break;

								case 'recommended_plugins':
									$this->recommended_plugins();
								break;

								case 'getting_started':
								default:
									$this->getting_started();
								break;

							}
						
						?>

                    </div>
                    
                </div>
                
            </div>
        
		<?php
		
		}

        public function getting_started() {

		?>
    
			<div class="getting-started-top-wrap clearfix">
                        
				<div class="theme-steps-list">

					<div class="theme-steps">
                                
						<h3><?php echo esc_html__('Step 1 - Ensure Your Page Home Page is set Your latest posts', 'bazaar-lite'); ?></h3>
						<ol>
							<li><?php echo esc_html__('Go to Settings > Reading > General settings > Your homepage displays', 'bazaar-lite'); ?></li>
							<li><?php echo esc_html__('Set "Your homepage displays" to Your latest posts', 'bazaar-lite'); ?></li>
							<li><?php echo esc_html__('Save changes', 'bazaar-lite'); ?></li>
						</ol>
						<a class="button button-primary" target="_blank" href="<?php echo esc_url(admin_url('options-reading.php')); ?>"><?php echo esc_html__('Assign Static Page', 'bazaar-lite'); ?></a>
					</div>
                        
					<div class="theme-steps">
						<h3><?php echo esc_html__('Step 2 - Customizer Options Panel', 'bazaar-lite'); ?></h3>
						<p><?php echo esc_html__('Now go to Customizer Page. Using the WordPress Customizer you can easily set up the home page and customize the theme.', 'bazaar-lite'); ?></p>
						<a class="button button-primary" href="<?php echo esc_url(admin_url('customize.php')); ?>"><?php echo esc_html__('Go to Customizer Panels', 'bazaar-lite'); ?></a>
					</div>

				</div>
                            
			</div>

        <?php
		
		}
		
		public function recommended_plugins() {

			$plugins = array(
	
				array(
					'filename'	=> 'init',
					'slug'      => 'internal-linking-of-related-contents',
				),
				array(
					'filename'	=> 'init',
					'slug'      => 'content-snippet-manager',
				),
				array(
					'filename'	=> 'init',
					'slug'      => 'wa-chatbox-manager',
				),
				array(
					'filename'	=> 'init',
					'slug'      => 'custom-thank-you-page',
				),
				array(
					'filename'	=> 'init',
					'slug'      => 'wip-custom-login',
				),
				array(
					'filename'	=> 'init',
					'slug'      => 'wip-woocarousel-lite',
				),
	
			);
			
		?>

			<div class="required-plugin-top-wrap clearfix">
                        
				<div class="required-plugin-list">

				<?php
                    
                    foreach ( $plugins as $plugin ) {
                        
                        $slug = $plugin['slug'];
                        $filename = $plugin['filename'];
        
                        $plugin_info = $this->call_plugin_api( $slug );
                        $plugin_desc = $plugin_info->short_description;
                        $plugin_img  = ( !isset($plugin_info->icons['1x']) ) ? $plugin_info->icons['default'] : $plugin_info->icons['1x'];
                        $plugin_banner  = $plugin_info->banners['low'];

                ?>

					<div class="required-plugin">
                    
                        <div class="required-plugin-head">
                        
							<img class="plugin-banner" src="<?php echo $plugin_banner;?>">
                            
                        </div>
                        
                        <div class="required-plugin-desc">
                            
                            <h3><?php echo $plugin_info->name; ?></h3>
							<?php echo $plugin_info->short_description; ?>
                            
						</div>
                           
                        <div class="required-plugin-footer">

							<span>
								
								<?php 
									echo esc_html__('v', 'bazaar-lite');
									echo $plugin_info->version;
									echo esc_html__(' by ', 'bazaar-lite');
									echo html_entity_decode( wp_strip_all_tags( $plugin_info->author ) );
								?>
                                
							</span>

							<?php if ( $this->check_installed_plugin( $slug, $filename ) ) : ?>
                                    
                            	<button type="button" class="button button-disabled" disabled="disabled">
                            		<?php esc_html_e( 'Installed', 'bazaar-lite' ); ?>
                            	</button>
                                
                            <?php else : ?>
                    
                            	<a class="install-now button-primary" href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin='. $slug ), 'install-plugin_'. $slug ) ); ?>" >
                            		<?php esc_html_e( 'Install Now', 'bazaar-lite' ); ?>
                            	</a>							
                                
                            <?php endif; ?>
                            
						</div>
                           
					</div>

				<?php
				
					}
			
				?>
                
				</div>
                            
			</div>
        
        <?php
		
		}
		
		public function free_pro() {
		
		?>
    
            <table class="card table free-pro" cellspacing="0" cellpadding="0" >
                
                <tbody class="table-body">
                    
                    <tr class="table-head">
                        <th class="large"></th>
                        <th class="indicator"><?php echo esc_html__('Bazaar Lite', 'bazaar-lite'); ?></th>
                        <th class="indicator"><?php echo esc_html__('Bazaar Pro', 'bazaar-lite'); ?></th>
                    </tr>
                    
                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__('WooCommerce Support', 'bazaar-lite'); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__('Masonry layout', 'bazaar-lite'); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__('Responsive Layout', 'bazaar-lite'); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__('Logo upload', 'bazaar-lite'); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>
                    
                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__('Social icons', 'bazaar-lite'); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__('Copyright text', 'bazaar-lite'); ?></h4>
                                <div class="feature-inline-row">
                                    <span class="info-icon dashicon dashicons dashicons-info"></span>
                                    <span class="feature-description">
										<?php echo esc_html__('Remove the copyright text from the Footer.', 'bazaar-lite'); ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    
                    </tr>
                    
                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__('Custom colors', 'bazaar-lite'); ?></h4>
                                <div class="feature-inline-row">
                                    <span class="info-icon dashicon dashicons dashicons-info"></span>
                                    <span class="feature-description">
										<?php echo esc_html__('Choose a color for the links, the backgrounds, the slogan and so on.', 'bazaar-lite'); ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    
                    </tr>
                    
                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__('Portfolio section', 'bazaar-lite'); ?></h4>
                                <div class="feature-inline-row">
                                    <span class="info-icon dashicon dashicons dashicons-info"></span>
                                    <span class="feature-description">
										<?php echo esc_html__('You can add and display your works and give a modern layout, thanks to the masonry layout.', 'bazaar-lite'); ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__('Galleries', 'bazaar-lite'); ?></h4>
                                <div class="feature-inline-row">
                                    <span class="info-icon dashicon dashicons dashicons-info"></span>
                                    <span class="feature-description">
										<?php echo esc_html__('For the gallery posts, pages and portfolio items, you can display a beautiful image slideshow.', 'bazaar-lite'); ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__('Unlimited sidebar areas', 'bazaar-lite'); ?></h4>
                                <div class="feature-inline-row">
                                    <span class="info-icon dashicon dashicons dashicons-info"></span>
                                    <span class="feature-description">
										<?php echo esc_html__('On Bazaar theme you can generate an unique sidebar area for each post, page, WooCommerce product and portfolio item.', 'bazaar-lite'); ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__('Google fonts', 'bazaar-lite'); ?></h4>
                                <div class="feature-inline-row">
                                    <span class="info-icon dashicon dashicons dashicons-info"></span>
                                    <span class="feature-description">
										<?php echo esc_html__('You can choose and use over 600 different fonts, for the logo, the menu and the titles.', 'bazaar-lite'); ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    
                    </tr>

                    <tr class="upsell-row">
                        
                        <td></td>
                        <td></td>
                        <td><a  target="_blank" href="<?php echo esc_url( 'https://www.themeinprogress.com/bazaar-free-ecommerce-wordpress-theme/?ref=2&campaign=bazaar-welcome-page' );?>" class="button button-primary"><?php echo esc_html__('Get Bazaar Pro Now', 'bazaar-lite'); ?></a></td>
                    </tr>
                    
                </tbody>
                
            </table>

        <?php
		
		}
		
	}

}

new bazaar_lite_welcome();

?>