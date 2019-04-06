<?php
/**
 * GENERAL ARRAY OPTIONS
 */

$general = array(

	'general'  => array(

		array(
			'title' => __( 'General Options', 'yith-woocommerce-added-to-cart-popup' ),
			'type' => 'title',
			'desc' => '',
			'id' => 'yith-wacp-general-options'
		),

		array(
			'title' => __( 'Show product image', 'yith-woocommerce-added-to-cart-popup' ),
			'desc' => __( 'Choose to show product image in the popup', 'yith-woocommerce-added-to-cart-popup' ),
			'type' => 'checkbox',
			'default'   => 'no',
			'id' => 'yith-wacp-show-image'
		),

		array(
			'title' => __( 'Show product price', 'yith-woocommerce-added-to-cart-popup' ),
			'desc' => __( 'Choose to show product price in the popup', 'yith-woocommerce-added-to-cart-popup' ),
			'type' => 'checkbox',
			'default'   => 'no',
			'id' => 'yith-wacp-show-price'
		),

		array(
			'title' => __( 'Show "View Cart" Button', 'yith-woocommerce-added-to-cart-popup' ),
			'desc' => __( 'Choose to show "View Cart" button in the popup', 'yith-woocommerce-added-to-cart-popup' ),
			'type' => 'checkbox',
			'default'   => 'yes',
			'id' => 'yith-wacp-show-go-cart'
		),

		array(
			'title' => __( 'Show "Continue Shopping" Button', 'yith-woocommerce-added-to-cart-popup' ),
			'desc' => __( 'Choose to show "Continue Shopping" button in the popup', 'yith-woocommerce-added-to-cart-popup' ),
			'type' => 'checkbox',
			'default'   => 'yes',
			'id' => 'yith-wacp-show-continue-shopping'
		),

		array(
			'title' => __( 'Button Background', 'yith-woocommerce-added-to-cart-popup' ),
			'desc'  => __( 'Select the button background color', 'yith-woocommerce-added-to-cart-popup' ),
			'type'  => 'color',
			'default'   => '#ebe9eb',
			'id'    => 'yith-wacp-button-background'
		),

		array(
			'title' => __( 'Button Background on Hover', 'yith-woocommerce-added-to-cart-popup' ),
			'desc'  => __( 'Select the button background color on mouse hover', 'yith-woocommerce-added-to-cart-popup' ),
			'type'  => 'color',
			'default'   => '#dad8da',
			'id'    => 'yith-wacp-button-background-hover'
		),

		array(
			'title' => __( 'Button Text', 'yith-woocommerce-added-to-cart-popup' ),
			'desc'  => __( 'Select the color of the text of the button', 'yith-woocommerce-added-to-cart-popup' ),
			'type'  => 'color',
			'default'   => '#515151',
			'id'    => 'yith-wacp-button-text'
		),

		array(
			'title' => __( 'Button Text on Hover', 'yith-woocommerce-added-to-cart-popup' ),
			'desc'  => __( 'Select the color of the text of the button on mouse hover', 'yith-woocommerce-added-to-cart-popup' ),
			'type'  => 'color',
			'default'   => '#515151',
			'id'    => 'yith-wacp-button-text-hover'
		),

		array(
			'type'      => 'sectionend',
			'id'        => 'yith-wacp-general-options'
		)
	)
);

return apply_filters( 'yith_wacp_panel_general_options', $general );