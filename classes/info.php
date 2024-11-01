<?php

/*
|--------------------------------------------------------------------------
| Class: Info
|--------------------------------------------------------------------------
|
| This class pulls information from various areas of the install about
| this install.
|
*/

class Info {
    
    /**
     * Returns the name of this install.
     *    
     * @return string
     */
    
    public static function name() {
        
        $return = get_bloginfo('name');
        return $return;
        
    }
    
    /**
     * Returns the URL of this install.
     *    
     * @return string
     */
    
    public static function url() {
        
        $return = get_bloginfo('wpurl');
        return $return;
        
    }
    
    /**
     * Returns the version number of this install.
     *    
     * @return string
     */
    
    public static function version() {
        
        $return = get_bloginfo('version');
        return $return;
        
    }
    
    /**
     * Returns information about the current theme.
     *    
     * @input  string $element
     * @return array
     */
    
    public static function theme($element = false) {
        
        $allowedElements = array("name", "version", "template");
        
        if(!$element && !in_array($element, $allowedElements)) {
            return false;
        }
        
        $return = wp_get_theme()->$element;
        return $return;
        
    }
    
    /**
     * Returns array of themes that have available updates.
     *    
     * @return array
     */
    
    public static function themeUpdates() {
        
        wp_update_themes();
        $return = self::get_theme_updates();
        //$return = do_action('get_theme_updates');
        return $return;
        
    }
    
    /**
     * Returns array of information about the sites plugins.
     *    
     * @return array
     */
    
    public static function plugins() {
        
        $return = self::get_plugins();
        //$return = do_action('get_plugins');
        return $return;
        
    }
    
    /**
     * Returns array of information about the sites plugins that have updates avilable.
     *    
     * @return array
     */
    
    public static function pluginUpdates() {
        
		wp_update_plugins();
        $return = self::get_plugin_updates();
        //$return = do_action('get_plugin_updates');
        return $return;
        
    }
    
    public static function get_plugin_updates() {
    	$all_plugins = get_plugins();
    	$upgrade_plugins = array();
    	$current = get_site_transient( 'update_plugins' );
    	foreach ( (array)$all_plugins as $plugin_file => $plugin_data) {
    		if ( isset( $current->response[ $plugin_file ] ) ) {
    			$upgrade_plugins[ $plugin_file ] = (object) $plugin_data;
    			$upgrade_plugins[ $plugin_file ]->update = $current->response[ $plugin_file ];
    		}
    	}
    
    	return $upgrade_plugins;
    }
    
    /**
     * Returns string of disk space / usage information.
     *    
     * @input  string $element
     * @return array
     */
    
    public static function disk($element = false) {
        
        if(!$element) {
            return false;
        }
        
        switch($element) {
            case "total":
                $return = disk_total_space(".");
                break;
            case "free":
                $return = disk_free_space(".");
                break;
            default:
                $return = false;
        }

        return $return;
        
    }
    
    /**
     * Returns this installs API URL.
     *    
     * @return string
     */
    
    public static function apiURL() {
        
        //$return = get_option(OPTION_URL);
        return PLUGIN_URL;
        
    }
    
    /**
     * Generates array of theme update information.
     * Original version: /wp-admin/wp-includes/update.php
     *    
     * @return array
     */
    
    public static function get_theme_updates() {
    	$themes = wp_get_themes();
    	$current = get_site_transient('update_themes');
    
    	if ( ! isset( $current->response ) )
    		return array();
    
    	$update_themes = array();
    	foreach ( $current->response as $stylesheet => $data ) {
    		$update_themes[ $stylesheet ] = wp_get_theme( $stylesheet );
    		$update_themes[ $stylesheet ]->update = $data;
    	}
    
    	return $update_themes;
    }
    
    /**
     * Generates array of plugin update information.
     * Original version: /wp-admin/wp-includes/plugin.php
     *    
     * @return array
     */
    
    public static function get_plugins($plugin_folder = '') {

    	if ( ! $cache_plugins = wp_cache_get('plugins', 'plugins') )
    		$cache_plugins = array();
    
    	if ( isset($cache_plugins[ $plugin_folder ]) )
    		return $cache_plugins[ $plugin_folder ];
    
    	$wp_plugins = array ();
    	$plugin_root = WP_PLUGIN_DIR;
    	if ( !empty($plugin_folder) )
    		$plugin_root .= $plugin_folder;
    
    	// Files in wp-content/plugins directory
    	$plugins_dir = @ opendir( $plugin_root);
    	$plugin_files = array();
    	if ( $plugins_dir ) {
    		while (($file = readdir( $plugins_dir ) ) !== false ) {
    			if ( substr($file, 0, 1) == '.' )
    				continue;
    			if ( is_dir( $plugin_root.'/'.$file ) ) {
    				$plugins_subdir = @ opendir( $plugin_root.'/'.$file );
    				if ( $plugins_subdir ) {
    					while (($subfile = readdir( $plugins_subdir ) ) !== false ) {
    						if ( substr($subfile, 0, 1) == '.' )
    							continue;
    						if ( substr($subfile, -4) == '.php' )
    							$plugin_files[] = "$file/$subfile";
    					}
    					closedir( $plugins_subdir );
    				}
    			} else {
    				if ( substr($file, -4) == '.php' )
    					$plugin_files[] = $file;
    			}
    		}
    		closedir( $plugins_dir );
    	}
    
    	if ( empty($plugin_files) )
    		return $wp_plugins;
    
    	foreach ( $plugin_files as $plugin_file ) {
    		if ( !is_readable( "$plugin_root/$plugin_file" ) )
    			continue;
    
    		$plugin_data = self::get_plugin_data( "$plugin_root/$plugin_file", false, false ); //Do not apply markup/translate as it'll be cached.
    
    		if ( empty ( $plugin_data['Name'] ) )
    			continue;
    
    		$wp_plugins[plugin_basename( $plugin_file )] = $plugin_data;
    	}
    
    	uasort( $wp_plugins, array(self, '_sort_uname_callback') );
    
    	$cache_plugins[ $plugin_folder ] = $wp_plugins;
    	wp_cache_set('plugins', $cache_plugins, 'plugins');
    
    	return $wp_plugins;
    }
    
    /**
     * Generates array of plugin data.
     * Original version: /wp-admin/wp-includes/plugin.php
     *    
     * @return array
     */
    
    public static function get_plugin_data( $plugin_file, $markup = true, $translate = true ) {

    	$default_headers = array(
    		'Name' => 'Plugin Name',
    		'PluginURI' => 'Plugin URI',
    		'Version' => 'Version',
    		'Description' => 'Description',
    		'Author' => 'Author',
    		'AuthorURI' => 'Author URI',
    		'TextDomain' => 'Text Domain',
    		'DomainPath' => 'Domain Path',
    		'Network' => 'Network',
    		// Site Wide Only is deprecated in favor of Network.
    		'_sitewide' => 'Site Wide Only',
    	);
    
    	$plugin_data = get_file_data( $plugin_file, $default_headers, 'plugin' );
    
    	// Site Wide Only is the old header for Network
    	if ( ! $plugin_data['Network'] && $plugin_data['_sitewide'] ) {
    		_deprecated_argument( __FUNCTION__, '3.0', sprintf( __( 'The <code>%1$s</code> plugin header is deprecated. Use <code>%2$s</code> instead.' ), 'Site Wide Only: true', 'Network: true' ) );
    		$plugin_data['Network'] = $plugin_data['_sitewide'];
    	}
    	$plugin_data['Network'] = ( 'true' == strtolower( $plugin_data['Network'] ) );
    	unset( $plugin_data['_sitewide'] );
    
    	if ( $markup || $translate ) {
    		$plugin_data = _get_plugin_data_markup_translate( $plugin_file, $plugin_data, $markup, $translate );
    	} else {
    		$plugin_data['Title']      = $plugin_data['Name'];
    		$plugin_data['AuthorName'] = $plugin_data['Author'];
    	}
    
    	return $plugin_data;
    }
    
    /**
     * Called uasort function.
     * Original version: /wp-admin/wp-includes/plugin.php
     *    
     * @return int
     */
    
    public static function _sort_uname_callback( $a, $b ) {
    	return strnatcasecmp( $a['Name'], $b['Name'] );
    }
    
}