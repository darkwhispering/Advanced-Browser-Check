<?php

class ABC_Core {

    static $this;

    public function __construct()
    {
        self::$this;

        add_action( 'init', array( $this, 'default_setting_values' ) ); // Default settings
        add_action( 'wp_footer', array( $this, 'content_wrapper' ) ); // HTML wrapper
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) ); // Needed scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) ); // Needed stylesheets
    }

    /**
     * Get activ users browser details
     * @return array [browser details]
     */
    public function get_browser()
    {
        $user_agent        = $_SERVER['HTTP_USER_AGENT'];
        $browser_full_name = 'Unknown';
        $browser_name      = 'Unknown';
        $platform          = 'Unknown';
        $version           = 'Unknown';
        $short_name        = 'Unknown';

        // First get the platform
        if ( preg_match( '/android/i', $user_agent ) )
        {
            $platform = 'android';
        }
        elseif ( preg_match( '/iphone/i', $user_agent ) || preg_match( '/ipad/i', $user_agent ) || preg_match( '/ipod/i', $user_agent ) )
        {
            $platform = 'iOS';
        }
        elseif ( preg_match( '/linux/i', $user_agent ) )
        {
            $platform = 'linux';
        }
        elseif ( preg_match( '/macintosh|mac os x/i', $user_agent ) )
        {
            $platform = 'mac';
        }
        elseif ( preg_match( '/windows|win32/i', $user_agent ) )
        {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if ( preg_match( '/Firefox/i',$user_agent ) )
        {
            $browser_full_name = 'Mozilla Firefox';
            $browser_name      = 'Firefox';
            $short_name        = 'ff';
        }
        elseif ( preg_match( '/Chrome/i',$user_agent ) )
        {
            $browser_full_name = 'Google Chrome';
            $browser_name      = 'Chrome';
            $short_name        = 'chrome';
        }
        elseif ( preg_match( '/Safari/i',$user_agent ) )
        {
            $browser_full_name = 'Apple Safari';
            $browser_name      = 'Safari';
            $short_name        = 'safari';
        }
        elseif ( preg_match( '/MSIE/i',$user_agent ) && ! preg_match( '/Opera/i',$user_agent )
            || preg_match( '/Windows NT/i',$user_agent ) && ! preg_match( '/Opera/i',$user_agent ) )
        {
            $browser_full_name = 'Internet Explorer';
            $browser_name      = 'MSIE';
            $short_name        = 'ie';
        }
        elseif ( preg_match( '/Opera/i',$user_agent ) )
        {
            $browser_full_name = 'Opera';
            $browser_name      = 'Opera';
            $short_name        = 'opera';
        }

        // finally get the correct version number
        $known = array( 'Version', $browser_name, 'rv' );
        $pattern = '#(?<browser>' . implode( '|', $known ) . ')[/ |:]+(?<version>[0-9.|a-zA-Z.]*)#';
        if ( ! preg_match_all( $pattern, $user_agent, $matches ) )
        {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count( $matches['browser'] );

        if ( $i != 1 )
        {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if ( strripos( $user_agent, 'Version' ) < strripos( $user_agent, $browser_name ) )
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
        if ( $version == null || $version == '' || $version == 0 ) { $version = 'Unknown'; }

        return array(
            'user_agent' => $user_agent,
            'name'       => $browser_full_name,
            'short_name' => $short_name,
            'version'    => floor($version),
            'platform'   => $platform,
            'pattern'    => $pattern
        );
    }

    /**
     * The wrapper, added to the site footer, that the popup
     * will be placed in after the ajax load
     * @return html [echos out the html code needed]
     */
    function content_wrapper()
    {
        echo "<div class='advanced-browser-check' style='display:none;' data-url='".json_encode( array( "abc_url" => admin_url( 'admin-ajax.php' ) ) )."'></div>";
    }

    /**
     * Default settings and settings array used trough out
     * the plugin
     * @return array [default settings array]
     */
    public function default_setting_values()
    {
        // Default settings values
        add_option( 'abc_title', __( 'You are using a web browser not supported by this website!', 'advanced-browser-check' ) );
        add_option( 'abc_message', __( 'You are using a web browser that is not supported by this website. This means that some functionality may not work as intended. This may result in strange behaviors when browsing around. Use or upgrade/install one of the following browsers to take full advantage of this website. - Thank you!', 'advanced-browser-check' ) );
        add_option( 'abc_hide', NULL );
        add_option( 'abc_show', array(
            'ie'        => '',
            'ff'        => 'http://www.mozilla.com/en-US/firefox/all.html',
            'safari'    => '',
            'opera'     => '',
            'chrome'    => 'https://www.google.com/chrome'
        ) );
        add_option( 'abc_check', array(
            'ie'        => '9',
            'ff'        => '25',
            'safari'    => '4',
            'opera'     => '17',
            'chrome'    => '30'
        ));
        add_option( 'abc_debug', 'off' );

        // Run update function, this is where the plugin version number is update
        $this->update();

        // Return the settings in array format
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
    * Default browsers settings. This builds the browser dropdowns on the admin page
    **/
    /**
     * Default browsers settings. This builds the browser
     * dropdowns on the admin page
     * @return array [versions of each browser to include]
     */
    public function default_browsers()
    {
        // Included version numbers is current stable (since latest plugin update)
        // and 5 future versions
        // and 8 older versions

        return array(
            'safari'    => array(0,3,4,5,6,7,8,9,10,11,12,13),
            'opera'     => array(0,25,26,27,28,29,30,31,32,33,34,35,36,37,38),
            'ff'        => array(0,36,37,36,37,38,39,40,41,42,43,44,45,46,47),
            'chrome'    => array(0,38,39,40,41,42,43,44,45,46,47,48,49,50,51),
            'ie'        => array(0,7,8,9,10,11,12)
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
