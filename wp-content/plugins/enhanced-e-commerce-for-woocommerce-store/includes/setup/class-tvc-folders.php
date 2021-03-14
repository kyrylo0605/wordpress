<?php

/**
 * TVC Folder Class.
 *
 * @package TVC Product Feed Manager/Setup/Classes
 * @version 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Folders' ) ) :


	/**
	 * Folder Class
	 */
	class TVC_Folders {

		public static function make_feed_support_folder() {
			if ( ! file_exists( TVC_FEEDS_DIR ) ) {
				self::make_tvc_dir( TVC_FEEDS_DIR );
			}
		}

		public static function make_channels_support_folder() {
			if ( ! file_exists( TVC_CHANNEL_DATA_DIR ) ) {
				self::make_tvc_dir( TVC_CHANNEL_DATA_DIR );
			}
		}

		public static function make_backup_folder() {
			if ( ! file_exists( TVC_BACKUP_DIR ) ) {
				self::make_tvc_dir( TVC_BACKUP_DIR );
			}
		}

		public static function make_tvc_dir( $dir ) {
			wp_mkdir_p( $dir );
		}

		public static function update_tvc_channel_dir() {
			$old_channel_folder = WP_PLUGIN_DIR . '/tvc-product-feed-manager-support/channels';

			if ( file_exists( $old_channel_folder ) ) {
				if ( file_exists( TVC_CHANNEL_DATA_DIR ) ) { // if channels folder already exists, remove it to prevent the rename function from failing
					self::delete_folder( TVC_CHANNEL_DATA_DIR );
				}

				if ( ! self::copy_folder( $old_channel_folder, TVC_CHANNEL_DATA_DIR ) ) {
					return false;
				}

				if ( ! self::delete_folder( $old_channel_folder ) ) {
					require_once( WP_PLUGIN_DIR . '/tvc-google-analytics-with-google-product-feed/includes/user-interface/tvc-messaging-functions.php' );
					/* translators: %s: old channel folder */
					echo tvc_show_wp_warning( sprintf( __( 'Unable to remove the %s folder. This folder is not required any more. Please try removing this folder manually using ftp software or an equivalent methode.', 'tvc-product-feed-manager' ), $old_channel_folder ) );
				}
			}
		}

		/**
		 * Deletes a directory and all its content
		 *
		 * @param string $folder_name
		 *
		 * @return boolean true when the directory has been deleted
		 */
		public static function delete_folder( $folder_name ) {
			if ( is_dir( $folder_name ) ) {

				$dir_handle = opendir( $folder_name );

				if ( ! $dir_handle ) {
					return false;
				}

				while ( $file = readdir( $dir_handle ) ) {
					if ( $file != "." && $file != ".." ) { // do not change this as it can cause serious issues with uninstalling the plugin
						if ( ! is_dir( $folder_name . "/" . $file ) ) {
							// only remove .xml, .js, .txt or .log files and a class-feed.php files. Do not delete .php or other system files
							if ( preg_match( '/.*(.xml$|.js$|.txt$|.log$)|\b(class-feed.php)\b/', $file ) ) {
								unlink( $folder_name . "/" . $file );
							}
						} else {
							self::delete_folder( $folder_name . '/' . $file );
						}
					}
				}

				closedir( $dir_handle );
				rmdir( $folder_name );

				return true;
			} else {
				return false;
			}
		}

		public static function copy_folder( $source_folder, $target_folder ) {
			$result = true;
			$dir    = opendir( $source_folder );

			self::make_tvc_dir( $target_folder );

			while ( false !== ( $file = readdir( $dir ) ) ) {

				if ( ! $result ) {
					break;
				}

				if ( ( '.' != $file ) && ( '..' != $file ) ) {
					if ( is_dir( $source_folder . '/' . $file ) ) {
						self::copy_folder( $source_folder . '/' . $file, $target_folder . '/' . $file );

					} else {
						$result = copy( $source_folder . '/' . $file, $target_folder . '/' . $file );
					}
				}

				closedir( $dir );
			}

			return $result;
		}

		public static function folder_is_empty( $folder ) {
			if ( ! is_readable( $folder ) ) {
				return null;
			}

			$handle = opendir( $folder );

			while ( false !== ( $entry = readdir( $handle ) ) ) {
				if ( $entry != "." && $entry != ".." ) {
					return false;
				}
			}

			return true;
		}
	}
	// end of TVC_Folders_Class

endif;	
