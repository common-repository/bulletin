=== Plugin Name ===
Contributors: martijngastkemper
Donate link: https://eresults.nl
Tags: bulletin, newsletters, marketing, newsletter
Requires at least: 3.0.1
Tested up to: 4.6.1
Stable tag: 1.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Let visitors subscribe to your Bulletin lists.

== Description ==

Easily let your visitors subscribe to a Bulletin list.

== Installation ==

1. Upload `bulletin` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add the Bulletin Widget to your page.

== Frequently Asked Questions ==

= The plugin isn't available in my locale =

Go to [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/bulletin) and suggest a translation.

= How to report a problem? =

Go to [github.com/eResults/wordpress-bulletin-plugin/issues](https://github.com/eResults/wordpress-bulletin-plugin/issues) and create an issue.

== Screenshots ==

Coming soon.

== Upgrade Notice==

= 1.0.0 =
Just released into the wild.

== Changelog ==

= 1.0.9 =
* Fixed: user feedback in widget

= 1.0.8 =
* New: Load i18n files from translate.wordpress.org
* Fixed: Don't load unused assets

= 1.0.7 =
* Added: Partial refresh widget in customizer
* Added: Support multiple widget on one page
* Added: Submit to #widget_id to send visitors back to the form
* Fixed: Undefined variables

= 1.0.6 =
* Fixed: Translate already subscribed error
* Added: Populate form when an error occurs

= 1.0.5 =
* Updated: i18n

= 1.0.4 =
* Added: message when successfully subscribed

= 1.0.3 =
* Changed: Replace input submit by button to make CSS pseudo elements possible
* Updated: i18n

= 1.0.2 =
* Fixed: handle client exceptions
* Fixed: adding widget without saving caused error when subscribing
* Updated: i18n
* Updated: composer dependencies

= 1.0.1 =
* Added: Link to the settings page in the plugin overview
* Added: Placeholder attribute on email input
* Updated: Use public bulletin-api-php package instead of a self writing cUrl wrapper
* Fixed: Prevent PHP Notices
* Fixed: Wrong API token would crash the Customizer

= 1.0.0 =
* Set the API key
* Add Bulletin widget

== Contribute ==

Did you find a bug or do you have a suggestions? Create an issue or, that's even beter, create a pull request to
enhance this plugin.

Is this plugin not available in your locale? I welcome you to propose translations on [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/bulletin).

The source code and issues can be found in the GitHub repository [eResults/wordpress-bulletin-plugin](https://github.com/eResults/wordpress-bulletin-plugin).