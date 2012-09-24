<?php
/*
Plugin Name: Cache Images
Plugin URI: http://wordpress.org/extend/plugins/cache-images/
Description: Goes through your posts and gives you the option to cache all hotlinked images from a domain locally in your upload folder
Version: 3.1
Author: Matt Mullenweg
Author URI: http://ma.tt/
WordPress Version Required: 2.8
*/

/**
 * If file is opened directly, return 403 error
 */

if (!function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

/**
 * Yes, we're localizing the plugin.  This partly makes sure non-English
 * users can use it too.  To translate into your language use the
 * cache-images.pot file in /languages folder.  Poedit is a good tool to for translating.
 * @link http://poedit.net
 *
 */
function cache_images_init() {
	load_plugin_textdomain( 'cache-images', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'cache_images_init' );

/**
 * Add action links to plugins page
 * Thanks Dion Hulse 
 * @link http://dd32.id.au/wordpress-plugins/?configure-link
 * (taken from Adminize plugin)
 */
function cache_images_filter_plugin_actions($links, $file){
	static $this_plugin;

	if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);

	if( $file == $this_plugin ){
		$settings_link = '<a href="' . admin_url('options-media.php') . '#cache_images_automatic_caching' . '">' . __('Settings', 'cache-images') . '</a>';
		$scaning_link = '<a href="' . admin_url('tools.php?page=cache-images/cache-images.php') . '">' . __('Scanning', 'cache-images') . '</a>';
		$links = array_merge( array($settings_link, $scaning_link), $links); // before other links
	}
	return $links;
}
add_filter( 'plugin_action_links', 'cache_images_filter_plugin_actions', 10, 2 );

/**
 * Search a multiple array
 * @link http://www.php.net/manual/en/function.in-array.php#20594
 */
function in_multi_array($needle, $haystack)
{
    $in_multi_array = false;
    if(in_array($needle, $haystack))
    {
        $in_multi_array = true;
    }
    else
    {   
        for($i = 0; $i < sizeof($haystack); $i++)
        {
            if(is_array($haystack[$i]))
            {
                if(in_multi_array($needle, $haystack[$i]))
                {
                    $in_multi_array = true;
                    break;
                }
            }
        }
    }
    return $in_multi_array;
}

/**
 * Add Cache Images page under Tools
 */
function mm_ci_add_pages() {
	add_management_page( __( 'Cache Remote Images', 'cache-images' ), __( 'Cache Remote Images', 'cache-images' ), 8, __FILE__,
'mm_ci_manage_page' );
}

function mm_ci_manage_page() {
	global $wpdb;
?>
<div class="wrap">
<h2><?php _e( 'Remote Image Caching', 'cache-images' ); ?></h2>
<p><?php _e( 'Here&#8217;s how this works:', 'cache-images' ); ?></p>
<ol>
	<li><?php _e( 'Click the button below and we&#8217;ll scan all of your posts for remote images', 'cache-images' ); ?> (<em><?php _e( 'Button <strong>Scan</strong> will search only for images that are hotlinked (ie. used like in example <code>&lt;img src="http://example.com/picture.jpg" /&gt;</code>), while button <strong>Scan (including linked)</strong> will search for images that are only linked from this site (ie. ised like in example <code>&lt;a href="http://example.com/picture.jpg"&gt;example&lt;/a&gt;</code>). Use second button with caution!', 'cache-images' ); ?></em>)</li>
	<li><?php _e( 'Then you&#8217;ll be presented with a list of domains. For each domain, press button Cache from this domain', 'cache-images' ); ?></li>
	<li><?php _e( 'The images will be copied to your upload directory, the links in your posts will be updated to the new location, and images will be added to your media library, associated to first post from where they are found.', 'cache-images' ); ?></li>
</ol>
<?php
	/**
	 * Show notice for WP Smush.it
	 */
	if ( !function_exists( 'wp_smushit' ) ) {
		?><div class="wp-smushit-notice error">
		<strong><?php _e( "Tip:", "gse_textdomain" );?></strong><br />
		<?php _e( "You can install plugin WP Smush.it to reduce image file sizes and improve performance using the Smush.it API.", "cache-images" );
		echo sprintf(__( " (<a href='%s'>read more about WP Smush.it</a>)", "cache-images" ), "http://dialect.ca/code/wp-smushit/" );?><br />
		<?php echo sprintf(__( "<a href='%s' class='thickbox'>Install WP Smush.it</a>", "cache-images" ),  esc_url(admin_url( 'plugin-install.php?tab=plugin-information&plugin=wp-smushit&TB_iframe=true&width=600&height=550' )));?><br />
		</div>
	<?php }
?>
<script type="text/javascript">
	function getdomains() {
		jQuery.ajax({
			url: ajaxurl, 
			type: "POST",
			//contentType: "application/json",
			data: "action=cache_images&do=getdomains",
			success: function(result) {
				if (result === 'null') {
					setStatusMessage('<?php _e( 'No posts with images were found.', 'cache-images' ); ?>');
					return;
				}
				var list = eval(result);
				var curr = 0;
				
				setStatusMessage('<?php _e( 'We found some results. Choose the domains from where you want to grab images from by clicking on a button "Cache from this domain" next to it.', 'cache-images' ); echo '<br />'; _e( '<strong>Note</strong>: you <strong>must not close</strong> this page while caching is performed. You can close it when you see message "Done caching from..." and yellow bar is removed', 'cache-images' ); ?>');
				
				function domainList() {
					if (curr >= list.length) {
						return;
					}
					jQuery("#listofdomains").after("<li id=\"domain_" + list[curr].domainmd5 + "\"><label><code>" + list[curr].domain + "</code> (<?php printf(__( 'results: %1$s', 'cache-images' ), '" + list[curr].num + "'); ?>) <input type=\"button\" onClick=\"javascript:regenerate('" + list[curr].domain + "\', \'" + list[curr].domainmd5 + "\');\" class=\"button\" name=\"cache_images_" + list[curr].domainmd5 + "\" id=\"cache_images_" + list[curr].domainmd5 + "\" value=\"<?php _e( 'Cache from this domain', 'cache-images' ) ?>\" /> </label></li>");
					curr = curr + 1;
					domainList();
				}

				domainList();
			},
			error: function(request, status, error) {
				var errormessageone = "<?php _e( 'Error %1$s', 'cache-images' ); ?>";
				var errormessage = errormessageone.replace("%1$s", request.status);
				setMessage(errormessage);
			}
		});
	}
</script>
<script type="text/javascript">
	function getalldomains() {
		jQuery.ajax({
			url: ajaxurl, 
			type: "POST",
			//contentType: "application/json",
			data: "action=cache_images&do=getalldomains",
			success: function(result) {
				if (result === 'null') {
					setStatusMessage('<?php _e( 'No posts with images were found.', 'cache-images' ); ?>');
					return;
				}
				var list = eval(result);
				var curr = 0;
				
				setStatusMessage('<?php _e( 'We found some results. Choose the domains from where you want to grab images from by clicking on a button "Cache from this domain" next to it.', 'cache-images' ); echo '<br />'; _e( '<strong>Note</strong>: you <strong>must not close</strong> this page while caching is performed. You can close it when you see message "Done caching from..." and yellow bar is removed', 'cache-images' ); ?>');
				
				function allDomainList() {
					if (curr >= list.length) {
						return;
					}
					jQuery("#listofdomains").after("<li id=\"domain_" + list[curr].domainmd5 + "\"><label><code>" + list[curr].domain + "</code> (<?php printf(__( 'results: %1$s', 'cache-images' ), '" + list[curr].num + "'); ?>) <input type=\"button\" onClick=\"javascript:cacheall('" + list[curr].domain + "\', \'" + list[curr].domainmd5 + "\');\" class=\"button\" name=\"cache_images_" + list[curr].domainmd5 + "\" id=\"cache_images_" + list[curr].domainmd5 + "\" value=\"<?php _e( 'Cache from this domain', 'cache-images' ) ?>\" /> </label></li>");
					curr = curr + 1;
					allDomainList();
				}

				allDomainList();
			},
			error: function(request, status, error) {
				var errormessageone = "<?php _e( 'Error %1$s', 'cache-images' ); ?>";
				var errormessage = errormessageone.replace("%1$s", request.status);
				setMessage(errormessage);
			}
		});
	}
</script>
<script type="text/javascript">
		
		
		function setMessage(msg) {
			jQuery("#message").html(msg);
			jQuery("#message").show();
		}
		
		function setStatusMessage(msg) {
			jQuery("#status-message").html(msg);
			jQuery("#status-message").show();
		}
		
		function regenerate(domain,domainmd5) {
			jQuery("#cache_images_" + domainmd5).attr("disabled", true);
			setMessage("<?php _e( 'Reading posts...', 'cache-images' ); ?>");
			jQuery.ajax({
				url: ajaxurl, 
				type: "POST",
				//contentType: "application/json",
				data: "action=cache_images&do=getlist&domain=" + domain,
				success: function(result) {
					var list = eval(result);
					var curr = 0;

					function regenItem() {
						if (curr >= list.length) {
							jQuery("#cache_images_" + domainmd5).removeAttr("disabled");
							jQuery("#thumb").hide();
							jQuery("#domain_" + domainmd5).hide();
							var donecachingone = "<?php _e( 'Done caching from %1$s', 'cache-images' ); ?>";
							var donecaching = donecachingone.replace("%1$s", domain);
							setMessage(donecaching);
							jQuery("#message").fadeOut(1300);
							return;
						}
						var cachestatusone = "<?php _e( 'Caching %1$s of %2$s', 'cache-images' ); ?>";
						var cachestatustwo = cachestatusone.replace("%1$s", curr+1);
						var cachestatus = cachestatustwo.replace("%2$s", list.length);
						setMessage(cachestatus);

						jQuery.ajax({
							url: ajaxurl,
							type: "POST",
							data: "action=cache_images&do=regen&url=" + list[curr].url + "&postid=" + list[curr].postid,
							success: function(result) {
								jQuery("#thumb").show();
								jQuery("#thumb-img").html(result);

								curr = curr + 1;
								regenItem();
							}
						});
					}

					regenItem();
				},
				error: function(request, status, error) {
					var errormessageone = "<?php _e( 'Error %1$s', 'cache-images' ); ?>";
					var errormessage = errormessageone.replace("%1$s", request.status);
					setMessage(errormessage);
				}
			});
		}
		
		function cacheall(domain,domainmd5) {
			jQuery("#cache_images_" + domainmd5).attr("disabled", true);
			setMessage("<?php _e( 'Reading posts...', 'cache-images' ); ?>");
			jQuery.ajax({
				url: ajaxurl, 
				type: "POST",
				//contentType: "application/json",
				data: "action=cache_images&do=getalllist&domain=" + domain,
				success: function(result) {
					var list = eval(result);
					var curr = 0;

					function regenItem() {
						if (curr >= list.length) {
							jQuery("#cache_images_" + domainmd5).removeAttr("disabled");
							jQuery("#thumb").hide();
							jQuery("#domain_" + domainmd5).hide();
							var donecachingone = "<?php _e( 'Done caching from %1$s', 'cache-images' ); ?>";
							var donecaching = donecachingone.replace("%1$s", domain);
							setMessage(donecaching);
							jQuery("#message").fadeOut(1300);
							return;
						}
						var cachestatusone = "<?php _e( 'Caching %1$s of %2$s', 'cache-images' ); ?>";
						var cachestatustwo = cachestatusone.replace("%1$s", curr+1);
						var cachestatus = cachestatustwo.replace("%2$s", list.length);
						setMessage(cachestatus);

						jQuery.ajax({
							url: ajaxurl,
							type: "POST",
							data: "action=cache_images&do=regen&url=" + list[curr].url + "&postid=" + list[curr].postid,
							success: function(result) {
								jQuery("#thumb").show();
								jQuery("#thumb-img").html(result);

								curr = curr + 1;
								regenItem();
							}
						});
					}

					regenItem();
				},
				error: function(request, status, error) {
					var errormessageone = "<?php _e( 'Error %1$s', 'cache-images' ); ?>";
					var errormessage = errormessageone.replace("%1$s", request.status);
					setMessage(errormessage);
				}
			});
		}
		
		function showexplanation() {
			jQuery("#explain-all-domains").show();
		}
		
		
</script>
<div id="status-message" class="updated fade" style="display:none"></div>
<form action="" method="post">
<p class="submit">
	<input name="step" type="hidden" id="step" value="2">
	<input type="button" onClick="javascript:getdomains();" class="button" name="Submit" value="<?php _e( 'Scan &raquo;', 'cache-images' ); ?>" />
	<input type="button" onClick="javascript:getalldomains();" class="button" name="Submit" style="margin-left: 200px;" value="<?php _e( 'Scan (including linked) &raquo;', 'cache-images' ); ?>" /> (<a onClick="javascript:showexplanation();" href="#"><?php _e( 'what is difference?', 'cache-images' ); ?></a>)
	<div id="explain-all-domains" class="notice" style="display:none"><?php _e( 'Button <strong>Scan</strong> will search only for images that are hotlinked (ie. used like in example <code>&lt;img src="http://example.com/picture.jpg" /&gt;</code>), while button <strong>Scan (including linked)</strong> will search for images that are only linked from this site (ie. ised like in example <code>&lt;a href="http://example.com/picture.jpg"&gt;example&lt;/a&gt;</code>). Use second button with caution!', 'cache-images' ); ?></div>
</p>
<ul>
	<li id="listofdomains"></li>
</ul>
</form>
<div id="message" class="updated fade" style="display:none"></div>
		<div id="thumb" style="display:none"><?php printf( __( 'Last cached picture: %1$s', 'cache-images' ), '<span id="thumb-img"></span>' ); ?></div>
</div>
<?php
}
add_action('admin_menu', 'mm_ci_add_pages');

/**
 * Handle AJAX requests
 */
function cache_images_ajax() {
	global $wpdb;
	$action = $_POST["do"];

	if ($action == "getlist") {
		$domain = $_POST["domain"];
		
		$postid_list = $wpdb->get_results("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_content LIKE ('%<img%') AND post_content LIKE ('%$domain%')");

		foreach ( $postid_list as $v ) {
			$postid = $v->ID;
			$post = $wpdb->get_results("SELECT post_content FROM $wpdb->posts WHERE ID = '$postid'");
			preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post[0]->post_content, $matches);
			foreach ( $matches[1] as $url ) :
				if ( strstr( $url, get_option('siteurl') . '/' . get_option('upload_path') ) || !strstr( $url, $domain) || (($res) && in_multi_array($url, $res)))
					continue; // Already local
					// got tip from http://www.devcomments.com/Plus-signs-in-url-replaced-with-space-in-GET-at49448.htm
				$origurl = $url;
				$url = urlencode($url);
				$res[] = array('url' => $url, 'postid' => $postid, 'origurl' => $origurl);
			endforeach;
		}
		die( json_encode($res) );
	} else if ($action == "getalllist") {
		$domain = $_POST["domain"];
		
		$postid_list = $wpdb->get_results("SELECT DISTINCT ID FROM $wpdb->posts WHERE post_content LIKE ('%$domain%')");

		foreach ( $postid_list as $v ) {
			$postid = $v->ID;
			$post = $wpdb->get_results("SELECT post_content FROM $wpdb->posts WHERE ID = '$postid'");
			preg_match_all('#(http://[^\s]+(?=\.(jpe?g|png|gif)))#i', $post[0]->post_content, $matches);
			foreach ( $matches[1] as $url ) :
				if ( strstr( $post[0]->post_content, $url . '.jpg' ) ) {
					$url = $url . '.jpg';
				} else if ( strstr( $post[0]->post_content, $url . '.jpeg' ) ) {
					$url = $url . '.jpeg';
				} else if ( strstr( $post[0]->post_content, $url . '.png' ) ) {
					$url = $url . '.png';
				} else if ( strstr( $post[0]->post_content, $url . '.gif' ) ) {
					$url = $url . '.gif';
				}
				if ( strstr( $url, get_option('siteurl') . '/' . get_option('upload_path') ) || !strstr( $url, $domain) || (($res) && in_multi_array($url, $res)))
					continue; // Already local
				$origurl = $url;
				$url = urlencode($url);
				$res[] = array('url' => $url, 'postid' => $postid, 'origurl' => $origurl);
			endforeach;
		}
		die( json_encode($res) );
	} else if ($action == "regen") {
		$url = $_POST["url"];
		$postid = $_POST["postid"];
		
		$url = cache_images_cache_image($url, $postid);

		die( $url );
	} else if ($action == "getdomains") {
		$posts = $wpdb->get_results("SELECT post_content FROM $wpdb->posts WHERE post_content LIKE ('%<img%') AND post_status LIKE ('%publish%') OR post_status LIKE ('%draft%')");

		if ( !$posts ) 
			die( __( "No posts with images were found.", "cache-images" ) );

		foreach ($posts as $post) :
			$domains = cache_images_find_images($post->post_content, $domains);
		endforeach;
		
		$local_domain = parse_url( get_option( 'siteurl' ) );

		foreach ($domains as $domain => $num) :
			if ( strstr( $domain,  $local_domain['host'] ) )
				continue; // Already local
			$domain_md5 = md5( $domain );
			$res[] = array('domain' => $domain, 'num' => $num, 'domainmd5' => $domain_md5);
		endforeach;
		
		die( json_encode($res) );
	} else if ($action == "getalldomains") {
		$posts = $wpdb->get_results("SELECT post_content FROM $wpdb->posts WHERE post_content LIKE ('%<a%') AND post_status LIKE ('%publish%') OR post_status LIKE ('%draft%')");

		if ( !$posts ) 
			die( __( "No posts with images were found.", "cache-images" ) );

		foreach ($posts as $post) :
			$domains = cache_images_find_all_images($post->post_content, $domains);
		endforeach;
		
		$local_domain = parse_url( get_option( 'siteurl' ) );

		foreach ($domains as $domain => $num) :
			if ( strstr( $domain,  $local_domain['host'] ) )
				continue; // Already local
			$domain_md5 = md5( $domain );
			$res[] = array('domain' => $domain, 'num' => $num, 'domainmd5' => $domain_md5);
		endforeach;
		
		if ( !$res ) 
			die( __( "No posts with images were found.", "cache-images" ) );
		
		die( json_encode($res) );
	}
}
add_action('wp_ajax_cache_images', 'cache_images_ajax');

/**
 * Find hotlinked external images in provided content
 */
function cache_images_find_images($content, $domains) {
	preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $content, $matches);
	foreach ($matches[1] as $url) :
		$url = parse_url($url);
		$domains[$url['host']]++;
	endforeach;
	
	return $domains;
}

/**
 * Find all external images in provided content
 * Regex by BDuelz on StackOverflow
 * @link http://stackoverflow.com/questions/3371902/php-regex-get-image-from-url/3372785#3372785
 */
function cache_images_find_all_images($content, $domains) {
	preg_match_all('#(http://[^\s]+(?=\.(jpe?g|png|gif)))#i', $content, $matches);
	
	foreach ($matches[1] as $url) :
		$url = parse_url($url);
		$domains[$url['host']]++;
	endforeach;
	
	return $domains;
}

/**
 * Cache image from provided URL
 *
 * Add image to media library and
 * replace all occurences of it
 */
function cache_images_cache_image($url, $postid) {
	global $wpdb;
	$orig_url = $url;
	
	//check if pic is on Blogger
	if (strpos($url, 'blogspot.com') || strpos($url, 'blogger.com') || strpos($url, 'ggpht.com') || strpos($url, 'googleusercontent.com') || strpos($url, 'gstatic.com')) {
		$response = wp_remote_request($url);
		if ( is_wp_error( $response ) )
			return 'error1';
			
		$my_body = wp_remote_retrieve_body($response);
		
		if (strpos($my_body, 'src')) {
			preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $my_body, $matches);
			foreach ( $matches[1] as $url ) :
				$spisak = $url;
			endforeach;
			
			$url = $spisak;
		}
	}
	
	set_time_limit( 300 );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$upload = media_sideload_image($url, $postid);
		
	if ( !is_wp_error($upload) ) {
		preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $upload, $locals);
		foreach ( $locals[1] as $newurl ) :
			$wpdb->query("UPDATE $wpdb->posts SET post_content = REPLACE(post_content, '$orig_url', '$newurl');");
		endforeach;
	}
	
	return $url;
}

/**
 * Cache images on post's saving
 */
function cache_images_save_post($post_ID, $post) {
	global $wpdb;

	$cache_images_settings = get_option( 'cache_images_automatic_caching' );

	if ( $cache_images_settings && $cache_images_settings == 1 ) {
		if ( wp_is_post_autosave($post_ID) || wp_is_post_revision($post_ID) )
			return $post_ID;
		
		$domains = cache_images_find_images($post->post_content, $domains);
		if ( !$domains )
			return $post_ID;

		$local_domain = parse_url( get_option( 'siteurl' ) );

		foreach ($domains as $domain => $num) :
			if ( strstr( $domain,  $local_domain['host'] ) )
				continue; // Already local

			preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post->post_content, $matches);
			foreach ( $matches[1] as $url ) :
				if ( strstr( $url, get_option('siteurl') . '/' . get_option('upload_path') ) || !strstr( $url, $domain) || (($res) && in_multi_array($url, $res)))
					continue; // Already local
				cache_images_cache_image($url, $post_ID);
			endforeach;
		endforeach;
		
		return $post_ID;
	}

	return $post_ID;
}
add_action( 'save_post', 'cache_images_save_post', 10, 2 );

/**
 * Add section & field on Media Settings page
 * @link http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
 * @link http://ottopress.com/2009/wordpress-settings-api-tutorial/
 */
function cache_images_add_settings_field() {
	add_settings_section( 'cache_images_settings_section', __( 'Cache Images', 'cache-images' ), 'cache_images_section_callback', 'media' );
	add_settings_field( 'cache_images_automatic_caching' , __( 'Automatic caching', 'cache-images' ) , 'cache_images_field_settings_form' , 'media' , 'cache_images_settings_section' );
	register_setting( 'media', 'cache_images_automatic_caching' );
}

function cache_images_section_callback() {
}

/**
 * Print field on Media Settings page
 * @link http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
 * @link http://ottopress.com/2009/wordpress-settings-api-tutorial/
 */
function cache_images_field_settings_form() {
	?>
	<label><input name="cache_images_automatic_caching" id="cache_images_automatic_caching" type="checkbox" value="1" 
	<?php checked( '1', get_option( 'cache_images_automatic_caching' ) ); ?> /> <?php _e( 'Automatically cache images on post&#8217;s saving', 'cache-images' ); ?> </label>
<?php
}
add_action( 'admin_init', 'cache_images_add_settings_field' );

/*
to do:
if pic from Blogger has + in it, how to handle it
cache only on page where is found
*/

?>