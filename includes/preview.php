<?php
/*
 * The Preview Form
 */
defined('ABSPATH') or die();

/*
 * Since 1.0.0
 * 
 * Update 1.0.8
 * 
 * return @array : $panels
 */
function cf7_preview_editor_panels($panels = array())
{
	if (!is_array($panels)) {
		return $panels;
	}

	$keys = array_keys($panels);

	if (in_array('review-panel', $keys) == false) {
		$temp = array();

		foreach ($panels as $key => $panel) {
			$temp[$key] = $panel;
			if ($key == 'form-panel') {
				$temp['preview-panel'] = array(
					'title' 	=> cf7_preview_text('Preview'),
					'callback' 	=> 'cf7_preview_tab',
				);
			}
		}

		return $temp;
	}

	return $panels;
}
add_filter('wpcf7_editor_panels', 'cf7_preview_editor_panels', 20, 1);

/*
 * Since 1.0.0
 * 
 * Update 1.0.2
 * 
 * return @html : $tab
 */
function cf7_preview_tab()
{
	$post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;

	echo '<div class="cf7_preview_tab_main">';

	echo '<h2>' . cf7_preview_text('Form') . '</h2>';

	echo '<hr />';

	echo '<div id="cf7_preview_tab_content" data-message="' . cf7_preview_text('Please waiting') . ' .... !">';

	if ($post_id > 0) {

		$form = do_shortcode('[contact-form-7 id="' . $post_id . '" title="' . get_the_title($post_id) . '"]');

		$form = str_replace($n = 'name="', 'data-' . $n, $form);

		echo $form;
	}

	echo '</div>';

	// Since 1.0.2
	if ($post_id > 0) {
		cf7_preview_list_page($post_id);
	}

	echo '</div>';
}

/*
 * Since 1.0.0
 * 
 * ajax get preview form html
 */
function cf7_preview_form()
{
	$content = isset($_POST['content']) ? format_to_edit($_POST['content'], true) : '';
	if ($content != '') {

		$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
		if ($post_id > 0) {
			update_post_meta($post_id, '_preview', $content);
		}

		if (preg_match('/<(div|p)/i', $content) == false) {
			$rows = explode("\n", $content);

			if (count($rows)) {
				foreach ($rows as $key => $row) {
					if ($row == '' || sanitize_text_field($row) == '') {
						unset($rows[$key]);
					}
				}
				$content = '<p>' . implode('</p><p>', $rows) . '</p>';
			}
		}

		$form = wpcf7_do_shortcode($content);

		$form = str_replace(array('\"', "\'"), array('"', "'"), $form);

		$form = str_replace($n = 'name="', 'data-' . $n, $form);

		echo '<div role="form" class="wpcf7" id="wpcf7-preview-now" lang="' . get_user_locale() . '" dir="ltr">';
		echo '<div class="screen-reader-response"></div>';
		echo $form;
		echo '</div>';
	}

	// Don't forget to stop execution afterward.
	wp_die();
}
add_action('wp_ajax_cf7_preview_form', 'cf7_preview_form', 10, 2);

/*
 * Since 1.0.2
 */
function cf7_preview_list_page($post_id = 0)
{
	echo '<hr />';
	echo '<div class="cf7_preview_list_page">';
	echo '<h3>' . cf7_preview_text('Select Page to preview') . '</h3>';

	$list = cf7_preview_get_pages_has_cf7_form();
	if (count($list) > 0) {
		echo '<ol>';
		foreach ($list as $p) {
			echo '<li>';
			echo '<a href="' . esc_url(add_query_arg(['cf7-preview' => $post_id], get_permalink($p->ID))) . '"  target="_blank">'
				. $p->post_title
				. '</a>';
			echo '</li>';
		}
		echo '</ol>';
	} else {
		echo '<p><a class="button-secondary" href="' . esc_url(add_query_arg(['post_type' => 'page'], admin_url('post-new.php'))) . '" target="_blank">'
			. cf7_preview_text('Add new page')
			. '</a></p>';
	}

	echo '</div>';
}

/*
 * Since 1.0.4
 * 
 * Update 1.1.5
 */
function cf7_preview_donate_sidebar()
{
?>
	<div id="donate" class="postbox cf7-preview-postbox-donate" style="display: none">
		<div class="inside" align="center">
			<p>
				<a href="<?php echo cf7_preview_donate_url() ?>" target="_blank">
					<img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" alt="<?php cf7_preview_text_e('Donate') ?>">
				</a>
			</p>
		</div>
	</div>
<?php
}
add_action('wpcf7_admin_footer', 'cf7_preview_donate_sidebar');

/*
 * Since 1.1.5
 */
function cf7_preview_donate_url()
{
	return esc_url(add_query_arg(['cf7donate' => time()], home_url()));
}

/*
 * Since 1.1.5
 */
function cf7_preview_plugin_row_meta($plugin_meta = array(), $plugin_file = '')
{
	if ($plugin_file == plugin_basename(cf7_preview_plugin_index())) {
		$plugin_meta[] = '<a class="dashicons-before dashicons-awards" href="' . cf7_preview_donate_url() . '" target="_blank">'
			. cf7_preview_text('Donate') . '</a>';
	}

	return $plugin_meta;
}
add_filter('plugin_row_meta', 'cf7_preview_plugin_row_meta', 10, 2);

/*
 * Since 1.1.8
 * 
 * Updated at 1.1.18
 */
function cf7_preview_donate_go()
{
	if (isset($_GET['cf7donate'])) {
		wp_redirect(cf7_preview_pbone_url('donate', ['for' => 'contact-form-7-preview']));
		exit();
	}
}
add_filter('wp', 'cf7_preview_donate_go');

/*
 * Since 1.1.15
 */
function cf7_preview_plugin_actions($actions = array())
{
	$pagenow = isset($GLOBALS['pagenow']) ? sanitize_text_field($GLOBALS['pagenow']) : '';
	if ($pagenow == 'plugins.php' && is_array($actions)) {
		$actions[] = '<a href="https://docs.photoboxone.com/cf7-preview.html" target="_blank">' . __("Documents") . '</a>';
	}

	return $actions;
}
add_filter("plugin_action_links_" . plugin_basename(cf7_preview_plugin_index()), "cf7_preview_plugin_actions");
