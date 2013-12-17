=== WP Content Filter ===
Contributors: dgwyer
Tags: filtered, keywords, options, profanity, swearing, post, admin
Requires at least: 2.7
Tested up to: 3.3.2
Stable tag: 2.31

Protect your site by filtering out profanity, swearing, abusive comments and other keywords of your choice. Flexible Plugin options available.

== Description ==

Update: Now filters bbPress titles and content!

With this Plugin you can block out all profanity, swearing, abusive comments and any other keywords of your choice specified on the Plugin options page. The Plugin now features advanced control over content such as flexible strict filtering options. For example, flagged keywords which are embedded in whole words can now be ignored.

i.e. 'competition' will now be ignored if you have 'pet' as a flagged keyword. Previously it would ALWAYS appear as 'com***ition'. 

You can select which sections of your WordPress site to filter keywords from including:

* Posts (including recent posts sidebar widget)
* Post title
* Comments (including recent comments sidebar widget)
* Comment authors
* Tags
* Tag cloud

Keywords are replaced with wild card characters (set in the Plugin options). Further options retain the first letter of each filtered keyword, remove all letters (wild card characters only), or retain the first and last letter of the filtered keyword. For example, the keyword pluto would be replaced with either p****, *****, or p***o. Keywords can also be filtered using case insensitive, or case sensitive options.

There is also a setting to preserve current options if the Plugin is ever temporarily deactivated. When the Plugin is reactivated it will either reload the default settings or the previous options will remain. In the next version the option database entries for the Plugin will be removed only if the plugin files are deleted, and not just when the Plugin is simply deactivated which was the case with the pre 2.0 version.

Please rate it if you find it useful, thanks.

See our <a href="http://www.presscoders.com" target="_blank">WordPress development site</a> for more information.

== Installation ==

Instructions for installing the WP Content Filter Plugin.

1. Download and extract the Plugin zip file.
2. Upload the folder containing the Plugin files to your WordPress Plugins folder (usually ../wp-content/plugins/ folder).
3. Activate the Plugin via the 'Plugins' menu in WordPress.
4. Once activated you go to the Plugin options by clicking the 'WP Content Filter' link under the 'Settings' menu.
5. Enter your required filter keywords and configure other options and your site content will be protected automatically.

== Frequently Asked Questions ==

= How do I specify a character other than '*' to blank out swear words etc? =

As of version 1.1 you can now set this in the Plugin options page.

= Does the Plugin change the content in the WordPress database? =

No, the Plugin just filters content directly before it is displayed on the screen. No content in the database is ever altered.

== Screenshots ==

1. Plugin options available.
2. Example of the Plugin working on a live site, filtering post content, comments, and the comment author name!
3. Activated Plugin on the Manage Plugins page.

== Changelog ==

*2.31 update*

* Minor update to Plugin options page.

*2.3 update*

* Support for filtering of bbPress title and content.
* Plugin options page updated.

*2.28 update*

* New internal function pre-fix used due to clash with another Plugin(s).
* Tested with WordPress 3.2.1.

*2.27 update*

* Updated for WordPress 3.1.

*2.26 update*

* Minor changes to Plugin options page.
* Tested and updated to work with WordPress 3.0.

*2.25 update*

* Ammendments to the Plugin options page.

*2.20 update*

* Strict filtering issue now fixed! Flagged keywords which are embedded in whole words can now be ignored with new Plugin options.
* New Plugin support package available.

*2.11 update*

* Admin help information labels updated.
* Screenshots resized, and a third one added.

*2.1 update*

* Added new filter character option - Blank, which replaces flagged keywords with nothing. i.e. basically just deletes the keyword(s).
* Changed drop down text for other filter character options to be in line with newly added option.
* Small CSS changes to WP Content Filter options page, to be consistent with WordPress options pages.

*2.05 update*

* Options table entries now removed when Plugin is deleted via WordPress admin.
* Legacy options entries now checked for and removed by default.

*2.01 update*

* Small edit to the menu page code.

*2.0 update*

* Overhaul of the Plugin code base.
* New options engine used.
* Options page completely updated.
* Keywords textbox input now validated, and automatically strips out HMTL tags.
* New option to preserve settings or restore defaults if the Plugin is temporarily deactivated and then reactivated.
* Option to delete Option db entries upon deactivation removed. Instead, a future version will delete these entries automatically when the Plugin is deactivated AND deleted.

*1.2 update*

* Comment author field, now filtered for keywords.
* Larger input area for entering keywords to be filtered.

*1.1 update*

* New option to remove all Plugin database settings when Plugin is deactivated. This is useful if you wish to delete the Plugin; in this case the option will clean up the database automatically. Note: If this option is selected, and the Plugin deactivated/activated then the Plugin options will revert to their defaults (i.e. same effect as installing the Plugin for the first time). So if you wish to retain your settings between updgrades leave this option at its default setting (off).
* Existing admin controls swapped around a bit.
* Can now select the wildcard character used in blanking out keywords (previous was fixed to only an asterisk).
* Choice of letters to retain in filtered keywords, options include: retain first letter (default), retain first and last letter, blank out ALL characters.