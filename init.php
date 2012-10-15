<?php
// Stylesheet
function abc_styles_and_scripts() {
	wp_enqueue_script("jquery");
	wp_enqueue_script("jquery.cookie", plugins_url('/js/jquery.cookie.js', __FILE__));
	wp_enqueue_script("abc_script", plugins_url('/js/script.js', __FILE__));
	wp_enqueue_style("abc_style", plugins_url('/css/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'abc_styles_and_scripts');

// Add plugin class to the body
// add_filter('body_class','abc_class');
// function abc_class($classes) {
// 	// add 'abc-active' to the $classes array
// 	$classes[] = 'abc-active';
// 	// return the $classes array
// 	return $classes;
// }

function abc_wrapper() {
	echo "<div class='advanced-browser-check' style='display:none;' data-url='".json_encode(array("abc_url" => plugins_url("advanced-browser-check.php",__FILE__)))."'></div>";
}
add_action('wp_footer','abc_wrapper');
?>