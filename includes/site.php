<?php
/*
 * The Preview Form
 */
defined('ABSPATH') or die();

/*
 * Since 1.0.2
 * 
 * Updated at 1.1.18
 */
function cf7_preview_filter_wpcf7_form_hidden_fields($hidden_fields = array())
{
	if(is_array($hidden_fields)){
		$post_id = isset($_GET['cf7-preview']) ? intval($_GET['cf7-preview']) : 0;
		if ($post_id > 0) {
			$hidden_fields = array(
				'_wpcf7_preview_id' => $post_id,
			);
		}
	}

	return $hidden_fields;
}
add_filter('wpcf7_form_hidden_fields', 'cf7_preview_filter_wpcf7_form_hidden_fields', 10, 1);

/*
 * Since 1.0.2
 * 
 * Updated at 1.1.18
 */
function cf7_preview_filter_contact_form_properties($properties = array())
{
	if (is_array($properties)) {
		$post_id = isset($_GET['cf7-preview']) ? intval($_GET['cf7-preview']) : 0;
		if ($post_id == 0) {
			$post_id = isset($_POST['_wpcf7_preview_id']) ? intval($_POST['_wpcf7_preview_id']) : 0;
			if ($post_id > 0) {
				$_POST['_wpcf7_id'] = $_POST['_wpcf7_preview_id'];
			}
		}

		if ($post_id > 0) {
			$content = (string) get_post_meta($post_id, '_preview', true);
			if ($content != '') {
				$properties['form'] = $content;
			}
		}
	}

	return $properties;
}
add_filter('wpcf7_contact_form_properties', 'cf7_preview_filter_contact_form_properties', 20, 2);

/*
 * Since 1.1.4
 * 
 * Updated at 1.1.5
 */
function cf7_preview_form_site()
{
	$action  = isset($_POST['action'])  ? sanitize_text_field($_POST['action']) : '';
	$content = isset($_POST['content']) ? format_to_edit($_POST['content'], true) : '';
	$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

	if ($action == 'cf7_preview_form' && $content != '' && $post_id > 0) {

		$p = get_post($post_id);
		if ($p == null || $p->post_type != 'wpcf7_contact_form') {
			return;
		}
		
		update_post_meta($post_id, '_preview', $content);

		$_GET['cf7-preview'] = $post_id;

		$content = '[contact-form-7 id="' . $post_id . '"]';

		$form = do_shortcode($content);

		$form = str_replace(array('\"', "\'"), array('"', "'"), $form);

		$form = str_replace($n = 'name="', 'data-' . $n, $form);

		$form = str_replace('form', 'div', $form);

		echo $form;
		
		exit();
	}
}
add_action('wp', 'cf7_preview_form_site');
