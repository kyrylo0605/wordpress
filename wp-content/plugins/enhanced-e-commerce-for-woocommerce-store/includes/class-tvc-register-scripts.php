<?php
/**
 * TVC Register Scripts Class.
 *
 * @package TVC Product Feed Manager/Classes
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if ( ! class_exists( 'TVC_Register_Scripts' ) ) :
    /**
     * Register Scripts Class
     */
    class TVC_Register_Scripts {
        // @private storage of scripts version
        private $_version_stamp;
        // @private register minified scripts
        private $_js_min;
        public function __construct() {
            $premium_version_nr   = TVC_EDD_SL_ITEM_NAME === 'Google Product Feed Manager' ? 'fr-' : 'pr-'; // prefix for version stamp depending on premium or free version
            $action_level         = 2; // for future use
            $this->_version_stamp = defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : $premium_version_nr . '1.0';
            $this->_js_min        = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
            // add hooks
            //add_action( 'admin_enqueue_scripts', array( $this, 'tvc_register_required_scripts' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'tvc_register_required_nonce' ) );
            // only load the next hooks when on the Settings page
            if ( tvc_on_plugins_settings_page() ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'tvc_register_required_options_page_scripts' ) );
                add_action( 'admin_enqueue_scripts', array( $this, 'tvc_register_required_options_page_nonce' ) );
            }
            if ( 1 === $action_level ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'tvc_register_level_one_scripts' ) );
            } elseif ( 2 === $action_level ) {
                add_action( 'admin_enqueue_scripts', array( $this, 'tvc_register_level_two_scripts' ) );
            }
        }
        /**
         * Registers all required java scripts for the feed manager pages.
         */
        public function tvc_register_required_scripts() {
            $screen = get_current_screen();

            if($screen->id == 'toplevel_page_aga-envato-api' || $screen->id == 'aga-google-shopping_page_tvc-product-feed-manager' || $screen->id == 'aga-google-shopping_page_tvc-configuration-page'){
                //wp_register_style("tvc_gmc_style-1", ENHANCAD_PLUGIN_URL . '/admin/css/style.css');
                //wp_enqueue_style( "tvc_gmc_style-1");
                wp_register_style("tvc_gmc_style-2", ENHANCAD_PLUGIN_URL . '/css/style.css');
                wp_enqueue_style( "tvc_gmc_style-2");
                wp_register_style("tvc-product-feed-manager-1", ENHANCAD_PLUGIN_URL . '/css/actionable-google-analytics-admin.css');
                wp_enqueue_style( "tvc-product-feed-manager-1");
                wp_register_script( 'tvc_gmc_bootstrap-script-1', ENHANCAD_PLUGIN_URL . '/includes/setup/js/bootstrap' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
                wp_enqueue_script( 'tvc_gmc_bootstrap-script-1');
                wp_register_script( 'tvc_gmc_bootstrap-script-1', ENHANCAD_PLUGIN_URL . '/includes/setup/js/jquery-3.5.1.slim' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
                wp_enqueue_script( 'tvc_gmc_bootstrap-script-1');
                wp_register_script( 'tvc_gmc_bootstrap-script-1', ENHANCAD_PLUGIN_URL . '/includes/setup/js/popper' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
                wp_enqueue_script( 'tvc_gmc_bootstrap-script-1');
                wp_register_script( 'tvc_gmc-api-script', ENHANCAD_PLUGIN_URL . '/includes/setup/js/merchant-center.js', array( 'jquery' ), $this->_version_stamp, true );
                wp_enqueue_script( 'tvc_gmc-api-script');
                //wp_enqueue_script( 'tvc-product-feed-manager', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/sweetalert' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
                wp_register_script( 'tvc-product-feed-manager-fa', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/fontawesome.js', array( 'jquery' ), $this->_version_stamp, false );
                wp_enqueue_script( 'tvc-product-feed-manager-fa');
                wp_register_style('aga_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css');
                wp_enqueue_style('aga_bootstrap');
            }
            // enqueue notice handling script
            wp_enqueue_script( 'tvc_message-handling-script', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_msg_events' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
            // do not load the other scripts unless a tvc page is on

            wp_register_style( 'tvc-product-feed-manager', ENHANCAD_PLUGIN_URL . '/css/tvc_admin-page' . $this->_js_min . '.css', '', $this->_version_stamp, 'screen' );
            wp_enqueue_style( 'tvc-product-feed-manager' );
            // embed the javascript file that makes the Ajax requests
            wp_register_script( 'tvc_feed-settings-script', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_feed-form' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp,'screen' );
            wp_enqueue_script( 'tvc_feed-settings-script');
            wp_enqueue_script( 'tvc_business-logic-script', ENHANCAD_PLUGIN_URL . '/includes/application/js/tvc_logic.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_enqueue_script( 'tvc_data-script', ENHANCAD_PLUGIN_URL . '/includes/data/js/tvc_data' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_enqueue_script( 'tvc_event-listener-script1', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_feed-form-events'. $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_enqueue_script( 'wp_head', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/fontawesome.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_enqueue_script( 'tvc_form-support-script1', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_support' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_enqueue_script( 'tvc_verify-inputs-script', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_verify-inputs' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_enqueue_script( 'tvc_feed-handling-script', ENHANCAD_PLUGIN_URL . '/includes/application/js/tvc_feedhandling' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_enqueue_script( 'tvc_feed-html', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_feed-html' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_register_script( 'tvc_feed-list-script', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_feed-list.js', array( 'jquery' ), $this->_version_stamp,'screen' );
            wp_enqueue_script( 'tvc_feed-list-script');
            wp_enqueue_script( 'tvc_feed-meta-script', ENHANCAD_PLUGIN_URL . '/includes/application/js/tvc_object-attribute-meta' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_enqueue_script( 'tvc_feed-objects-script', ENHANCAD_PLUGIN_URL . '/includes/application/js/tvc_object-feed' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_enqueue_script( 'tvc_general-functions-script', ENHANCAD_PLUGIN_URL . '/includes/application/js/tvc_general-functions' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_enqueue_script( 'tvc_object-handling-script', ENHANCAD_PLUGIN_URL . '/includes/data/js/tvc_metadatahandling' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false );
            wp_enqueue_script( 'tvc_script_ajax', ENHANCAD_PLUGIN_URL . '/includes/data/js/tvc_ajaxdatahandling' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, false);
            wp_enqueue_script( 'tvc_feed-queue-string-script', ENHANCAD_PLUGIN_URL . '/includes/data/js/tvc_feed-queue-string.js', array( 'jquery' ), $this->_version_stamp, false );
        }
        /**
         * Generate the required nonce's.
         */
        public function tvc_register_required_nonce() {
            // make a unique nonce for all Ajax requests
            wp_localize_script(
                'tvc_script_ajax',
                'myAjaxNonces',
                array(
                    // URL to wp-admin/admin-ajax.php to process the request
                    'ajaxurl'                => admin_url( 'admin-ajax.php' ),
                    // generate the nonce's
                    'campaignCategoryListsNonce'     => wp_create_nonce( 'tvcajax-campaign-category-lists-nonce' ),
                    'campaignStatusNonce'     => wp_create_nonce( 'tvcajax-update-campaign-status-nonce' ),
                    'campaignDeleteNonce'     => wp_create_nonce( 'tvcajax-delete-campaign-nonce' ),
                    'categoryListsNonce'     => wp_create_nonce( 'tvcajax-category-lists-nonce' ),
                    'deleteFeedNonce'        => wp_create_nonce( 'tvcajax-delete-feed-nonce' ),
                    'feedDataNonce'          => wp_create_nonce( 'tvcajax-feed-data-nonce' ),
                    'feedStatusNonce'        => wp_create_nonce( 'tvcajax-feed-status-nonce' ),
                    'inputFieldsNonce'       => wp_create_nonce( 'tvcajax-input-fields-nonce' ),
                    'inputFeedFiltersNonce'  => wp_create_nonce( 'tvcajax-feed-filters-nonce' ),
                    'logMessageNonce'        => wp_create_nonce( 'tvcajax-log-message-nonce' ),
                    'nextCategoryNonce'      => wp_create_nonce( 'tvcajax-next-category-nonce' ),
                    'outputFieldsNonce'      => wp_create_nonce( 'tvcajax-output-fields-nonce' ),
                    'postFeedsListNonce'     => wp_create_nonce( 'tvcajax-post-feeds-list-nonce' ),
                    'switchFeedStatusNonce'  => wp_create_nonce( 'tvcajax-switch-feed-status-nonce' ),
                    'duplicateFeedNonce'     => wp_create_nonce( 'tvcajax-duplicate-existing-feed-nonce' ),
                    'updateFeedDataNonce'    => wp_create_nonce( 'tvcajax-update-feed-data-nonce' ),
                    'updateAutoFeedFixNonce' => wp_create_nonce( 'tvcajax-set-auto-feed-fix-nonce' ),
                    'updateFeedFileNonce'    => wp_create_nonce( 'tvcajax-update-feed-file-nonce' ),
                    'nextFeedInQueueNonce'   => wp_create_nonce( 'tvcajax-next-feed-in-queue-nonce' ),
                    'noticeDismissionNonce'  => wp_create_nonce( 'tvcajax-duplicate-backup-nonce' ),
                )
            );
        }
        /**
         * Registers all required java scripts for the feed manager Settings page.
         */
        public function tvc_register_required_options_page_scripts() {
            // enqueue notice handling script
            //wp_enqueue_style( 'tvc-product-feed-manager-setting', ENHANCAD_PLUGIN_URL . '/css/tvc_setting-page' . $this->_js_min . '.css', '', $this->_version_stamp, 'screen' );
            //wp_enqueue_script( 'tvc_message-handling-script', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_msg_events' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
            //wp_enqueue_style('font_awesome','//use.fontawesome.com/releases/v5.0.13/css/all.css');
            //==wp_enqueue_script( 'tvc_backup-list-script', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_backup-list'. $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp,'screen' );
            wp_enqueue_script( 'tvc_data-handling-script', ENHANCAD_PLUGIN_URL . '/includes/data/js/tvc_ajaxdatahandling' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
            //wp_enqueue_script( 'tvc_setting-script', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_setting-form' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
            //wp_enqueue_script( 'tvc_event-listener-script', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_feed-form-events' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
            //wp_enqueue_script( 'tvc_form-support-script', ENHANCAD_PLUGIN_URL . '/includes/user-interface/js/tvc_support' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
        }
        /**
         * Generate the nonce's for the Settings page.
         */
        public function tvc_register_required_options_page_nonce() {
            // make a unique nonce for all Ajax requests
            wp_localize_script(
                'tvc_data-handling-script',
                'myAjaxNonces',
                array(
                    // URL to wp-admin/admin-ajax.php to process the request
                    'ajaxurl'                      => admin_url( 'admin-ajax.php' ),
                    // generate the required nonce's
                    'setAutoFeedFixNonce'          => wp_create_nonce( 'tvcajax-auto-feed-fix-nonce' ),
                    'setBackgroundModeNonce'       => wp_create_nonce( 'tvcajax-background-mode-nonce' ),
                    'setFeedLoggerStatusNonce'     => wp_create_nonce( 'tvcajax-logger-status-nonce' ),
                    'setShowPINonce'               => wp_create_nonce( 'tvcajax-show-pi-nonce' ),
                    'setThirdPartyKeywordsNonce'   => wp_create_nonce( 'tvcajax-set-third-party-keywords-nonce' ),
                    'setNoticeMailaddressNonce'    => wp_create_nonce( 'tvcajax-set-notice-mailaddress-nonce' ),
                    'setBatchProcessingLimitNonce' => wp_create_nonce( 'tvcajax-set-batch-processing-limit-nonce' ),
                    'backupNonce'                  => wp_create_nonce( 'tvcajax-backup-nonce' ),
                    'deleteBackupNonce'            => wp_create_nonce( 'tvcajax-delete-backup-nonce' ),
                    'restoreBackupNonce'           => wp_create_nonce( 'tvcajax-restore-backup-nonce' ),
                    'duplicateBackupNonce'         => wp_create_nonce( 'tvcajax-duplicate-backup-nonce' ),
                    'postBackupListNonce'          => wp_create_nonce( 'tvcajax-backups-list-nonce' ),
                    'postSetupOptionsNonce'        => wp_create_nonce( 'tvcajax-setting-options-nonce' ),
                    'setClearFeedProcessNonce'     => wp_create_nonce( 'tvcajax-clear-feed-nonce' ),
                    'setReInitiateNonce'           => wp_create_nonce( 'tvcajax-reinitiate-nonce' ),
                )
            );
        }
        public function tvc_register_level_one_scripts() {
            if ( ! tvc_on_own_main_plugin_page() ) {
                return;
            }
            $data               = new TVC_Data;
            $installed_channels = $data->get_channels();
            wp_enqueue_script( 'tvc_channel-functions-script', ENHANCAD_PLUGIN_URL . '/includes/application/js/tvc_channel-functions' . $this->_js_min . '.js', array( 'jquery' ), $this->_version_stamp, true );
            foreach ( $installed_channels as $channel ) {
                wp_enqueue_script( 'tvc_' . $channel['short'] . '-source-script', TVC_UPLOADS_URL . '/tvc-channels/' . $channel['short'] . '/tvc_' . $channel['short'] . '-source.js', array( 'jquery' ), $this->_version_stamp, true );
            }
        }
        public function tvc_register_level_two_scripts() {
            wp_enqueue_script(
                'tvc_channel-functions-script',
                ENHANCAD_PLUGIN_URL . '/includes/application/js/tvc_channel-functions.js',
                array( 'jquery' ),
                $this->_version_stamp,
                false
            );
            wp_enqueue_script(
                'tvc_google-source-script',
                ENHANCAD_PLUGIN_URL . '/includes/application/google/tvc_google-source.js',
                array( 'jquery' ),
                $this->_version_stamp,
                false
            );
        }
    }
// End of TVC_Register_Scripts class
endif;
$my_ajax_registration_class = new TVC_Register_Scripts();
