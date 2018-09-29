<?php 
/*
Plugin Name: Wordpress Pretty Rev Slider
Plugin URI: http://raihanb.com/premium/pretty-rev-slider
Description: This plugin will enable pretty slider in your WordPress site. You can change color & other setting from <a href="options-general.php?page=pretty-slider-settings">Option Panel</a>
Author: crAzy coDer
Author URI:http://plugime.com/
Version: 1.0
*/


// This code enable for widget shortcode support
add_filter('widget_text', 'do_shortcode');

/* Adding Latest jQuery from WordPress */
function wp_pretty_slider_wp_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'wp_pretty_slider_wp_jquery');

/* Adding pretty slider js file*/
function include_slider_file_js() {

	wp_enqueue_script( 'pretty-slider-jquery', plugins_url( 'js/jquery.cslider.js', __FILE__ ), array('jquery'));	
	wp_enqueue_script( 'pretty-slider-modernizr', plugins_url( 'js/modernizr.custom.28468.js', __FILE__ ), array('jquery'));
	wp_enqueue_script( 'pretty-slider-admintab', plugins_url( 'js/admin_tab.js', __FILE__ ), array('jquery'));
	
	
}
add_action('wp_enqueue_scripts', 'include_slider_file_js');



/* Adding pretty slider main css and custom css file*/
function include_slider_file_css() {
    
    wp_enqueue_style( 'pretty-slider-demo-css', plugins_url( '/css/demo.css', __FILE__ ));
    wp_enqueue_style( 'pretty-slider-style-css', plugins_url( '/css/style.css', __FILE__ ));
	wp_enqueue_style( 'pretty-slider-custom-css', plugins_url( '/css/custom.css', __FILE__ ));
	wp_enqueue_style( 'pretty-slider-tab-css', plugins_url( '/css/tab.css', __FILE__ ));
    
    
}
add_action('init', 'include_slider_file_css');

/* Adding necessary scripts and css */
define('PRETTY_SLIDER', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

function admintab_function() {
	wp_enqueue_script('two-items-both-admin-tab', PRETTY_SLIDER.'js/admin_tab.js', array('jquery'));
}
add_action('admin_head', 'admintab_function');


/* Active*/
function slider_tab_active() {?>
    
<script type="text/javascript">
	jQuery(document).ready(function(){
	   jQuery('#tab-container').easytabs();
	});	
</script> 
 
<?php    
}
add_action('wp_footer', 'slider_tab_active');

// Default options values
$pretty_slider_options = array(	
	'plugin_active_deactive' => 'block',
	'slider_title_color' => '#4d4f52',
	'slider_content_color' => '#4d4f52',
	'border_top_bottom_color' => '#4d4f52',
	'middle_title_content' => 'dont_middle_title_content',
	'border_radius' => '0',
	'bullet_radius' => '0',
	'arrow_bg_color' => '#555',


);

            

/*  Pretty Slider Shortcode*/
 function pretty_slider_shortcode ($atts) {
 
  global $pretty_slider_options; $pretty_slider_settings = get_option( 'pretty_slider_options', $pretty_slider_options ); 
 
         extract ( shortcode_atts( array(
		 'id' =>'',		 
		 'posts_per_page' =>-1,
		 'category' =>'',		 
		 'slider_title_color' =>$pretty_slider_settings['slider_title_color'],
		 'slider_content_color' =>$pretty_slider_settings['slider_content_color'],
		 'border_top_bottom_color' =>$pretty_slider_settings['border_top_bottom_color'],
		 'arrow_bg_color' =>$pretty_slider_settings['arrow_bg_color'],
		 'middle_title_content' =>$pretty_slider_settings['middle_title_content'],
		 'border_radius' =>$pretty_slider_settings['border_radius'],
		 
		 


    ), $atts, 'category_post' ) );

      $q = new WP_Query (
            array( 'posts_per_page' =>$posts_per_page, 'post_type' =>'slider-items', 'slider_cat' => $category)
           );
  $list = '
  
  
<script type="text/javascript">

  	
		jQuery(document).ready(function(){
			jQuery(function() {
				jQuery("#da-slider'.$id.'").cslider({
				 current     : 0,
				

				});
			}); 
		}); 	
	
</script>  
  
  
        <div class="container"><div id="da-slider'.$id.'" class="da-slider" style="  border-radius:'.$border_radius.'%;border-top:8px solid '.$border_top_bottom_color.';border-bottom:8px solid '.$border_top_bottom_color.'">';

  while ($q->have_posts() ) : $q->the_post ();
 $post_thumbnail = get_the_post_thumbnail ( get_the_ID(), 'post_thumbnail' );
	$list .= '	 
	 
				<div class="da-slide">
					 <h2 style="color:'.$slider_title_color.';">'.get_the_title().'</h2>
					 <p style="color:'.$slider_content_color.';">'.get_the_content().'</p>
					 <div style="margin-top:65px;" class="da-img"> '.$post_thumbnail.'</div>
				</div>

				<nav class="da-arrows">
					<span style="background-color:'.$arrow_bg_color.'" class="da-arrows-prev"></span>
					<span style="background-color:'.$arrow_bg_color.'" class="da-arrows-next"></span>
				</nav>
				
	 
    ';
    endwhile;
    $list.= '</div></div>';
   wp_reset_query();
   return $list;
   }
   add_shortcode('pretty_slider', 'pretty_slider_shortcode');



// remove unnecessary data 
remove_filter('the_content', 'wptexturize');
remove_filter( 'the_content', 'wpautop' );

//add options framework
function add_pretty_slider_options_framework()  
{  
	add_options_page('Pretty Slider Options', 'Pretty Slider Options', 'manage_options', 'pretty-slider-settings','pretty_slider_options_framework');  
}  
add_action('admin_menu', 'add_pretty_slider_options_framework');

// add color picker
function pretty_slider_color_picker_function( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'pretty_slider_color_picker_function' );

if ( is_admin() ) : // Load only if we are viewing an admin page

function pretty_slider_register_settings() {
	// Register settings and call sanitation functions
	register_setting( 'pretty_slider_p_options', 'pretty_slider_options', 'pretty_slider_validate_options' );
}

add_action( 'admin_init', 'pretty_slider_register_settings' );


// Hide or show plugin
$plugin_active_deactive = array(
	'active_plugin_yes' => array(
		'value' => 'none',
		'label' => 'Hide plugin'
	)
);


// Middle title and content
$middle_title_content = array(
	'middle_title_content_yes' => array(
		'value' => 'middle_title_content_ta',
		'label' => 'Do you want to middle title and content ?'
	)
);



// Function to generate options page
function pretty_slider_options_framework() {
	global $pretty_slider_options, $plugin_active_deactive,$middle_title_content;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>

<div class="wrap admin_panel">
	
	
<h3 style="font-style:italic;color:red"> <a href="http://code.realwebcare.com/item/awesome-responsive-photo-gallery/">See our awesome responsive photo gallery wordpress plugin</a></h4>	

<h4 style="font-style:italic">Where you want pretty slider ? just copy the shortcode and paste where you want the pretty slider &nbsp;&nbsp;  [pretty_slider id="1" category="home" ] &nbsp;&nbsp;( atfirst you must create a category. Please go to pretty slider custom post and create category name then put your category name like  &nbsp; category="category name" then you give a unique id in shortcode like id="id number" )</h4>
	
	
	<h2>WordPress Pretty Slider</h2>

	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; // If the form has just been submitted, this shows the notification ?>

	<form method="post" action="options.php">

	<?php $settings = get_option( 'pretty_slider_options', $pretty_slider_options ); ?>
	
	<?php settings_fields( 'pretty_slider_p_options' );
	/* This function outputs some hidden fields required by the form,
	including a nonce, a unique number used to ensure the form has been submitted from the admin page
	and not somewhere else, very important for security */ ?>


	
<div id="tab-container" class='tab-container rasel_option_panel'>
 <ul class='etabs'>
   <li class='tab'><a href="#basic_settings">Basic Settings</a></li>
   <li class='tab'><a href="#advance_settings">Advance Settings</a></li>
 </ul>
 <div class='panel-container'>
  <div id="basic_settings">
   <h2>Basic Settings</h2>	
	
	
	<table class="form-table margin_top"><!-- Grab a hot cup of coffee, yes we're using tables! -->
	
		<tr>
			<td align="center"><input type="submit" class="button-secondary default_settings_button" name="pretty_slider_options[default_settings]" value="Default settings" /><p class="font_size">If you want to default settings of plugin just click default settings button.</p></td>
			<td colspan="2"><input type="submit" class="button-primary" value="Save Options" /></td>
		</tr>		

		
		<tr valign="top">
			<th scope="row"><label for="border_top_bottom_color">Slider top and bottom border color</label></th>
			<td>
				<input id="border_top_bottom_color" type="text" class="my-color-field" name="pretty_slider_options[border_top_bottom_color]" value="<?php echo isset($settings['border_top_bottom_color']) ? stripslashes($settings['border_top_bottom_color']) : ''; ?>" /><p class="description">Choose your slider top and bottom border color. Default color is #4d4f52.</p>
			</td>
		</tr>
		
		
		<tr valign="top">
			<th scope="row"><label for="slider_content_color">Slider title & content color</label></th>
			<td>
				<input id="slider_content_color" type="text" class="my-color-field" name="pretty_slider_options[slider_content_color]" value="<?php echo isset($settings['slider_content_color']) ? stripslashes($settings['slider_content_color']) : ''; ?>" /><p class="description">Choose your slider title & content color. Default color is #4d4f52.</p>
			</td>
		</tr>		

		<tr valign="top">
			<th scope="row"><label for="border_radius">Slider Border radius</label></th>
			<td>
				<input id="border_radius" type="number" style="width:80px;height:24px;padding-left:3px" name="pretty_slider_options[border_radius]" value="<?php echo isset($settings['border_radius']) ? stripslashes($settings['border_radius']) : ''; ?>" /><span style="padding-left:3px;"><strong>%</strong></span><p class="description"> Give slider border radius. Default radius is 0</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="bullet_radius">Slider Bullet border radius</label></th>
			<td>
				<input id="bullet_radius" type="number" style="width:80px;height:24px;padding-left:3px" name="pretty_slider_options[bullet_radius]" value="<?php echo isset($settings['bullet_radius']) ? stripslashes($settings['bullet_radius']) : ''; ?>" /><span style="padding-left:3px;"><strong>%</strong></span><p class="description"> Give your slider bullet border radius. Default radius is 0</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="arrow_bg_color">Next Previous Arrow Background Color</label></th>
			<td>
				<input id="arrow_bg_color" type="text" class="my-color-field" name="pretty_slider_options[arrow_bg_color]" value="<?php echo isset($settings['arrow_bg_color']) ? stripslashes($settings['arrow_bg_color']) : ''; ?>" /><p class="description"> Choose your arrow color. Default color is #555.</p>
			</td>
		</tr>
		
	</table>
  </div>		

  
  
  <div id="advance_settings">
	
	<table class="form-table margin_top">
		<h2>Advance Settings</h2> 
		
		<tr>
			<td align="center"><input type="submit" class="button-secondary default_settings_button" name="pretty_slider_options[default_settings]" value="Default settings" /><p class="font_size">If you want to default settings of plugin just click default settings button.</p></td>
			<td colspan="2"><input type="submit" class="button-primary" value="Save Options" /></td>
		</tr>			

		<tr valign="top">
			<th scope="row"><label for="middle_title_content">Middle Title and content</label></th>
			<td>
				<?php foreach( $middle_title_content as $activate ) : ?>
				<input type="checkbox" id="<?php echo $activate['value']; ?>" name="pretty_slider_options[middle_title_content]" 
				value="<?php esc_attr_e( $activate['value'] ); ?>" <?php checked( $settings['middle_title_content'], $activate['value'] ); ?> />
				<label for="<?php echo $activate['value']; ?>"><?php echo $activate['label']; ?></label><br />
				<?php endforeach; ?>
			<p class="description">If you want to middle title and content just select it. </p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="plugin_active_deactive">Hide plugin</label></th>
			<td>
				<?php foreach( $plugin_active_deactive as $activate ) : ?>
				<input type="checkbox" id="<?php echo $activate['value']; ?>" name="pretty_slider_options[plugin_active_deactive]" 
				value="<?php esc_attr_e( $activate['value'] ); ?>" <?php checked( $settings['plugin_active_deactive'], $activate['value'] ); ?> />
				<label for="<?php echo $activate['value']; ?>"><?php echo $activate['label']; ?></label><br />
				<?php endforeach; ?>
			<p class="description">You can Hide or show your plugin. If you select it your plugin will be hide if you deselect it your plugin will be show.</p>
			</td>
		</tr>		
		
	</table>
  </div>
 </div>
</div>
		
	<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>			

	</form>

</div>

	<?php
}

function pretty_slider_validate_options( $input ) {
	global $pretty_slider_options, $plugin_active_deactive, $middle_title_content;

	$settings = get_option( 'pretty_slider_options', $pretty_slider_options );
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS

	
	$input['posts_per_page'] = isset( $input['default_settings'] ) ? -1 : wp_filter_post_kses( $input['posts_per_page'] );
	$input['slider_count'] = isset( $input['default_settings'] ) ? '4' : wp_filter_post_kses( $input['slider_count'] );
	$input['slider_title_color'] = isset( $input['default_settings'] ) ? '#4d4f52' : wp_filter_post_kses( $input['slider_content_color'] );
	$input['slider_content_color'] = isset( $input['default_settings'] ) ? '#4d4f52' : wp_filter_post_kses( $input['slider_title_color'] );
	$input['border_top_bottom_color'] = isset( $input['default_settings'] ) ? '#4d4f52' : wp_filter_post_kses( $input['border_top_bottom_color'] );
	$input['middle_title_content'] = isset( $input['default_settings'] ) ? 'dont_middle_title_content' : wp_filter_post_kses( $input['middle_title_content'] );
	$input['plugin_active_deactive'] = isset( $input['default_settings'] ) ? 'block' : wp_filter_post_kses( $input['plugin_active_deactive'] );
	$input['border_radius'] = isset( $input['default_settings'] ) ? '0' : wp_filter_post_kses( $input['border_radius'] );
	$input['bullet_radius'] = isset( $input['default_settings'] ) ? '0' : wp_filter_post_kses( $input['bullet_radius'] );
	$input['arrow_bg_color'] = isset( $input['default_settings'] ) ? '#555' : wp_filter_post_kses( $input['arrow_bg_color'] );

	
	// We select the previous value of the field, to restore it in case an invalid entry has been given
	$prev = $settings['layout_only'];
	// We verify if the given value exists in the layouts array
	if ( !array_key_exists( $input['layout_only'], $plugin_active_deactive ) )
		$input['layout_only'] = $prev;
		
	$prev = $settings['layout_only'];
	// We verify if the given value exists in the layouts array
	if ( !array_key_exists( $input['layout_only'], $middle_title_content ) )
		$input['layout_only'] = $prev;	

	return $input;
}

endif;  // EndIf is_admin()




	//  Custom post title name
	
function change_default_slider_title( $title ){
     $screen = get_current_screen();
     if  ( 'slider-items' == $screen->post_type ) {
          $title = 'Enter pretty slider title here';
     }
     return $title;
}
add_filter( 'enter_title_here', 'change_default_slider_title' );

    //   Pretty slider custom post
            add_action( 'init', 'pretty_custom_post' );
            function pretty_custom_post() {
                    register_post_type( 'slider-items',
                            array(
                                    'labels' => array(
                                            'name' => __( 'Pretty Slider' ),
                                            'singular_name' => __( 'Pretty Slider' ),
                                            'add_new' => __( 'Add New Slider' ),
                                            'add_new_item' => __( 'Add New Pretty Slider' ),
                                            'edit_item' => __( 'Edit Pretty Slider' ),
                                            'new_item' => __( 'New Pretty Slider' ),
                                            'view_item' => __( 'View Pretty Slider' ),
                                            'not_found' => __( 'Sorry, we couldn\'t find the Pretty Slider you are looking for.' )
                                    ),
                            'public' => true,
                            'publicly_queryable' => false,
                            'show_in_admin_bar' => true,
                            'exclude_from_search' => true,
                            'menu_position' => 15,
                            'has_archive' => false,
                            'hierarchical' => false,
                            'capability_type' => 'page',
                            'rewrite' => array( 'slug' => 'slider' ),
                            'supports' => array( 'title', 'editor', 'thumbnail')
                            )
                    );
            }



			
	  //   Slider custom taxonomy
	 function pretty_slider_taxonomy() {
                    register_taxonomy('slider_cat', //the name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
	         'slider-items',                   //post type name
                           array(
                           'hierarchical'                =>true,
                           'label'                          =>'Slider Category',   //Display name
                           'query_var'                   =>true,
                           'show_admin_column'   =>true,
                           'rewrite'                        =>array(
                           'slug'                           =>'slider-category',  // This controls the base slug that will display before each term
						    
                          'with_front'                    =>false   // Don't display the category base before
                         )
                  )
           );

     }
   add_action(  'init', 'pretty_slider_taxonomy' );	 	


	
// Pretty slider some css
function pretty_slider_css() {?>

<?php global $pretty_slider_options; $pretty_slider_settings = get_option( 'pretty_slider_options', $pretty_slider_options ); ?>

<style type="text/css">

	   .da-dots span {  border-radius:<?php echo $pretty_slider_settings['bullet_radius']; ?>%;}		   
	   
		<!-- Plugin active & deactive -->
		<?php if ( $pretty_slider_settings['plugin_active_deactive'] =='block' ) : ?>
			<?php wp_enqueue_style( 'pretty-slider-plugin-active', plugins_url( 'css/plugin-active.css', __FILE__ ));  ?>
		<?php endif; ?>	
	<!-- Plugin active & deactive -->
		<?php if ( $pretty_slider_settings['plugin_active_deactive'] =='none' ) : ?>
			<?php wp_enqueue_style( 'pretty-slider-plugin-deactive', plugins_url( 'css/plugin-deactive.css', __FILE__ ));  ?>
		<?php endif; ?>	
		
		<!-- Middle title and content -->
		<?php if ( $pretty_slider_settings['middle_title_content'] =='middle_title_content_ta' ) : ?>
			<?php wp_enqueue_style( 'pretty-slider-middle-title', plugins_url( 'css/middle-title-content.css', __FILE__ ));  ?>
		<?php endif; ?>			

		<?php if ( $pretty_slider_settings['middle_title_content'] =='dont_middle_title_content' ) : ?>
			<?php wp_enqueue_style( 'pretty-slider-middle-title-na', plugins_url( 'css/dont-middle-title-content.css', __FILE__ ));  ?>
		<?php endif; ?>	
	.da-slide p {
  margin-top: 57px!important;
}


.entry-content img, .comment-content img, .widget img, img.header-image, .author-avatar img, img.wp-post-image {
  height: 174px!important;
  margin-top: 36px!important;
}


.da-slide h2{
	color: #fff;
	font-size: 28px;
	width: 50%;
	top: 60px;
	white-space: nowrap;
	z-index: 10;
	text-shadow: 1px 1px 1px rgba(0,0,0,0.1);
	font-family: 'Economica', Arial, sans-serif;
	font-weight: 700;
}

</style> 

<?php
}
add_action('wp_head', 'pretty_slider_css');	





?>