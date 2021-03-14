<?php

/**
 * TVC Channels Class.
 *
 * @package TVC Product Feed Manager/Data/Classes
 * @version 1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TVC_Channel' ) ) :

	/**
	 * Channel Class
	 */
	class TVC_Channel {
		/**
		 * Placeholder for the Channel Classes
		 *
		 * @var string
		 */
		private $_channels;

		public function __construct() {
			// TVC_CHANNEL_RELATED
			$this->_channels = array(
				new Channel( '0', 'usersetup', 'Free User Setup' ),
				new Channel( '1', 'google', 'Google Merchant Centre' ),
				new Channel( '2', 'bing', 'Bing Merchant Centre' ),
				new Channel( '3', 'beslis', 'Beslis.nl' ),
				new Channel( '4', 'pricegrabber', 'PriceGrabber' ),
				new Channel( '5', 'shopping', 'Shopping.com (eBay)' ),
				new Channel( '6', 'amazon', 'Amazon product ads' ),
				new Channel( '7', 'connexity', 'Connexity' ),
				new Channel( '8', 'become', 'Become' ),
				// Become has been taken over by Connexity, https://merchants.become.com/DataFeedSpecification.html links to Connexity
				new Channel( '9', 'nextag', 'Nextag' ),
				new Channel( '10', 'kieskeurig', 'Kieskeurig.nl' ),
				new Channel( '11', 'vergelijk', 'Vergelijk.nl' ),
				new Channel( '12', 'koopjespakker', 'Koopjespakker.nl' ),
				new Channel( '13', 'avantlink', 'AvantLink' ),
				new Channel( '14', 'zbozi', 'Zbozi' ),
				new Channel( '15', 'comcon', 'Commerce Connector' ),
				new Channel( '16', 'facebook', 'Facebook' ),
				new Channel( '17', 'bol', 'Bol.com' ),
				new Channel( '18', 'adtraction', 'Adtraction' ),
				new Channel( '19', 'ricardo', 'Ricardo.ch' ),
				new Channel( '20', 'ebay', 'eBay' ),
				new Channel( '21', 'shopzilla', 'Shopzilla' ),
				new Channel( '22', 'converto', 'Converto' ),
				new Channel( '23', 'idealo', 'Idealo' ),
				new Channel( '24', 'heureka', 'Heureka' ),
				new Channel( '25', 'pepperjam', 'Pepperjam' ),
				new Channel( '26', 'galaxus_data', 'Galaxus Product Data' ),
				new Channel( '27', 'galaxus_properties', 'Galaxus Product Properties' ),
				new Channel( '28', 'galaxus_stock_pricing', 'Galaxus Product Stock Pricing' ),
				new Channel( '996', '_tsv', 'Custom TSV Export' ),
				new Channel( '997', '_txt', 'Custom TXT Export' ),
				new Channel( '998', '_csv', 'Custom CSV Export' ),
				new Channel( '999', '', 'Custom XML Export' ),
			);
		}

		/**
		 * Returns channel data from a specific channel
		 *
		 * @param string $channel_name channel name
		 *
		 * @return string Channel class
		 */
		public function get_active_channel_details( $channel_name ) {
			foreach ( $this->_channels as $channel ) {
				if ( $channel->channel_short === $channel_name ) {
					return $channel;
				}
			}
			return false;
		}

		public function get_channel_short_name( $channel_id ) {
			foreach ( $this->_channels as $channel ) {
				if ( $channel->channel_id === $channel_id ) {
					return $channel->channel_short;
				}
			}
			return false;
		}

		public function get_channel_name( $channel_id ) {
			foreach ( $this->_channels as $channel ) {
				if ( $channel->channel_id === $channel_id ) {
					return $channel->channel_name;
				}
			}
			return false;
		}

		public function get_installed_channel_names() {
			$file_class = new TVC_File();

			return $file_class->get_installed_channels_from_file();
		}

		public function remove_channel( $channel, $nonce ) {
			if ( wp_verify_nonce( $nonce, 'delete-channel-nonce' ) ) {
				$this->remove_channel_source( $channel );
			}
		}

		public function update_channel( $channel, $code, $nonce ) {
			if ( wp_verify_nonce( $nonce, 'update-channel-nonce' ) ) {
				$this->update_channel_source( $channel, $code );
			} else {
				tvc_write_log_file( sprintf( 'Failed to update channel %s because then nonce was not accepted. Given nonce = %s', $channel, $nonce ) );
			}
		}

		public function install_channel( $channel, $code, $nonce ) {
			if ( wp_verify_nonce( $nonce, 'install-channel-nonce' ) ) {
				$this->install_channel_source( $channel, $code );
			} else {
				tvc_write_log_file( sprintf( 'Failed to install channel %s because then nonce was not accepted. Given nonce = %s', $channel, $nonce ) );
			}
		}

		public function get_channels_from_server() {
            $response = "Google Merchant Centre";
			return $response;
		}

		public function get_number_of_updates_from_server( $channel_updated ) {
			if ( date( 'Ymd' ) === get_option( 'tvc_channel_update_check_date' ) ) {
				if ( $channel_updated ) {
					tvc_decrease_update_ready_channels();
				}

				return get_option( 'tvc_channels_to_update' );
			} else {
				$response = $this->get_channels_from_server();

				if ( ! is_wp_error( $response ) ) {
					$available_channels = json_decode( $response['body'] );

					if ( $available_channels ) {
						$installed_channels_names = $this->get_installed_channel_names();

						$this->add_status_data_to_available_channels( $available_channels, $installed_channels_names, false );

						$stored_count = $this->count_updatable_channels( $available_channels );

						$count = $channel_updated ? ( $stored_count - 1 ) : $stored_count;
						update_option( 'tvc_channels_to_update', $count > 0 ? $count : 0 );
						update_option( 'tvc_channel_update_check_date', date( 'Ymd' ) );

						return $count;
					}
				} else {
					echo tvc_handle_wp_errors_response(
						$response,
						sprintf(
							/* translators: %s: url to the support page */
                            esc_html__(
								'2141 - Please open a support ticket at %s for support on this issue.',
								'tvc-product-feed-manager'
							),
							TVC_SUPPORT_PAGE_URL
						)
					);

					return false;
				}
			}

			return 0;
		}

		public function add_status_data_to_available_channels( &$available_channels, $installed_channels, $updated ) {
			for ( $i = 0; $i < count( $available_channels ); $i ++ ) {
				if ( in_array( $available_channels[ $i ]->short_name, $installed_channels ) ) {
					$available_channels[ $i ]->status = 'installed';

					$available_channels[ $i ]->installed_version = $available_channels[ $i ]->short_name === $updated ? $available_channels[ $i ]->version
						: $this->get_channel_file_version( $available_channels[ $i ]->short_name, 0 );
				} else {
					$available_channels[ $i ]->status            = 'not installed';
					$available_channels[ $i ]->installed_version = '0';
				}
			}
		}

		private function get_channel_file_version( $channel_name, $rerun_counter ) {
			if ( $rerun_counter < 3 ) {
				if ( class_exists( 'TVC_' . ucfirst( $channel_name ) . '_Feed_Class' ) ) {
					$class_var = 'TVC_' . ucfirst( $channel_name ) . '_Feed_Class';

					$channel_class = new $class_var();

					return $channel_class->get_version();
				} else {
					// reset the registered channels in the channel table
					$db_class = new TVC_Database_Management();
					$db_class->reset_channel_registration();

					include_channels(); // include the channel classes

					$rerun_counter ++;

					return $this->get_channel_file_version( $channel_name, $rerun_counter );
				}
			} else {
				if ( tvc_on_any_own_plugin_page() ) {
					/* translators: %s: Name of a channel */
					echo tvc_show_wp_error( sprintf( esc_html__( 'Channel %s is not installed correctly. Please try to Deactivate and then Activate the Feed Manager Plugin in your Plugins page.', 'tvc-product-feed-manager' ), $channel_name ) );
					tvc_write_log_file( sprintf( 'Error: Channel %s is not installed correctly.', $channel_name ) );
				}

				return 'unknown';
			}
		}

		private function count_updatable_channels( $channel_data ) {
			$counter = 0;

			foreach ( $channel_data as $channel ) {
				if ( 'installed' === $channel->status && ( $channel->version > $channel->installed_version ) ) {
					$counter ++;
				}
			}

			return $counter;
		}

		private function update_channel_source( $channel, $code ) {
			$file_class = new TVC_File();
			$ftp_class  = new TVC_Channel_FTP();

			// remove the out dated channel source files from the server
			$file_class->delete_channel_source_files( $channel );

			$get_result = $ftp_class->get_channel_source_files( $channel, $code );

			// get the update files from .com
			if ( false !== $get_result ) {
				// unzip the file
				$file_class->unzip_channel_file( $channel );

				// register the update
				tvc_decrease_update_ready_channels();
			}
		}

		private function remove_channel_source( $channel_short ) {
			$data_class = new TVC_Data();
			$file_class = new TVC_File();

			// get the channel id that needs to be removed
			$channel_id = $data_class->get_channel_id_from_short_name( $channel_short );

			// unregister the channel
			wp_dequeue_script( 'tvc_' . $channel_short . '-source-script' );

			if ( $channel_id ) {
				// remove channel related feed files
				$file_class->delete_channel_feed_files( $channel_id );

				// remove any channel related feed data and feed meta
				$data_class->delete_channel_feeds( $channel_id );
			}

			// remove the channel from the feedmanager_channel table
			$data_class->delete_channel( $channel_short );

			// remove the channel source files from the server
			$file_class->delete_channel_source_files( $channel_short );
		}

		private function install_channel_source( $channel_name, $code ) {
			$ftp_class  = new TVC_Channel_FTP();
			$file_class = new TVC_File();
			$data_class = new TVC_Data();

			if ( plugin_version_supports_channel( $channel_name ) ) {
				$get_result = $ftp_class->get_channel_source_files( $channel_name, $code );

				// get the update files from .com
				if ( false !== $get_result ) {

					// unzip the file
					$file_class->unzip_channel_file( $channel_name );

					// register the new channel
					$channel_details = $this->get_active_channel_details( $channel_name );

					if ( false !== $channel_details ) {
						$data_class->register_channel( $channel_name, $channel_details );
					} else {
						tvc_write_log_file( sprintf( 'Unable to register channel %s' . $channel_name ) );
					}
				} else {
					tvc_write_log_file(
						sprintf(
							'Could not get the %s channel file from the server. Get_result message is %s.',
							$channel_name,
							$get_result
						)
					);
				}
			} else {
				echo tvc_show_wp_warning(
					sprintf(
						/* translators: %s: Name of the selected channel */
                        esc_html__(
							'Channel %s is not supported by your current plugin version. Please update your plugin to the latest version and try uploading this channel again.',
							'tvc-product-feed-manager'
						),
						$channel_name
					),
					'tvc-product-feed-manager'
				);
			}
		}
	}

	// end of TVC_Channel class

	class Channel {
		public $channel_id;
		public $channel_short;
		public $channel_name;

		public function __construct( $id, $short, $name ) {
			$this->channel_id    = $id;
			$this->channel_short = $short;
			$this->channel_name  = $name;
		}
	}

	// end of Channel class
endif;
