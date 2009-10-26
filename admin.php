<?php
class pc_searchengine_verify_admin {

	function pc_searchengine_verify_admin() {
		// stuff to do when the plugin is loaded
		add_action('admin_menu', array(&$this, 'admin_menu'));
	}

	function admin_menu() {
		add_options_page('PC Search Engine Verify Settings', 'PC Search Engine Verify', 'manage_options', __FILE__, array(&$this, 'settings_page'));
	}// end function admin_menu()

	function settings_page() {
		
		global $pc_searchengine_verify;

		$options = $pc_searchengine_verify->get_options();
		
		if ( isset($_POST['update']) ) {
			
			// check user is authorised
			if ( function_exists('current_user_can') && !current_user_can('manage_options') )
				die('Sorry, not allowed...');
			check_admin_referer('pc_searchengine_verify_settings');

			$options['google'] = trim($_POST['google']);
			$options['yahoo'] = trim($_POST['yahoo']);
			$options['bing'] = trim($_POST['bing']);

			isset($_POST['remove_settings']) ? $options['remove_settings'] = true : $options['remove_settings'] = false;

			update_option('pc_searchengine_verify', $options);

			echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>';
		
		}# end if ( isset($_POST['update']) ) {

		echo '<div class="wrap">'
			.'<h2>PC Search Engine Verify Settings</h2>'
			.'<form method="post">';
		if ( function_exists('wp_nonce_field') ) wp_nonce_field('pc_searchengine_verify_settings');
		echo '<p>To verify ownership of your website, the search engines will ask you to add specific information to your site. One option will be to add a meta tag to your home page.</p><p>Log in to the following webmaster tools areas to get your meta tag code;</p>'
			.'<table>'
			.'<tr><td><strong>Google:</strong></td><td><a href="https://www.google.com/webmasters/tools/" target="_blank">https://www.google.com/webmasters/tools/</a></td></tr>'
			.'<tr><td><strong>Yahoo:</strong></td><td><a href="https://siteexplorer.search.yahoo.com/" target="_blank">https://siteexplorer.search.yahoo.com/</a></td></tr>'
			.'<tr><td><strong>Bing:</strong></td><td><a href="http://www.bing.com/webmaster/" target="_blank">http://www.bing.com/webmaster/</a></td></tr>'
			.'</table>'
			.'<p>When you have added your site in the webmaster tools area of these search engines you will receive the meta tag code needed to verify ownership. Add the content value of the meta tag code into the appropriate box below and click the Save Changes button. You can then verify your site in the webmaster tools area.</p>'
			.'<table class="form-table">'
			.'<tr>'
				.'<th scope="row">Google:</th>'
				.'<td>&lt;meta name="google-site-verification" content="<input type="text" name="google" id="google" value="' . stripslashes($options['google']) . '" class="regular-text" />" /&gt;</td>'
			.'</tr>'
			.'<tr>'
				.'<th scope="row">Yahoo:</th>'
				.'<td>&lt;meta name="y_key" content="<input type="text" name="yahoo" id="yahoo" value="' . stripslashes($options['yahoo']) . '" class="regular-text" style="width:150px;" />"  /&gt;</td>'
			.'</tr>'
			.'<tr>'
				.'<th scope="row">Bing:</th>'
				.'<td>&lt;meta name="msvalidate.01" content="<input type="text" name="bing" id="bing" value="' . stripslashes($options['bing']) . '" class="regular-text" />" /&gt;</td>'
			.'</tr>'
			.'<tr>'
				.'<th scope="row">Delete settings on deactivation:</th>'
				.'<td><input type="checkbox" id="remove_settings" name="remove_settings"';
					if ( $options['remove_settings'] ) echo 'checked="checked"';
					echo ' /> <span class="setting-description">When you tick this box all saved settings will be deleted when you deactivate this plugin.</span></td>'
			.'</tr>'
			.'</table>'
			.'<p class="submit"><input type="submit" name="update" class="button-primary" value="Save Changes" /></p>'
			.'</form>'
			.'</div>';
		
	}# end function settings_page() {

}// end class pc_searchengine_verify_admin {
$pc_searchengine_verify_admin = new pc_searchengine_verify_admin;
