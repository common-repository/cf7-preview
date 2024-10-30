<?php
/*
 * The Preview Form
 */
defined('ABSPATH') or die();

/*
 * Since 1.0.0
 * 
 * return @string : url
 */
function cf7_preview_url($path = '')
{
	return plugins_url($path, cf7_preview_plugin_index());
}

/*
 * Since 1.0.0
 * 
 * return @string : path
 */
function cf7_preview_path($path = '')
{
	return dirname(cf7_preview_plugin_index()) . (substr($path, 0, 1) !== '/' ? '/' : '') . $path;
}

/*
 * Since 1.0.0
 * 
 * return @boolean
 */
function cf7_preview_include($path_file = '')
{
	if ($path_file != '' && file_exists($p = cf7_preview_path('includes/' . $path_file))) {
		require $p;

		return true;
	}

	return false;
}

/*
 * Since 1.0.0
 */
function cf7_preview_scripts()
{
	$url = cf7_preview_url('/media/');

	wp_enqueue_style('cf7-preview', $url . 'preview.css');
	wp_enqueue_script('cf7-preview', $url . 'preview.min.js', array('jquery'), '', true);
}
add_action('admin_enqueue_scripts', 'cf7_preview_scripts', 70);

/*
 * Since 1.0.2
 * 
 * return @array WP_Post : pages
 */
function cf7_preview_get_pages_has_cf7_form()
{
	$list = get_posts([
		'post_status' => 'any',
		'post_type' => 'page',
		's' => '[contact-form-7'
	]);
	
	return $list;
}

/*
 * Since 1.0.8
 * 
 * return @string
 */
function cf7_preview_text($text = '')
{
	return __($text, 'cf7-preview');
}

/*
 * Since 1.0.8
 * 
 * echo @string
 */
function cf7_preview_text_e($text = '')
{
	_e($text, 'cf7-preview');
}

/*
 * Since 1.1.6
 * 
 * return @string
 */
function cf7_preview_replace_all_char($text = '')
{
	$chars = [
		'\"' => '"',
		"\'" => "'",
		'name="' => 'data-name="',
	];

	foreach ($chars as $old => $new) {
		$text = str_replace($old, $new, $text);
	}

	return $text;
}

/*
 * Since 1.1.18
 * 
 * return @string
 */
function cf7_preview_pbone_url($pathname = '', $args = [])
{
	$url = 'http://photoboxone.com';

	if ($pathname != '') {
		$url .= '/' . ltrim($pathname, '/');
	}

	if (count($args) > 0) {
		$url = add_query_arg($args, $url);
	}
	
	return esc_url($url);
}