<?php

// Include the Browscap library
require(plugin_dir_path( __FILE__ ).'lib/browscap.php');
use phpbrowscap\Browscap;

class ABC_Core {

    static $this;

    public function __construct()
    {

        self::$this;

        // Cache folder settings
        $this->cache_dir = ABC_DIR_PATH.'cache';
        $this->cache_dir_error = false;

        // Validate cache folder
        // $this->validate_cache_dir();

        // Display admin notice if we are missing the cache folder
        // if ($this->cache_dir_error) {
        //     add_action('admin_notices', array($this, 'cache_error'));
        // }

        add_action('init', array($this, 'default_setting_values')); // Default settings
        add_action('wp_footer', array($this, 'content_wrapper')); // HTML wrapper
        add_action('wp_enqueue_scripts', array($this, 'scripts')); // Needed scripts
        add_action('wp_enqueue_scripts', array($this, 'styles')); // Needed stylesheets

    }

    /**
    * Get activ users browser details
    **/
    public function get_browser()
    {

        // if (!$this->cache_dir_error) { // Don't load Browscap if we have an issue with the cache folder

            $bc = new Browscap($this->cache_dir);
            $user_browser = $bc->getBrowser();

            return $user_browser;

        // } else {

        //     return array();

        // }

    }

    /**
    * The wrapper, added to the site footer, that the popup will be placed in after the ajax load
    **/
    function content_wrapper()
    {
        echo "<div class='advanced-browser-check' style='display:none;' data-url='".json_encode(array("abc_url" => admin_url('admin-ajax.php')))."'></div>";

        // echo "<div class='advanced-browser-check' style='display:none;' data-url='".json_encode(array("abc_url" => plugins_url("advanced-browser-check.php",__FILE__)))."'></div>";
    }

    /**
    * Default settings and settings array used trough out the plugin
    **/
    public function default_setting_values()
    {

        // Default settings values
        add_option('abc_title', 'You are using a web browser not supported by this website!');
        add_option('abc_message', 'You are using a web browser that is not supported by this website. This means that some function may not work as supposed which can result in strange behaviors when browsing around. Use or upgrade/install on of the following browsers to take full advantage of this website. - Thank you!');
        add_option('abc_hide', NULL);
        add_option('abc_show', array(
            'ie'        => '',
            'ff'        => 'http://www.mozilla.com/en-US/firefox/all.html',
            'safari'    => '',
            'opera'     => '',
            'chrome'    => 'https://www.google.com/chrome'
        ));
        add_option('abc_check', array(
            'ie'        => '9',
            'ff'        => '24',
            'safari'    => '4',
            'opera'     => '16',
            'chrome'    => '30'
        ));
        add_option('abc_debug', 'off');

        // Run update function, this is where the plugin version number is update
        $this->update();

        // Return the settings in array format
        return array(
            'title'         => get_option('abc_title'),
            'msg'           => get_option('abc_message'),
            'hide'          => get_option('abc_hide'),
            'show_browser'  => get_option('abc_show'),
            'check_browser' => get_option('abc_check'),
            'debug'         => get_option('abc_debug')
        );

    }

    /**
    * Default browsers settings. This builds the browser dropdowns on the admin page
    **/
    public function default_browsers()
    {

        // Included version numbers is current stable (since latest plugin update)
        // and 5 future versions
        // and 10 older versions

        return array(
            'safari'    => array(0,3,4,5,6,7,8,9,10),
            'opera'     => array(0,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26),
            'ff'        => array(0,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34),
            'chrome'    => array(0,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40),
            'ie'        => array(0,7,8,9,10,11,12,13,14)
        );

    }

    /**
    * Display cache error
    **/
    /*public function cache_error()
    {

        ?>

            <div class="error">
                <p><?php _e('Could not create required cache directory. Please go to your wp-content folder and create it manually. Remember to give it full read and write permissions (0777)'); ?></p>
            </div>

        <?php

    }*/

    /**
    * Check and validate if we have a cache directory
    * Try to create if it don't exsits
    **/
    // private function validate_cache_dir()
    // {

    //     $this->cache_dir_error = false;

    //     if (!is_dir($this->cache_dir)) {

    //         if (!mkdir($this->cache_dir, 0777)) {

    //             $this->cache_dir_error = true;

    //         }

    //     }

    // }

    /**
    * Add scripts we need
    **/
    public function scripts() {
        // Load jQuery
        wp_enqueue_script("jquery");

        // jQuery cookie, used to add a cookie so visitors can hide the popup
        wp_enqueue_script("apc_jquery_cookie", plugins_url('/js/jquery.cookie.js', __FILE__));

        // The ajax request so the plugin works with caching plugins
        wp_enqueue_script("abc_script", plugins_url('/js/script.js', __FILE__));
    }

    /**
    * Add styles we need
    **/
    public function styles() {
        // Stylesheet for the popup
        wp_enqueue_style("abc_style", plugins_url('/css/style.css', __FILE__));
    }

    /**
    * Sets plugin version and there update functions will be added when larger updates is made
    **/
    private function update()
    {

        // If no version number exists, we need to update the settings to v2.0.0 and above
        if (!get_option('abc_version')) {

            // Get old settings array
            $old_settings = get_option('advanced-browser-check');

            if ($old_settings) {
                // Add the old settings to the new once
                update_option('abc_title', $old_settings['title']);
                update_option('abc_message', $old_settings['msg']);
                update_option('abc_show', $old_settings['show_browser']);
                update_option('abc_check', $old_settings['check_browser']);
                update_option('abc_hide', $old_settings['hide']);
            }

            // Delete the old settings array from the DB
            delete_option('advanced-browser-check');

        }

        update_option('abc_version', ABC_VERSION);

    }

}
