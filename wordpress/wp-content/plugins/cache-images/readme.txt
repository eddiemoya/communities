=== Cache Images ===
Contributors: dimadin, matt
Tags: hotlink, images, sideload, media, media library
Requires at least: 2.8
Tested up to: 3.0.2
Stable tag: 3.1

Goes through your posts and gives you the option to cache all hotlinked images from a domain locally in your upload folder

== Description ==

[Plugin homepage](http://blog.milandinic.com/wordpress/plugins/cache-images/) | [Plugin author](http://blog.milandinic.com/)

Cache Images is a plugin that gives users option to sideload images that are hosted on other domains to their own site. Sideloaded images are added to WordPress media library so you can use all tools related to images that you can use with images uploaded through WordPress. Image will be added as an attachment of first post where it is found, and every post where original URL is occurring will be updated with new URL. User can select from which domains to sideload images, including Blogger's domains.

It uses AJAX so it means you can sideload large number of images even on slow servers. (AJAX functions are made by fork of code from plugin [AJAX Thumbnail Rebuild](http://wordpress.org/extend/plugins/ajax-thumbnail-rebuild/))

This plugin is fully internationalized. You can find .pot file in <em>languages</em> folder where you should place your translation. Current translations are Serbian and Spanish. Please send your translation by [contacting author](http://blog.milandinic.com/contact/) so that it can be included it in next releases.

Read more information about usage on [author's site](http://blog.milandinic.com/wordpress/plugins/cache-images/).

== Installation ==

1. Upload `cache-images` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to 'Tools' > 'Cache Remote Images' page
3. Go to 'Settings' > 'Media' page to enable automatic caching


== Screenshots ==

1. First screen with basic information about usage of plugin and a button Scan
2. List of all domains from which there is hotlinked image
3. Settings for automatic caching

== Changelog ==

= 3.1 =
* Released on 3rd December 2010
* Tries to fix issue with bad Blogger's URLs
* Tries to fix issue with "invalid argument for foreach" error
* Tries to fix issue with "undefined function media_sideload_image()" error
* Fixed unescaped characters in code examples
* Updated screenshot-2

= 3.0 =
* Released on 25th November 2010
* Complete ajaxification of a plugin
* Option for automatic caching on post's saving
* Option to cache linked images
* Made standalone functions for finding and caching
* Improved documentation
* Code cleanup
* Updated Serbian and Spanish translations

= 2.0 =
* Complete rewrite of plugin
* Images are now sideloaded via built in function and added to WordPress' media library
* Plugin now uses AJAX so it means you can cache large number of images even on slow servers
* You can now cache images from Blogger's domains too
