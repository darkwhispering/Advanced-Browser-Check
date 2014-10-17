<?php

class ABC_Settings extends ABC_Core {

    public function __construct()
    {

        parent::__construct();

    }

	function settings_panel()
    {
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		$abc = $this->default_setting_values();

	?>
		<div class="wrap abc-settings">
			<h2><?php _e('Advanced Browser Check'); ?></h2>

			<form method="post" action="options.php">
                <?php wp_nonce_field('update-options'); ?>

				<table class="form-table abc-settings">
                    <tbody>
                        <tr valign="top">
                            <th scope="row">
                            	<label for="abc_title"><?php  _e('Title'); ?></label>
                            </th>
                            <td>
								<input type="text" class="large-text" id="abc_title" name="abc_title" value="<?php echo $abc['title']; ?>">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                            	<label for="abc_message"><?php _e('Message'); ?></label>
                            </th>
                            <td>
								<textarea class="large-text" id="abc_message" name="abc_message" cols="50" rows="8"><?php echo $abc['msg']; ?></textarea>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                            	<?php _e('Let user hide the popup'); ?>
                            	<p class="description"><?php _e('Let the user hide the popup and use your site. The popup will be hidden for 24h only, this is set by a cookie'); ?></p>
                            </th>
                            <td>
                            	<label for="hide">
									<input type="checkbox" id="abc_hide" name="abc_hide" value="yes" <?php echo !empty($abc['hide']) ? 'checked="checked"' : '' ?> /> <?php _e('Yes'); ?>
								</label>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                            	<?php _e('Choose browsers to link'); ?>
                            	<p class="description"><?php _e('This is the browsers that you can display a link to and tell ypur visitor to use'); ?></p>
                            </th>
                            <td>
                            	<ul>
                            		<li>
										<label for="ie">
											<input type="checkbox" id="ie" name="abc_show[ie]" value="http://www.microsoft.com/windows/internet-explorer/worldwide-sites.aspx" <?php echo !empty($abc['show_browser']['ie']) ? 'checked="checked"' : '' ?> /> <?php _e('Internet Explorer'); ?>
										</label>
									</li>
									<li>
										<label for="ff">
											<input type="checkbox" id="ff" name="abc_show[ff]" value="http://www.mozilla.com/en-US/firefox/all.html" <?php echo !empty($abc['show_browser']['ff']) ? 'checked="checked"' : '' ?> /> <?php _e('Firefox'); ?>
										</label>
									</li>
									<li>
										<label for="safari">
											<input type="checkbox" id="safari" name="abc_show[safari]" value="http://www.apple.com/safari/download/" <?php echo !empty($abc['show_browser']['safari']) ? 'checked="checked"' : '' ?> /> <?php _e('Safari'); ?>
										</label>
									</li>
									<li>
										<label for="opera">
											<input type="checkbox" id="opera" name="abc_show[opera]" value="http://www.opera.com/download/" <?php echo !empty($abc['show_browser']['opera']) ? 'checked="checked"' : '' ?> /> <?php _e('Opera'); ?>
										</label>
									</li>
									<li>
										<label for="chrome">
											<input type="checkbox" id="chrome" name="abc_show[chrome]" value="https://www.google.com/chrome" <?php echo !empty($abc['show_browser']['chrome']) ? 'checked="checked"' : '' ?> /> <?php _e('Chrome'); ?>
										</label>
									</li>
								</ul>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                            	<?php _e('Browsers and versions to check'); ?>
                            </th>
                            <td>
                        		<ul>
	                            	<?php $browsers = $this->default_browsers(); ?>
									<?php $browser_selects = ''; ?>
									<?php foreach($browsers as $key => $browser) : ?>
										<li>
											<?php $browser_selects .= $key.','; ?>
											<select name="abc_check[<?php echo $key; ?>]">
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
													<?php if ($b == '0') : ?>
														<option value="<?php echo $b; ?>" <?php echo $abc['check_browser'][$key] == $b ? 'selected="selected"' : '' ?>>
															<?php _e('Do not block ').$browser_name; ?>
														</option>
													<?php else : ?>
														<option value="<?php echo $b; ?>" <?php echo $abc['check_browser'][$key] == $b ? 'selected="selected"' : '' ?>>
															<?php echo $browser_name .' '. $b; ?> <?php _e(' or lower'); ?>
														</option>
													<?php endif; ?>
												<?php endforeach; ?>
											</select>
										</li>
									<?php endforeach; ?>
								</ul>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <?php _e('Debug'); ?>
                                <p class="description"><?php _e('Having problems. Activate debug and see what information the plugin detects about your browser. Debug is only visible for administrators!'); ?></p>
                            </th>
                            <td>
                                <?php
                                    $debug_on = $debug_off = null;

                                    if (!empty($abc['debug'])) {
                                        $debug_on = $abc['debug'] === 'on' ? 'checked="checked"' : '';
                                        $debug_off = $abc['debug'] === 'off' ? 'checked="checked"' : '';
                                    }
                                ?>

                                <label for="hide">
                                    <input type="radio" id="abc_debug" name="abc_debug" value="on" <?php echo $debug_on; ?> /> <?php _e('On'); ?>
                                    <br/>
                                    <input type="radio" id="abc_debug" name="abc_debug" value="off" <?php echo $debug_off; ?> /> <?php _e('Off'); ?>
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>
				<div class="form-row">
					<input type="hidden" name="action" value="update" />
					<input type="hidden" name="page_options" value="abc_title,abc_message,abc_hide,abc_show,abc_check,abc_debug" />
					<input type="submit" name="submit" value="Save" class="button-primary save" />
				</div>
			</form>
		</div>
	<?php
	}
}

function abc_settings()
{
	$settings_panel = new ABC_Settings;
	return $settings_panel->settings_panel();
}

function abc_adminmenu()
{
	add_submenu_page('options-general.php', 'Advanced Browser Check', 'Advanced Browser Check', 'manage_options', 'advanced-browser-check', 'abc_settings');
}

add_action('admin_menu','abc_adminmenu');
?>
