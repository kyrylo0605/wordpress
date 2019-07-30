<?php

/**
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if( !class_exists( 'bazaar_lite_admin_notice' ) ) {

	class bazaar_lite_admin_notice {
	
		/**
		 * Constructor
		 */
		 
		public function __construct( $fields = array() ) {

			if ( 
				!get_option( 'bazaar-lite-dismissed-notice') &&
				version_compare( PHP_VERSION, BAZAAR_LITE_MIN_PHP_VERSION, '>=' )
			) {

				add_action( 'admin_notices', array(&$this, 'admin_notice') );
				add_action( 'admin_head', array( $this, 'dismiss' ) );
			
			}

		}

		/**
		 * Dismiss notice.
		 */
		
		public function dismiss() {

			if ( isset( $_GET['bazaar-lite-dismiss'] ) && check_admin_referer( 'bazaar-lite-dismiss-action' ) ) {
		
				update_option( 'bazaar-lite-dismissed-notice', intval($_GET['bazaar-lite-dismiss']) );
				remove_action( 'admin_notices', array(&$this, 'admin_notice') );
				
			} 
		
		}

		/**
		 * Admin notice.
		 */
		 
		public function admin_notice() {
			
		?>
			
            <div class="update-nag notice bazaar_lite-notice">
            
            	<div class="bazaar_lite-noticedescription">
					
					<strong><?php esc_html_e( 'Upgrade to the premium version of Bazaar, to enable 600+ Google Fonts, unlimited sidebars, portfolio and much more.', 'bazaar-lite' ); ?></strong><br/>

					<?php 
					
						printf( 
							'<a href="%1$s" class="dismiss-notice">' . esc_html__( 'Dismiss this notice', 'bazaar-lite' ) . '</a>', 
							esc_url( wp_nonce_url( add_query_arg( 'bazaar-lite-dismiss', '1' ), 'bazaar-lite-dismiss-action'))
						);
					
					?>
                
                </div>
                
                <a target="_blank" href="<?php echo esc_url( 'https://www.themeinprogress.com/bazaar-free-ecommerce-wordpress-theme/?ref=2&campaign=bazaar-notice' ); ?>" class="button"><?php esc_html_e( 'Upgrade to Bazaar Premium', 'bazaar-lite' ); ?></a>
                
                <div class="clear"></div>

            </div>
		
		<?php
		
		}

	}

}

new bazaar_lite_admin_notice();

?>