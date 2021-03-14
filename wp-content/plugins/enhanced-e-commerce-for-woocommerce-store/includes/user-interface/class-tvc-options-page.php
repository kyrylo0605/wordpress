<?php

/**
 * TVC Product Feed Manager Add Feed Page Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Options_Page' ) ) :

	/**
	 * Option Form Class
	 *
	 * @since        1.5.0
	 */
	class TVC_Options_Page {

		/**
		 * Generates the main part of the Settings page
		 *
		 * @since 1.5.0
		 *
		 * @return string The html code for the mail part of the Settings page
		 */
		public function display() {
			$html_code  = '<table class="form-table"><tbody>';
			$html_code .= $this->settings();
			$html_code .= '</tbody></table>';

			echo $html_code;
		}

		/**
		 * Generates html code for the Setting page
		 *
		 * @since 1.5.0
		 * @since 1.7.0 Added the backups table
		 * @since 1.8.0 Added the third party attributes text field
		 * @since 1.9.0 Added the Re-initialize button
		 * @since 2.3.0 Added the Notice option
		 */
		private function settings() {
			$html_code = '';

			$auto_fix_feed_option            = get_option( 'tvc_auto_feed_fix', false );
			$auto_feed_fix_checked           = true === $auto_fix_feed_option || 'true' === $auto_fix_feed_option ? ' checked ' : '';
			$background_processing_option    = get_option( 'tvc_disabled_background_mode', 'true' );
			$background_processing_unchecked = true === $background_processing_option || 'true' === $background_processing_option ? ' checked ' : '';
			$product_identifiers_option      = get_option( 'tvc_show_product_identifiers', 'false' );
			$show_product_identifiers        = true === $product_identifiers_option || 'true' === $product_identifiers_option ? ' checked ' : '';
			$notice_mailaddress             = get_option( 'tvc_notice_mailaddress' ) ? get_option( 'tvc_notice_mailaddress' ) : get_bloginfo( 'admin_email' );

			$html_code .= '<tr valign="top" class="">';
			$html_code .= '<th scope="row" class="titledesc">' . esc_html__( 'Auto feed fix', 'tvc-product-feed-manager' ) . '</th>';
			$html_code .= '<td class="forminp forminp-checkbox">';
			$html_code .= '<fieldset>';
			$html_code .= '<input name="tvc_auto_feed_fix_mode" id="tvc_auto_feed_fix_mode" type="checkbox" class="" value="1"' . $auto_feed_fix_checked . '> ';
			$html_code .= '<label for="tvc_auto_feed_fix_mode">';
			$html_code .= esc_html__( 'Automatically try regenerating feeds that are failed (default off).', 'tvc-product-feed-manager' ) . '</label></fieldset>';
			$html_code .= '<p><i>' . esc_html__( 'Leaving this option on can put extra strain on your server when feeds keep failing.', 'tvc-product-feed-manager' ) . '</p></i>';
			$html_code .= '</td></tr>';

			$html_code .= '<tr valign="top" class="">';
			$html_code .= '<th scope="row" class="titledesc">' . esc_html__( 'Disable background processing', 'tvc-product-feed-manager' ) . '</th>';
			$html_code .= '<td class="forminp forminp-checkbox">';
			$html_code .= '<fieldset>';
			$html_code .= '<input name="tvc_background_processing_mode" id="tvc_background_processing_mode" type="checkbox" class="" value="1"' . $background_processing_unchecked . '> ';
			$html_code .= '<label for="tvc_background_processing_mode">';
			$html_code .= esc_html__( 'Process feeds directly instead of in the background (default off). Try this option when feeds keep getting stuck in processing. ', 'tvc-product-feed-manager' ) . '</label>';
			$html_code .= '<p><i>' . esc_html__( 'WARNING: When this option is selected the system can only update one feed at a time. Make sure to deconflict your feeds auto-update schedules to prevent more than one feed auto-updates at a time.', 'tvc-product-feed-manager' ) . '</i></p></fieldset>';
			$html_code .= '</td></tr>';

			// @since 2.10.0.
			$html_code .= '<tr valign="top" class="">';
			$html_code .= '<th scope="row" class="titledesc">' . esc_html__( 'Clear feed process', 'tvc-product-feed-manager' ) . '</th>';
			$html_code .= '<td class="forminp forminp-checkbox">';
			$html_code .= '<fieldset>';
			$html_code .= '<input class="button-primary" type="button" name="clear" value="' . esc_html__( 'Clear feed process', 'tvc-product-feed-manager' ) . '" id="tvc-clear-feed-process-button" /> ';
			$html_code .= '<label for="clear">';
			$html_code .= esc_html__( 'Use this option when feeds get stuck processing - does not delete your current feeds or settings.', 'tvc-product-feed-manager' ) . '</label></fieldset>';
			$html_code .= '</td></tr>';

			$html_code .= '<tr valign="top" class="">';
			$html_code .= '<th scope="row" class="titledesc">' . esc_html__( 'Re-initialize', 'tvc-product-feed-manager' ) . '</th>';
			$html_code .= '<td class="forminp forminp-checkbox">';
			$html_code .= '<fieldset>';
			$html_code .= '<input class="button-primary" type="button" name="reinitiate" value="' . __( 'Re-initiate plugin', 'tvc-product-feed-manager' ) . '" id="tvc-reinitiate-plugin-button" /> ';
			$html_code .= '<label for="reinitiate">';
			$html_code .= esc_html__( 'Updates the tables if required, re-initiates the cron events and resets the stored license - does not delete your current feeds or settings.', 'tvc-product-feed-manager' ) . '</label></fieldset>';
			$html_code .= '</td></tr>';

			$html_code .= '<tr valign="top" class="">';
			$html_code .= '<th scope="row" class="titledesc">' . esc_html__( 'Backups', 'tvc-product-feed-manager' ) . '</th>';
			$html_code .= '<td>';

			$html_code .= '<p>Available backups</p>';
			$html_code .= '<table id="tvc-backups" class="wp-list-table smallfat fixed posts"';
			$html_code .= '<thead>';
			$html_code .= '<tr><th scope="col" class="tvc-backup-filename">' . esc_html__( 'File name', 'tvc-product-feed-manager' ) . '</th>';
			$html_code .= '<th scope="col" class="tvc-backup-date">' . esc_html__( 'Backup date', 'tvc-product-feed-manager' ) . '</th>';
			$html_code .= '<th scope="col">' . esc_html__( 'Actions', 'tvc-product-feed-manager' ) . '</th></tr>';
			$html_code .= '</thead>';
			$html_code .= '<tbody id="tvc-backups-list"></tbody>';
			$html_code .= '</table>';
			$html_code .= '<p>';
			$html_code .= '<span class="button-secondary" id="tvc_prepare_backup">' . esc_html__( 'Add new backup', 'tvc-product-feed-manager' ) . '</span>';
			$html_code .= '</p>';
			$html_code .= '</td></tr>';
			$html_code .= '<tr style="display:none;" id="tvc_backup-wrapper"><th>&nbsp</th><td>';
			$html_code .= '<input type="text" class="regular-text" id="tvc_backup-file-name" placeholder="Enter a file name">';
			$html_code .= '<span class="button-secondary" id="tvc_make_backup" disabled>' . esc_html__( 'Backup current feeds', 'tvc-product-feed-manager' ) . '</span>';
			$html_code .= '<span class="button-secondary" id="tvc_cancel_backup">' . esc_html__( 'Cancel backup', 'tvc-product-feed-manager' ) . '</span>';

			$html_code .= '</td></tr>';

			return $html_code;
		}
	}

endif;
