<?php
// ========================
// = Output			 	  =
// ========================
class abc_output {

	/**
	* Check if we should display the popup
	**/
	function html() {
		$user_browser 	= $this->getBrowser();
		$abc_options 	= abc_setting_values();
		$check_browsers = $abc_options['check_browser'];
		$show_browsers 	= $abc_options['show_browser'];
		$title 			= $abc_options['title'];
		$msg 			= $abc_options['msg'];
		$hide 			= $abc_options['hide'];

		foreach($check_browsers as $browser => $version) {
			if($user_browser['short_name'] === $browser && $user_browser['version'] <= $version.'______________________________') {
				$ie6 = ($user_browser['short_name'] === 'ie' && $user_browser['version'] <= '6______________________________') ? 'ie6' : '';
				return $this->build_html($title, $msg, $show_browsers, $hide, $ie6);
			}
		}
	}

	/**
	* Build up the HTML for the popup
	**/
	private function build_html($title = NULL, $msg = NULL, $show_browsers = array(), $hide = NULL, $ie6 = '') {
		$html = '<div class="adv_browser_check '.$ie6.'">';
			$html .= '<div class="adv_browser_check_msg">';
				$html .= '<h1>'. $title .'</h2>';
				$html .= nl2br($msg);
			$html .= '</div>';
			$html .= '<ul class="adv_browser_check_icons">';
				foreach($show_browsers as $browser => $link) {
					if($link) {
						$html .= '<li><a href="'. $link .'" class="'. $browser .'"><img src="'. plugins_url('/img/'. $browser .'-128x128.png', __FILE__) .'" alt="'. $browser .'"></a></li>';
					}
				}
			$html .= '</ul>';
			if($hide) {
				$html .= '<a href="#" class="abc-hide">Hide</a>';
			}
		$html .= '</div>';

		return $html;
	}

	/**
	* Get the visitors browser, browser version and platform
	**/
	private function getBrowser()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		} elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}

		// Next get the name of the useragent yes seperately and for good reason
		if (preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
			$bname = 'Internet Explorer';
			$short_name = "ie";
			$ub = "MSIE";
		} elseif (preg_match('/Firefox/i',$u_agent)) {
			$bname = 'Mozilla Firefox';
			$short_name = "ff";
			$ub = "Firefox";
		} elseif (preg_match('/Chrome/i',$u_agent)) {
			$bname = 'Google Chrome';
			$short_name = "chrome";
			$ub = "Chrome";
		} elseif (preg_match('/Safari/i',$u_agent)) {
			$bname = 'Apple Safari';
			$short_name = "safari";
			$ub = "Safari";
		} elseif (preg_match('/Opera/i',$u_agent)) {
			$bname = 'Opera';
			$short_name = "opera";
			$ub = "Opera";
		}

		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}

		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			} else {
		     	$version= $matches['version'][1];
			}
		} else {
			$version= $matches['version'][0];
		}

		// check if we have a number
		if ($version==null || $version=="") {$version="?";}

		return array(
			'userAgent' 	=> $u_agent,
			'name'      	=> $bname,
			'short_name'	=> $short_name,
			'version'		=> floor($version),
			'platform'  	=> $platform,
			'pattern'    	=> $pattern
		);
	}

}

/**
* Output the popup
**/
function abc_output() {
	$output = new abc_output;
	echo $output->html();
}

abc_output();

?>