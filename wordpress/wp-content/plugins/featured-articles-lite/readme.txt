=== Featured Articles Lite ===
Contributors: constantin.boiangiu
Tags: slider, featured, articles, posts, pages
Requires at least: 3.1
Tested up to: 3.3.1
Stable tag: trunk

Put featured posts or pages into a fancy JavaScript slider that can be set to display on any category page, page or homepage.

== Description ==

**FeaturedArticles for Wordpress** slideshow plugin allows you to create sliders in your blog pages directly from your already written content, be it posts or pages. With the ability to change any slider aspect by making use of slideshow themes that are delivered with the plugin, almost anything is possible. Also, it offers the possibility to create additional themes (with some PHP, CSS and JavaScript knowledge) that can have custom written animations by extending the base functionality of the main slider script.

Slideshows can be placed by one of these ways (or all at the same time):

* manual placement directly into your WOrdpress theme template files (requires editing the template file you want to place a slideshow in and add a small piece of code);
* shortcode placement directly into a post or page content;
* widget placement into any widget areas your theme has;
* automatic placement in any page just above the page loop.

While Lite version of FeaturedArticles provides all the neccessary tools for creating very nice slideshows, PRO version of this same plugin comes in addition with:

* 4 more themes developed by our developers team (7 in total, 3 video enabled by default in PRO version);
* **video enabled** custom slides for **YouTube and Vimeo** that can replace the image with a video ( [See examples here](http://www.codeflavors.com/featured-articles-pro/examples/ "FeaturedArticles PRO examples") );
* custom writen slides that can be written using the visual editor;
* slideshows can display custom post types if your WordPress blog has them;
* for posts and custom post types, selection of posts by taxonomies;
* mixing of posts, custom posts, pages and custom slides into the same slideshow;
* visual color scheme editor that allows you to blend-in slideshows into your overall blog design without having to write a single line of CSS;
* priority support and debugging for 3'rd party plugins and themes conflicts.

**Important links:**

* [Documentation](http://www.codeflavors.com/documentation/ "FeaturedArticles for Wordpress documentation") on plugin usage and slideshow theme structure;
* [Forums](http://www.codeflavors.com/codeflavors-forums/ "CodeFlavors Community Forums") (while we try to keep up with the forums here, please post any requests on our forums for a faster response);
* [FeaturedArticles homepage](http://www.codeflavors.com/featured-articles-pro/ "FeaturedArticles for Wordpress")
* [CodeFlavors News](http://www.codeflavors.com/news/ "CodeFlavors news on FeaturesArticles for Wordpress plugin") - [CodeFlavors](http://www.codeflavors.com/ "CodeFlavors - devoted to Wordpress") is our new home.

**Features:**

* Add, remove, order any slideshow content made of pages or mixed content;
* Animation control (based on individual themes);
* Write custom slides by using the Wordpress editor (PRO);
* Put videos in custom slides from Vimeo or YouTube to replace the image for both thumbnails and full background image(PRO);
* Customize posts and pages displayed into slideshows by specifying a different title, content, slide background color, image and more;
* Change themes by choosing from up to 7 currently available themes (only 3 in Lite version);
* Change theme color palette by simply creating a new color stylesheet that can skin the theme without messing with the CSS responsible for layout;
* Create color palette stylesheets using a visual editor;
* Preview slideshow even before publishing it in your pages.
* Display slideshows by widgets, shorcodes, manual code snippet or automatic display above any page loop;
* Create new themes that can completely change the default animations and can add new options fields custom for it in Slider editing in Wordpress admin.

These are just a few of the things this plugin can do so just go on and try it for yourself.

What can this plugin be used for? Well, you name it and we'll find a way to make it happen. Guaranteed! 

== Installation ==

**Before updating, make sure you back-up all your custom made themes.**

With version 2.4, things went a little different. We tried (you judge) to make an easier, funnier and more extensible way of creating themes for FeaturedArticles slideshows. Themes prior to this version should still work.
Also, starting with this version we moved the plugin to a different website called [CodeFlavors](http://www.codeflavors.com/ "CodeFlavors - devoted to Wordpress"). This will be the home of all plugins we are going to develop for Wordpress from now on.
If you find yourself into trouble with this update, we have docs, community forums and we'll be happy to help you.

**You'll notice something missing!**. Automatic placement is no longer displayed into slideshow settings by default. To enable it, go to Settings and check the *Enable automatic slider insertion* option.
Why we did this? Well, starting with next version (after 2.4), this automatic placement will become much, much better. We have some ideas, we just need to figure out some things first and if we would have done this with this update, it would have taken too long.

Now, here are the update instructions (automatic update will also work):

If you are upgrading to the current version, make sure you backup all the files of the previous installation. After you backup, go to wp-admin and disable the plugin. With a FTP client, delete the plugin completely.

1. Disable the previous installation of Featured Articles Lite
2. FTP to plugins directory of your blog and delete the plugin. 
1. Download and extract folder featured-articles-lite
2. Upload the whole *featured-articles-lite* folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to WP Admin->FA Lite->Edit/Add and configure your first slider
5. To display the slider, you can either choose sections from admin ( homepage, pages or categories ), display the newly created slider as a widget, use shortcodes in post or pages or place the following code in your template: 

`<?php FA_display_slider(YOUR SLIDER ID HERE); ?>`

For your convenience, once you save a slider, under Manual Placement you will see the code needed to be placed into your theme files to display it.

For any clarifications see the [Docs](http://www.codeflavors.com/documentation/ "FeaturedArticles for Wordpress documentation") or ask for help on our [Forums](http://www.codeflavors.com/codeflavors-forums/ "CodeFlavors Forums").
We don't actively monitor the forum here on Wordpres.org so responses to questions posted here might take a while to get answered.

== Screenshots ==

1. Edit slideshow WP admin page
2. Theme Classic (screenshot is dark palette color and also has light)
3. The Smoke
4. Theme Title Navigation
5. Theme Minimal (PRO)
6. Theme Navobar (PRO)
7. Theme Ribbons (PRO)
8. Theme Strips (PRO)

== Changelog ==

= 2.4.8 =
* Compatibility with WPtouch plugin by verifying if WPtouch Restricted Mode option is on and preventing any slideshows from displaying. A new option under Settings can also prevent any slideshows from displaying in WPtouch mobile themes but if Restricted Mode is on, even if allowed from Featured Articles Lite, no slideshows will display.
* Compatibility with qTranslate plugin. No action needed to enable it.

= 2.4.7 =
* Solves problem of post/page text and title not being replaced by custom ones specified in post/page editing.

= 2.4.6 =
* Solves the problem of multiple slideshows displayed into the same page that have same slideshow theme but different color schemes in settings.

= 2.4.5 =
* Solved small bug related to linked slide titles target attribute value not being between double quotes.

= 2.4.4 =
* Compatibility with jQuery 1.7 from Wordpress 3.3 for mouse wheel navigation in front-end slideshows.
* Automatic placement enabled by default when installing/updating the plugin (previously set disabled and could be enabled from plugin Settings page).
* Wordpress 3.3 compatibility for gallery uploaded images with the new flash uploader.

= 2.4.3 =
* Backwards compatibility for old Light and Dark themes. Current version merged these 2 themes into a single one called Classic that uses color scheme option to change between Light and Dark. If in FeaturedArticles 2.3 you were using the default Light and/or Dark themes, on update they will get deleted and no longer available. This update solves the problem by automatically assigning Light and Dark to Classic with the appropriate color scheme.

= 2.4.2 =
* Repaired dead/wrong links from Wordpress admin plugin pages.

= 2.4.1 =
* Option to change the default slideshow themes folder from within the plugin contents to anywhere into wp_content folder. Option is available in plugin menu->Settings page.
* Solved bug that was keeping dark color palette selected by default for all themes making Classic Light unavailable.
* Solved bug that was removing the link that places custom featured image on posts/pages in Wordpress gallery when search of filtering was performed.

= 2.4 =
* Image attachment to slide is made using the default Wordpress Media Gallery
* Templating functions for themes to display information in slideshows
* Themes can extend the default slideshow script to create custom animations (requires jQuery knowledge)
* Themes can add custom fields to slideshow editing form that are unique for them (options that apply only to a theme)
* Custom written slides that can be created with the default Wordpress editor (PRO)
* Slideshows created from pages or featured content (mix of pages, posts and custom slides) have slides ordering option by drag-and-drop
* Custom titles, text and read-more link texts individual for every post/page or custom slide
* Themes can have multiple color palette stylesheets to allow a better blending with the website without having to change the main theme stylesheet
* And probably many other things that we forgot during all this time :)

= 2.3.7 =
* Solved bug that disabled custom image placement on post/page when Google Analyticator plugin was installed
* Solved bug that caused automatic image detection within post content not to be performed because of plugin stripping HTML from post content. Please note that you need to do a small change if you display the slider with a custom theme made by you or others. In display.php inside your theme folder change

`<?php echo FA_truncate_text($post->post_content, $image ? $options['desc_truncate'] : $options['desc_truncate_noimg']);?>` 

into

`<?php echo FA_truncate_text($post->FA_post_content, $image ? $options['desc_truncate'] : $options['desc_truncate_noimg']);?>`

= 2.3.6 =
* Solved shortcode bug that caused the slider not to be displayed into the exact place the shortcode was placed in post content (changes made in file: featured_articles.php)
* Solved stylesheet issue not being loaded for developer link at the bottom of the slider (changes made in file: featured_articles.php)

= 2.3.5 =
* Solved IE8 bug that caused autoslide not to start (error message: Message: 'currentKey' is null or not an object)
* Solved issue with autoslide that wasn't correctly reset when navigation was clicked
* Made links in article description have the same color as the text

= 2.3.4 =
* Created shortcode support to display sliders inside post/page content.

= 2.3.3 =

* Solved Allowed tags option bug that wasn't taken into account by the plugin when displaying posts in slider.

= 2.3.2 =
* Solved z-index issue in themes causing side navigation not to be able to be clicked 

= 2.3.1 =
* Solved JavaScript bug that made only the first post clickable.

= 2.3 =
* Slider script developed with jQuery (this solves the conflict between different JavaScript frameworks)
* Creation/management and placement of multiple sliders into the same page or all over your blog
* Widget support provided (go to Appearance->Widgets and look for FA Lite Slider)
* Easy manual placement with code to be implemented provided for each slider created
* Slightly modified themes to make it work with multiple sliders

= 2.2 =
* User specified HTML tags allowed into featured post description displayed into the slider
* Meta box to ease the way custom images and featured posts are inserted into the slider
* Possibility to display posts or pages in random order into the slider
* Featured articles slider resizable from administration area ( default values get specified into stylesheet )
* Author link ( if you want to support the plugin ) that can be disabled from administration
* Slider settings access is restricted to administrators only with the possibility to give access to any other group of users available in wordpress
* Menu no longer available under Settings->Featured articles but directly in Wordpress admin sidebar ( look for FA Lite )
* Themes modified to support featured posts slider resizing ( both CSS files and display files have changed a little ). If you update the plugin and you made custom themes, back-up first your themes folder.
* Custom post/pages images improved usage and interface
* Easy setting for featured posts and pages to be displayed into the slider
* Image detection improved even more. Currently there are 2 ways to set an image for a certain post: by setting the image as a custom field and second by detecting the image from post content. For images detected in post content, the plugin tries to identify the exact attachment from the database and if found, it automatically sets the image into the custom field. The only thing it needs is for the image to have the width and height attributes set in HTML.
* New theme available (Smoke). See screenshots for details and [WP featured articles Lite homepage](http://www.php-help.ro/mootools-12-javascript-examples/wordpress-featured-content-plugin/#additional-themes "WP Featured Articles plugin - theme Smoke settings") for instructions on how to set up this theme.

= 2.1 =
* Date format in featured post short description displays according to blog date format option setting
* Editable text for read more link
* New option to set featured post title as link
* Image detection no more made inside theme but done by function ( less code in slider theme )
* New option to display the slider manually by adding a function to theme files ( function is FA_display_slider - see installation for instructions ) 
* Slider mouse wheel navigation can be enabled/disabled from wp admin
* Links in featured posts text allowed 
* For automatic placement, option to choose loop to display on top of 

= 2.0 =
Initial release for the new redesigned Wordpress Featured Articles

== Troubleshooting ==

Plugin is guaranteed to work on a clean Wordpress install. Since themes and other plugins don't always play nice, there are a few things you could check if slideshows won't work in your pages:

1. Check that only one jquery file is loaded. Most times, a manually loaded jQuery version in theme header or by a plugin may be the cause. Look in your page source in browser and see if that's the problem.
2. Plugins merging JavaScript and CSS files may also cause problems. In this case, it's all about god intentions with bad results.
3. Use the proper Wordpress version (3.1 +).
4. See if your theme footer.php file calls wp_footer().

If all options are exhausted, you can always ask for help on our forums. We'll answer whenever the time allows us to.

ONLY FOR VERSIONS PRIOR TO 2.3

The slider script is developed using MooTools 1.2. Since the framework isn't bundled in Wordpress (as jQuery is), the plugin adds the MooTools framework along with the other scripts it needs to run into the blog header. If other plugins running on MooTools are installed the page will issue Javascript errors. To solve this problem, in slider administration page there's an option to drop the MooTools script so that conflicts no longer occur.

Another known problem is if any of the plugin installed use Prototype framework. MooTools and Prototype are conflicting and the only solution would be to remove one of the plugins (either the MooTools based or the Prototype based plugins).

Usually, after you install Featured Articles Lite into your blog and you go see it in front-end and the slider doesn't work it's a clear sign that there's a framework conflict. First thing to to is to go to wp-admin and open the FA Lite settings panel. Look for option Unload MooTools framework and uncheck it. Go back to front-end and see if the slider works. If it does, this means that another plugin uses MooTools and there was a conflict because MooTools was included twice in header.
If the slider still doesn't work, look into page source and do a search for "prototype.js". If you can see it in your page source it's time to make a decision: use FA Lite and deactivate the plugin using Prototype or drop FA Lite and continue using the other plugin.

If you need help troubleshooting leave a comment on [WP featured articles Lite homepage](http://www.php-help.ro/mootools-12-javascript-examples/wordpress-featured-content-plugin/#additional-themes "WP Featured Articles plugin homepage").
