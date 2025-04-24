<?php
//namespace RYSE\DCScrollTop;

/**
 * Plugin Name: DC Scroll Top
 * Plugin URI: https://github.com/dynamiccreative/dc-scroll-top
 * Update URI: https://github.com/dynamiccreative/dc-scroll-top
 * Description: Rajoute un bouton scroll to top.
 * Version: 0.3.15
 * Author: Team dynamic Creative
 * Author URI: http://www.dynamic-creative.com
 * GitHub Plugin URI: https://github.com/dynamiccreative/dc-scroll-top
 * Primary Branch: main
 * Text Domain: dc-scroll-top
 * Domain Path: /languages
 * Tested up to:       6.8
 * Requires at least:  6.7
 */


define( 'DST_VERSION', '0.3.15' );
define( 'DST_FILE', __FILE__ );
define( 'DST_DIR_PATH', plugin_dir_path( DST_FILE ) );
define( 'DST_DIR_URL', plugin_dir_url( DST_FILE ) );

class Scroll_Top {
	private $config = [
	    'slug'          => 'dc-scroll-top/dc-scroll-top.php',
	    'repo'          => 'dc-scroll-top',
	    'access_token'  => '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',
	    'icon_url'      => 'https://raw.githubusercontent.com/dynamiccreative/dc-scroll-top/main/assets/img/icon-256x256.png',
	    'banner_url'      => 'https://raw.githubusercontent.com/dynamiccreative/dc-scroll-top/main/assets/img/banner-1544x500.png'
	];

	public function initialize() {
		//$this->include_files();
		$this->update_plugin();

		//add_action( 'admin_enqueue_scripts', [$this, 'load_admin_styles'] );
		add_filter('plugin_row_meta', [$this,'add_row_meta'], 10, 4);
	}

	public function include_files() {
		//require_once DST_DIR_PATH . 'inc/update.php';
	}

	public function update_plugin() {
        require_once DST_DIR_PATH . 'inc/GitHubUpdater.php';
        $gitHubUpdater = new DstGitHubUpdater(DST_FILE);
        $gitHubUpdater->setAccessToken($this->config['access_token']);
        $gitHubUpdater->setPluginIcon($this->config['icon_url']);
        $gitHubUpdater->setPluginBannerSmall($this->config['banner_url']);
        $gitHubUpdater->setPluginBannerLarge($this->config['banner_url']);
        $gitHubUpdater->add();
    }

	public function add_row_meta($links, $file, $plugin_data, $status) {
        if ($this->config['slug'] === $file) {
            $links[] = '<a href="'.esc_attr($plugin_data['id']).'" class="" target="_blank"><img src="' . $this->config['icon_url'] . '" alt="Icon" style="width:16px;height:16px;vertical-align:middle;"/></a>';
        }
        return $links;
    }

}
$dst = new Scroll_Top();
$dst->initialize();

add_action('init', 'dcscrolltop_assets');

function dcscrolltop_assets(){
	wp_enqueue_style ( 'dcscrolltop', plugin_dir_url( __FILE__ ) . '/assets/css/dcscrolltop.css');
	wp_enqueue_script( 'dcscrolltop', plugin_dir_url( __FILE__ ) . '/assets/js/jquery.scrollUp.js',['jquery'],'2.1.1',true );
}


/**/
add_action( 'admin_enqueue_scripts', 'wptuts_add_color_picker' );
function wptuts_add_color_picker( $hook ) {
    if( is_admin() ) {
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'assets/js/custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
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
	$st_opt_responsive_width= 'st_opt_responsive_width';
	$st_opt_color			= 'st_opt_color';
	$st_opt_pos_bottom		= 'st_opt_pos_bottom';
	$st_opt_pos_right		= 'st_opt_pos_right';
	$st_opt_size			= 'st_opt_size';
	$st_opt_anim			= 'st_opt_anim';
	$st_opt_style			= 'st_opt_style';

	// Read in existing option value from database
  	$st_opt_val_responsive_width = get_option( $st_opt_responsive_width );
  	$st_opt_val_color 		= get_option( $st_opt_color );
  	$st_opt_val_pos_bottom 	= get_option( $st_opt_pos_bottom );
  	$st_opt_val_pos_right	= get_option( $st_opt_pos_right );
  	$st_opt_val_size 		= get_option( $st_opt_size );
  	$st_opt_val_anim 		= get_option( $st_opt_anim );
  	$st_opt_val_style 		= get_option( $st_opt_style );

	// See if the user has posted us some information
  	// If they did, this hidden field will be set to 'Y'
  	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
  		// Read their posted value
      	$st_opt_val_responsive_width 		= $_POST[ $st_opt_responsive_width ];
      	$st_opt_val_color 		= $_POST[ $st_opt_color ];
      	$st_opt_val_pos_bottom 	= $_POST[ $st_opt_pos_bottom ];
      	$st_opt_val_pos_right 	= $_POST[ $st_opt_pos_right ];
      	$st_opt_val_size 		= $_POST[ $st_opt_size ];
      	$st_opt_val_anim 		= $_POST[ $st_opt_anim ];
      	$st_opt_val_style 		= $_POST[ $st_opt_style ];

      	// Save the posted value in the database
     	update_option( $st_opt_responsive_width, $st_opt_val_responsive_width );
     	update_option( $st_opt_color, $st_opt_val_color );
     	update_option( $st_opt_pos_bottom, $st_opt_val_pos_bottom );
     	update_option( $st_opt_pos_right, $st_opt_val_pos_right );
     	update_option( $st_opt_size, $st_opt_val_size );
     	update_option( $st_opt_anim, $st_opt_val_anim );
     	update_option( $st_opt_style, $st_opt_val_style );

     	// Put an settings updated message on the screen
	?>
	<div class="updated"><p><strong><?php _e('Your settings have been saved.', 'dcscrolltop' ); ?></strong></p></div>
	<?php
	}
	// Now display the settings editing screen
    echo '<div class="wrap">';

    // header
    echo "<h2>" . __( 'DC Scroll Top Settings', 'dcscrolltop' ) . "</h2>";
    echo "<p>" . __( 'Ce plugin rajoute un bouton Scroll to Top en bas de votre site.', 'dcscrolltop' ) . "</p>";

    // left part
    echo '<div class="dc_admin_left">';

    // settings form
    echo '<form method="post" action="">';

    // register settings
	settings_fields( 'dcscrolltop_settings' );
	register_setting( 'dcscrolltop_settings', 'st_opt_responsive_width' ); 
	register_setting( 'dcscrolltop_settings', 'st_opt_color' );
	register_setting( 'dcscrolltop_settings', 'st_opt_pos_bottom' );
	register_setting( 'dcscrolltop_settings', 'st_opt_pos_right' );
	register_setting( 'dcscrolltop_settings', 'st_opt_size' );
	register_setting( 'dcscrolltop_settings', 'st_opt_anim' ); 
	register_setting( 'dcscrolltop_settings', 'st_opt_style' ); 
	?>
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<div class="st_margins">
		<h2>Responsive</h2>
		<p>
			<span class="st_label"><?php _e("Breakpoint Mobile :", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_width = get_option( 'st_opt_responsive_width' ); if ( !$st_opt_val_responsive_width ) { $st_opt_val_responsive_width = "650"; }  ?>
			<span class="st_input"><input type="text" name="<?php echo $st_opt_responsive_width; ?>" value="<?php echo $st_opt_val_responsive_width; ?>" size="5"> px</span>
		</p>
	</div>


	<div class="st_margins mt-2">
		<h2>Style</h2>
		<p>
			<span class="st_label"><?php _e("Color:", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_color = get_option( 'st_opt_color' ); if ( !$st_opt_val_color ) { $st_opt_val_color = "#000"; }  ?>
			<input type="text" name="<?php echo $st_opt_color; ?>" value="<?php echo $st_opt_val_color; ?>" class="color-field" data-default-color="<?php echo $st_opt_val_color; ?>" />
		</p>

		<p>
			<span class="st_label"><?php _e("Bottom position:", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_pos_bottom = get_option( 'st_opt_pos_bottom' ); if ( !$st_opt_val_pos_bottom ) { $st_opt_val_pos_bottom = "10"; }  ?>
			<span class="st_input"><input type="text" name="<?php echo $st_opt_pos_bottom; ?>" value="<?php echo $st_opt_val_pos_bottom; ?>" size="4"> px</span>
		</p>

		<p>
			<span class="st_label"><?php _e("Right position:", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_pos_right = get_option( 'st_opt_pos_right' ); if ( !$st_opt_val_pos_right ) { $st_opt_val_pos_right = "10"; }  ?>
			<span class="st_input"><input type="text" name="<?php echo $st_opt_pos_right; ?>" value="<?php echo $st_opt_val_pos_right; ?>" size="4"> px</span>
		</p>

		<p>
			<span class="st_label"><?php _e("Width:", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_size = get_option( 'st_opt_size' ); if ( !$st_opt_val_size ) { $st_opt_val_size = "40"; }  ?>
			<span class="st_input"><input type="text" name="<?php echo $st_opt_size; ?>" value="<?php echo $st_opt_val_size; ?>" size="4"> px</span>
		</p>

		<p>
			<span class="st_label"><?php _e("Animation:", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_anim = get_option( 'st_opt_anim' ); if ( !$st_opt_val_anim ) { $st_opt_val_anim = "fade"; }  ?>
			<span class="st_input">
				<select name="<?php echo $st_opt_anim; ?>" id="su-anim">
					<option value="fade" <?php if ($st_opt_val_anim == 'fade') echo 'selected'; ?>>Fade</option>
					<option value="slide" <?php if ($st_opt_val_anim == 'slide') echo 'selected'; ?>>Slide</option>
				</select>
			</span>
		</p>

		

		<p class="st_radio_svg">
			<span class="st_label"><?php _e("Style:", 'dcscrolltop' ); ?></span>
			<?php $st_opt_val_style = get_option( 'st_opt_style' ); if ( !$st_opt_val_style ) { $st_opt_val_style = 1; }  ?>
			<span>
				<label for="s1">
					<input type="radio" id="s1" name="<?php echo $st_opt_style; ?>" value="1"
	             	<?php if ($st_opt_val_style == 1) echo 'checked'; ?>>
	         		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="<?php echo getStyle(1); ?>"/></svg>
	      		</label>
	      		<label for="s2">
					<input type="radio" id="s2" name="<?php echo $st_opt_style; ?>" value="2"
	             	<?php if ($st_opt_val_style == 2) echo 'checked'; ?>>
	      			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="<?php echo getStyle(2); ?>"/></svg>
	      		</label>
	      		<label for="s3">
					<input type="radio" id="s3" name="<?php echo $st_opt_style; ?>" value="3"
	             	<?php if ($st_opt_val_style == 3) echo 'checked'; ?>>
	      			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="<?php echo getStyle(3); ?>"/></svg>
	      		</label>
	      		<label for="s4">
					<input type="radio" id="s4" name="<?php echo $st_opt_style; ?>" value="4"
	             	<?php if ($st_opt_val_style == 4) echo 'checked'; ?>>
	      			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="<?php echo getStyle(4); ?>"/></svg>
	      		</label>
	      		<label for="s5">
					<input type="radio" id="s5" name="<?php echo $st_opt_style; ?>" value="5"
	             	<?php if ($st_opt_val_style == 5) echo 'checked'; ?>>
	      			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="<?php echo getStyle(5); ?>"/></svg>
	      		</label>
	      		<label for="s6">
					<input type="radio" id="s6" name="<?php echo $st_opt_style; ?>" value="6"
	             	<?php if ($st_opt_val_style == 6) echo 'checked'; ?>>
	      			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20"><path d="<?php echo getStyle(6); ?>"/></svg>
	      		</label>
	      	</span>
		</p>
	</div>
	<?php submit_button(); ?>
	<?php

	echo "</form>";
	echo "</div><!-- end .admin_left -->";
	?>
	<div class="dc_admin_right">
			<h3>A propos de Dynamic Creative</h3>
			<?php echo "<p>Agence Web cr&eacute;e en 1999. Conception de sites Internet, Mobile, d&eacute;veloppement et bien d'autres...</p>"; ?>
			<p><a href="https://www.dynamic-creative.com" target="_blank"><img src="<?php echo plugins_url( 'assets/img/logo.png' , __FILE__ ); ?>" alt="dynamic-creative.com" width="200" height="44"/></a></p>
			
			<hr />

		</div><!-- end .admin_right -->
	</div><!-- end .wrap -->
	<?php

}

/*different svg style*/
function getStyle($n){
	switch ( $n ) {
		case 1 :
			$icon = 'M256,0C114.6,0,0,114.6,0,256s114.6,256,256,256s256-114.6,256-256S397.4,0,256,0z M369.2,311.1c-7.7,7.7-20.2,7.7-28,0l-85.1-85.1l-85.1,85.1c-8.3,7.1-20.8,6.1-28-2.2c-6.4-7.4-6.4-18.4,0-25.8l99.1-99.1c7.7-7.7,20.2-7.7,28,0	l99.1,99.1C377.1,291,377,303.5,369.2,311.1z';
			break;
		case 2 :
			$icon = 'M256,0C114.6,0,0,114.6,0,256s114.6,256,256,256s256-114.6,256-256S397.4,0,256,0z M360.4,214.1c-7.1,7.2-18.7,7.2-25.9,0.1	c0,0-0.1-0.1-0.1-0.1l-60.2-60.3v248.5c0,10.1-8.2,18.3-18.3,18.3c-10.1,0-18.3-8.2-18.3-18.3V153.8l-60.2,60.3	c-7.1,7.2-18.7,7.2-25.9,0.1c0,0-0.1-0.1-0.1-0.1c-7.2-7.1-7.2-18.7-0.1-25.9c0,0,0.1-0.1,0.1-0.1L243,96.7	c7.1-7.2,18.7-7.2,25.9-0.1c0,0,0.1,0.1,0.1,0.1l91.4,91.4c7.2,7.1,7.2,18.7,0.1,25.9C360.5,214.1,360.4,214.1,360.4,214.1z';
			break;
		case 6 :
			$icon = 'M256,0C114.6,0,0,114.6,0,256s114.6,256,256,256s256-114.6,256-256S397.4,0,256,0z M256,475.4 c-121.2,0-219.4-98.2-219.4-219.4S134.8,36.6,256,36.6S475.4,134.8,475.4,256S377.2,475.4,256,475.4z M256,184.5l117.2,117.2 l-25.8,25.8L256,236.1l-91.4,91.4l-25.8-25.8L256,184.5z';
			break;	
		case 4 :
			$icon = 'M256,512C114.6,512,0,397.4,0,256S114.6,0,256,0s256,114.6,256,256S397.4,512,256,512z M256,32C132.3,32,32,132.3,32,256 s100.3,224,224,224s224-100.3,224-224S379.7,32,256,32z M384,336c-4.2,0.1-8.3-1.7-11.2-4.8L256,214.7L139.2,331.2	c-6.2,6.2-16.2,6.2-22.4,0c-6.2-6.2-6.2-16.2,0-22.4l128-128c5.8-6.2,15.5-6.5,21.7-0.7c0.2,0.2,0.5,0.5,0.7,0.7l128,128	c6.2,5.8,6.5,15.5,0.7,21.7c-0.2,0.2-0.5,0.5-0.7,0.7C392.3,334.3,388.2,336.1,384,336L384,336z';
			break;
		case 5 :
			$icon = 'M256,0C114.8,0,0,114.8,0,256s114.8,256,256,256s256-114.8,256-256S397.2,0,256,0z M256,480	C132.5,480,32,379.5,32,256S132.5,32,256,32s224,100.5,224,224S379.5,480,256,480z M347.3,228.7c6.3,6.3,6.3,16.4,0,22.6	c-3.1,3.1-7.2,4.7-11.3,4.7s-8.2-1.6-11.3-4.7L272,198.6V352c0,8.8-7.2,16-16,16s-16-7.2-16-16V198.6l-52.7,52.7	c-6.3,6.3-16.4,6.3-22.6,0s-6.3-16.4,0-22.6l80-80c6.3-6.3,16.4-6.3,22.6,0L347.3,228.7z';
			break;
		case 3 :
			$icon = 'M256,0C114.6,0,0,114.6,0,256s114.6,256,256,256s256-114.6,256-256S397.4,0,256,0z M348.2,319.1l-91.4-91.4 l-91.4,91.4l-25.8-25.8l117.2-117.2L374,293.3L348.2,319.1z';
			break;		
		default:
			$icon = 'M256,0C114.6,0,0,114.6,0,256s114.6,256,256,256s256-114.6,256-256S397.4,0,256,0z M369.2,311.1c-7.7,7.7-20.2,7.7-28,0l-85.1-85.1l-85.1,85.1c-8.3,7.1-20.8,6.1-28-2.2c-6.4-7.4-6.4-18.4,0-25.8l99.1-99.1c7.7-7.7,20.2-7.7,28,0	l99.1,99.1C377.1,291,377,303.5,369.2,311.1z';
	}
	return $icon;
}

/*Transform hexa color for svg*/
function returnColor($c){
	$r = str_replace("#", "%23", $c);
    return $r;
}

/**
 * Add settings link on plugin page
 * @author c.bavota (http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/)
 */

function dcscrolltop_settings_link($links) { 
	  $settings_link = sprintf('<a href="options-general.php?page=dcscrolltop-options">%s</a>', __("Settings", "dcscrolltop")); 
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
	$st_responsive_width = get_option( 'st_opt_responsive_width' );
	$st_color = get_option( 'st_opt_color' );
	$st_pos_bottom = get_option( 'st_opt_pos_bottom' );
	$st_pos_right = get_option( 'st_opt_pos_right' );
	$st_size = get_option( 'st_opt_size' );
	$st_style = get_option( 'st_opt_style' );
	if ( !$st_responsive_width ) { $st_responsive_width = "650"; } 
	if ( !$st_color ) { $st_color = "#000"; }
	if ( !$st_pos_bottom ) { $st_pos_bottom = "10"; }
	if ( !$st_pos_right ) { $st_pos_right = "10"; }
	if ( !$st_size ) { $st_size = "40"; }
	if ( !$st_style ) { $st_style = 1; }
?>
.scrollup-slide #scrollUp {bottom: -<?php echo ($st_pos_bottom + $st_size); ?>px;}
.scrollup-slide.scrollup #scrollUp {bottom: <?php echo $st_pos_bottom; ?>px; opacity:1; visibility: visible;}
#scrollUp {bottom: <?php echo $st_pos_bottom; ?>px; right: <?php echo $st_pos_right; ?>px; height: <?php echo $st_size; ?>px; width: <?php echo $st_size; ?>px; background: url('data: image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="<?php echo returnColor($st_color); ?>" d="<?php echo getStyle($st_style); ?>"/></svg>') no-repeat; background-size: contain;}
@media screen and (max-width: <?php echo $st_responsive_width; ?>px) {#scrollUp { display: none!important;}}
</style>
<!-- End DcScrollTop CSS -->
<?php
}
add_action('wp_head','dcscrolltop_header', '11' );

function dcscrolltop_script(){
	$st_anim = get_option( 'st_opt_anim' );
	?>
	<script type="text/javascript">(function($){$.scrollUp({animation: "<?php echo $st_anim; ?>"});})(jQuery);</script>
	<?php
}

add_action('wp_footer', 'dcscrolltop_script',100);
