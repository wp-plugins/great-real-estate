=== Great Real Estate ===
Contributors: RogerTheriault
Donate link: http://www.rogertheriault.com/agents/plugins/great-real-estate-plugin/
Tags: real estate,ajax,listings,real,estate,homes
Requires at least: 2.8
Tested up to: 2.8.2
Stable tag: 1.3.0

Great Real Estate provides functionality to turn your WordPress installation into a content managed real estate website.

== Description ==

Great Real Estate was developed to enable real estate agents, brokers, and REALTORS to display and manage information about their listings in WordPress pages.

The property information and listing templates allow for consistent display of the property listings across the site. Authors do not need to format the content, they simply enter it into a form.

Widgets and custom page templates permit display of only available homes, only sold homes, a random "featured" home, and a detailed property page for a listing.

This plugin works best if you also use the following plugins:
* NextGen Gallery - to upload, manage, and display property listing photos
* WordTube - to upload, manage, and display videos
* FPP Pano - to display panorama photos in a Flash 360 degree interface ("virtual tours")
* Feed Wrangler - to enable you to send a listings feed to various websites, such as Googe Base, Trulia, and Zillow
* WP-DownloadManager - to display and track links to downloadable brochures

You'll also need to get a Google API key to enable the maps display, and a Truilia, Google Base, and Zillow account to enable feeds.

To use the panorama plugin, you'll need to obtain the Flash Panorama Player, appropriately licensed for your domain.

Please read the documentation carefully... to get the most out of this plugin, you may need to edit some of your theme files, and/or copy files from this folder to your theme folder

Very detailed documentation is be available at the Plugin home page at http://www.rogertheriault.com/agents/plugins/great-real-estate-plugin/

== Screenshots ==

1. Sample listing page with tabbed navigation

== Frequently Asked Questions ==

= How do I get support? =

The plugin author has set up a Forum for support questions and announcements at http://www.rogertheriault.com/forums/

= What are the template tags I can use? =

generally, they are the functions in the PHP file templatefunctions.php. But please visit the plugin homepage for complete details on all the tags, and how you can use them in The WordPress Loop.


== Installation ==

Quick Start - See website for more detailed info
1. Unzip into your `/wp-content/plugins/` directory. 
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Make your settings in the Admin panel "Real Estate"
- be sure to choose a main Listings page
- for a quick preview, check the options to automatically generate the page info
1. Create sub-pages under the main listings index page; they should automatically display additional input form fields to capture listing information
1. See the plugin website for detailed template function documentation

== Credits ==

    Great Real Estate - The Real Estate plugin for WordPress
    Copyright (C) 2008, 2009  Roger Theriault

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.


== Changelog ==

= 1.3.0 =
* [2009-07-27] support for WP 2.8 (requires WordPress 2.8)
* moved the JS scripts to the page footer
* changed invocation of tabs to conform to jQuery 1.3.x (target the parent div instead of > ul) 
* changed checking for NextGen version to be more compatible with PHP 5.0.x
* additional localizations: German, Spanish, French, Italian, and Russian supported now
* support WP Download Manager version 1.5 (Files with permission "Everyone" were hidden)

= 1.2.1 =
 * [2008-12-16] Added compatibility with NextGen Gallery version 1.0+
 
= 1.2 =
 * [2008-12-14] After WP 2.6, get default WP supplied jQuery ui and tabs
 * [2008-08-02] included widget file; widget now installed automatically and no longer has a separate (confusing) entry in the plugins list
 
= 1.1 =
 * [2008-07-27] updated for WP2.6
 * 		added localization (correctly)
 
= 1.01 =
 * [2008-06-27] added shortcode handler for featured listings block
 
= 1.0 =
 * (original)
