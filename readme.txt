=== Advanced Browser Check ===
Contributors: darkwhispering
Tags: firefox, chrome, opera, safari, internet explorer, ie6, ie7, ie8, ie, ff, plugin, browser, block browser, block ie6, browser check, check, popup, warning
Requires at least: 3.3.0
Tested up to: 3.6.0
Stable tag: 2.1.3

Tell IE users to change browser? Or is your site for chrome only? Now you choose what browsers should trigger a warning popup or not on your site.

== Description ==

This plugin give you the option to give a visitor of your site a warning popup if they use a browser that you don't support. For example, Internet Explorer 6.

It supports the 5 largest and most populare browser on the market.

* Firefox
* Chrome
* Safari
* Opera
* Internet Explorer

You can customize the warning message, choose what browsers download pages you want to display a link to, select what browsers and browser versions that should trigger the popup and if the use can or can't hide the popup.

If you allow users to hide the popup, a cookie is set that expires after 24h. After 24h, the user will see the popup again with the option to hide it for another 24h.

This plugin is tested and works with the WP Super Cache plugin. It is also tested and working on Wordpress Networks.

*There is versions added of browsers that it not yet released as stable versions. This is just to minimize the need of an plugin update in the future when new browsers are released and to let users that use the beta and alpha version to test this plugin with there browsers. I will do my best to keep this plugin updated with the latest versions of avaliable browsers.*

== Installation ==

1. Upload the zipped file to yoursite/wp-content/plugins
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Frontend screenshot
2. Backend screenshot

== Changelog ==

= 2.1.3 =
* Updated list of browser versions
* Tested on Wordpress 3.6

= 2.1.2 =
* Fixed small error with the post ajax variable triggering error when empty or not existing.

= 2.1.1 =
* Hotfix: bug in the check for iOS devices

= 2.1.0 =
* Browser icon links now open in new tab/window
* Popup will no longer show up when the browser is unknown or not one of the supported
* Popup will NOT show up on iOS devices (iPhone & iPad) or Android devices
* Updated and removed browser version numbers on settings page

= 2.0.2 =
* Plugin tested and now working on Wordpress Networks

= 2.0.1 =
* Fixed problem with default values not beeing loaded on a fresh plugin installwith no records in the wp option table

= 2.0.0 =
* Large part of the plugin is re-written
* the .json file ir removed and replaced with a php function in the init.php
* Better commenting in the code
* Removed screenshots from the plugin folder, no need for them to be dl with the plugin
* New layout of the settings page, now more streamlined with the Wordpress admin
* Popup now looking good in IE 6 to 9
* Added option to not block a browser at all.

= 1.0.2 =
* Fixed problem with popup not beeing displayed properly in IE6

= 1.0.1 =
* Fixed problem with browser select boxes not shownig up.

= 1.0.0 =
* Initial release