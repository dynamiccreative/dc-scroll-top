<?php

/**
 * GitHub Updater for WordPress Plugin
 *
 * Adds support for updating the plugin from a GitHub repository
 * via the WordPress admin interface.
 */

/**
 * Class MyPlugin_GitHub_Updater
 */
class MyPlugin_GitHub_Updater {
	/**
	 * GitHub repository information
	 *
	 * @var array
	 */
	private $config = [
	    'slug'          => 'dc-scroll-top/dc-scroll-top.php',
	    'repo'          => 'dc-scroll-top',
	    'owner'         => 'dynamiccreative', 
	    'github_url'    => 'https://github.com/dynamiccreative/dc-scroll-top',
	    'zip_url'       => 'https://github.com/dynamiccreative/dc-scroll-top/archive/refs/tags/{tag}.zip',
	    'access_token'  => '', // Optional: GitHub Personal Access Token for private repos
	];

	/**
	 * Current plugin data
	 *
	 * @var array
	 */
	private $plugin_data;

	/**
	 * Constructor
	 */
	public function __construct() {
	    add_filter( 'update_plugins_github.com', [ $this, 'self_update' ], 10, 4 );
	    add_filter( 'plugins_api', [ $this, 'dst_plugin_info' ], 9999, 3 );
	    //add_filter( 'all_plugins', [ $this,'prefix_add_plugin_icon' ] );
	    
	}

	/**
	 * Check for updates to this plugin
	 *
	 * @param array  $update   Array of update data.
	 * @param array  $plugin_data Array of plugin data.
	 * @param string $plugin_file Path to plugin file.
	 * @param string $locales    Locale code.
	 *
	 * @return array|bool Array of update data or false if no update available.
	 */
	public function self_update( $update, array $plugin_data, string $plugin_file, $locales ) {

		// only check this plugin
		if ( $this->config['slug'] !== $plugin_file ) {
			return $update;
		}

		// already completed update check elsewhere
		if ( ! empty( $update ) ) {
			return $update;
		}
		//error_log('$plugin_update: ' . print_r( $plugin_file, true ));

		$output = $this->dst_plugin_request();

		error_log('$plugin_update_version: ' . print_r( $output['tag_name'], true ));

		$new_version_number  = $output['tag_name'];
		$is_update_available = version_compare( $plugin_data['Version'], $new_version_number, '<' );

		error_log('$plugin_version: ' . print_r( $plugin_data['Version'], true ));
		error_log('$plugin_check: ' . print_r( $is_update_available, true ));

		if ( ! $is_update_available ) {
			return false;
		}

		$new_url     = $output['html_url'];
		$new_package = $output['assets'][0]['browser_download_url'];
		//$new_package = $output['zipball_url'];

		error_log('$plugin_data: ' . print_r( $plugin_data, true ));
		error_log('$new_version_number: ' . $new_version_number );
		error_log('$new_url: ' . $new_url );
		error_log('$new_package: ' . $new_package );

		return array(
			'slug'    => $plugin_data['TextDomain'],
			'version' => $new_version_number,
			'url'     => $new_url,
			'package' => $new_package,
		);
	}

	 public function dst_plugin_request() {
	   $access = wp_remote_get(
			"https://api.github.com/repos/{$this->config['owner']}/{$this->config['repo']}/releases/latest",
			array(
				'user-agent' => $this->config['owner'],
			)
		);
	   if ( ! is_wp_error( $access ) && 200 === wp_remote_retrieve_response_code( $access ) ) {
	      $result = json_decode( wp_remote_retrieve_body( $access ), true );
	      return $result;      
	    }
	}

	/**
	* Get plugin information for the update screen
	*
	* @param false|object|array $result
	* @param string $action
	* @param object $args
	* @return object|array|false
	*/
	public function dst_plugin_info( $result, $action, $args ) {
	  if ( $action !== 'plugin_information' ) {
	      return $result;
	  }

	  if ( $args->slug !== $this->config['repo'] ) {
	      return $result;
	  }

	  $plugin_data = $this->dst_get_plugin_data();
	  $changelog = $this->dst_plugin_request();

	  $info = new stdClass();
	  $info->name = $plugin_data['Name'];
	  $info->slug = $this->config['repo'];
	  $info->version = $changelog['tag_name'];
	  $info->author = $plugin_data['Author'];
	  $info->homepage = $this->config['github_url'];
	  $info->requires = '6.0'; // Adjust as needed
	  $info->tested = '6.7.2';   // Adjust as needed
	  $info->downloaded = 0;
	  $info->last_updated = gmdate( 'Y-m-d' );
	  $info->sections = [
	      'description' => $plugin_data['Description'],
	      'changelog'   => $changelog['body'].'<br>'.$this->get_changelog(),
	  ];
	  $info->download_link = str_replace( '{tag}', $changelog['tag_name'], $this->config['zip_url'] );
	  return $info;
	}

	/**
	* Get plugin data
	*
	* @return array
	*/
	public function dst_get_plugin_data() {
	  if ( ! function_exists( 'get_plugin_data' ) ) {
	      require_once ABSPATH . 'wp-admin/includes/plugin.php';
	  }
	  return get_plugin_data( WP_PLUGIN_DIR . '/' . $this->config['slug'] );
	}

	/**
	* Get changelog (optional, can be customized)
	*
	* @return string
	*/
	public function get_changelog() {
	  // You can fetch the changelog from GitHub or hardcode it
	  return 'See the full changelog on <a href="' . esc_url( $this->config['github_url'] ) . '/releases">GitHub</a>.';
	}

	/**
	 * Add a custom plugin icon to the plugins update page (introduced in WP4.9).
	 *
	 * @param $plugins
	 *
	 * @return mixed
	 */
	public function prefix_add_plugin_icon( $plugins ) {
		$icon_url = 'https://ps.w.org/seo-by-rank-math/assets/icon.svg?rev=3218327';//plugins_url('/assets/img/icon-256x256.png', __FILE__);
		$plugin_basename = $this->config['slug'];
		$plugins[$plugin_basename]['icons']['default'] = $icon_url;
	
		return $plugins;
	}
	
}

// Instantiate the updater
if ( is_admin() ) {
    new MyPlugin_GitHub_Updater();
}