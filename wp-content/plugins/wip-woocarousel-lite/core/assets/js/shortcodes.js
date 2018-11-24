(function() {
	tinymce.PluginManager.add( 'wip_woocarousel_lite_shortcode_generator', function( editor, url ) {
		editor.addButton( 'wip_woocarousel_lite_shortcode_generator', {
			title: 'WooCarousel Lite',
			type: 'menubutton',
			icon: 'icon add-shortcodes-icon',
			menu: [

				/** Layout **/
				{
					text: 'Shortcode',
					menu: [

						/* Columns */
						{
							text: 'Product Carousel',
							onclick: function() {
								editor.windowManager.open( {
									title: 'Manage this WooCommerce carousel.',
									body: [

									// Numbers of items
									{
										type: 'textbox', 
										name: 'productItems', 
										label: 'Numbers of items ( Add -1 to display all products. )',
										value: '-1'
									},

									// Best seller products
									{
										type: 'listbox',
										name: 'productBestseller',
										label: 'Do you want to display first the best seller products?',
										'values': [
											{text: 'Off', value: 'off'},
											{text: 'On', value: 'on'},
										]
									},

									// Carousel Columns
									{
										type: 'textbox', 
										name: 'carouselColumns', 
										label: 'Set the number of columns.',
										value: '3'
									},
									
									// Carousel rating
									{
										type: 'listbox',
										name: 'productRating',
										label: 'Do you want to show the product rating?',
										'values': [
											{text: 'Off', value: 'off'},
											{text: 'On', value: 'on'},
										]
									},


									// Latest products
									{
										type: 'listbox',
										name: 'productLatest',
										label: 'Do you want to display first the latest products?',
										'values': [
											{text: 'Off', value: 'off'},
											{text: 'On', value: 'on'},
										]
									},

									// Dots
									{
										type: 'listbox',
										name: 'carouselDots',
										label: 'Do you want to show the dots?',
										'values': [
											{text: 'Off', value: 'off'},
											{text: 'On', value: 'on'},
										]
									},

									],

									// Column generator

									onsubmit: function( e ) {

										editor.insertContent( '[wip_woocarousel_products_carousel' + 
										
										' product_items="' + e.data.productItems + '"' + 
										' product_bestseller="' + e.data.productBestseller + '"' + 
										' product_columns="' + e.data.carouselColumns + '"' + 
										' product_rating="' + e.data.productRating + '"' + 
										' product_latest="' + e.data.productLatest + '"' + 
										' product_dots="' + e.data.carouselDots + '"' + 
										
										']<br />');

									
									}
									
								});
							}
						}, // End columns

					]
				}, // End Layout Section

			]
		});
	});
})();