<?php
/**
 * Plugin Name: YITH WooCommerce Added to Cart Popup
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-added-to-cart-popup/
 * Description: The <code><strong>YITH WooCommerce Added to Cart Popup</strong></code> plugin allow you to display a popup cart with suggested products and cart actions after an "add to cart" action. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>.
 * Version: 1.6.1
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Text Domain: yith-woocommerce-added-to-cart-popup
 * Domain Path: /languages/
 * WC requires at least: 4.5
 * WC tested up to: 5.3
 *
 * @author  YITH
 * @package YITH WooCommerce Added to Cart Popup
 * @version 1.6.1
 */
/**  Copyright 2015-2021  Your Inspiration Solutions (email : plugins@yithemes.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

function yith_wacp_free_install_woocommerce_admin_notice() {
	?>
	<div class="error">
		<p><?php esc_html_e( 'YITH WooCommerce Added to Cart Popup is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-added-to-cart-popup' ); ?></p>
	</div>
	<?php
}


function yith_wacp_install_free_admin_notice() {
	?>
	<div class="error">
		<p><?php esc_html_e( 'You can\'t activate the free version of YITH WooCommerce Added to Cart Popup while you are using the premium one.', 'yith-woocommerce-added-to-cart-popup' ); ?></p>
	</div>
	<?php
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );


if ( ! defined( 'YITH_WACP_VERSION' ) ) {
	define( 'YITH_WACP_VERSION', '1.6.1' );
}

if ( ! defined( 'YITH_WACP_FREE_INIT' ) ) {
	define( 'YITH_WACP_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_WACP_INIT' ) ) {
	define( 'YITH_WACP_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YITH_WACP' ) ) {
	define( 'YITH_WACP', true );
}

if ( ! defined( 'YITH_WACP_FILE' ) ) {
	define( 'YITH_WACP_FILE', __FILE__ );
}

if ( ! defined( 'YITH_WACP_URL' ) ) {
	define( 'YITH_WACP_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'YITH_WACP_DIR' ) ) {
	define( 'YITH_WACP_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YITH_WACP_TEMPLATE_PATH' ) ) {
	define( 'YITH_WACP_TEMPLATE_PATH', YITH_WACP_DIR . 'templates' );
}

if ( ! defined( 'YITH_WACP_ASSETS_URL' ) ) {
	define( 'YITH_WACP_ASSETS_URL', YITH_WACP_URL . 'assets' );
}

if ( ! defined( 'YITH_WACP_SLUG' ) ) {
	define( 'YITH_WACP_SLUG', 'yith-woocommerce-added-to-cart-popup' );
}

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_WACP_DIR . 'plugin-fw/init.php' ) ) {
	require_once YITH_WACP_DIR . 'plugin-fw/init.php';
}
yit_maybe_plugin_fw_loader( YITH_WACP_DIR );

function yith_wacp_free_init() {

	load_plugin_textdomain( 'yith-woocommerce-added-to-cart-popup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	// Load required classes and functions.
	require_once 'includes/class.yith-wacp.php';
	// Let's start the game!
	YITH_WACP();
}

add_action( 'yith_wacp_free_init', 'yith_wacp_free_init' );


function yith_wacp_free_install() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'yith_wacp_free_install_woocommerce_admin_notice' );
	} elseif ( defined( 'YITH_WACP_PREMIUM' ) ) {
		add_action( 'admin_notices', 'yith_wacp_install_free_admin_notice' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	} else {
		do_action( 'yith_wacp_free_init' );
	}
}

add_action( 'plugins_loaded', 'yith_wacp_free_install', 11 );