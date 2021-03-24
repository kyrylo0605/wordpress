<?php
/**
 * Main class
 *
 * @author  YITH
 * @package YITH WooCommerce Added to Cart Popup
 * @version 1.0.0
 */

defined( 'YITH_WACP' ) || exit; // Exit if accessed directly.

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
		 * @since 1.0.0
		 * @var YITH_WACP
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $version = YITH_WACP_VERSION;


		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return YITH_WACP
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {

			// Load Plugin Framework.
			add_action( 'after_setup_theme', array( $this, 'plugin_fw_loader' ), 1 );

			// Class admin.
			if ( $this->is_admin() ) {

				// Require admin class.
				require_once 'class.yith-wacp-admin.php';

				YITH_WACP_Admin();
			} elseif ( $this->load_frontend() ) {
				// Require frontend class.
				require_once 'class.yith-wacp-frontend.php';

				YITH_WACP_Frontend();
			}

			add_action( 'init', array( $this, 'update_old_options' ), 1 );
		}

		/**
		 * Load Plugin Framework
		 *
		 * @since  1.0
		 * @access public
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 * @return void
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Check if is admin
		 *
		 * @since  1.0.6
		 * @access public
		 * @author Francesco Licandro
		 * @return boolean
		 */
		public function is_admin() {
			$is_ajax          = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
			$context_check    = isset( $_REQUEST['context'] ) && 'frontend' === sanitize_text_field( wp_unslash( $_REQUEST['context'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$actions_to_check = apply_filters( 'yith_wacp_is_admin_action_check', array( 'ivpa_add_to_cart_callback' ) );
			$action_check     = isset( $_REQUEST['action'] ) && in_array( sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ), $actions_to_check, true );  // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			return is_admin() && ! ( $is_ajax && ( $context_check || $action_check ) );
		}

		/**
		 * Check if load or not frontend class
		 *
		 * @since  1.2.0
		 * @author Francesco Licandro
		 * @return boolean
		 */
		public function load_frontend() {
			$is_one_click = isset( $_REQUEST['_yith_wocc_one_click'] ) && 'is_one_click' === $_REQUEST['_yith_wocc_one_click']; // phpcs:ignore
			$load         = ( ! wp_is_mobile() || get_option( 'yith-wacp-enable-mobile' ) !== 'no' ) && ! $is_one_click;
			return apply_filters( 'yith_wacp_check_load_frontend', $load );
		}

		/**
		 * Update old option for new panel in version 1.5
		 *
		 * @since  1.5.0
		 * @author Francesco Licandro
		 * @return void
		 */
		public function update_old_options() {
			$last_options_updated = get_option( 'yith-wacp-option-version', '1.0.0' );
			if ( version_compare( $last_options_updated, '1.5', '<' ) ) {

				$button_color       = get_option( 'yith-wacp-button-background' );
				$button_color_hover = get_option( 'yith-wacp-button-background-hover' );

				if ( $button_color_hover ) {
					update_option(
						'yith-wacp-button-background',
						array(
							'normal' => $button_color,
							'hover'  => $button_color_hover,
						)
					);

					delete_option( 'yith-wacp-button-background-hover' );
				}

				$button_text_color       = get_option( 'yith-wacp-button-text' );
				$button_text_color_hover = get_option( 'yith-wacp-button-text-hover' );

				if ( $button_text_color_hover ) {
					update_option(
						'yith-wacp-button-text',
						array(
							'normal' => $button_text_color,
							'hover'  => $button_text_color_hover,
						)
					);

					delete_option( 'yith-wacp-button-text-hover' );
				}

				update_option( 'yith-wacp-option-version', '1.5' );
			}
		}
	}
}

/**
 * Unique access to instance of YITH_WACP class
 *
 * @since 1.0.0
 * @return YITH_WACP
 */
function YITH_WACP() { // phpcs:ignore
	return YITH_WACP::get_instance();
}
