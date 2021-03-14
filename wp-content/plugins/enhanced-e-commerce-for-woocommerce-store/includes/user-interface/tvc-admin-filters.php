<?php

/**
 * @package Google Product Feed Manager/User Interface/Functions
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Adds links to the started guide and premium site in the plugin description on the Plugins page.
 *
 * @since 2.6.0
 *
 * @param   array   $actions        Associative array of action names to anchor tags.
 * @param   string  $plugin_file    Plugin file name.
 * @param   array   $plugin_data    Array of plugin data from the plugin file.
 * @param   string  $context        Plugin status context.
 *
 * @return  array   Html code that adds links to the plugin description.
 */
function tvc_plugins_action_links( $actions, $plugin_file, $plugin_data, $context ) {
    $actions['starter_guide'] = '<a href="#">' . __( 'FAQ', 'tvc-product-feed-manager' ) . '</a>';
    return $actions;
}

add_filter( 'plugin_action_links_' . TVC_PLUGIN_CONSTRUCTOR, 'tvc_plugins_action_links', 10, 4 );

function tvc_change_query_filter() {
    return 100;
}

add_filter( 'tvc_product_query_limit', 'tvc_change_query_filter' );