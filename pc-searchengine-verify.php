<?php
/*
Plugin Name: PC Search Engine Verify
Plugin URI: http://petercoughlin.com/search-engine-verify-wordpress-plugin/
Description: Adds verify meta tags for Google, Bing and Yahoo!
Author: Peter Coughlin
Version: 2.3
Author URI: http://petercoughlin.com/

Version History
----------------
2.3		Updated Google meta tag name
2.2		Added support for Yahoo
2.1		Added support for Bing
2.0		Added class functionality
1.1		Updated to new Google format
1.0		Initial version
*/ 

class pc_searchengine_verify {

	function pc_searchengine_verify() {

		//add quick links to plugins page
		$plugin = plugin_basename(__FILE__);
		add_filter("plugin_action_links_$plugin", array(&$this, 'settings_link'));

		// make sure we have the right paths...
		if ( !defined('WP_PLUGIN_URL') ) {
			if ( !defined('WP_CONTENT_DIR') ) define('WP_CONTENT_DIR', ABSPATH.'wp-content');
			if ( !defined('WP_CONTENT_URL') ) define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
			if ( !defined('WP_PLUGIN_DIR') ) define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
			define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
		}// end if ( !defined('WP_PLUGIN_URL') ) {

		// stuff to do when the plugin is loaded
		// i.e. register_activation_hook(__FILE__, array(&$this, 'activate'));
		// i.e. register_deactivation_hook(__FILE__, array(&$this, 'deactivate'));
		register_deactivation_hook(__FILE__, array(&$this, 'deactivate'));

		// add_filter('hook_name', 'your_filter', [priority], [accepted_args]);
		// i.e. add_filter('the_content', array(&$this, 'filter'));
		
		// add_action ('hook_name', 'your_function_name', [priority], [accepted_args]);
		// i.e. add_action('wp_head', array(&$this, 'action'));
		add_action('wp_head', array(&$this, 'action'));

	}// end function

	function activate() {
		// stuff to do when the plugin is activated
	}// end function
	
	function deactivate() {
		// stuff to do when plugin is deactivated
		// i.e. delete_option('pc_searchengine_verify');
		$options = $this->get_options();
		if ( $options['remove_settings'] )
			delete_option('pc_searchengine_verify');
	}// end function
	
	function settings_link($links) {
		$settings_link = '<a href="options-general.php?page=pc-searchengine-verify/admin.php">Settings</a>';
		array_unshift($links,$settings_link);
		return $links;
	}// end function
	
	function filter($content) {
		
		// do stuff with content, return it..		
		return $content;
	
	}// end function
	
	function action() {

		if ( function_exists('is_front_page') && is_front_page() ) {
				
			$options = $this->get_options();

			if ( '' != $options['google'] )
				echo '<meta name="google-site-verification" content="'.stripslashes($options['google']).'" />'."\n";

			if ( '' != $options['bing'] )
				echo '<meta name="msvalidate.01" content="'.stripslashes($options['bing']).'" />'."\n";

			if ( '' != $options['yahoo'] )
				echo '<meta name="y_key" content="'.stripslashes($options['yahoo']).'" />'."\n";

		}// end if
		
	}// end function action() {
	
	function get_options() {
		$options = get_option('pc_searchengine_verify');
		if ( empty($options) )
			$options = $this->set_defaults();
		return $options;
	}// end function
	
	function set_defaults() {
		$options = array(
			'google' => '',
			'bing' => '',
			'yahoo' => '',
			'remove_settings' => false
		);
		update_option('pc_searchengine_verify', $options);
		return $options;
	}// end function

}// end class
$pc_searchengine_verify = new pc_searchengine_verify;

if ( is_admin() )
	include_once dirname(__FILE__).'/admin.php';
