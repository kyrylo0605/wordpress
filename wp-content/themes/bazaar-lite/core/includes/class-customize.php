<?php

/**
 * Contains methods for customizing the theme customization screen.
 * @link http://codex.wordpress.org/Theme_Customization_API
 * 
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class bazaarlite_customize {

	public $theme_fields;

	public function __construct( $fields = array() ) {

		$this->theme_fields = $fields;

		add_action ('admin_init' , array( &$this, 'admin_scripts' ) );
		add_action ('customize_register' , array( &$this, 'customize_panel' ) );
		add_action ('customize_controls_enqueue_scripts' , array( &$this, 'customize_scripts' ) );

	}

	public function admin_scripts() {
	
		global $wp_version, $pagenow;

		$file_dir = get_template_directory_uri()."/core/admin/assets/";
			
		if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' || $pagenow == 'edit.php' ) {
			wp_enqueue_style ( 'bazaar-lite-style', $file_dir.'css/theme.css' ); 
			wp_enqueue_script( 'bazaar-lite-script', $file_dir.'js/theme.js',array('jquery'),'',TRUE ); 
			wp_enqueue_script( "jquery-ui-core");
			wp_enqueue_script( "jquery-ui-tabs");
		}
			
	}

    public function customize_scripts() {

		wp_enqueue_style ( 
			'bazaaar-lite-customizer', 
			get_template_directory_uri() . '/core/admin/assets/css/customize.css', 
			array(), 
			''
		);
	  
   }
	
   public function customize_panel ( $wp_customize ) {

		global $wp_version;

		$theme_panel = $this->theme_fields ;

		foreach ( $theme_panel as $element ) {
			
			switch ( $element['type'] ) {
					
				case 'panel' :
				
					$wp_customize->add_panel( $element['id'], array(
					
						'title' => $element['title'],
						'priority' => $element['priority'],
						'description' => $element['description'],
						'capability'     => 'edit_theme_options',
					
					) );
			 
				break;
				
				case 'section' :
						
					$wp_customize->add_section( $element['id'], array(
					
						'title' => $element['title'],
						'panel' => $element['panel'],
						'priority' => $element['priority'],
						'capability'     => 'edit_theme_options',
					
					) );
					
				break;

				case 'text' :
							
					$wp_customize->add_setting( $element['id'], array(
					
						'sanitize_callback' => 'sanitize_text_field',
						'default' => $element['std'],
						'capability'     => 'edit_theme_options',

					) );
											 
					$wp_customize->add_control( $element['id'] , array(
					
						'type' => $element['type'],
						'section' => $element['section'],
						'label' => $element['label'],
						'description' => $element['description'],
									
					) );
							
				break;

				case 'upload' :
							
					$wp_customize->add_setting( $element['id'], array(

						'default' => $element['std'],
						'capability' => 'edit_theme_options',
						'sanitize_callback' => 'esc_url_raw'

					) );

					$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, $element['id'], array(
					
						'label' => $element['label'],
						'mime_type' => 'image',
						'description' => $element['description'],
						'section' => $element['section'],
						'settings' => $element['id'],
					
					)));

				break;

				case 'url' :
							
					$wp_customize->add_setting( $element['id'], array(
					
						'sanitize_callback' => 'esc_url_raw',
						'default' => $element['std'],
						'capability'     => 'edit_theme_options',

					) );
											 
					$wp_customize->add_control( $element['id'] , array(
					
						'type' => $element['type'],
						'section' => $element['section'],
						'label' => $element['label'],
						'description' => $element['description'],
									
					) );
							
				break;

				case 'button' :
							
					$wp_customize->add_setting( $element['id'], array(
					
						'sanitize_callback' => array( &$this, 'customize_button_sanize' ),
						'default' => $element['std'],
						'capability'     => 'edit_theme_options',

					) );
											 
					$wp_customize->add_control( $element['id'] , array(
					
						'type' => 'url',
						'section' => $element['section'],
						'label' => $element['label'],
						'description' => $element['description'],
									
					) );
							
				break;

				case 'textarea' :
							
					$wp_customize->add_setting( $element['id'], array(
					
						'sanitize_callback' => 'esc_textarea',
						'default' => $element['std'],
						'capability'     => 'edit_theme_options',

					) );
											 
											 
					$wp_customize->add_control( $element['id'] , array(
					
						'type' => $element['type'],
						'section' => $element['section'],
						'label' => $element['label'],
						'description' => $element['description'],
									
					) );
							
				break;

				case 'custom_css' :
							
					$wp_customize->add_setting( $element['id'], array(
					
						'default' => $element['std'],
						'sanitize_callback'    => 'wp_filter_nohtml_kses',
						'sanitize_js_callback' => 'wp_filter_nohtml_kses',
						'capability'     => 'edit_theme_options',

					) );
											 
											 
					$wp_customize->add_control( $element['id'] , array(
					
						'type' => 'textarea',
						'section' => $element['section'],
						'label' => $element['label'],
						'description' => $element['description'],
									
					) );
							
				break;

				case 'select' :
							
					$wp_customize->add_setting( $element['id'], array(

						'sanitize_callback' => array( &$this, 'customize_select_sanize' ),
						'default' => $element['std'],
						'capability'     => 'edit_theme_options',

					) );
											 

					$wp_customize->add_control( $element['id'] , array(
						
						'type' => $element['type'],
						'section' => $element['section'],
						'label' => $element['label'],
						'description' => $element['description'],
						'choices'  => $element['options'],
									
					) );
							
				break;

				case 'bazaar-lite-customize-info' :

					$wp_customize->add_section( $element['id'], array(
					
						'title' => $element['title'],
						'priority' => $element['priority'],
						'capability' => 'edit_theme_options',

					) );

					$wp_customize->add_setting(  $element['id'], array(
						'sanitize_callback' => 'esc_url_raw'
					) );
					 
					$wp_customize->add_control( new BazaarLite_Customize_Info_Control( $wp_customize,  $element['id'] , array(
						'section' => $element['section'],
					) ) );		
										
				break;

			}
			
		}

		if ( $wp_version >= 4.5 ) :
		
			$wp_customize->remove_section( 'header_section');
		
		endif;

		if ( bazaarlite_is_woocommerce_active() ) :
			
			$wp_customize->remove_control( 'woocommerce_catalog_rows');
			$wp_customize->remove_control( 'woocommerce_catalog_columns');
				
		endif;

		if ( !bazaarlite_is_woocommerce_active() ) :
		
			$wp_customize->remove_control( 'wip_woocommerce_header_cart');
			$wp_customize->remove_control( 'wip_woocommerce_category_layout');
			
		endif;
		
   }

	public function customize_select_sanize ( $value, $setting ) {
		
		$theme_panel = $this->theme_fields ;

		foreach ( $theme_panel as $element ) {
			
			if ( $element['id'] == $setting->id ) :

				if ( array_key_exists($value, $element['options'] ) ) : 
						
					return $value;

				endif;

			endif;
			
		}
		
	}

	public function customize_button_sanize ( $value, $setting ) {
		
		$sanize = array (
		
			'wip_footer_email_button' => 'mailto:',
			'wip_footer_skype_button' => 'skype:',
			'wip_footer_whatsapp_button' => 'tel:',
		
		);

		if (!isset($value) || $value == '' || $value == $sanize[$setting->id]) {
	
			return '';

		} elseif (!strstr($value, $sanize[$setting->id])) {
	
			return $sanize[$setting->id] . $value;
	
		} else {
	
			return esc_url_raw($value, array('mailto', 'skype', 'tel'));
	
		}

	}

}

if ( class_exists( 'WP_Customize_Control' ) ) {

	class BazaarLite_Customize_Info_Control extends WP_Customize_Control {

		public $type = "bazaarlite-customize-info";

		public function render_content() { ?>

            <div class="inside">

                <h2><?php esc_html_e('Premium features','bazaar-lite');?></h2>
                <p><?php esc_html_e('Upgrade to the premium version of Bazaar, to enable all features.','bazaar-lite');?></p>

                <ul class="features-list">

                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('14 blog layouts','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('Remove the copyright text from the footer','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('Choose custom colors','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('Portfolio section','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('Display a slideshow for gallery posts','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('Testimonials section','bazaar-lite');?></li>
                    <li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('Unlimited sidebars','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('Custom shortcodes','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('Shortcodes generator','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('Backup section','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('Sample data','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('Unlimited website usage','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('60% discount on subscription renewal','bazaar-lite');?></li>
                	<li><span class="dashicon dashicons dashicons-yes"></span><?php esc_html_e('7 days money back guarantee','bazaar-lite');?></li>

                </ul>

                <ul>
                
                    <li><a class="button demo-button" href="<?php echo esc_url( 'https://demo.themeinprogress.com/?theme=Bazaar' ); ?>" title="<?php esc_attr_e('View live demo','bazaar-lite');?>" target="_blank"><?php esc_html_e('View live demo','bazaar-lite');?></a></li>
                    <li><a class="button purchase-button" href="<?php echo esc_url( 'https://www.themeinprogress.com/bazaar-free-ecommerce-wordpress-theme/?ref=2&campaign=bazaar-panel' ); ?>" title="<?php esc_attr_e('Upgrade to Bazaar premium','bazaar-lite');?>" target="_blank"><?php esc_html_e('Upgrade to Bazaar premium','bazaar-lite');?></a></li>
                
                </ul>

            </div>
            
            <div class="inside">

                <h2><?php esc_html_e('Become a supporter','bazaar-lite');?></h2> 

                <p><?php esc_html_e("If you like this theme and support, I'd appreciate any of the following:","bazaar-lite");?></p>

                <ul>
                
                    <li><a class="button" href="<?php echo esc_url( 'https://wordpress.org/support/view/theme-reviews/'.get_stylesheet().'#postform' ); ?>" title="<?php esc_attr_e('Rate this Theme','bazaar-lite');?>" target="_blank"><?php esc_html_e('Rate this Theme','bazaar-lite');?></a></li>
                    <li><a class="button" href="<?php echo esc_url( 'https://www.themeinprogress.com/reserved-area/' ); ?>" title="<?php esc_attr_e('Subscribe our newsletter','bazaar-lite');?>" target="_blank"><?php esc_html_e('Subscribe our newsletter','bazaar-lite');?></a></li>
                
                </ul>
    
            </div>
    
		<?php

		}
	
	}

}

?>