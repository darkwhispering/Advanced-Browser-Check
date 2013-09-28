<?php
/*
Plugin Name: Advanced Browser Check
Plugin URI: http://www.darkwhispering.com
Description: Tell IE users to change browser? Or is your site for chrome only? Now you choose what browsers should trigger a warning popup or not on your site.
Author: Mattias Hedman
Version: 2.1.3
Author URI: http://www.darkwhispering.com
*/

define('ABC_VERSION', '2.1.3');

// Check if the file is loaded via AJAX
if (!empty($_POST['ajax'])) {
	if(!defined('ABSPATH')) require_once("../../../wp-load.php");
	include_once('init.php');
	include_once('output.php');
} else {
	include_once('init.php');
	include_once('settings-page.php');
}



