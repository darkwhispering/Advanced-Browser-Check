<?php
// ========================
// = Output			 	  =
// ========================
class ABC_Output extends ABC_Core {

	public function __construct()
	{

		parent::__construct();

	}

	/**
	* Check if we should display the popup
	**/
	public function html()
	{

		$user_browser 	= $this->get_browser();
		$abc_options 	= $this->default_setting_values();
		$check_browsers = $abc_options['check_browser'];
		$show_browsers 	= $abc_options['show_browser'];
		$title 			= $abc_options['title'];
		$message 		= $abc_options['msg'];
		$hide 			= $abc_options['hide'];
		$debug			= $abc_options['debug'];

		if ($debug === 'on' && current_user_can('level_10')) {

			$old_ie = ($this->get_short_name($user_browser->Browser) === 'ie' && $user_browser->MajorVer < '8') ? 'old-ie' : '';
			return $this->build_html($title, $message, $show_browsers, $hide, $old_ie, $user_browser, $debug);

		} else {

			foreach($check_browsers as $browser => $version) {

				if (
					$this->get_short_name($user_browser->Browser) === $browser
					&& $user_browser->MajorVer <= $version
					&& $user_browser->Platform != 'Android'
					&& $user_browser->Platform != 'iOS'
					&& $user_browser->Platform != 'BB10')
				{

					$old_ie = ($this->get_short_name($user_browser->Browser) === 'ie' && $user_browser->MajorVer < '8') ? 'old-ie' : '';
					return $this->build_html($title, $message, $show_browsers, $hide, $old_ie, $user_browser);

				}

			}

		}

	}

	/**
	* Build up the HTML for the popup
	**/
	private function build_html($title = NULL, $msg = NULL, $show_browsers = array(), $hide = NULL, $old_ie = '', $user_browser = array(), $debug = false)
	{

		if ($debug) {

			$debug_html = '<ul class="adv_browser_check_debug">';

			foreach ($user_browser as $key => $value) {

				$debug_html .= '<li><strong>'.$key.'</strong>: '.$value.'</li>';

			}

			$debug_html .= '</ul>';

		}

		$html = '<div class="adv_browser_check '.$old_ie.'">';

			if ($debug) { $html .= $debug_html; }

			$html .= '<div class="adv_browser_check_msg '.$old_ie.'">';
				$html .= '<h1>'. $title .'</h2>';
				$html .= nl2br($msg);
			$html .= '</div>';
			$html .= '<ul class="adv_browser_check_icons '.$old_ie.'">';

				foreach($show_browsers as $browser => $link) {

					if($link) {
						
						$html .= '<li>';
							$html .= '<a href="'. $link .'" class="'. $browser .'" target="_blank">';
								$html .= '<img src="'. plugins_url('/img/'. $browser .'-64x64.png', __FILE__) .'" alt="'. $browser .'">';
								$html .= $this->get_full_name($browser);
							$html .= '</a>';
						$html .= '</li>';
					}

				}

			$html .= '</ul>';

			if($hide) {

				$html .= '<a href="#" class="abc-hide"></a>';

			}

		$html .= '</div>';

		return $html;

	}

	/**
	* Return short browser name based on the full name
	**/
	private function get_short_name($browser)
	{

		switch($browser) {

			case 'Firefox':
				$short = 'ff';
				break;

			case 'Chrome':
				$short = 'chrome';
				break;

			case 'Safari':
				$short = 'safari';
				break;

			case 'Opera':
				$short = 'opera';
				break;

			case 'IE':
				$short = 'ie';
				break;

			default:
				$short = 'other';
				break;

		}

		return $short;

	}

	/**
	* Return full browser name based on the short name
	**/
	private function get_full_name($short)
	{

		switch($short) {

			case 'ff':
				$browser = 'Firefox';
				break;

			case 'chrome':
				$browser = 'Chrome';
				break;

			case 'safari':
				$browser = 'Safari';
				break;

			case 'opera':
				$browser = 'Opera';
				break;

			case 'ie':
				$browser = 'Internet Explorer';
				break;

			default:
				$browser = 'other';
				break;

		}

		return $browser;

	}

}