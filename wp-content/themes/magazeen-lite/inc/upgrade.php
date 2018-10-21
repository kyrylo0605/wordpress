<?php
add_action( 'admin_menu', 'draft_portfolio_admin_menu' );

function draft_portfolio_admin_menu() {

	add_theme_page( 'Upgrade To PRO', 'Upgrade To PRO', 'manage_options', 'pro-upgrade', 'draft_portfolio_admin_page');

}

function draft_portfolio_admin_page(){
	?>
	<style type="text/css">
	.col-1-2 { width: 50%; float: left; text-align: left;}
	img {width: 100%;}
	.wrap h2.page-title { margin: 0 0 0px; font-size: 40px; font-weight: bold; text-align: center;}
	ul {margin: 0; padding: 20px 40px;}
	ul li {font-weight: bold;}
	ul li ul li { font-weight: normal;}
	ul ul { padding: 0 20px;}
	.padded h1 { padding: 0 40px}
	.aligncenter {text-align: center; padding: 20px 0;}
	a.demo {text-decoration: none; border-radius: 5px; background: #fff; color: #000; padding: 10px 15px;}
	a.buy {text-decoration: none; border-radius: 3px; background: red; color: #fff; padding: 10px 15px; margin-left: 10px;}
	li a {}
	</style>
	<div class="wrap">
		<h2 class="page-title">Upgrade to Magazeen PRO</h2>
		<div class="aligncenter"><i>Use the discount code " <b>wporg</b> " & get 20% OFF today</i></div>
		<div class="col-1-2">
			<img src="https://thepixeltribe.com/wp-content/uploads/2016/09/magazeen-main-shot-png.png">
		</div>

		<div class="col-1-2 padded">
		<h1>5 Solid reasons to Upgrade Today</h1>
		<ul>
			<li>More Customization Options
			<ul>
				<li>Upload a custom logo</li>
				<li>Choose from over 600 google fonts</li>
				<li>Change your theme colors</li>
				<li>Better comments</li>
			</ul>
			</li>
			<li>A Swanky New Homepage layout
			<ul>
				<li>Static faetured content grid</li>
				<li>Classic featured content slider </li>
			</ul>
			</li>
			<li>2 more page layouts
			<ul>
				<li>Simple Blog layout</li>
				<li>Masonry grid layout</li>
			</ul>
			</li>
			<li>Custom Widgets
			<ul>
				<li>Feature content</li>
				<li>Popular content</li>
				<li>Instagram</li>
			</ul>
			</li>
			<li>One on One Email Support
			<ul>
				<li>Professional support, from installation to upgrades</li>
				
			</ul>
			</li>

		</ul>
			<ul><li>
				<a class="demo" target="_blank" href="http://themes.thepixeltribe.com/?theme=magazeen">Live Demo</a>
				<a class="buy" target="_blank" href="https://thepixeltribe.com/template/magazeen/">Upgrade Now</a>
			</li></ul>
		</div>
	</div>
<?php }