<?php

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
		$user_browser   = $this->get_browser();
		$abc_options    = $this->default_setting_values();
		$check_browsers = $abc_options['check_browser'];
		$show_browsers  = $abc_options['show_browser'];
		$title          = $abc_options['title'];
		$message        = $abc_options['msg'];
		$hide           = $abc_options['hide'];
		$debug          = $abc_options['debug'];
		$old_ie         = ( $user_browser['short_name'] === 'ie' && $user_browser['version'] < '8' ) ? 'old-ie' : '';

		if ( $debug === 'on' && current_user_can( 'level_10' ) )
		{
			return $this->build_html( $title, $message, $show_browsers, $hide, $old_ie, $user_browser, $debug );
		}
		else
		{
			foreach( $check_browsers as $browser => $version )
			{
				if (
					$user_browser['short_name'] === $browser
					&& $user_browser['version'] <= $version
					&& $user_browser['version'] > 0
					&& $user_browser['platform'] != 'android'
					&& $user_browser['platform'] != 'iOS'
					&& $user_browser['platform'] != 'Unknown'
					)
				{
					return $this->build_html( $title, $message, $show_browsers, $hide, $old_ie, $user_browser );
				}
			}
		}
	}

	/**
	* Build up the HTML for the popup
	**/
	private function build_html( $title = NULL, $msg = NULL, $show_browsers = array(), $hide = NULL, $old_ie = '', $user_browser = array(), $debug = false )
	{
		if ( $debug )
		{
			$debug_html = '<ul class="adv_browser_check_debug">';

			foreach ( $user_browser as $key => $value )
			{
				$debug_html .= '<li><strong>'.$key.'</strong>: '.$value.'</li>';
			}

			$debug_html .= '</ul>';
		}

		$html = '<div class="adv_browser_check '.$old_ie.'">';

			if ( $debug ) { $html .= $debug_html; }

			$html .= '<div class="adv_browser_check_msg '.$old_ie.'">';
				$html .= '<h1>'. $title .'</h1>';
				$html .= nl2br($msg);
			$html .= '</div>';
			$html .= '<ul class="adv_browser_check_icons '.$old_ie.'">';

			foreach( $show_browsers as $browser => $link )
			{
				if ( $link )
				{
					$html .= '<li>';
						$html .= '<a href="'. $link .'" class="'. $browser .'" target="_blank">';
							$html .= '<img src="'. plugins_url( '/img/'. $browser .'.png', __FILE__ ) .'" alt="'. $browser .'" width="64" height="64">';
							$html .= $this->get_full_name( $browser );
						$html .= '</a>';
					$html .= '</li>';
				}
			}

			$html .= '</ul>';

			if ( $hide )
			{
				$html .= '<a href="#" class="abc-hide"></a>';
			}

		$html .= '</div>';

		return $html;
	}

	/**
	* Return full browser name based on the short name
	**/
	private function get_full_name( $short )
	{
		switch( $short )
		{
			case 'ff':
				$browser = __( 'Firefox', 'advanced-browser-check' );
				break;

			case 'chrome':
				$browser = __( 'Chrome', 'advanced-browser-check' );
				break;

			case 'safari':
				$browser = __( 'Safari', 'advanced-browser-check' );
				break;

			case 'opera':
				$browser = __( 'Opera', 'advanced-browser-check' );
				break;

			case 'ie':
				$browser = __( 'Internet Explorer', 'advanced-browser-check' );
				break;

			default:
				$browser = __( 'other', 'advanced-browser-check' );
				break;
		}

		return $browser;
	}
}
