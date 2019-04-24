<?php
/**
* Template for Inner Banner Section for all the inner pages
*
* @since Education Booster 1.0.0
*/
?>
<section class="wrapper section-banner-wrap">
<div class="wrap-inner-banner" style="background-image: url('<?php header_image(); ?>')">
	<div class="container">
		<header class="page-header">
			<div class="inner-header-content">
				<h1 class="page-title"><?php echo esc_html( $args[ 'title' ] ); ?></h1>
				<?php if( $args[ 'description' ] ): ?>
					<div class="page-description">
						<?php echo esc_html( $args[ 'description' ] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</header>
	</div>
</div>
<?php if(!is_front_page() ): ?>
	<div class="breadcrumb-wrap">
		<div class="container">
			<?php
				educationbooster_breadcrumb();
			?>
		</div>
	</div>
<?php endif; ?>
</section>