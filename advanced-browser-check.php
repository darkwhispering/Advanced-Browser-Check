<?php
/*
Plugin Name: Advanced Browser Check
Plugin URI: http://www.darkwhispering.com
Description: Do you want to give all IE users a warning to change browser? Or is your site built specificly for chrome? This plugin lets you choose what browsers should trigger a warning popup or not on your site.
Author: Mattias Hedman
Version: 1.0.2
Author URI: http://www.darkwhispering.com
*/

// Check if the file is loaded via AJAX
if($_POST['ajax']) {
	if(!defined('ABSPATH')) require_once("../../../wp-load.php");
	include_once('init.php');
	include_once('output.php');
} else {
	include_once('init.php');
	include_once('settings.php');
}



