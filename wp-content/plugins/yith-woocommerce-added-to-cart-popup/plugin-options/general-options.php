<?php
/**
 * General tab options array
 *
 * @author  YITH
 * @package YITH WooCommerce Added to Cart Popup
 * @version 1.0.0
 */

defined( 'YITH_WACP' ) || exit; // Exit if accessed directly.

$general = array(

	'general' => array(

		array(
			'title' => __( 'General Options', 'yith-woocommerce-added-to-cart-popup' ),
			'type'  => 'title',
			'desc'  => '',
			'id'    => 'yith-wacp-general-options',
		),

		array(
			'title'     => __( 'Show product image', 'yith-woocommerce-added-to-cart-popup' ),
			'desc'      => __( 'Choose to show product image in the popup', 'yith-woocommerce-added-to-cart-popup' ),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'no',
			'id'        => 'yith-wacp-show-image',
		),

		array(
			'title'     => __( 'Show product price', 'yith-woocommerce-added-to-cart-popup' ),
			'desc'      => __( 'Choose to show product price in the popup', 'yith-woocommerce-added-to-cart-popup' ),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'no',
			'id'        => 'yith-wacp-show-price',
		),

		array(
			'title'     => __( 'Show "View Cart" Button', 'yith-woocommerce-added-to-cart-popup' ),
			'desc'      => __( 'Choose to show "View Cart" button in the popup', 'yith-woocommerce-added-to-cart-popup' ),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'yes',
			'id'        => 'yith-wacp-show-go-cart',
		),

		array(
			'title'     => __( 'Show "Continue Shopping" Button', 'yith-woocommerce-added-to-cart-popup' ),
			'desc'      => __( 'Choose to show "Continue Shopping" button in the popup', 'yith-woocommerce-added-to-cart-popup' ),
			'type'      => 'yith-field',
			'yith-type' => 'onoff',
			'default'   => 'yes',
			'id'        => 'yith-wacp-show-continue-shopping',
		),

		array(
			'title'        => __( 'Button Background', 'yith-woocommerce-added-to-cart-popup' ),
			'type'         => 'yith-field',
			'yith-type'    => 'multi-colorpicker',
			'desc'         => '',
			'id'           => 'yith-wacp-button-background',
			'colorpickers' => array(
				array(
					'name'    => _x( 'Default color', '[admin]Plugin option label', 'yith-woocommerce-added-to-cart-popup' ),
					'id'      => 'normal',
					'default' => '#ebe9eb',
				),
				array(
					'name'    => _x( 'Hover color', '[admin]Plugin option label', 'yith-woocommerce-added-to-cart-popup' ),
					'id'      => 'hover',
					'default' => '#dad8da',
				),
			),
		),

		array(
			'title'        => __( 'Button Text', 'yith-woocommerce-added-to-cart-popup' ),
			'type'         => 'yith-field',
			'yith-type'    => 'multi-colorpicker',
			'desc'         => '',
			'id'           => 'yith-wacp-button-text',
			'colorpickers' => array(
				array(
					'name'    => _x( 'Default color', '[admin]Plugin option label', 'yith-woocommerce-added-to-cart-popup' ),
					'id'      => 'normal',
					'default' => '#515151',
				),
				array(
					'name'    => _x( 'Hover color', '[admin]Plugin option label', 'yith-woocommerce-added-to-cart-popup' ),
					'id'      => 'hover',
					'default' => '#515151',
				),
			),
		),

		array(
			'type' => 'sectionend',
			'id'   => 'yith-wacp-general-options',
		),
	),
);

return apply_filters( 'yith_wacp_panel_general_options', $general );
