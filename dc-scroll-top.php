<?php
/**
 * Plugin Name: DC Scroll Top
 * Plugin URI: https://github.com/bastiendc/dc-scroll-top
 * Description: Rajoute un bouton scroll to top.
 * Version: 0.12
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
	add_options_page( 'DcScrollTop Options', 'DcScrollTop', 'manage_options', 'dcscrolltop-options', 'dcscrolltop_options' );
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
	$st_data_field_name1 	= 'st_menu_customwidth';

	// Read in existing option value from database
  	$st_opt_val1 		= get_option( $st_opt_name1 );

	// See if the user has posted us some information
  	// If they did, this hidden field will be set to 'Y'
  	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
  		// Read their posted value
      	$st_opt_val1 		= $_POST[ $st_data_field_name1 ];

      	// Save the posted value in the database
     	update_option( $st_opt_name1, $st_opt_val1 );

     	// Put an settings updated message on the screen
	?>
	<div class="updated"><p><strong><?php _e('Your settings have been saved.', 'dcscrolltop-updated' ); ?></strong></p></div>
	<?php
	}
	// Now display the settings editing screen
    echo '<div class="wrap">';
    echo '<div id="icon-options-general" class="icon32"><br /></div>';

    // header
    echo "<h2>" . __( 'DcScrollTop Settings', 'dcscrolltop-header' ) . "</h2>";
    echo "<p>" . __( 'Ce plugin rajoute un bouton Scroll to Top en bas de votre site.', 'dcscrolltop-headerdescription' ) . "</p>";

    // left part
    echo '<div class="admin_left">';

    // settings form
    echo '<form method="post" action="">';

    // register settings
	settings_fields( 'dcscrolltop_settings' );
	register_setting( 'dcscrolltop_settings', 'st_menu_customwidth' );  
	?>
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<div class="st_margins">
		<p>
			Responsive: disparition du bouton en dessous de 650px de largeur par défaut.
		</p>
		<p>
			<span class="st_label"><?php _e("Enter a screen size in pixels:", 'dcscrolltop-customwidth' ); ?></span>
			<?php $st_opt_val1 = get_option( 'st_menu_customwidth' ); if ( !$st_opt_val1 ) { $st_opt_val1 = "650"; }  ?>
			<span class="st_input"><input type="text" name="<?php echo $st_data_field_name1; ?>" value="<?php echo $st_opt_val1; ?>" size="5"> px</span>
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
	if ( !$st_customwidth ) { $st_customwidth = "650"; } 
?>
@media screen and (max-width: <?php echo $st_customwidth; ?>px) {#scrollUp { display: none!important;}}
</style>
<!-- End DcScrollTop CSS -->
<?php
}
add_action('wp_head','dcscrolltop_header', '11' );

