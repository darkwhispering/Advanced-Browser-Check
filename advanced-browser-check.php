<?php
/*
Plugin Name: Advanced Browser Check
Plugin URI: http://darkwhispering.com/wp-plugins/advanced-browser-check
Description: Tell IE users to change browser? Or is your site for Chrome only? Now you choose what browsers should trigger a warning popup or not on your site.
Author: Mattias Hedman
Version: 3.0.3
Author URI: http://www.darkwhispering.com
*/

define('ABC_VERSION', '3.0.3');


add_action('wp_ajax_abc_ajax', 'abc_ajax');
add_action('wp_ajax_nopriv_abc_ajax', 'abc_ajax');


// Create html output on ajax request
function abc_ajax() {

    include_once('abc-core.php');
    include_once('abc-output.php');
    
    $output = new ABC_Output;
    echo $output->html();
    die();

}

// If it's not an ajax request, load settings page
if (!defined('DOING_AJAX') || !DOING_AJAX) {

    include_once('abc-core.php');
    include_once('abc-settings-page.php');

    new ABC_Core;

}



