<?php
/*
Plugin Name: Preview Form
Plugin URI: https://docs.photoboxone.com/cf7-preview.html
Description: The preview form for Contact Form 7.
Author: DevUI
Author URI: http://photoboxone.com/donate/?developer=devui
Version: 1.1.24
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 6.2
Requires PHP: 7.4
*/

defined('ABSPATH') or die();

function cf7_preview_plugin_index()
{
	return __FILE__;
}

// All
require(__DIR__ . '/includes/functions.php');

// Admin
require(__DIR__ . '/includes/preview.php');

// Site
require(__DIR__ . '/includes/site.php');
