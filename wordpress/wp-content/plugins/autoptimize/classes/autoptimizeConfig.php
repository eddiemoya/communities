<?php

class autoptimizeConfig
{
	private $config = null;
	static private $instance = null;
	
	//Singleton: private construct
	private function __construct()
	{
		if( is_admin() )
		{
			//Add the admin page and settings
			add_action('admin_menu',array($this,'addmenu'));
			add_action('admin_init',array($this,'registersettings'));

			//Set meta info
			if(function_exists('plugin_row_meta'))
			{
				//2.8+
				add_filter('plugin_row_meta',array($this,'setmeta'),10,2);
			} elseif(function_exists('post_class')) {
				//2.7
				$plugin = plugin_basename(WP_PLUGIN_DIR.'/autoptimize/autoptimize.php');
				add_filter('plugin_action_links_'.$plugin,array($this,'setmeta'));
			}

			//Clean cache?
			if(get_option('autoptimize_cache_clean'))
			{
				autoptimizeCache::clearall();
				update_option('autoptimize_cache_clean',0);
			}
		}
	}
	
	static public function instance()
	{
		//Only one instance
		if (self::$instance == null)
		{
			self::$instance = new autoptimizeConfig();
		}
		
		return self::$instance;
    }
	
	public function show()
	{
?>
<div class="wrap">
<h2><?php _e('Autoptimize Settings','autoptimize'); ?></h2>

<div style="float:left;width:70%;">
<form method="post" action="options.php">
<?php settings_fields('autoptimize'); ?>

<h3><?php _e('HTML Options','autoptimize'); ?></h3>
<table class="form-table">
<tr valign="top">
<th scope="row"><?php _e('Optimize HTML Code?','autoptimize'); ?></th>
<td><input type="checkbox" name="autoptimize_html" <?php echo get_option('autoptimize_html')?'checked="checked" ':''; ?>/></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Keep HTML comments?','autoptimize'); ?></th>
<td><label for="autoptimize_html_keepcomments"><input type="checkbox" name="autoptimize_html_keepcomments" <?php echo get_option('autoptimize_html_keepcomments')?'checked="checked" ':''; ?>/>
<?php _e('Disabled by default. Enable this if you want HTML comments to remain in the page.','autoptimize'); ?></label></td>
</tr>
</table>

<h3><?php _e('JavaScript Options','autoptimize'); ?></h3>
<table class="form-table"> 
<tr valign="top">
<th scope="row"><?php _e('Optimize JavaScript Code?','autoptimize'); ?></th>
<td><input type="checkbox" name="autoptimize_js" <?php echo get_option('autoptimize_js')?'checked="checked" ':''; ?>/></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Exclude scripts from autoptimize:','autoptimize'); ?></th>
<td><label for="autoptimize_js_exclude"><input type="text" style="width:100%;" name="autoptimize_js_exclude" value="<?php echo get_option('autoptimize_js_exclude',"s_sid,smowtion_size,sc_project,WAU_,wau_add,comment-form-quicktags,edToolbar,ch_client"); ?>"/><br />
<?php _e('A comma-seperated list of scripts you want to exclude from being Autoptimized, for example \'whatever.js, another.js\' (without the quotes) to exclude those scripts from being aggregated and minimized by Autoptimize.','autoptimize'); ?></label></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Look for scripts only in &lt;head&gt;?','autoptimize'); ?></th>
<td><label for="autoptimize_js_justhead"><input type="checkbox" name="autoptimize_js_justhead" <?php echo get_option('autoptimize_js_justhead')?'checked="checked" ':''; ?>/>
<?php _e('Disabled by default. If the cache gets big, you might want to enable this.','autoptimize'); ?></label></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Add try-catch wrapping?','autoptimize'); ?></th>
<td><label for="autoptimize_js_trycatch"><input type="checkbox" name="autoptimize_js_trycatch" <?php echo get_option('autoptimize_js_trycatch')?'checked="checked" ':''; ?>/>
<?php _e('Disabled by default. If your scripts break because of an script error, you might want to try this.','autoptimize'); ?></label></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Use YUI compression?','autoptimize'); ?></th>
<td><label for="autoptimize_js_yui"><input type="checkbox" name="autoptimize_js_yui" <?php echo get_option('autoptimize_js_yui')?'checked="checked" ':''; ?>/>
<?php _e('Disabled by default. Read [autoptimize]/yui/README.txt for more information.','autoptimize'); ?></label></td>
</tr>
</table>

<h3><?php _e('CSS Options','autoptimize'); ?></h3>
<table class="form-table"> 
<tr valign="top">
<th scope="row"><?php _e('Optimize CSS Code?','autoptimize'); ?></th>
<td><input type="checkbox" name="autoptimize_css" <?php echo get_option('autoptimize_css')?'checked="checked" ':''; ?>/></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Look for styles on just &lt;head&gt;?','autoptimize'); ?></th>
<td><label for="autoptimize_css_justhead"><input type="checkbox" name="autoptimize_css_justhead" <?php echo get_option('autoptimize_css_justhead')?'checked="checked" ':''; ?>/>
<?php _e('Disabled by default. If the cache gets big, you might want to enable this.','autoptimize'); ?></label></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Generate data: URIs for images?','autoptimize'); ?></th>
<td><label for="autoptimize_css_datauris"><input type="checkbox" name="autoptimize_css_datauris" <?php echo get_option('autoptimize_css_datauris')?'checked="checked" ':''; ?>/>
<?php _e('Disabled by default. Enable this to include images on the CSS itself.','autoptimize'); ?></label></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Use YUI compression?','autoptimize'); ?></th>
<td><label for="autoptimize_css_yui"><input type="checkbox" name="autoptimize_css_yui" <?php echo get_option('autoptimize_css_yui')?'checked="checked" ':''; ?>/>
<?php _e('Disabled by default. Read [autoptimize]/yui/README.txt for more information.','autoptimize'); ?></label></td>
</tr>
</table>

<h3><?php _e('CDN Options','autoptimize'); ?></h3>
<table class="form-table"> 
<tr valign="top">
<th scope="row"><?php _e('Rewrite JavaScript URLs?','autoptimize'); ?></th>
<td><label for="autoptimize_cdn_js"><input type="checkbox" name="autoptimize_cdn_js" <?php echo get_option('autoptimize_cdn_js')?'checked="checked" ':''; ?>/>
<?php _e('Disabled by default. Do not enable this unless you know what you are doing.','autoptimize'); ?></label></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('JavaScript Base URL','autoptimize'); ?></th>
<td><label for="autoptimize_cdn_js_url"><input type="text" name="autoptimize_cdn_js_url" value="<?php $it = get_option('autoptimize_cdn_js_url');echo htmlentities($it?$it:site_url()); ?>" />
<?php _e('This is the new base URL that will be used when rewriting. It should point to the blog root directory.','autoptimize'); ?></label></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Rewrite CSS URLs?','autoptimize'); ?></th>
<td><label for="autoptimize_cdn_css"><input type="checkbox" name="autoptimize_cdn_css" <?php echo get_option('autoptimize_cdn_css')?'checked="checked" ':''; ?>/>
<?php _e('Disabled by default. Do not enable this unless you know what you are doing.','autoptimize'); ?></label></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('CSS Base URL','autoptimize'); ?></th>
<td><label for="autoptimize_cdn_css_url"><input type="text" name="autoptimize_cdn_css_url" value="<?php $it = get_option('autoptimize_cdn_css_url');echo htmlentities($it?$it:site_url()); ?>" />
<?php _e('This is the new base URL that will be used when rewriting. It should point to the blog root directory.','autoptimize'); ?></label></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Rewrite Image URLs?','autoptimize'); ?></th>
<td><label for="autoptimize_cdn_img"><input type="checkbox" name="autoptimize_cdn_img" <?php echo get_option('autoptimize_cdn_img')?'checked="checked" ':''; ?>/>
<?php _e('Disabled by default. Do not enable this unless you know what you are doing.','autoptimize'); ?></label></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Image Base URL','autoptimize'); ?></th>
<td><label for="autoptimize_cdn_img_url"><input type="text" name="autoptimize_cdn_img_url" value="<?php $it = get_option('autoptimize_cdn_img_url');echo htmlentities($it?$it:site_url()); ?>" />
<?php _e('This is the new base URL that will be used when rewriting. It should point to the blog root directory.','autoptimize'); ?></label></td>
</tr>
</table>

<h3><?php _e('Cache Info','autoptimize'); ?></h3>
<table class="form-table"> 
<tr valign="top">
<th scope="row"><?php _e('Cache folder','autoptimize'); ?></th>
<td><?php echo htmlentities(AUTOPTIMIZE_CACHE_DIR); ?></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Can we write?','autoptimize'); ?></th>
<td><?php echo (autoptimizeCache::cacheavail() ? __('Yes','autoptimize') : __('No','autoptimize')); ?></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Cached styles and scripts','autoptimize'); ?></th>
<td><?php echo autoptimizeCache::stats(); ?></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Do not compress cache files','autoptimize'); ?></th>
<td><label for="autoptimize_cache_nogzip"><input type="checkbox" name="autoptimize_cache_nogzip" <?php echo get_option('autoptimize_cache_nogzip')?'checked="checked" ':''; ?>/>
<?php _e('Disabled by default. Enable this if you want to compress the served files using your webserver.','autoptimize'); ?></label></td>
</tr>
</table>

</table>

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
<input type="submit" name="autoptimize_cache_clean" value="<?php _e('Save Changes and Empty Cache') ?>" />
</p>

</form>
</div>
<div style="float:right;width:30%" id="autoptimize_admin_feed">
        <div style="margin-left:10px;margin-top:-5px;">
                <h3>
                        <?php _e("futtta about","autoptimize") ?>
                        <select id="feed_dropdown" >
                                <option value="1"><?php _e("Autoptimize","autoptimize") ?></option>
                                <option value="2"><?php _e("WordPress","autoptimize") ?></option>
                                <option value="3"><?php _e("Web Technology","autoptimize") ?></option>
                        </select>
                </h3>
                <div id="futtta_feed"></div>
        </div>
</div>

<script type="text/javascript">
	var feed = new Array;
	feed[1]="http://feeds.feedburner.com/futtta_autoptimize";
	feed[2]="http://feeds.feedburner.com/futtta_wordpress";
	feed[3]="http://feeds.feedburner.com/futtta_webtech";
	cookiename="autoptimize_feed";

        jQuery(document).ready(function() {
		jQuery("#feed_dropdown").change(function() { show_feed(jQuery("#feed_dropdown").val()) });

		feedid=jQuery.cookie(cookiename);
		if(typeof(feedid) !== "string") feedid=1;

		show_feed(feedid);
		})

	function show_feed(id) {
  		jQuery('#futtta_feed').rssfeed(feed[id], {
			<?php if ( is_ssl() ) echo "ssl: true,"; ?>
    			limit: 4,
			date: true,
			header: false
  		});
		jQuery("#feed_dropdown").val(id);
		jQuery.cookie(cookiename,id,{ expires: 365 });
	}
</script>

</div>
<?php
	}
	
	public function addmenu()
	{
		$hook=add_options_page(__('Autoptimize Options','autoptimize'),'Autoptimize','manage_options','autoptimize',array($this,'show'));
        	add_action( 'admin_print_scripts-'.$hook,array($this,'autoptimize_admin_scripts'));
        	add_action( 'admin_print_styles-'.$hook,array($this,'autoptimize_admin_styles'));
	}

	public function autoptimize_admin_scripts() {
		wp_enqueue_script('jqzrssfeed', plugins_url('/external/jquery.zrssfeed.min.js', __FILE__), array('jquery'),null,true);
		wp_enqueue_script('jqcookie', plugins_url('/external/jquery.cookie.min.js', __FILE__), array('jquery'),null,true);
	}

	public function autoptimize_admin_styles() {
        	wp_enqueue_style('zrssfeed', plugins_url('/external/jquery.zrssfeed.css', __FILE__));
	}

	
	public function registersettings()
	{
		register_setting('autoptimize','autoptimize_html');
		register_setting('autoptimize','autoptimize_html_keepcomments');
		register_setting('autoptimize','autoptimize_js');
		register_setting('autoptimize','autoptimize_js_exclude');
		register_setting('autoptimize','autoptimize_js_trycatch');
		register_setting('autoptimize','autoptimize_js_justhead');
		register_setting('autoptimize','autoptimize_js_yui');
		register_setting('autoptimize','autoptimize_css');
		register_setting('autoptimize','autoptimize_css_justhead');
		register_setting('autoptimize','autoptimize_css_datauris');
		register_setting('autoptimize','autoptimize_css_yui');
		register_setting('autoptimize','autoptimize_cdn_js');
		register_setting('autoptimize','autoptimize_cdn_js_url');
		register_setting('autoptimize','autoptimize_cdn_css');
		register_setting('autoptimize','autoptimize_cdn_css_url');
		register_setting('autoptimize','autoptimize_cdn_img');
		register_setting('autoptimize','autoptimize_cdn_img_url');
		register_setting('autoptimize','autoptimize_cache_clean');
		register_setting('autoptimize','autoptimize_cache_nogzip');
	}
	
	public function setmeta($links,$file=null)
	{
		//Inspired on http://wpengineer.com/meta-links-for-wordpress-plugins/
		
		//Do it only once - saves time
		static $plugin;
		if(empty($plugin))
			$plugin = plugin_basename(WP_PLUGIN_DIR.'/autoptimize/autoptimize.php');
		
		if($file===null)
		{
			//2.7
			$settings_link = sprintf('<a href="options-general.php?page=autoptimize">%s</a>', __('Settings'));
			array_unshift($links,$settings_link);
		}else{
			//2.8
			//If it's us, add the link
			if($file === $plugin)
			{
				$newlink = array(sprintf('<a href="options-general.php?page=autoptimize">%s</a>',__('Settings')));
				$links = array_merge($links,$newlink);
			}
		}
		
		return $links;
	}
	
	public function get($key)
	{		
		if(!is_array($this->config))
		{
			//Default config
			$config = array('autoptimize_html' => 0,
				'autoptimize_html_keepcomments' => 0,
				'autoptimize_js' => 0,
				'autoptimize_js_exclude' => "s_sid, smowtion_size, sc_project, WAU_, wau_add, comment-form-quicktags, edToolbar, ch_client",
				'autoptimize_js_trycatch' => 0,
				'autoptimize_js_justhead' => 0,
				'autoptimize_js_yui' => 0,
				'autoptimize_css' => 0,
				'autoptimize_css_justhead' => 0,
				'autoptimize_css_datauris' => 0,
				'autoptimize_css_yui' => 0,
				'autoptimize_cdn_js' => 0,
				'autoptimize_cdn_js_url' => site_url(),
				'autoptimize_cdn_css' => 0,
				'autoptimize_cdn_css_url' => site_url(),
				'autoptimize_cdn_img' => 0,
				'autoptimize_cdn_img_url' => site_url(),
				'autoptimize_cache_nogzip' => 0,
				);
			
			//Override with user settings
			foreach(array_keys($config) as $name)
			{
				$conf = get_option($name);
				if($conf!==false)
				{
					//It was set before!
					$config[$name] = $conf;
				}
			}
			
			//Save for next question
			$this->config = $config;
		}
		
		if(isset($this->config[$key]))
			return $this->config[$key];
		
		return false;
	}
}
