<?php
/*
Plugin Name: Advanced Browser Check
Plugin URI: http://darkwhispering.com/wp-plugins/advanced-browser-check
Description: Tell IE users to change browser? Or is your site for Chrome only? Now you choose what browsers should trigger a warning popup or not on your site.
Author: Mattias Hedman
Version: 3.0.1
Author URI: http://www.darkwhispering.com
*/

define('ABC_VERSION', '3.0.1');

// Check if the file is loaded via AJAX
if (!empty($_POST['abc_ajax'])) {

	if(!defined('ABSPATH')) require_once("../../../wp-load.php");
    include_once('abc-core.php');
	include_once('abc-output.php');

    
    new ABC_Output;

} else {

    include_once('abc-core.php');
	include_once('abc-settings-page.php');

    new ABC_Core;

}



