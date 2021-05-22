<?php

/**
 * Wp in Progress
 *
 * @package Wordpress
 * @author WPinProgress
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * It is also available at this URL: http://www.gnu.org/licenses/gpl-3.0.txt
 */

$optpanel = array (

	array (	"name" => "Navigation",
			"type" => "navigation",
			"item" => array(

				"Chatbox_Generator"	=> esc_html__( "Chatbox Generator","chatbox-manager"),
				"free_vs_pro" => esc_html__( "Free vs Pro","chatbox-manager"),
				CM_SALE_PAGE . "cm-panel"	=> esc_html__( "Upgrade to Premium","chatbox-manager"),

			),

			"start" => "<ul>",
			"end" => "</ul>"
	),

	array(	"tab" => "Chatbox_Generator",
			"element" =>

		array(	"type" => "start-form",
				"name" => "Chatbox_Generator"),

			array(	"type" => "start-open-container",
					"name" => esc_html__( "Chatbox Generator","chatbox-manager")),

				array(	"name" => esc_html__( "Chatbox Generator","chatbox-manager"),
						"desc" => esc_html__( "Click on 'New chatbox' button to add a new chatbox.","chatbox-manager"),
						"id" => "chatbox_manager_chatboxes",
						"data" => "array",
						"type" => "chatboxGenerator",
						"std" => ""),

			array(	"type" => "end-container"),

		array(	"type" => "end-form"),

	),
	
	array(	"tab" => "free_vs_pro",
			"element" =>

		array(	"type" => "start-form",
				"name" => "free_vs_pro"),

			array(	"type" => "start-open-container",
					"name" => esc_html__( "Free vs Pro", "chatbox-manager")),

				array(	"name" => esc_html__( "Free vs Pro", "chatbox-manager"),
						"type" => "free_vs_pro"),

			array(	"type" => "end-container"),

		array(	"type" => "end-form"),

	),

	array(	"type" => "end-tab"),

);

new chatbox_manager_panel ($optpanel);

?>
