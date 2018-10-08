<?php
/**
 * Main class
 *
 * @author YITH
 * @package YITH WooCommerce Added to Cart Popup
 * @version 1.0.0
 */


if ( ! defined( 'YITH_WACP' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WACP' ) ) {
	/**
	 * YITH WooCommerce Added to Cart Popup
	 *
	 * @since 1.0.0
	 */
	class YITH_WACP {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WACP
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WACP_VERSION;


		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WACP
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @return mixed YITH_WACP_Admin | YITH_WACP_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {

			// Load Plugin Framework
			add_action( 'after_setup_theme', array( $this, 'plugin_fw_loader' ), 1 );

			// Class admin
			if ( $this->is_admin() ) {

				// require admin class
				require_once( 'class.yith-wacp-admin.php' );
				
				YITH_WACP_Admin();
			}
			elseif( $this->load_frontend() ) {
				// require frontend class
				require_once( 'class.yith-wacp-frontend.php' );

				YITH_WACP_Frontend();
			}

		}

		/**
		 * Load Plugin Framework
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {

			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if( ! empty( $plugin_fw_data ) ){
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once( $plugin_fw_file );
				}
			}
		}

		/**
		 * Check if is admin
		 *
		 * @since 1.0.6
		 * @access public
		 * @author Francesco Licandro
		 * @return boolean
		 */
		public function is_admin(){
            $is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
            $context_check = isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'frontend';
            $actions_to_check = apply_filters( 'yith_wacp_is_admin_action_check', array( 'ivpa_add_to_cart_callback' ) );
            $action_check = isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], $actions_to_check );

			return is_admin() && ! ( $is_ajax && ( $context_check || $action_check ) );
		}

		/**
		 * Check if load or not frontend class
		 *
		 * @since 1.2.0
		 * @author Francesco Licandro
		 * @return boolean
		 */
		public function load_frontend(){
			$is_one_click = isset( $_REQUEST['_yith_wocc_one_click'] ) && $_REQUEST['_yith_wocc_one_click'] == 'is_one_click';
			$load = ( ! wp_is_mobile() || get_option( 'yith-wacp-enable-mobile' ) != 'no' ) && ! $is_one_click;
			return apply_filters( 'yith_wacp_check_load_frontend', $load );
		}
	}
}

/**
 * Unique access to instance of YITH_WACP class
 *
 * @return \YITH_WACP
 * @since 1.0.0
 */
function YITH_WACP(){
	return YITH_WACP::get_instance();
}