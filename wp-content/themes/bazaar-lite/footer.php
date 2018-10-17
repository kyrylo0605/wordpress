    <footer id="footer">
        
		<?php do_action('bazaarlite_footer_sidebar'); ?>
                
        <section id="footer-copyright">
                
            <div class="container">
        
                <div class="row" >
                    
                    <div class="col-md-5" >
                        
                        <div class="copyright">
        
                            <p>
                                
								<?php if (bazaarlite_setting('wip_copyright_text')): ?>
                                   <?php echo wp_filter_post_kses(bazaarlite_setting('wip_copyright_text')); ?>
                                <?php else: ?>
                                  <?php esc_html_e('Copyright','bazaar-lite'); ?> <?php echo get_bloginfo("name"); ?> <?php echo date_i18n("Y"); ?> 
                                <?php endif; ?> 
                                | <?php esc_html_e('Theme by','bazaar-lite'); ?> <a href="<?php echo esc_url('https://www.themeinprogress.com/'); ?>" target="_blank">Theme in Progress</a> |
                                <a href="<?php echo esc_url('http://wordpress.org/'); ?>" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'bazaar-lite' ); ?>" rel="generator"><?php printf( esc_html__( 'Proudly powered by %s', 'bazaar-lite' ), 'WordPress' ); ?></a>
                            
                            </p>

                        </div>
                    
                    </div>
                
                    <div class="col-md-7" >
        
                        <div class="social-buttons">
                        
                            <?php do_action( 'bazaarlite_socials' ); ?>
                        
                        </div>
                        
                    </div>
                
                </div>
                
            </div>
    
        </section>

    </footer>

	<div id="back-to-top"> <i class="fa fa-chevron-up"></i> </div>

</div>

<?php wp_footer() ?>  
 
</body>

</html>