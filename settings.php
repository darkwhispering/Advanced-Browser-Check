<?php
// ========================
// = Plugin settings page =
// ========================

class abc_settings {
	function abc_admin_page() {
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		$title = 'You are using a web browser not supported by this website!';

		$message = 'You are using a web browser that is not supported by this website. This means that some function may not work as supposed which can result in strange behaviors when browsing around. Use or upgrade/install on of the following browsers to take full advantage of this website. 

Thank you!';

		$abc_default_values = array(
			'title' => $title,
			'msg' => $message,
			'hide' => '',
			'show_browser' => array(
				'ie' 	=> '',
				'ff' 	=> 'http://www.mozilla.com/en-US/firefox/all.html',
				'safari' 	=> '',
				'opera' 	=> '',
				'chrome' 	=> 'https://www.google.com/chrome'
			),
			'check_browser' => array(
				'ie' 	=> '8',
				'ff' 	=> '12',
				'safari' 	=> '4',
				'opera' 	=> '10',
				'chrome' 	=> '20'
			)
		);

		add_option('advanced-browser-check',$abc_default_values);
		$abc_default_value = NULL;

	?>
		<div class="wrap abc-settings">
			<div class="icon32" id="icon-options-general"><br></div>
			<h2>Advanced Browser Check</h2>
			
			<?php if(!empty($_POST['submit'])) : ?>
				<?php $abc_new_values = $this->abc_save_settings($_POST); ?>
				<div class="updated">
					Your settings has been saved!
				</div>
			<?php endif;

			$abc = get_option('advanced-browser-check'); 

			if(!empty($abc_new_values)) {
				$abc = $abc_new_values;
			}
			?>

			<?php
				$referer = wp_get_referer();
				$referer = !empty($referer) ? wp_get_referer() : '/wp-admin/options-general.php?page=advanced-browser-check';
			?>
			<form method="post" action="<?php echo $referer; ?>">
			<!-- <form method="post" action="/wp-admin/options-general.php?page=advanced-browser-check"> -->
				<?php //wp_nonce_field('update-options'); ?>

				<h2><?php echo __('Title and Message'); ?></h2>
				<div class="form-row">
					<span><?php echo __('Title'); ?></span><br/>
					<input type="text" class="regular-text" name="abc_title" value="<?php echo $abc['title']; ?>">
				</div>
				<div class="form-row">
					<span><?php echo __('Message'); ?></span><br/>
					<textarea name="abc_message"><?php echo $abc['msg']; ?></textarea>
				</div>
				<h2><?php echo __('Choose browsers to link to below your message'); ?></h2>
				<div class="form-row">
					<label for="ie">
						<input type="checkbox" id="ie" name="abc_show_ie" value="http://www.microsoft.com/windows/internet-explorer/worldwide-sites.aspx" <?php echo !empty($abc['show_browser']['ie']) ? 'checked="checked"' : '' ?> /> Internet Explorer
					</label>
					<label for="ff">
						<input type="checkbox" id="ff" name="abc_show_ff" value="http://www.mozilla.com/en-US/firefox/all.html" <?php echo !empty($abc['show_browser']['ff']) ? 'checked="checked"' : '' ?> /> Firefox
					</label>
					<label for="safari">
						<input type="checkbox" id="safari" name="abc_show_safari" value="http://www.apple.com/safari/download/" <?php echo !empty($abc['show_browser']['safari']) ? 'checked="checked"' : '' ?> /> Safari
					</label>
					<label for="opera">
						<input type="checkbox" id="opera" name="abc_show_opera" value="http://www.opera.com/download/" <?php echo !empty($abc['show_browser']['opera']) ? 'checked="checked"' : '' ?> /> Opera
					</label>
					<label for="chrome">
						<input type="checkbox" id="chrome" name="abc_show_chrome" value="https://www.google.com/chrome" <?php echo !empty($abc['show_browser']['chrome']) ? 'checked="checked"' : '' ?> /> Chrome
					</label>
				</div>

				<h2><?php echo __('Browsers and versions to check for'); ?></h2>
				<div class="form-row">
					<?php $browsers = json_decode(file_get_contents(WP_PLUGIN_DIR.'/advanced-browser-check/browser-versions.json')); ?>
					<?php $browser_selects = ''; ?>
					<?php foreach($browsers as $key => $browser) : ?>
						<?php $browser_selects .= $key.','; ?>
						<select name="abc_check_<?php echo $key; ?>">
							<?php
								switch($key) {
									case $key == 'ff' :
										$browser_name = 'Firefox';
									break;
									case $key == 'ie' :
										$browser_name = 'Internet Explorer';
									break;
									case $key == 'safari' :
										$browser_name = 'Safari';
									break;
									case $key == 'opera' :
										$browser_name = 'Opera';
									break;
									case $key == 'chrome' :
										$browser_name = 'Chrome';
									break;
								}
							?>
							<?php foreach($browser as $b) : ?>
								<option value="<?php echo $b; ?>" <?php echo $abc['check_browser'][$key] == $b ? 'selected="selected"' : '' ?>>
									<?php echo $browser_name .' '. $b; ?> or lower
								</option>
							<?php endforeach; ?>
						</select>
					<?php endforeach; ?>
				</div>
				<div class="form-row">
					<label for="hide">
						<input type="checkbox" id="hide" name="abc_hide" value="yes" <?php echo !empty($abc['hide']) ? 'checked="checked"' : '' ?> /> User can hide the warning popup module <em>(It will stay hidden for 24h only)</em>
					</label>
				</div>
				<div class="form-row">
<!-- 					<input type="hidden" name="action" value="update" />
					<input type="hidden" name="page_options" value="<?php echo $browser_selects; ?>abc_title,abc_message,abc_show_ie,abc_show_ff,abc_show_safari,abc_show_opera,abc_show_chrome,abc_hide" /> -->

					<input type="submit" name="submit" value="Save" class="button-primary save" />
				</div>
			</form>
		</div>
	<?php
	}
	
	private function abc_save_settings($data) {

		$abc_values = array(
			'title'	=> $data['abc_title'],
			'msg' 	=> $data['abc_message'],
			'hide' 	=> $data['abc_hide'],
			'show_browser' => array(
				'ie' 		=> $data['abc_show_ie'],
				'ff' 		=> $data['abc_show_ff'],
				'safari' 		=> $data['abc_show_safari'],
				'opera' 		=> $data['abc_show_opera'],
				'chrome' 		=> $data['abc_show_chrome']
			),
			'check_browser' => array(
				'ie' 		=> $data['abc_check_ie'],
				'ff' 		=> $data['abc_check_ff'],
				'safari' 		=> $data['abc_check_safari'],
				'opera' 		=> $data['abc_check_opera'],
				'chrome' 		=> $data['abc_check_chrome']
			)
		);

		update_option('advanced-browser-check',$abc_values);
		
		return $abc_values;
	}
}

// Add admin page css
function abc_admin_css() {
	wp_register_style('abc_adminstyle', plugins_url('css/admin-style.css', __FILE__));
	wp_enqueue_style('abc_adminstyle');
}
add_action('admin_init','abc_admin_css');

function abc_settings() {
	$settings_panel = new abc_settings;
	return $settings_panel->abc_admin_page();
}

function abc_adminmenu() {
	add_submenu_page('options-general.php', 'Advanced Browser Check', 'Advanced Browser Check', 'manage_options', 'advanced-browser-check', 'abc_settings');
}
add_action('admin_menu','abc_adminmenu');
?>