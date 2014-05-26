<?php
// ========================
// = Output			 	  =
// ========================
class ABC_Output extends ABC_Core {

	public function __construct()
	{

		parent::__construct();

		// Do the magic!
		$this->html();

	}

	/**
	* Check if we should display the popup
	**/
	private function html()
	{

		$user_browser 	= $this->get_browser();
		$abc_options 	= $this->default_setting_values();
		$check_browsers = $abc_options['check_browser'];
		$show_browsers 	= $abc_options['show_browser'];
		$title 			= $abc_options['title'];
		$message 		= $abc_options['msg'];
		$hide 			= $abc_options['hide'];

		foreach($check_browsers as $browser => $version) {

			if (
				$this->get_short_name($user_browser->Browser) === $browser
				&& $user_browser->MajorVer < ($version+1)
				&& $user_browser->Platform != 'Android'
				&& $user_browser->Platform != 'iOS'
				&& $user_browser->Platform != 'BB10')
			{

				$ie6 = ($this->get_short_name($user_browser->Browser) === 'ie' && $user_browser->MajorVer < '6') ? 'ie6' : '';
				$this->build_html($title, $message, $show_browsers, $hide, $ie6, $user_browser);

			}

		}

	}

	/**
	* Build up the HTML for the popup
	**/
	private function build_html($title = NULL, $msg = NULL, $show_browsers = array(), $hide = NULL, $ie6 = '', $user_browser = array())
	{

		$html = '<div class="adv_browser_check '.$ie6.'">';
			$html .= '<div class="adv_browser_check_msg">';
				$html .= '<h1>'. $title .'</h2>';
				$html .= nl2br($msg);
			$html .= '</div>';
			$html .= '<ul class="adv_browser_check_icons">';

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

		echo $html;

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

			case 'Internet Explorer':
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