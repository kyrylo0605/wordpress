<?php

/*

Plugin Name: Chatbox Manager
Plugin URI: https://www.themeinprogress.com/chatbox-manager-pro
Description: Chatbox Manager allow you to display multiple WhatsApp buttons on your website
Version: 1.0.9
Text Domain: chatbox-manager
Author: ThemeinProgress
Author URI: https://www.themeinprogress.com
License: GPL2
Domain Path: /languages/

Copyright 2021  ThemeinProgress  (email : support@wpinprogress.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

define( 'CM_VERSION', '1.0.9' );
define( 'CM_DEMO_PAGE', 'https://demo.themeinprogress.com/chatbox-manager-pro');
define( 'CM_SALE_PAGE', 'https://www.themeinprogress.com/chatbox-manager-pro/?ref=2&campaign=');
define( 'CM_ITEM_SLUG', 'chatbox_manager');

if( !class_exists( 'chatbox_manager_init' ) ) {

	class chatbox_manager_init {

		/**
		* Constructor
		*/

		public function __construct() {

			add_action('admin_init', array(&$this, 'disable_plugins') );
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links' ), 10, 2 );
			add_action('plugins_loaded', array(&$this,'plugin_setup'));
			add_action('wp_enqueue_scripts', array(&$this,'site_scripts') );

		}

		/**
		* Disable free plugin
		*/

		public function disable_plugins() {

			if (is_plugin_active('chatbox-manager-pro/init.php'))
				deactivate_plugins('chatbox-manager-pro/init.php');

		}

		/**
		* Plugin settings link
		*/

		public function plugin_action_links( $links ) {

			$links[] = '<a href="'. esc_url(get_admin_url(null, 'admin.php?page=chatbox_manager_panel') ) .'">' . esc_html__('Settings','chatbox-manager') . '</a>';
			$links[] = '<a target="_blank" href="'. esc_url(CM_SALE_PAGE . 'action_link') .'">' . esc_html__('Upgrade to PRO','chatbox-manager') . '</a>';
			return $links;

		}

		/**
		* Site scripts
		*/

		public function site_scripts() {

			wp_enqueue_style ( 'chatbox_manager_style', plugins_url('/assets/css/style.css', __FILE__ ), array(), null );

		}

		/**
		* Plugin setup
		*/

		public function plugin_setup() {

			load_plugin_textdomain( 'chatbox-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

			require_once dirname(__FILE__) . '/core/includes/class-form.php';
			require_once dirname(__FILE__) . '/core/includes/class-panel.php';
			require_once dirname(__FILE__) . '/core/includes/class-chatboxes.php';
			require_once dirname(__FILE__) . '/core/includes/class-notice.php';
			require_once dirname(__FILE__) . '/core/functions/functions.php';
			require_once dirname(__FILE__) . '/core/shortcode/button.php';

			new chatbox_manager_chatboxes();

			if ( is_admin() == 1 )
				require_once dirname(__FILE__) . '/core/admin/panel.php';

		}

	}

	new chatbox_manager_init();

}

?>
