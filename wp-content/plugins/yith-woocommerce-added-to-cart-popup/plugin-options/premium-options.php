<?php
/**
 * Premium tab options array
 *
 * @author  YITH
 * @package YITH WooCommerce Added to Cart Popup
 * @version 1.0.0
 */

defined( 'YITH_WACP' ) || exit; // Exit if accessed directly.


return array(
	'premium' => array(
		'home' => array(
			'type'   => 'custom_tab',
			'action' => 'yith_wacp_premium',
		),
	),
);
