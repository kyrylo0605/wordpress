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

if( !class_exists( 'chatbox_manager_admin_notice' ) ) {

	class chatbox_manager_admin_notice {
	
		/**
		 * Constructor
		 */
		 
		public function __construct( $fields = array() ) {

			if ( 
				!get_option( 'cm-dismissed-notice')
			) {

				add_action( 'admin_notices', array(&$this, 'admin_notice') );
				add_action( 'admin_head', array( $this, 'dismiss' ) );
			
			}

		}

		/**
		 * Dismiss notice.
		 */
		
		public function dismiss() {

			if ( isset( $_GET['cm-dismiss'] ) && check_admin_referer( 'cm-dismiss-action' ) ) {
		
				update_option( 'cm-dismissed-notice', intval($_GET['cm-dismiss']) );
				remove_action( 'admin_notices', array(&$this, 'admin_notice') );
				
			} 
		
		}

		/**
		 * Admin notice.
		 */
		 
		public function admin_notice() {
			
		?>
			
            <div class="notice notice-warning is-dismissible chatbox_manager_notice">
            
            	<p>
            
            		<strong>

						<?php
                        
                            esc_html_e( 'Upgrade to the premium version of Chatbox Manager plugin to enable unlimited chatboxes, shake button, dynamic values to include the current title and URL, sharing feature and much more.', 'chatbox-manager' ); 

                        ?>
                    
                    </strong>
                    
                    <p>
						<?php

                            printf( 
                                '<a href="%1$s" class="dismiss-notice">' . esc_html__( 'Dismiss this notice', 'cm' ) . '</a>', 
                                esc_url( wp_nonce_url( add_query_arg( 'cm-dismiss', '1' ), 'cm-dismiss-action'))
                            );
                            
                        ?>
                    
                    </p>
                    
            	</p>
                    
            	<p>
            		
                    <a target="_blank" href="<?php echo esc_url(CM_SALE_PAGE . 'cm-notice'); ?>" class="button button-primary"><?php esc_html_e( 'Upgrade to Chatbox Manager premium', 'chatbox-manager' ); ?></a>
            	</p>

            </div>
		
		<?php
		
		}

	}

}

new chatbox_manager_admin_notice();

?>