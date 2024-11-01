=== WordPress Management Tool by Hardy Code ===
Contributors: bomberaza
Tags: management, updates, monitor, remote, portal
Requires at least: 3.4
Tested up to: 3.8
Stable tag: 0.0.5
License: GNU General Public License, version 2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

The WordPress Management Tool by Hardy Code helps administrators with multiple WordPress installs monitor each build from a single portal.

== Description ==

The WordPress Management Tool (WPMT) by Hardy Code is designed to assist those that are responsible for multiple installations of WordPress around the net.
			        
All an administrator has to do is install the WPMT plugin, sign up for the Hardy Code Tools and register the generated Site Key.

It's as simple as can be!

== Installation ==

= Requirements =

* WordPress 3.4 and up
* PHP 5.3
* PHP cURL
* A Hardy Code Tools account

= Easy install via WordPress's Plugin management interface: =

1. Log into your websites Administration Area.
1. Select Add New from the Plugin Management area.
1. Search for Hardy Code
1. Press Install Now associated with the plugin WordPress Management Tool by Hardy Code.
1. Press Ok to confirm that you wish to install the plugin.
1. Press Activate Plugin once the plugin is downloaded and installed.
1. Now you can register the website with the [Hardy Code's Tools](https://hc/tools/wordpress-management-tool#register-hardy-code-instructions).

= Manual install via plugin upload: =

1. [Download](https://github.com/aaronheath/WPMTPlugin/archive/master.zip) the plugin.
1. Upload the plugin to the /wp-content/plugins/ directory.
1. Activate the plugin through the Plugins menu in WordPress.

= Setup your Hardy Code Tools account and Register WordPress installation =

Register for Hardy Code Tools:

1. Navigate to the [Sign-up for Tools](https://hardycode.com.au/tools/signup) page.
1. Enter in your information and you're done.

Register a WordPress Installation with WPMT:

1. Log into the Admin area of the WordPress Installation, navigate to the Plugins menu and select WPMT.
1. Copy the long string of text which is next to the Site Key label. You'll need this later.
1. Once logged into the [Hardy Code Tools portal](https://hardycode.com.au/tools) select [Add A Site](https://hardycode.com.au/tools/wpmt/add-site) from the WordPress Management menu item.
1. Enter in the previously recorded Site Key and press the Add Site button.

== Frequently Asked Questions ==

= How much does this cost? =

Short Answer: Free (for now)!

Long Answer: Whilst we all like to make some money, this product is still, in our minds, in beta. At this stage we feel the service should prove itself before we charge for it. This doesn't mean that we will charge for it once it's a proven service, it just means that we might.

= How do we capture information about a site? =

First of all let's get it out there that we wouldn't have build this service unless we were able to make it secure.

Here is how the plugin transmits site information to the Hardy Code servers:

* Upon plugin installation and activation, the plugin will transmit one set of information about the site to the Hardy Code servers.
* Once a site is registered with the Hardy Code servers, the server will periodically poll the plugin for site information. If multiple Hardy Code users have the site registered as a part of their profile, all users must deactivate their registration for the polling to cease.
* The plugin will only respond to polling requests if it's activate. Want to stop it from sending information? De-activate the plugin.

= Exactly what information does the plugin send? =

The plugin sends the following information to the Hardy Code servers:

* Site Name
* Site URL
* WordPress Build
* List of installed plugins
* List of installed plugins versions
* List of installed plugins summary information
* Name of the current theme
* Version of the current theme
* The servers IP Address
* Total disk space
* Used Disk space

All this information is transmitted to the Hardy Code servers using a secure SSL connection.

== Screenshots ==

1. Plugin interface
1. Hardy Code Tools - My Sites
1. Hardy Code Tools - Individual Site
1. Hardy Code Tools - Add A Site

== Changelog ==

= v0.0.4 =
* Initial Release
= v0.0.5 =
* Now using xml-rpc for API
* Removed includes of core WordPress files.

== Feedback or Issues ==

Please feel free to lodge issues using the [GitHub](https://github.com/aaronheath/WPMTPlugin/issues) issues interface. Should you wish to provide direct feedback please email me at [aaron@hardycode.com.au](mailto:aaron@hardycode.com.au).