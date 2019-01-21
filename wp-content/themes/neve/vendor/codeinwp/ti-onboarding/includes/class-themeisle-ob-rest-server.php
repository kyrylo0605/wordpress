<?php
/**
 * Onboarding Rest Endpoints Handler.
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      12/07/2018
 *
 * @package         themeisle-onboarding
 * @soundtrack      Caterpillar (feat. Eminem, King Green) - Royce da 5'9"
 */

/**
 * Class Themeisle_OB_Rest_Server
 *
 * @package themeisle-onboarding
 */
class Themeisle_OB_Rest_Server {

	/**
	 * Front Page Id
	 * @var
	 */
	private $frontpage_id;

	/**
	 * The theme support contents.
	 * @var
	 */
	private $theme_support = array();

	/**
	 * The array that passes all template data to the app.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * @var bool
	 */
	private $valid_lic = false;

	/**
	 * Initialize the rest functionality.
	 */
	public function init() {
		$this->setup_props();
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	/**
	 * Setup class properties.
	 */
	public function setup_props() {
		$theme_support = get_theme_support( 'themeisle-demo-import' );

		if ( empty( $theme_support[0] ) || ! is_array( $theme_support[0] ) ) {
			return;
		}

		$this->theme_support = $theme_support[0];
		$this->valid_lic     = $this->is_valid_lic();
	}

	/**
	 * Register endpoints.
	 */
	public function register_endpoints() {
		register_rest_route(
			Themeisle_Onboarding::API_ROOT,
			'/initialize_sites_library',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'init_library' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
		register_rest_route(
			Themeisle_Onboarding::API_ROOT,
			'/install_plugins',
			array(
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'run_plugin_importer' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
		register_rest_route(
			Themeisle_Onboarding::API_ROOT,
			'/import_content',
			array(
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'run_xml_importer' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
		register_rest_route(
			Themeisle_Onboarding::API_ROOT,
			'/import_theme_mods',
			array(
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'run_theme_mods_importer' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
		register_rest_route(
			Themeisle_Onboarding::API_ROOT,
			'/import_widgets',
			array(
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'run_widgets_importer' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
		register_rest_route(
			Themeisle_Onboarding::API_ROOT,
			'/migrate_frontpage',
			array(
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'run_front_page_migration' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
		register_rest_route(
			Themeisle_Onboarding::API_ROOT,
			'/dismiss_migration',
			array(
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => array( $this, 'dismiss_migration' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	/**
	 * Get the default layout - the case in which nothing is imported.
	 *
	 * @return array
	 */
	private function get_default_template() {
		if ( ! isset( $this->theme_support['default_template'] ) ) {
			return array();
		}

		return array(
			'screenshot' => $this->theme_support['default_template']['screenshot'],
			'name'       => $this->theme_support['default_template']['name'],
			'editor'     => $this->theme_support['default_template']['editor'],
		);
	}

	/**
	 * Get data for the local templates.
	 *
	 * @return array
	 */
	private function get_local_templates() {
		$returnable = array();

		require_once( ABSPATH . '/wp-admin/includes/file.php' );

		global $wp_filesystem;

		foreach ( $this->theme_support['editors'] as $editor ) {

			if ( ! isset( $this->theme_support['local'][ $editor ] ) ) {
				continue;
			}

			foreach ( $this->theme_support['local'][ $editor ] as $template_slug => $template_data ) {
				$json_path = get_template_directory() . '/onboarding/' . $template_slug . '/data.json';
				if ( ! file_exists( $json_path ) || ! is_readable( $json_path ) ) {
					continue;
				}

				WP_Filesystem();
				$json = $wp_filesystem->get_contents( $json_path );

				$returnable[ $editor ][ $template_slug ]                 = json_decode( $json, true );
				$returnable[ $editor ][ $template_slug ]['title']        = esc_html( $template_data['title'] );
				$returnable[ $editor ][ $template_slug ]['demo_url']     = esc_url( $template_data['url'] );
				$returnable[ $editor ][ $template_slug ]['content_file'] = get_template_directory() . '/onboarding/' . $template_slug . '/export.xml';
				$returnable[ $editor ][ $template_slug ]['screenshot']   = esc_url( get_template_directory_uri() . '/onboarding/' . $template_slug . '/screenshot.png' );
				$returnable[ $editor ][ $template_slug ]['source']       = 'local';
			}
		}

		return $returnable;
	}

	/**
	 * Get data for the remote templates.
	 *
	 * @return array
	 */
	private function get_remote_templates() {
		if ( $this->valid_lic === false ) {
			return array();
		}

		$returnable = array();

		foreach ( $this->theme_support['editors'] as $editor ) {

			if ( ! isset( $this->theme_support['remote'][ $editor ] ) ) {
				continue;
			}

			foreach ( $this->theme_support['remote'][ $editor ] as $template_slug => $template_data ) {
				$request       = wp_remote_get( $template_data['url'] . '/wp-json/ti-demo-data/data' );
				$response_code = wp_remote_retrieve_response_code( $request );

				if ( $response_code !== 200 ) {
					continue;
				}

				if ( empty( $request['body'] ) || ! isset( $request['body'] ) ) {
					continue;
				}

				$returnable[ $editor ][ $template_slug ]               = json_decode( $request['body'], true );
				$returnable[ $editor ][ $template_slug ]['title']      = esc_html( $template_data['title'] );
				$returnable[ $editor ][ $template_slug ]['demo_url']   = esc_url( $template_data['url'] );
				$returnable[ $editor ][ $template_slug ]['screenshot'] = esc_url( $template_data['screenshot'] );
				$returnable[ $editor ][ $template_slug ]['source']     = 'remote';
			}
		}

		return $returnable;
	}

	/**
	 * Get data for the upsells.
	 *
	 * @return array
	 */
	private function get_upsell_templates() {
		if ( $this->valid_lic === true ) {
			return array();
		}
		$returnable = array();

		foreach ( $this->theme_support['editors'] as $editor ) {
			if ( ! isset( $this->theme_support['upsell'][ $editor ] ) ) {
				continue;
			}

			foreach ( $this->theme_support['upsell'][ $editor ] as $template_slug => $template_data ) {
				$request       = wp_remote_get( $template_data['url'] . '/wp-json/ti-demo-data/data' );
				$response_code = wp_remote_retrieve_response_code( $request );
				if ( $response_code !== 200 ) {
					continue;
				}
				if ( empty( $request['body'] ) || ! isset( $request['body'] ) ) {
					continue;
				}
				$returnable[ $editor ][ $template_slug ]               = json_decode( $request['body'], true );
				$returnable[ $editor ][ $template_slug ]['title']      = esc_html( $template_data['title'] );
				$returnable[ $editor ][ $template_slug ]['demo_url']   = esc_url( $template_data['url'] );
				$returnable[ $editor ][ $template_slug ]['screenshot'] = esc_url( $template_data['screenshot'] );
				$returnable[ $editor ][ $template_slug ]['source']     = 'remote';
				$returnable[ $editor ][ $template_slug ]['in_pro']     = true;
			}
		}

		return $returnable;
	}

	/**
	 * Initialize Library
	 *
	 * @return array
	 */
	public function init_library() {
		if ( empty( $this->theme_support ) ) {
			return array();
		}

		$this->data['i18n']             = isset( $this->theme_support['i18n'] ) ? $this->theme_support['i18n'] : array();
		$this->data['editors']          = isset( $this->theme_support['editors'] ) ? $this->theme_support['editors'] : array();
		$this->data['pro_link']         = isset( $this->theme_support['pro_link'] ) ? $this->theme_support['pro_link'] : '';
		$this->data['default_template'] = $this->get_default_template();
		$this->data['migrate_data']     = $this->get_migrateable();
		$this->data['local']            = $this->get_local_templates();
		$this->data['remote']           = $this->get_remote_templates();
		$this->data['upsell']           = $this->get_upsell_templates();

		return $this->data;
	}

	/**
	 * Get migratable data.
	 *
	 * This is used if we can ensure migration from a previous theme to a template.
	 *
	 * @return array
	 */
	private function get_migrateable() {

		if ( ! isset( $this->theme_support['can_migrate'] ) ) {
			return array();
		}

		$data = $this->theme_support['can_migrate'];

		$old_theme = get_theme_mod( 'ti_prev_theme', 'ti_onboarding_undefined' );

		if ( ! array_key_exists( $old_theme, $data ) ) {
			return array();
		}

		$content_imported = get_theme_mod( $data[ $old_theme ]['theme_mod_check'], 'not-imported' );
		if ( $content_imported === 'yes' ) {
			return array();
		}

		$folder_name = $old_theme;
		if ( $old_theme === 'zerif-lite' || $old_theme === 'zerif-pro' ) {
			$folder_name = 'zelle';
		}

		return array(
			'theme_name'        => ! empty( $data[ $old_theme ]['theme_name'] ) ? esc_html( $data[ $old_theme ]['theme_name'] ) : '',
			'screenshot'        => get_template_directory_uri() . Themeisle_Onboarding::OBOARDING_PATH . '/migration/' . $folder_name . '/' . $data[ $old_theme ]['template'] . '.png',
			'template'          => get_template_directory() . Themeisle_Onboarding::OBOARDING_PATH . '/migration/' . $folder_name . '/' . $data[ $old_theme ]['template'] . '.json',
			'template_name'     => $data[ $old_theme ]['template'],
			'heading'           => $data[ $old_theme ]['heading'],
			'description'       => $data[ $old_theme ]['description'],
			'theme_mod'         => $data[ $old_theme ]['theme_mod_check'],
			'mandatory_plugins' => $data[ $old_theme ]['mandatory_plugins'] ? $data[ $old_theme ]['mandatory_plugins'] : array(),
			'recommended_plugins' => $data[ $old_theme ]['recommended_plugins'] ? $data[ $old_theme ]['recommended_plugins'] : array(),
		);
	}

	/**
	 * Run the plugin importer.
	 *
	 * @param WP_REST_Request $request the async request.
	 */
	public function run_plugin_importer( WP_REST_Request $request ) {
		require_once 'importers/class-themeisle-ob-plugin-importer.php';
		if ( ! class_exists( 'Themeisle_OB_Plugin_Importer' ) ) {
			wp_send_json_error( 'ti__ob_rest_err_1', 500 );
		}
		$plugin_importer = new Themeisle_OB_Plugin_Importer();
		$plugin_importer->install_plugins( $request );
	}

	/**
	 * Run the XML importer.l
	 *
	 * @param WP_REST_Request $request the async request.
	 */
	public function run_xml_importer( WP_REST_Request $request ) {
		require_once 'importers/class-themeisle-ob-content-importer.php';
		if ( ! class_exists( 'Themeisle_OB_Content_Importer' ) ) {
			wp_send_json_error( 'ti__ob_rest_err_2', 500 );
		}
		$content_importer = new Themeisle_OB_Content_Importer();
		$content_importer->import_remote_xml( $request );
		if ( ! empty( $frontpage_id ) ) {
			$this->frontpage_id = $frontpage_id;
		}
	}

	/**
	 * Run the theme mods importer.
	 *
	 * @param WP_REST_Request $request the async request.
	 */
	public function run_theme_mods_importer( WP_REST_Request $request ) {
		require_once 'importers/class-themeisle-ob-theme-mods-importer.php';
		if ( ! class_exists( 'Themeisle_OB_Theme_Mods_Importer' ) ) {
			wp_send_json_error( 'ti__ob_rest_err_3', 500 );
		}
		$theme_mods_importer = new Themeisle_OB_Theme_Mods_Importer();
		$theme_mods_importer->import_theme_mods( $request );
	}

	/**
	 * Run the widgets importer.
	 *
	 * @param WP_REST_Request $request the async request.
	 */
	public function run_widgets_importer( WP_REST_Request $request ) {
		require_once 'importers/class-themeisle-ob-widgets-importer.php';
		if ( ! class_exists( 'Themeisle_OB_Widgets_Importer' ) ) {
			wp_send_json_error( 'ti__ob_rest_err_4', 500 );
		}
		$theme_mods_importer = new Themeisle_OB_Widgets_Importer();
		$theme_mods_importer->import_widgets( $request );

		set_theme_mod( 'ti_content_imported', 'yes' );
	}

	/**
	 * Run front page migration.
	 *
	 * @param WP_REST_Request $request
	 */
	public function run_front_page_migration( WP_REST_Request $request ) {

		$params = $request->get_json_params();
		if ( ! isset( $params['template'] ) ) {
			wp_send_json_error( 'ti__ob_rest_err_5', 500 );
		}
		if ( ! isset( $params['template_name'] ) ) {
			wp_send_json_error( 'ti__ob_rest_err_6', 500 );
		}
		require_once 'importers/class-themeisle-ob-' . $params['template_name'] . '-importer.php';
		$class_name = 'Themeisle_OB_' . ucfirst( $params['template_name'] ) . '_Importer';
		if ( ! class_exists( $class_name ) ) {
			wp_send_json_error( 'ti__ob_rest_err_7', 500 );
		}
		$migrator = new $class_name;
		$migrator->import_zelle_frontpage( $params['template'] );
		wp_send_json_success( 'success', 200 );
	}

	/**
	 * Dismiss the front page migration notice.
	 *
	 * @param WP_REST_Request $request
	 */
	public function dismiss_migration( WP_REST_Request $request ) {
		$params = $request->get_json_params();
		if ( ! isset( $params['theme_mod'] ) ) {
			wp_send_json_error( 'ti__ob_rest_err_8', 500 );
		}
		set_theme_mod( $params['theme_mod'], 'yes' );
		wp_send_json_success( $this->frontpage_id );
	}

	/**
	 * Check license
	 *
	 * @return bool
	 */
	private function is_valid_lic() {
		if ( ! class_exists( '\ThemeisleSDK\Common\Module_Factory' ) ) {
			return false;
		}
		$sdk_modules = \ThemeisleSDK\Common\Module_Factory::get_modules_map();
		$theme       = get_stylesheet();

		if ( ! array_key_exists( $theme, $sdk_modules ) ) {
			$theme = 'neve-pro-addon';
		}

		if ( ! array_key_exists( $theme, $sdk_modules ) ) {
			return false;
		}

		if( ! isset( $sdk_modules[$theme]['licenser'] ) ) {
			return false;
		}

		$licenser = $sdk_modules[ $theme ]['licenser'];
		$validity = $licenser->get_license_status();

		if ( $validity === 'valid' ) {
			return true;
		}

		return false;
	}
}
