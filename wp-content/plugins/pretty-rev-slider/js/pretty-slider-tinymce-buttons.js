(function() {
	tinymce.create('tinymce.plugins.buttonPlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mcebutton', function() {
				ed.windowManager.open({
					file : url + '/../includes/button_popup.php', // file that contains HTML for our modal window
					width : 340 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 350 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : 1
				}, {
					plugin_url : url
				});
			});
			 
			// Register buttons
			ed.addButton('pretty_slider_friendly_button', {title : 'WordPress Pretty Slider', cmd : 'mcebutton', image: url + '/../includes/images/images.png' });
		},
		 
		getInfo : function() {
			return {
				longname : 'WordPress Pretty Slider',
				author : 'crAzy coDer',
				authorurl : 'http://raihanb.com/premium/pretty-slider',
				infourl : 'http://raihanb.com/premium/pretty-slider',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	 
	// Register plugin
	// first parameter is the button ID and must match ID elsewhere
	// second parameter must match the first parameter of the tinymce.create() function above
	tinymce.PluginManager.add('pretty_slider_button', tinymce.plugins.buttonPlugin);

})();