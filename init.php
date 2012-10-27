<?php
/**
* Default settings and settings array used trough out the plugin
**/
function abc_setting_values() {

	// If this is the first time the plugin is activated, add all default values to the WP option
	add_option('abc_title', 'You are using a web browser not supported by this website!');
	add_option('abc_message', 'You are using a web browser that is not supported by this website. This means that some function may not work as supposed which can result in strange behaviors when browsing around. Use or upgrade/install on of the following browsers to take full advantage of this website. - Thank you!');
	add_option('abc_hide', NULL);
	add_option('abc_show', array(
		'ie' 		=> '',
		'ff' 		=> 'http://www.mozilla.com/en-US/firefox/all.html',
		'safari' 	=> '',
		'opera' 	=> '',
		'chrome' 	=> 'https://www.google.com/chrome'
	));
	add_option('abc_check', array(
		'ie' 		=> '8',
		'ff' 		=> '12',
		'safari' 	=> '4',
		'opera' 	=> '10',
		'chrome' 	=> '20'
	));

	// Run update function, this is where the plugin version number is update
	abc_update();

	// Return the settings in array format
	return array(
		'title' 		=> get_option('abc_title'),
		'msg' 			=> get_option('abc_message'),
		'hide' 			=> get_option('abc_hide'),
		'show_browser' 	=> get_option('abc_show'),
		'check_browser' => get_option('abc_check')
	);
}

/**
* Sets plugin version and there update functions will be added when larger updates is made
**/
function abc_update() {

	// If no version number exists, we need to update the settings to v2.0.0 and above
	if (!get_option('abc_version')) {

		// Get old settings array
		$old_settings = get_option('advanced-browser-check');

		// Add the old settings to the new once
		update_option('abc_title', $old_settings['title']);
		update_option('abc_message', $old_settings['msg']);
		update_option('abc_show', $old_settings['show_browser']);
		update_option('abc_check', $old_settings['check_browser']);
		update_option('abc_hide', $old_settings['hide']);

		// Delete the old settings array from the DB
		delete_option('advanced-browser-check');

	}

	update_option('abc_version', ABC_VERSION);

}

/**
* Default browsers settings. This builds the browser dropdowns on the admin page
**/
function abc_default_browsers() {
	return array(
		'safari'	=> array(0,3,4,5,6,7,8,9),
		'opera'		=> array(0,7,8,9,10,11,12,13,14,15,16),
		'ff' 		=> array(0,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20),
		'chrome'	=> array(0,13,14,15,16,17,18,19,20,21,22,23,24,25),
		'ie'		=> array(0,5,6,7,8,9,10,11)
	);
}

/**
* Styles and scripts needed for the plugin to work
**/
function abc_styles_and_scripts() {
	// Load jQuery
	wp_enqueue_script("jquery");

	// jQuery cookie, used to add a cookie so visitors can hide the popup
	wp_enqueue_script("jquery.cookie", plugins_url('/js/jquery.cookie.js', __FILE__));

	// The ajax request so the plugin works with caching plugins
	wp_enqueue_script("abc_script", plugins_url('/js/script.js', __FILE__));

	// Stylesheet for the popup
	wp_enqueue_style("abc_style", plugins_url('/css/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'abc_styles_and_scripts');

/**
* The wrapper, added to the site footer, that the popup will be placed in after the ajax load
**/
function abc_wrapper() {
	echo "<div class='advanced-browser-check' style='display:none;' data-url='".json_encode(array("abc_url" => plugins_url("advanced-browser-check.php",__FILE__)))."'></div>";
}
add_action('wp_footer','abc_wrapper');

?>