    <footer id="footer">
        
		<?php do_action('bazaarlite_footer_sidebar'); ?>
                
        <section id="footer-copyright">
                
            <div class="container">
        
                <div class="row" >
                    
                    <div class="col-md-5" >
                        
                        <div class="copyright">

                            <p>
        
                                <?php 
                                
                                    if ( bazaarlite_setting('wip_copyright_text')) :
                                    
                                        echo wp_filter_post_kses(bazaarlite_setting('wip_copyright_text'));
                                        
                                    else:
                                    
                                        esc_html_e('Copyright ', 'bazaar-lite');
                                        echo esc_html(get_bloginfo('name'));
                                        echo esc_html( date_i18n( __( ' Y', 'bazaar-lite' )));
                                    
                                    endif;
                                    
                                ?>
        
                                <a href="<?php echo esc_url('https://www.themeinprogress.com/'); ?>" target="_blank"><?php printf( esc_html__( ' | Theme by %s', 'bazaar-lite' ), 'ThemeinProgress' ); ?></a>
                                <a href="<?php echo esc_url('http://wordpress.org/'); ?>" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'bazaar-lite' ); ?>" rel="generator"><?php printf( esc_html__( ' | Proudly powered by %s', 'bazaar-lite' ), 'WordPress' ); ?></a>
                                    
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