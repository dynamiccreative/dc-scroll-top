<?php
/**
 * Plugin Name: DC Scroll Top
 * Plugin URI: https://github.com/bastiendc/dc-scroll-top
 * Description: Rajoute un bouton scroll to top.
 * Version: 0.2
 * Author: Dynamic Creative
 * Author URI: http://www.dynamic-creative.com
 * GitHub Plugin URI: https://github.com/bastiendc/dc-scroll-top
 * Primary Branch: main
 */


add_action('init', 'dcscrolltop_init');

function dcscrolltop_init(){
	wp_enqueue_style ( 'dcscrolltop', plugins_url() . '/dc-scroll-top/css/dcscrolltop.css');
	wp_enqueue_script( 'dcscrolltop', plugins_url() . '/dc-scroll-top/js/jquery.scrollUp.min.js',array('jquery'),'2.1.1',true );
	add_action('wp_footer', 'dcscrolltop_script',100);
}


function dcscrolltop_script(){
	?>
	<script type="text/javascript">(function($){$.scrollUp();})(jQuery);</script>
	<?php
}

/**
 * Adds a page in the settings menu
 */
function dcscrolltop_menu() {
	add_options_page( 'DC Scroll Top Options', 'DC Scroll Top', 'manage_options', 'dcscrolltop-options', 'dcscrolltop_options' );
}
add_action( 'admin_menu', 'dcscrolltop_menu' );

/**
 * Content for the settings page
 */
function dcscrolltop_options() {
	if ( !current_user_can('manage_options') )  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	// variables for the field and option names
	$hidden_field_name 		= 'st_submit_hidden';
	$st_opt_name1 			= 'st_menu_customwidth';
	$st_opt_color			= 'st_opt_color';
	$st_opt_pos_bottom		= 'st_opt_pos_bottom';
	$st_opt_pos_right		= 'st_opt_pos_right';
	$st_opt_size			= 'st_opt_size';
	//$st_data_field_name1 	= 'st_menu_customwidth';

	// Read in existing option value from database
  	$st_opt_val_width 		= get_option( $st_opt_name1 );
  	$st_opt_val_color 		= get_option( $st_opt_color );
  	$st_opt_val_pos_bottom 	= get_option( $st_opt_pos_bottom );
  	$st_opt_val_pos_right	= get_option( $st_opt_pos_right );
  	$st_opt_val_size 		= get_option( $st_opt_size );

	// See if the user has posted us some information
  	// If they did, this hidden field will be set to 'Y'
  	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
  		// Read their posted value
      	$st_opt_val_width 		= $_POST[ $st_opt_name1 ];
      	$st_opt_val_color 		= $_POST[ $st_opt_color ];
      	$st_opt_val_pos_bottom 	= $_POST[ $st_opt_pos_bottom ];
      	$st_opt_val_pos_right 	= $_POST[ $st_opt_pos_right ];
      	$st_opt_val_size 		= $_POST[ $st_opt_size ];

      	// Save the posted value in the database
     	update_option( $st_opt_name1, $st_opt_val_width );
     	update_option( $st_opt_color, $st_opt_val_color );
     	update_option( $st_opt_pos_bottom, $st_opt_val_pos_bottom );
     	update_option( $st_opt_pos_right, $st_opt_val_pos_right );
     	update_option( $st_opt_size, $st_opt_val_size );

     	// Put an settings updated message on the screen
	?>
	<div class="updated"><p><strong><?php _e('Your settings have been saved.', 'dcscrolltop-updated' ); ?></strong></p></div>
	<?php
	}
	// Now display the settings editing screen
    echo '<div class="wrap">';
    echo '<div id="icon-options-general" class="icon32"><br /></div>';

    // header
    echo "<h2>" . __( 'DC Scroll Top Settings', 'dcscrolltop' ) . "</h2>";
    echo "<p>" . __( 'Ce plugin rajoute un bouton Scroll to Top en bas de votre site.', 'dcscrolltop' ) . "</p>";

    // left part
    echo '<div class="admin_left">';

    // settings form
    echo '<form method="post" action="">';

    // register settings
	settings_fields( 'dcscrolltop_settings' );
	register_setting( 'dcscrolltop_settings', 'st_menu_customwidth' ); 
	register_setting( 'dcscrolltop_settings', 'st_opt_color' );
	register_setting( 'dcscrolltop_settings', 'st_opt_pos_bottom' );
	register_setting( 'dcscrolltop_settings', 'st_opt_pos_right' );
	register_setting( 'dcscrolltop_settings', 'st_opt_size' ); 
	?>
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<div class="st_margins">
		<p>
			Responsive: disparition du bouton en dessous de 650px de largeur par défaut.
		</p>
		<p>
			<span class="st_label"><?php _e("Enter a screen size in pixels:", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_width = get_option( 'st_opt_name1' ); if ( !$st_opt_val_width ) { $st_opt_val_width = "650"; }  ?>
			<span class="st_input"><input type="text" name="<?php echo $st_opt_name1; ?>" value="<?php echo $st_opt_val_width; ?>" size="5"> px</span>
		</p>
	</div>

	<div class="">
		<p>
			<span class="st_label"><?php _e("Enter color hexa:", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_color = get_option( 'st_opt_color' ); if ( !$st_opt_val_color ) { $st_opt_val_color = "000"; }  ?>
			<span class="st_input"># <input type="text" name="<?php echo $st_opt_color; ?>" value="<?php echo $st_opt_val_color; ?>" size="7"></span>
		</p>
	</div>
	<div class="">
		<p>
			<span class="st_label"><?php _e("Bottom position:", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_pos_bottom = get_option( 'st_opt_pos_bottom' ); if ( !$st_opt_val_pos_bottom ) { $st_opt_val_pos_bottom = "10"; }  ?>
			<span class="st_input"><input type="text" name="<?php echo $st_opt_pos_bottom; ?>" value="<?php echo $st_opt_val_pos_bottom; ?>" size="4"> px</span>
		</p>
	</div>
	<div class="">
		<p>
			<span class="st_label"><?php _e("Right position:", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_pos_right = get_option( 'st_opt_pos_right' ); if ( !$st_opt_val_pos_right ) { $st_opt_val_pos_right = "10"; }  ?>
			<span class="st_input"><input type="text" name="<?php echo $st_opt_pos_right; ?>" value="<?php echo $st_opt_val_pos_right; ?>" size="4"> px</span>
		</p>
	</div>
	<div class="">
		<p>
			<span class="st_label"><?php _e("Width:", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_size = get_option( 'st_opt_size' ); if ( !$st_opt_val_size ) { $st_opt_val_size = "40"; }  ?>
			<span class="st_input"><input type="text" name="<?php echo $st_opt_size; ?>" value="<?php echo $st_opt_val_size; ?>" size="3"> px</span>
		</p>
	</div>
	<?php submit_button(); ?>
	<?php

	echo "</form>";
	echo "</div><!-- end .admin_left -->";
	?>
	<div class="admin_right">
			<h3>A propos de Dynamic Creative</h3>
			<?php echo "<p>Agence Web cr&eacute;e en 1999. Conception de sites Internet, Mobile, d&eacute;veloppement et bien d'autres...</p>"; ?>
			<p><a href="http://www.dynamic-creative.com" target="_blank">dynamic-creative.com</a></p>
			
			<hr />

		</div><!-- end .admin_right -->
	</div><!-- end .wrap -->
	<?php

    echo "</div>";
}

/**
 * Add settings link on plugin page
 * @author c.bavota (http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/)
 */

function dcscrolltop_settings_link($links) { 
	  $settings_link = '<a href="options-general.php?page=dcscrolltop-options">Settings</a>'; 
	  array_unshift($links, $settings_link); 
	  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'dcscrolltop_settings_link' );

/**
 * Adds CSS on the admin side
 */
function DcScrollTop_admin_addCSS(){
	echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'admin.css' , __FILE__ ) . '" />';
	echo "\n";
}
add_action('admin_head','DcScrollTop_admin_addCSS');

/**
 * Adds DcScrollTop <script> and css to your header
 */
function dcscrolltop_header(){
?>
<!-- Add DcScrollTop CSS -->
<style type="text/css">
<?php
	$st_customwidth = get_option( 'st_menu_customwidth' );
	$st_color = get_option( 'st_opt_color' );
	$st_pos_bottom = get_option( 'st_opt_pos_bottom' );
	$st_pos_right = get_option( 'st_opt_pos_right' );
	$st_size = get_option( 'st_opt_size' );
	if ( !$st_customwidth ) { $st_customwidth = "650"; } 
	if ( !$st_color ) { $st_color = "000"; }
	if ( !$st_pos_bottom ) { $st_pos_bottom = "10"; }
	if ( !$st_pos_right ) { $st_pos_right = "10"; }
	if ( !$st_size ) { $st_size = "40"; }
?>
#scrollUp {bottom: <?php echo $st_pos_bottom; ?>px; right: <?php echo $st_pos_right; ?>px; height: <?php echo $st_size; ?>px; width: <?php echo $st_size; ?>px; background: url('data: image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="%23<?php echo $st_color; ?>" d="M256,512c141.4,0,256-114.6,256-256S397.4,0,256,0S0,114.6,0,256S114.6,512,256,512z M256,211.1l-99.8,100.8 c-9.2,9.2-24.4,9.2-33.6,0s-9.2-24.4,0-33.6l117.8-117.4c9-9,23.5-9.2,32.7-0.7l116.1,115.7c4.7,4.7,7,10.7,7,16.9 c0,6-2.3,12.2-6.9,16.7c-9.2,9.2-24.2,9.4-33.6,0L256,211.1L256,211.1z"/></svg>') no-repeat; background-size: contain;}
@media screen and (max-width: <?php echo $st_customwidth; ?>px) {#scrollUp { display: none!important;}}
</style>
<!-- End DcScrollTop CSS -->
<?php
}
add_action('wp_head','dcscrolltop_header', '11' );

