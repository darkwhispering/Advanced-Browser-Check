<?php

class ABC_Core
{
    const UNKNOWN = 'unknown';

    const PLATFORM_ANDROID = 'android';
    const PLATFORM_IOS     = 'iOS';
    const PLATFORM_LINUX   = 'linux';
    const PLATFORM_MAC     = 'mac';
    const PLATFORM_WINDOWS = 'windows';

    static $this;

    /**
     * ABC_Core constructor.
     */
    public function __construct()
    {
        self::$this;

        add_action( 'init', array( $this, 'default_setting_values' ) ); // Default settings
        add_action( 'wp_footer', array( $this, 'content_wrapper' ) ); // HTML wrapper
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) ); // Needed scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) ); // Needed stylesheets
    }

    /**
     * Get users browser details
     *
     * @return array
     */
    public function get_browser()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $platform   = $this->get_platform( $user_agent );
        $browser    = $this->get_browser_name( $user_agent );

        // finally get the correct version number
        $known = array( 'Version', $browser['name'], 'rv' );
        if ($browser['name'] === 'Opera') {
            $known = array( 'Version', $browser['name'], 'OPR', 'rv' );
        }
        $pattern = '#(?<browser>' . implode( '|', $known ) . ')[/ |:]+(?<version>[0-9.|a-zA-Z.]*)#';
        preg_match_all( $pattern, $user_agent, $matches );

        // see how many we have
        $i = count( $matches['browser'] );

        if ( $i != 1 )
        {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if ( strripos( $user_agent, 'Version' ) < strripos( $user_agent, $browser['name'] ) )
            {
                $version = $matches['version'][0];
            }
            else
            {
                $version = $matches['version'][1];
            }
        }
        else
        {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ( $version === null || $version === '' || $version === 0 )
        {
            $version = self::UNKNOWN;
        }

        return array(
            'user_agent' => $user_agent,
            'name'       => $browser['full_name'],
            'short_name' => $browser['short_name'],
            'version'    => floor($version),
            'platform'   => $platform,
            'pattern'    => $pattern
        );
    }

    /**
     * The wrapper, added to the site footer, that the popup will be placed in after the ajax load
     *
     * @echo html
     */
    function content_wrapper()
    {
        echo "<div class='advanced-browser-check' style='display:none;' data-url='".json_encode(
                array( "abc_url" => admin_url( 'admin-ajax.php' ) )
            )."'></div>";
    }

    /**
     * Default settings and settings array used trough out the plugin
     *
     * @return array
     */
    public function default_setting_values()
    {
        // Default settings values
        add_option( 'abc_title', __( 'You are using a web browser not supported by this website!', 'advanced-browser-check' ) );
        add_option( 'abc_message', __( 'You are using a web browser that is not supported by this website. This means that some functionality may not work as intended. This may result in strange behaviors when browsing around. Use or upgrade/install one of the following browsers to take full advantage of this website. - Thank you!', 'advanced-browser-check' ) );
        add_option( 'abc_hide', NULL );
        add_option( 'abc_show', array(
            'ie'     => '',
            'edge'   => '',
            'ff'     => 'http://www.mozilla.com/en-US/firefox/all.html',
            'safari' => '',
            'opera'  => '',
            'chrome' => 'https://www.google.com/chrome'
        ) );
        add_option( 'abc_check', array(
            'ie'     => '11',
            'ff'     => '0',
            'safari' => '4',
            'opera'  => '27',
            'chrome' => '0',
            'edge'   => '0',
        ) );
        add_option( 'abc_debug', 'off' );

        // Add new settings option introduced in version 4.4.0 if it does not exists
        $check = get_option( 'abc_check' );
        if ( ! isset( $check['edge'] ) )
        {
            $check['edge'] = '';
            update_option( 'abc_check', $check );
        }

        // Update plugin version
        $this->update();

        return array(
            'title'         => get_option( 'abc_title' ),
            'msg'           => get_option( 'abc_message' ),
            'hide'          => get_option( 'abc_hide' ),
            'show_browser'  => get_option( 'abc_show' ),
            'check_browser' => get_option( 'abc_check' ),
            'debug'         => get_option( 'abc_debug' )
        );
    }

    /**
     * Default browsers settings. This builds the browser drop downs on the admin page
     *
     * @return array
     */
    public function default_browsers()
    {
        // Included version numbers is current stable (since latest plugin update)
        // and 5 future versions as most
        // and 8 older versions as most

        return array(
            'safari' => array(0,3,4,5,6,7,8,9,10,11,12,13),
            'opera'  => array(0,27,28,29,30,31,32,33,34,35,36,37,38,39,40),
            'ff'     => array(0,38,39,40,41,42,43,44,45,46,47,48,49,51,52),
            'chrome' => array(0,43,44,45,46,47,48,49,50,51,52,53,54,55,56),
            'ie'     => array(0,7,8,9,10,11),
            'edge'   => array(0,12,13,14,15,16)
        );
    }

    /**
    * Add scripts we need
    **/
    public function scripts()
    {
        // Load jQuery
        wp_enqueue_script( "jquery" );

        // jQuery cookie, used to add a cookie so visitors can hide the popup
        wp_enqueue_script( "apc_jquery_cookie", plugins_url( '/js/jquery.cookie.js', __FILE__ ), array( 'jquery' ) );

        // The ajax request so the plugin works with caching plugins
        wp_enqueue_script( "abc_script", plugins_url( '/js/script.js', __FILE__ ), array( 'jquery' ) );
    }

    /**
    * Add styles we need
    **/
    public function styles()
    {
        // Stylesheet for the popup
        wp_enqueue_style( "abc_style", plugins_url( '/css/style.css', __FILE__ ) );
    }

    /**
     * Get browser name details
     *
     * @param $user_agent
     *
     * @return array
     */
    protected function get_browser_name( $user_agent )
    {
        // Next get the name of the useragent yes seperately and for good reason
        if ( preg_match( '/Opera/i',$user_agent ) || preg_match( '/OPR/i',$user_agent ))
        {
            return array(
                'full_name'  => 'Opera',
                'name'       => 'Opera',
                'short_name' => 'opera'
            );
        }
        elseif ( preg_match( '/Edge/i',$user_agent ) )
        {
            return array(
                'full_name'  => 'Microsoft Edge',
                'name'       => 'Edge',
                'short_name' => 'edge'
            );

        } elseif ( preg_match( '/Firefox/i',$user_agent ) )
        {
            return array(
                'full_name'  => 'Mozilla Firefox',
                'name'       => 'Firefox',
                'short_name' => 'ff'
            );
        }
        elseif ( preg_match( '/Chrome/i',$user_agent ) )
        {
            return array(
                'full_name'  => 'Google Chrome',
                'name'       => 'Chrome',
                'short_name' => 'chrome'
            );
        }
        elseif ( preg_match( '/Safari/i',$user_agent ) )
        {
            return array(
                'full_name'  => 'Apple Safari',
                'name'       => 'Safari',
                'short_name' => 'safari'
            );
        }
        elseif ( preg_match( '/MSIE/i',$user_agent ) || preg_match( '/Windows NT/i',$user_agent ) )
        {
            return array(
                'full_name'  => 'Internet Explorer',
                'name'       => 'MSIE',
                'short_name' => 'ie'
            );
        }

        return array(
            'full_name'  => self::UNKNOWN,
            'name'       => self::UNKNOWN,
            'short_name' => self::UNKNOWN
        );
    }

    /**
     * Get users platform (OS)
     *
     * @param $user_agent
     *
     * @return string
     */
    protected function get_platform( $user_agent )
    {
        if ( preg_match( '/android/i', $user_agent ) )
        {
            return self::PLATFORM_ANDROID;
        }
        elseif ( preg_match( '/iphone/i', $user_agent ) || preg_match( '/ipad/i', $user_agent ) || preg_match( '/ipod/i', $user_agent ) )
        {
            return self::PLATFORM_IOS;
        }
        elseif ( preg_match( '/linux/i', $user_agent ) )
        {
            return self::PLATFORM_LINUX;
        }
        elseif ( preg_match( '/macintosh|mac os x/i', $user_agent ) )
        {
            return self::PLATFORM_MAC;
        }
        elseif ( preg_match( '/windows|win32/i', $user_agent ) )
        {
            return self::PLATFORM_WINDOWS;
        }

        return self::UNKNOWN;
    }

    /**
    * Sets plugin version and there update functions will be added when larger updates is made
    **/
    private function update()
    {
        // If no version number exists, we need to update the settings to v2.0.0 and above
        if ( ! get_option( 'abc_version' ) )
        {
            // Get old settings array
            $old_settings = get_option( 'advanced-browser-check' );

            if ( $old_settings )
            {
                // Add the old settings to the new once
                update_option( 'abc_title', $old_settings['title'] );
                update_option( 'abc_message', $old_settings['msg'] );
                update_option( 'abc_show', $old_settings['show_browser'] );
                update_option( 'abc_check', $old_settings['check_browser'] );
                update_option( 'abc_hide', $old_settings['hide'] );
            }

            // Delete the old settings array from the DB
            delete_option( 'advanced-browser-check' );
        }

        update_option( 'abc_version', ABC_VERSION );
    }
}
