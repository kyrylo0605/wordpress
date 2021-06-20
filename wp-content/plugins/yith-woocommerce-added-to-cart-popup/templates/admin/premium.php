<?php
/**
 * Premium tab template
 *
 * @author  YITH
 * @package YITH WooCommerce Added to Cart Popup
 * @version 1.0.0
 */

defined( 'YITH_WACP' ) || exit;
?>

<style>
.section{
	margin-left: -20px;
	margin-right: -20px;
	font-family: "Raleway",san-serif;
}
.section h1{
	text-align: center;
	text-transform: uppercase;
	color: #808a97;
	font-size: 35px;
	font-weight: 700;
	line-height: normal;
	display: inline-block;
	width: 100%;
	margin: 50px 0 0;
}
.section ul{
	list-style-type: disc;
	padding-left: 15px;
}
.section:nth-child(even){
	background-color: #fff;
}
.section:nth-child(odd){
	background-color: #f1f1f1;
}
.section .section-title img{
	display: table-cell;
	vertical-align: middle;
	width: auto;
	margin-right: 15px;
}
.section h2,
.section h3 {
	display: inline-block;
	vertical-align: middle;
	padding: 0;
	font-size: 24px;
	font-weight: 700;
	color: #808a97;
	text-transform: uppercase;
}

.section .section-title h2{
	display: table-cell;
	vertical-align: middle;
	line-height: 25px;
	border: none;
	background: transparent;
}

.section-title{
	display: table;
}

.section h3 {
	font-size: 14px;
	line-height: 28px;
	margin-bottom: 0;
	display: block;
}

.section p{
	font-size: 13px;
	margin: 25px 0;
}
.section ul li{
	margin-bottom: 4px;
}
.landing-container{
	max-width: 750px;
	margin-left: auto;
	margin-right: auto;
	padding: 50px 0 30px;
}
.landing-container:after{
	display: block;
	clear: both;
	content: '';
}
.landing-container .col-1,
.landing-container .col-2{
	float: left;
	box-sizing: border-box;
	padding: 0 15px;
}
.landing-container .col-1 img{
	width: 100%;
}
.landing-container .col-1{
	width: 55%;
}
.landing-container .col-2{
	width: 45%;
}
.premium-cta{
	background-color: #808a97;
	color: #fff;
	border-radius: 6px;
	padding: 20px 15px;
}
.premium-cta:after{
	content: '';
	display: block;
	clear: both;
}
.premium-cta p{
	margin: 7px 0;
	font-size: 14px;
	font-weight: 500;
	display: inline-block;
	width: 60%;
}
.premium-cta a.button{
	border-radius: 6px;
	height: 60px;
	float: right;
	background: url(<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/upgrade.png) #ff643f no-repeat 13px 13px;
	border-color: #ff643f;
	box-shadow: none;
	outline: none;
	color: #fff;
	position: relative;
	padding: 9px 50px 9px 70px;
}
.premium-cta a.button:hover,
.premium-cta a.button:active,
.premium-cta a.button:focus{
	color: #fff;
	background: url(<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/upgrade.png) #971d00 no-repeat 13px 13px;
	border-color: #971d00;
	box-shadow: none;
	outline: none;
}
.premium-cta a.button:focus{
	top: 1px;
}
.premium-cta a.button span{
	line-height: 13px;
}
.premium-cta a.button .highlight{
	display: block;
	font-size: 20px;
	font-weight: 700;
	line-height: 20px;
}
.premium-cta .highlight{
	text-transform: uppercase;
	background: none;
	font-weight: 800;
	color: #fff;
}

.section.one{
	background: url(<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/01-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.two{
	background: url(<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/02-bg.png) no-repeat #f1f1f1; background-position: 15% 75%
}
.section.three{
	background: url(<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/03-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.four{
	background: url(<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/04-bg.png) no-repeat #f1f1f1; background-position: 15% 75%
}
.section.five{
	background: url(<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/05-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.six{
	background: url(<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/06-bg.png) no-repeat #f1f1f1; background-position: 15% 75%
}
.section.seven{
	background: url(<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/07-bg.png) no-repeat #fff; background-position: 85% 75%
}
.section.eight{
	background: url(<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/08-bg.png) no-repeat #f1f1f1; background-position: 15% 75%
}
.section.nine{
	background: url(<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/09-bg.png) no-repeat #fff; background-position: 85% 75%
}

@media (max-width: 768px) {
	.section{margin: 0}
	.premium-cta p{
		width: 100%;
	}
	.premium-cta{
		text-align: center;
	}
	.premium-cta a.button{
		float: none;
	}
}

@media (max-width: 480px){
	.wrap{
		margin-right: 0;
	}
	.section{
		margin: 0;
	}
	.landing-container .col-1,
	.landing-container .col-2{
		width: 100%;
		padding: 0 15px;
	}
	.section-odd .col-1 {
		float: left;
		margin-right: -100%;
	}
	.section-odd .col-2 {
		float: right;
		margin-top: 65%;
	}
}

@media (max-width: 320px){
	.premium-cta a.button{
		padding: 9px 20px 9px 70px;
	}

	.section .section-title img{
		display: none;
	}
}
</style>
<div class="landing">
	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					<?php
					// translators: placeholders are used for add html tag.
					echo sprintf( esc_html__( 'Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Added to Cart Popup%2$s to benefit from all features!', 'yith-woocommerce-added-to-cart-popup' ), '<span class="highlight">', '</span>' );
					?>
				</p>
				<a href="<?php echo esc_url( $this->get_premium_landing_uri() ); ?>" target="_blank" class="premium-cta-button button btn">
					<span class="highlight"><?php esc_html_e( 'UPGRADE', 'yith-woocommerce-added-to-cart-popup' ); ?></span>
					<span><?php esc_html_e( 'to the premium version', 'yith-woocommerce-added-to-cart-popup' ); ?></span>
				</a>
			</div>
		</div>
	</div>
	<div class="one section section-even clear">
		<h1><?php esc_html_e( 'Premium Features', 'yith-woocommerce-added-to-cart-popup' ); ?></h1>
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/01.png" alt="Shop or detail page?" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/01-icon.png" alt="icon 01"/>
					<h2><?php esc_html_e( 'Shop or detail page?', 'yith-woocommerce-added-to-cart-popup' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: placeholders are used for add <b> html tag.
					echo sprintf( esc_html__( 'Pick your choice and %1$sshow the popup%2$s just to users that add the product in the cart from the Shop page, or also to those who make the same action from the product detail page.', 'yith-woocommerce-added-to-cart-popup' ), '<b>', '</b>' );
					?>
				</p>
			</div>
		</div>
	</div>
	<div class="two section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/02-icon.png" alt="icon 02" />
					<h2><?php esc_html_e( 'Popup content', 'yith-woocommerce-added-to-cart-popup' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: placeholders are used for add <b> html tag.
					echo sprintf( esc_html__( 'What will you choose? %1$sThe list of the elements of the cart, or the information about the last product added?%2$s It\'s up to you: choose what to show in the popup that will be opened once clicked on the "Add to cart" button.%3$s If you want to provide further details in addition to the cart total, you can show the amount of the %1$sshipping fees%2$s and %1$staxes%2$s. ', 'yith-woocommerce-added-to-cart-popup' ), '<b>', '</b>', '<br>' );
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/02.png" alt="Popup content" />
			</div>
		</div>
	</div>
	<div class="three section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/03.png" alt="Animations" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/03-icon.png" alt="icon 03" />
					<h2><?php esc_html_e( '8 entrance animations', 'yith-woocommerce-added-to-cart-popup' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: placeholders are used for add <b> html tag.
					echo sprintf( esc_html__( 'Eight different styles for the opening of the popup in the pages of your shop.%3$sFrom the classic "Fade" effect, to the more dynamic "3D Flip": with %1$sYITH WooCommerce Added To Cart Popup%2$s you will satisfy also the most demanding users.', 'yith-woocommerce-added-to-cart-popup' ), '<b>', '</b>', '<br>' );
					?>
				</p>
			</div>
		</div>
	</div>
	<div class="four section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/04-icon.png" alt="icon 04" />
					<h2><?php esc_html_e( 'Popup style', 'yith-woocommerce-added-to-cart-popup' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: placeholders are used for add <b> html tag.
					echo sprintf( esc_html__( 'The default color scheme of the popup can be totally different from the one of the pages in which it will be showed: but do not be afraid, you will not have to change manually the CSS code to have the perfect style.%3$sThanks to the %1$sadvanced option panel%2$s, you will be able to change the colors of all elements contained in the popup, in order to get to the result you want with few clicks.', 'yith-woocommerce-added-to-cart-popup' ), '<b>', '</b>', '<br>' );
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/04.png" alt="popup style" />
			</div>
		</div>
	</div>
	<div class="five section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/05.png" alt="Related products" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/05-icon.png" alt="icon 05" />
					<h2><?php esc_html_e( 'Suggested Products', 'yith-woocommerce-added-to-cart-popup' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: placeholders are used for add <b> html tag.
					echo sprintf( esc_html__( '%1$sA strategic and smart way to highlight%2$s a selection of products in the end of the popup. In this way, you will be able to encourage your users to know more about the products they could have not discovered otherwise.%3$sIf you don\'t want to highlight particular products, this section could also be reserved to WooCommerce %1$s"up-sell"%2$s, %1$s"cross-sell"%2$s or %1$s"related"%2$s products.', 'yith-woocommerce-added-to-cart-popup' ), '<b>', '</b>', '<br>' );
					?>
				</p>
			</div>
		</div>
	</div>
	<div class="six section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/06-icon.png" alt="icon 06" />
					<h2><?php esc_html_e( 'Mobile devices', 'yith-woocommerce-added-to-cart-popup' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: placeholders are used for add <b> html tag.
					echo sprintf( esc_html__( 'Nowadays, the majority of the users that surf the web uses mobile devices. Be prepared and make your site compatible to every device.%3$sThe premium version of the plugin is %1$sperfectly accessible to every resolution%2$s, and it can be the winning weapon of your shop for all the smartphone and tablet lovers.', 'yith-woocommerce-added-to-cart-popup' ), '<b>', '</b>', '<br>' );
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/06.png" alt="Mobile devices" />
			</div>
		</div>
	</div>
	<div class="seven section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/07.png" alt="Product exclusion" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/07-icon.png" alt="icon 07" />
					<h2><?php esc_html_e( 'Product exclusion', 'yith-woocommerce-added-to-cart-popup' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: placeholders are used for add <b> html tag.
					echo sprintf( esc_html__( 'Restrict the features of the plugin only to %1$sspecific products%2$s of the shop.%3$sSelect those on which you want to show the popup to your users, or choose the categories of the products you want to exclude from the plugin behavior.', 'yith-woocommerce-added-to-cart-popup' ), '<b>', '</b>', '<br>' );
					?>
				</p>
			</div>
		</div>
	</div>
	<div class="eight section section-odd clear">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/08-icon.png" alt="icon 08" />
					<h2><?php esc_html_e( 'Add to cart', 'yith-woocommerce-added-to-cart-popup' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: placeholders are used for add <b> html tag.
					echo sprintf( esc_html__( 'User experience is essential on your site. It is well-known that an %1$seasy purchase process encourages%2$s the users to increase their cart.', 'yith-woocommerce-added-to-cart-popup' ), '<b>', '</b>' );
					?>
				</p>
				<p>
					<?php
					// translators: placeholders are used for add <b> html tag.
					echo sprintf( esc_html__( 'With the premium version of the plugin, you can give your users the possibility to add the %1$ssuggested products%2$s to the cart without abandoning the modal window.  ', 'yith-woocommerce-added-to-cart-popup' ), '<b>', '</b>' );
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/08.png" alt="Add to cart" />
			</div>
		</div>
	</div>
	<div class="nine section section-even clear">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/09.jpg" alt="Product exclusion" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_url( YITH_WACP_ASSETS_URL ); ?>/images/09-icon.png" alt="icon 09" />
					<h2><?php esc_html_e( 'Add a floating cart icon to your shop.', 'yith-woocommerce-added-to-cart-popup' ); ?></h2>
				</div>
				<p>
					<?php
					// translators: placeholders are used for add <b> html tag.
					echo sprintf( esc_html__( 'This way, your users can find the cart icon and check what\'s in the cart at any time. Set up the position of the cart icon as it should appear on the screen and it will always appear there, even if you scroll the page. This way, your users will be able to see the counter of products added to the cart and what\'s in it at any time!', 'yith-woocommerce-added-to-cart-popup' ), '<b>', '</b>', '<br>' );
					?>
				</p>
			</div>
		</div>
	</div>
	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					<?php
					// translators: placeholders are used for add html tag.
					echo sprintf( esc_html__( 'Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Added to Cart Popup%2$s to benefit from all features!', 'yith-woocommerce-added-to-cart-popup' ), '<span class="highlight">', '</span>' );
					?>
				</p>
				<a href="<?php echo esc_url( $this->get_premium_landing_uri() ); ?>" target="_blank" class="premium-cta-button button btn">
					<span class="highlight"><?php esc_html_e( 'UPGRADE', 'yith-woocommerce-added-to-cart-popup' ); ?></span>
					<span><?php esc_html_e( 'to the premium version', 'yith-woocommerce-added-to-cart-popup' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</div>
