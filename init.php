<?php
function abc_setting_values() {
	add_option('abc_title', 'You are using a web browser not supported by this website!');
	add_option('abc_message', 'You are using a web browser that is not supported by this website. This means that some function may not work as supposed which can result in strange behaviors when browsing around. Use or upgrade/install on of the following browsers to take full advantage of this website. - Thank you!');
	add_option('abc_show', NULL);
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

	return array(
		'title' 		=> get_option('abc_title'),
		'msg' 			=> get_option('abc_message'),
		'hide' 			=> get_option('abc_hide'),
		'show_browser' 	=> get_option('abc_show'),
		'check_browser' => get_option('abc_check')
	);
}

function abc_default_browsers() {
	return array(
		'safari'	=> array(0,3,4,5,6,7),
		'opera'		=> array(0,8,9,10,11,12,13),
		'ff' 		=> array(0,10,11,12,13,14,15,16,17),
		'chrome'	=> array(0,18,19,20,21,22,23),
		'ie'		=> array(0,6,7,8,9,10)
	);
}

// Stylesheet
function abc_styles_and_scripts() {
	wp_enqueue_script("jquery");
	wp_enqueue_script("jquery.cookie", plugins_url('/js/jquery.cookie.js', __FILE__));
	wp_enqueue_script("abc_script", plugins_url('/js/script.js', __FILE__));
	wp_enqueue_style("abc_style", plugins_url('/css/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'abc_styles_and_scripts');

function abc_wrapper() {
	echo "<div class='advanced-browser-check' style='display:none;' data-url='".json_encode(array("abc_url" => plugins_url("advanced-browser-check.php",__FILE__)))."'></div>";
}
add_action('wp_footer','abc_wrapper');

?>