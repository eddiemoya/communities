=== Networks for WordPress ===
Contributors: ddean
Tags: multisite, multi-site, sites, networks, multi-network, multiple, multi-domain, domains
Requires at least: 3.0
Tested up to: 3.4-beta4
Stable tag: 1.1.2

Adds a Networks panel for network admins to create and manage multiple Networks from one WordPress installation.

== Description ==

Adds a Networks panel allowing network admins to create and manage multiple Networks from one WordPress installation.  Each Network can exist on its own domain, and have its own set of blogs / sites.

Each Network can have its own set of plugins, themes, administrators, permissions, and policies, but all will share a database of user accounts.

Sites can be moved freely among Networks.

= Notes =

Each Network will require changes to your web server and DNS.

See **Frequently Asked Questions** for detailed instructions.

== Installation ==

1. Extract the plugin archive 
1. Upload plugin files to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Comment out `DOMAIN_CURRENT_SITE` in your `wp-config.php` file.

== Frequently Asked Questions ==

= How do I set up new domains as networks with a single WordPress install? =

Your webserver must direct requests for each domain you want to use to your WordPress install's filesystem location.

Here's a quick overview:

1. DNS should resolve each desired domain to your web server.
1. Configure your web server to direct requests for each desired domain to the same site (e.g. via `ServerAlias` directives or `Host Headers`)

Tip: You can use the New Network Preview feature to verify DNS and server configuration BEFORE creating your networks.

== Known Issues ==

* Plugins that create global (i.e. not blog-specific) tables will behave as though they are on a single network install.  This is a limitation of WordPress's table naming scheme.

== Changelog ==

= 1.1.2 =
* Added: Screen Options for selecting number of Networks per page
* Changed: `add_site` function now defaults to a path of '/' if one is not supplied
* Fixed: searching and sorting by Network Name - thanks, skvwp

= 1.1.1 =
* Added: descriptions for MANY more sitemeta keys, using WP native strings where possible
* Added: placeholders for most text fields
* Added: `is_super_admin_for()` function for checking permissions on other networks
* Changed: reorganized the "Create a Network" panel to be more intuitive
* Changed: (for developers) `$options_to_copy` now carries the sitemeta keys to be cloned as values instead of keys
* Changed: switched to `add_query_arg()` where possible
* Changed: split code up into different files for easier maintenance
* Fixed: hardcoded URL that may have prevented some from deleting networks in bulk

= 1.1.0 =
* Added: New Network Preview will try to check mapped domains for possible collisions
* Changed: `upload_filetypes` is now cloned by default, so you don't have to save settings before uploading files on new networks

= 1.0.9 =
* Added: enhanced help menu for WP 3.3
* Added: ability to restrict Networks Menu to a certain Network with the `RESTRICT_MANAGEMENT_TO` constant
* Added: WP 3.3 help pointer
* Changed: removed ability to delete the primary network
* Fixed: enhanced compatibility with WP < 3.1 - thanks, suresh.sambandam

= 1.0.8 =
* Added: support for the `siteurl` sitemeta entry
* Changed: Updated Help screen to display in WP 3.3
* Fixed: Adding a network now displays the correct update message

= 1.0.7 =
* Added: Network diagnostic screen
* Changed: allow Site assignment page to continue even if a blogname key is missing

= 1.0.6 =
* Added: hooks with new wpms prefixes
* Fixed: enabled checkbox to avoid creating a root blog.  This is for advanced users only.

= 1.0.5 =
* Added: better documentation in the Help tab
* Added: basic automated validation of new Network settings
* Removed: documentation on domain names that no longer applies

= 1.0.4 =
* Changed: show each network only once in Move Site field, regardless of metadata issues
* Fixed: a bug affecting network installs with old table name scheme (created before WP 3.0)
* Fixed: admin display bug affecting WP 3.2 installs

= 1.0.3 =
* Changed: processing to ensure that new Network paths are always valid
* Changed: documentation on new Networks
* Fixed: short_open_tag off compatibility

= 1.0.2 =
* Fixed: a bug that showed the Networks panel in the Site Admin backend on 3.1
* Fixed: a typo that left network-dependent blog options behind when moving blogs - thanks, edmeister

= 1.0.1 =
* Fixed an issue with the link to Network backends for versions before 3.1 - thanks, RavaH

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.1.2 =
* Fixed searching / sorting bug; added screen options for Networks per page

= 1.1.1 =
* UI enhancements

= 1.1.0 =
* `upload_filetypes` key cloned by default, some domain mapping checks added

= 1.0.9 =
* Fixed compatibility with WP < 3.1, enhanced help for WP 3.3, added ability to restrict Networks panel to a single Network

= 1.0.8 =
* Updated for WP 3.3

= 1.0.7 =
* Added diagnostic screen to help identify and resolve Network issues

= 1.0.6 =
* Enabled checkbox to avoid creating a new root blog - for advanced users only

= 1.0.5 =
* Documentation and interface improvements only

= 1.0.3 =
* Documentation and processing changes

= 1.0.2 =
* Bugfix - All users should upgrade

= 1.0.1 =
* Upgrade if using a version of WordPress earlier than 3.1

= 1.0 =
* Initial release - upgrade if you are still using my old WPMU Multi-Site Manager plugin somehow
