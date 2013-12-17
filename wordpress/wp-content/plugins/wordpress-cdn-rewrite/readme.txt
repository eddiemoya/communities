=== WordPress CDN Rewrite ===
Contributors: bluewormlabs
Donate link: http://bluewormlabs.com/projects.php
Tags: cdn
Requires at least: 2.8
Tested up to: 3.3.1
Stable tag: 1.1
License: [zlib/libpng license][http://opensource.org/licenses/zlib-license]

WP CDN Rewrite is a simple plugin that rewrites URLs, generally to static assets, automatically based on a set of rules.

== Description ==

For performance reasons, some websites host static content (images, CSS files, Javascript files, etc.) on something other than the main webserver. It could be as simple as separate, higher performance software running on the same server or a full-fledged CDN serving the files from multiple servers around the world.

While many plugins like this already exist for WordPress, we couldn't find any that matched our needs. Furthermore, most required running a caching plugin in addition to the CDN plugin. In many cases, that's fine, but we don't always want to have caching.

The following are rewritten if a rule matches:

1. Values in `href` attributes of A tags
2. Values in `href` attributes of LINK tags
3. Values in `src` attributes of IMG tags
4. Values in `src` attributes of SCRIPT tags

== Installation ==

1. Extract wpcdnrewrite into the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Configuration ==
The plugin creates a new section in the Settings area of the 
WordPress admin site titled 'WP CDN Rewrite'. All configuration is 
performed there.

First, add domains to the whitelist. URLs on these domains will be 
rewritten if a rule is found for the given URL.

Second, add rules. Two types of rules can be specified:

1.  Host rewrite
    
    These rules only change the host on a given URL. For cases along 
    the lines of the CloudFiles use case that we wrote this for, simply 
    specify the new host and the extension to match on (e.g., `jpg`).
2.  Full rewrite
    
    These rewrite everything except the filename in the URL. Specify 
    whatever should prefix the filename (e.g., `http://images.example.com/`).

== Frequently Asked Questions ==

= N/A =
No questions yet.

== Screenshots ==

1. Configuration

== Changelog ==

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.0 =
* This is the first version.
