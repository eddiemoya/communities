<?php
/**
 * @package WP Cache Inspect
 * @author Frank B&uuml;ltge
 * @version 0.8.3
 */
 
/**
Plugin Name: WP Cache Inspect
Plugin URI: http://bueltge.de/wordpress-cache-steuern-plugin/819/
Description: Enables you to inspect how the builtin cache is working.
Author: Frank B&uuml;ltge
Author URI: http://bueltge.de/
Version: 0.8.3
Last Change: 08.01.2010 10:39:31
*/

/**
 * the first version for oöder WordPress was from 
 * Peter Westwood (http://blog.ftwr.co.uk/wordpress/wp-cache-inspect/)
 *
 * follow version was from Frank B&uuml;ltge
 * version 0.7 of this plugin was write with many new functions and
 * classes for WordPress 2.7 and smaller
 */


if ( !function_exists('add_action') ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

// Pre-2.6 compatibility
if ( !defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( !defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );


/**
 * Images/ Icons in base64-encoding
 * @use function wpag_get_resource_url() for display
 */
if ( isset($_GET['resource']) && !empty($_GET['resource']) ) {
	# base64 encoding performed by base64img.php from http://php.holtsmark.no
	$resources = array(
		'wp-cache-management.gif' =>
		'R0lGODlhCwALAMQfAIuKirq6unNzc+/v76GhodLS0s7NzaqpqZ'.
		'ycnP39/ZqamoGBgcHBwaSkpYaGhtjY2LGwscXFxZCPj8rKyri3'.
		'uLW1tbSztK+vr6urq5mYmfj4+JiXl5+en5ybnZWVlf///yH5BA'.
		'EAAB8ALAAAAAALAAsAAAVf4DcYWJZRxfB9heQRAJMUWfFVW2NM'.
		'D8McFBZnwmhwIgzF46MRICAND2Wh0KwEh4CkcfAsEqsFAkMgSB'.
		'aO1UciUEA8CEl65dkAPA60R014GCwQEQYNYB+DGB0cFw0RYCEA'.
		'Ow=='.
		'',
		'wp-cache-management-empty.gif' =>
		'R0lGODlhCwALAMQfAJ2cnfz8/IyLi83MzJWVldHR0cPDw7Kxsa'.
		'alpra2t6qqqvDw8Ht7e7m5uaKiopGRka6urpiWl9bW1o+Pj4+Q'.
		'kMfHyNnZ2ZiXmKCfn5KSktPT05aWlvf396CgoJqamv///yH5BA'.
		'EAAB8ALAAAAAALAAsAAAVi4LcMEIYdxfJ9xTNl3kUQl/Yl3tZ1'.
		'XkYgjY8mM6ggFJqCRyISEDyRC2Bz4Xw4AoShkWgoNtaAp3OAHA'.
		'4YQGD1YFwEggmDsvpkTIDLQwCoOyQDCQkaFghrHwMOCgAAEA4G'.
		'ayEAOw=='.
		'');
	
	if ( array_key_exists($_GET['resource'], $resources) ) {

		$content = base64_decode($resources[ $_GET['resource'] ]);

		$lastMod = filemtime(__FILE__);
		$client = ( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false );
		// Checking if the client is validating his cache and if it is current.
		if ( isset($client) && (strtotime($client) == $lastMod) ) {
			// Client's cache IS current, so we just respond '304 Not Modified'.
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastMod).' GMT', true, 304);
			exit;
		} else {
			// Image not cached or cache outdated, we respond '200 OK' and output the image.
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastMod).' GMT', true, 200);
			header('Content-Length: '.strlen($content));
			header('Content-Type: image/' . substr(strrchr($_GET['resource'], '.'), 1) );
			echo $content;
			exit;
		}
	}
}


if ( !class_exists('wp_cache_inspect') ) {
	class wp_cache_inspect {
		
		// Constructor
		function wp_cache_inspect() {
			global $wp_version;
			
			// set default options
			$this->options_array = array('wpci_settings_footer' => '1',
																	 'wpci_settings_fav' => '',
																	 'wpci_settings_data' => '',
																	 'wpci_settings_front' => '',
																	 'wpci_settings_front_ajax' => ''
																	);
			
			// add class WPoption for options in WP
			$GLOBALS['WPoption'] = new WPoption(
																		 'wpci_settings',
																		 $this->options_array
																		 );
																		 
			add_action( 'init', array(&$this,'textdomain') );
			
			if ( is_admin() ) {				
				if ( version_compare( $wp_version, '2.6.999', '>' ) && file_exists(ABSPATH . '/wp-admin/admin-ajax.php') && (basename($_SERVER['QUERY_STRING']) == 'page=wp-cache-inspect.php') ) {
					wp_enqueue_script( 'wp_cache_inspect_plugin_win_page',  plugins_url( $path = 'wp-cache-inspect/js/page.php' ), array('jquery') );
				} elseif ( version_compare( $wp_version, '2.6.999', '<' ) && file_exists(ABSPATH . '/wp-admin/admin-ajax.php') && (basename($_SERVER['QUERY_STRING']) == 'page=wp-cache-inspect.php') ) {
					wp_enqueue_script( 'wp_cache_inspect_plugin_win_page',  plugins_url( $path = 'wp-cache-inspect/js/page_s27.php' ), array('jquery') );
				}
				add_action( 'wp_ajax_set_toggle_status', array($this, 'set_toggle_status') );
				
				if ( function_exists('register_activation_hook') )
					register_activation_hook(__FILE__, array(&$this,'activate') );
				if ( function_exists('register_uninstall_hook') )
					register_uninstall_hook(__FILE__, array(&$this,'deactivate') );
				if ( function_exists('register_deactivation_hook') )
					register_deactivation_hook(__FILE__, array(&$this,'deactivate') );
				
				add_action('admin_menu', array(&$this,'admin_menu'));
				
				if ( (basename($_SERVER['QUERY_STRING']) == 'page=wp-cache-inspect.php') && ($GLOBALS['WPoption']->get_option('wpci_settings_data') == '1') && function_exists('wp_enqueue_style') ) {
					wp_enqueue_style( 'wp_cache_inspect_css', plugins_url( $path = 'wp-cache-inspect/css/style.css' ) );
				}
				
				if ( ( $GLOBALS['WPoption']->get_option('wpci_settings_footer') || $GLOBALS['WPoption']->get_option('wpci_settings_fav') ) == '1' )
					add_action( 'admin_init', array(&$this, 'add_ajax_cache') );
				if ( $GLOBALS['WPoption']->get_option('wpci_settings_footer') == '1' )
					add_action( 'in_admin_footer', array(&$this, 'add_to_admin_footer') );
				if ( $GLOBALS['WPoption']->get_option('wpci_settings_fav') == '1' )
					add_filter('favorite_actions', array(&$this, 'add_to_fav') );
				add_action( 'wp_ajax_cache_flush', array(&$this, 'ajax_cache_flush') );
				add_action( 'in_admin_footer', array(&$this, 'admin_footer') );
		
				add_action('publish_page', 'wp_cache_flush');
				add_action('publish_post', 'wp_cache_flush');
				
				add_action('save_post', 'wp_cache_flush');
				
				add_action('edit_post', 'wp_cache_flush');
				add_action('edit_comment', 'wp_cache_flush');
				
				add_action('wp_insert_post', 'wp_cache_flush');
				
				add_action('delete_comment', 'wp_cache_flush');
				add_action('delete_post', 'wp_cache_flush');
				add_action('deleted_post', 'wp_cache_flush');
				//add_action('wp_delete_post_revision', 'wp_cache_flush');
				
				add_action('comment_post', 'wp_cache_flush'); 
				
				add_action('private_to_published', 'wp_cache_flush');
			}
			
			if ( $GLOBALS['WPoption']->get_option('wpci_settings_front_ajax') == '1' ) {
				add_action('init', array(&$this, 'add_ajax_cache') );
				add_action('wp_footer', array(&$this,'wp_footer_ajax') );
			}
			
			if ( $GLOBALS['WPoption']->get_option('wpci_settings_front') == '1' ) {
				add_action('wp_head', array(&$this,'wp_head') );
				add_action('wp_footer', array(&$this,'wp_footer') );
			}
			
			/**
			 * Retrieve the url to the plugins directory.
			 *
			 * @package WordPress
			 * @since 2.6.0
			 *
			 * @param string $path Optional. Path relative to the plugins url.
			 * @return string Plugins url link with optional path appended.
			 */
			if ( !function_exists('plugins_url') ) {
				function plugins_url($path = '') {
					if ( function_exists( 'is_ssl' ) ) {
						$scheme = ( is_ssl() ? 'https' : 'http' );
					} else {
						$scheme = ( 'http' );
					}
					$url = WP_PLUGIN_URL;
					if ( 0 === strpos($url, 'http') ) {
						if ( is_ssl() )
							$url = str_replace( 'http://', "{$scheme}://", $url );
					}
				
					if ( !empty($path) && is_string($path) && strpos($path, '..') === false )
						$url .= '/' . ltrim($path, '/');
				
					return $url;
				}
			}
		}
		
		
		// active for multilanguage
		function textdomain() {
		
			if (function_exists('load_plugin_textdomain')) {
				if ( !defined('WP_PLUGIN_DIR') ) {
					load_plugin_textdomain('wp-cache-inspect', str_replace( ABSPATH, '', dirname(__FILE__) ) . '/languages');
				} else {
					load_plugin_textdomain('wp-cache-inspect', false, dirname( plugin_basename(__FILE__) ) . '/languages');
				}
			}
		}
		
		
		function activate() {
			global $wp_roles;
			
			$wp_roles->add_cap('administrator','manage_cache');
			//Workaround this - http://trac.wordpress.org/ticket/2387
			//wp_cache_close();
		}
		
		
		function deactivate() {
			global $wp_roles;
			
			$GLOBALS['WPoption']->delete_option();
			
			$wp_roles->remove_cap('administrator','manage_cache');
			//Workaround this - http://trac.wordpress.org/ticket/2387
			wp_cache_close();
		}
		
		
		/**
		 * Add option for tabboxes via ajax
		 */
		function set_toggle_status() {
			if ( current_user_can('manage_options') && $_POST['set_toggle_id'] ) {
				
				$id     = $_POST['set_toggle_id'];
				$status = $_POST['set_toggle_status'];
					
				$GLOBALS['WPoption']->update_option($id, $status);
			}
		}
		
		
		/**
		 * @version WP 2.7
		 * Add action link(s) to plugins page
		 */
		function filter_plugin_actions_new($links) {
		
			$settings_link = '<a href="options-general.php?page=wp-cache-inspect.php">' . __('Settings') . '</a>';
			array_unshift( $links, $settings_link );
			
			return $links;
		}
		
		
		/**
		 * Display Images/ Icons in base64-encoding
		 * @return $resourceID
		 */
		function get_resource_url($resourceID) {
		
			return trailingslashit( get_bloginfo('url') ) . '?resource=' . $resourceID;
		}
		
		
		/**
		 * settings in plugin-admin-page
		 */
		function admin_menu() {
			global $wp_version;
			
			if ( function_exists('add_management_page') && current_user_can('manage_options') ) {
			
				$menutitle = '';
				if ( version_compare( $wp_version, '2.6.999', '>' ) ) {
					$menutitle = '<img src="' . $this->get_resource_url('wp-cache-management.gif') . '" alt="" />' . ' ';
				}
				$menutitle .= __('Cache', 'wp-cache-inspect');
				
				if ( version_compare( $wp_version, '2.6.999', '>' ) && function_exists('add_contextual_help') ) {
					$hook = add_submenu_page( 'options-general.php',__('Cache Management', 'wp-cache-inspect'), $menutitle, 'manage_cache', basename(__FILE__), array(&$this, 'display_page') );
					add_contextual_help( $hook, __('<a href="http://wordpress.org/extend/plugins/wp-cache-inspect/">Documentation</a>', 'wp-cache-inspect') );
					//add_filter( 'contextual_help', array(&$this, 'contextual_help') );
				} else {
					add_submenu_page( 'options-general.php',__('Cache Management', 'wp-cache-inspect'), $menutitle, 'manage_cache', basename(__FILE__), array(&$this, 'display_page') );
				}
				
				$plugin = plugin_basename(__FILE__); 
				add_filter( 'plugin_action_links_' . $plugin, array(&$this, 'filter_plugin_actions_new') );
			}
		}
		
		
		/**
		 * credit in wp-footer
		 */
		function admin_footer() {
			if( basename($_SERVER['QUERY_STRING']) == 'page=wp-cache-inspect.php') {
				$plugin_data = get_plugin_data( __FILE__ );
				printf('%1$s plugin | ' . __('Version') . ' <a href="http://bueltge.de/wordpress-cache-steuern-plugin/819/#historie" title="' . __('History', 'wp-cache-inspect') . '">%2$s</a> | ' . __('Author') . ' %3$s<br />', $plugin_data['Title'], $plugin_data['Version'], $plugin_data['Author']);
			}
		}
				
		
		/**
		 * css for cache-data in backend
		 */
		function wp_admin_head() {
			if ( current_user_can('manage_cache') ) {
				$return = '<link rel="stylesheet" href="' . plugins_url($path = 'wp-cache-inspect/css/style.css') . '" type="text/css" media="screen" />';
			
				echo $return;
			}
		}
		
		
		/**
		 * infos in frontend, add css to head
		 */
		function wp_head() {
			if ( current_user_can('manage_cache') ) {
				$return = '<link rel="stylesheet" href="' . plugins_url($path = 'wp-cache-inspect/css/style-frontend.css') . '" type="text/css" media="screen" />';
			
				echo $return;
			}
		}
		
		
		/**
		 * infos in frontend, add to footer
		 */
		function wp_footer() {
			if ( current_user_can('manage_cache') ) {
				global $wp_object_cache, $wpdb;
				
				echo '<p id="cachestats" class="transparent">';
				if ( version_compare( $wp_version, '2.6.999', '>' ) ) {
					echo '<strong>Cache Hits:</strong> ' . $wp_object_cache->cache_hits . '<br/>';
				} else {
					echo '<strong>Warm Cache Hits:</strong> ' . $wp_object_cache->warm_cache_hits . '<br />';
					echo '<strong>Cold Cache Hits:</strong> ' . $wp_object_cache->cold_cache_hits . '<br/>';
				}
				echo '<strong>Cache Misses:</strong> ' . $wp_object_cache->cache_misses . '<br />';
				echo '<strong>DB Queries:</strong> ' . $wpdb->num_queries . '<br />';
				echo '<br/>';
				echo '<strong>Loaded data:</strong><br />';
				$n = 0;
				foreach ($wp_object_cache->cache as $group => $cache) {
					$n++;
					echo '<strong>' . $group . '</strong> - ' . count($cache)  . ' items <br />';
					if ($n > 15) {
						echo '...<br />';
						break;
					}
				}
				echo '</p>';
			}
		}
		
		
		/**
		 * infos in frontend, add to footer
		 */
		function wp_footer_ajax() {
			if ( current_user_can('manage_cache') ) {
				echo '<p id="cachelink" class="transparent">';
				echo '<a id="cache_flush_footer" href="#">' . __('Cache flush', 'wp-cache-inspect') . '</a>';
				echo '</p>';
			}
		}
		
		
		/**
		 * load script for ajax-call
		 */
		function add_ajax_cache() {
			if ( current_user_can('manage_cache') ) {
				wp_enqueue_script( 'wp_cache_plugin_ajax_cache',  plugins_url($path = 'wp-cache-inspect/js/ajax_cache.php'), array('jquery') );
			}
		}
	
	
		/**
		 * link to footer for flush cache via ajax
		 */
		function add_to_admin_footer() {
			if ( current_user_can('manage_cache') ) {
				printf('<a id="cache_flush_footer" href="#">' . __('Cache flush', 'wp-cache-inspect') . '</a> | ');
			}
		}
		
		
		/**
		 * link in fav; WP > 2.7
		 */
		function add_to_fav($actions) {
			if ( current_user_can('manage_cache') ) {
				// add quick link to our favorite plugin
				$actions['#'] = array( '<span id="cache_flush_fav">' . __('Cache flush', 'wp-cache-inspect') . '</span>', 'manage_options');
			
				return $actions;
			}
		}
		
		
		/**
		 * Ajax call for cache_flush
		 */
		function ajax_cache_flush() {
			if ( current_user_can('manage_cache') ) {
				wp_cache_flush();
				_e('Removes all cache items.', 'wp-cache-inspect');
				die('');
			} else {
				_e('Cache not removed - you don&lsquo;t have the privilidges to do this!.', 'wp-cache-inspect');
				die('');
			}
		}
		
		
		/**
		 * tree for array
		 */
		function get_as_ul_tree($arr, $root_name = '', $unserialized_string = false) {
			$output = '';
			
			if ( !is_object($arr) && !is_array($arr) )
				return $output;
			
			if ($root_name) {
				$output .= '<ul class="root' . ($unserialized_string ? ' unserialized' : '') . '">' . "\n";
				if ( is_object($arr) ) {
					$output .= '<li class="vt-object"><span class="' . ($unserialized_string ? 'unserialized' : 'key') . '">' . $root_name . '</span>';
					if (!$unserialized_string) $output .= '<br />' . "\n";
					$output .= '<small><em>type</em>: object (' . get_class($arr) . ')</small><br/><small><em>count</em>: ' . count( get_object_vars($arr) ) . '</small><ul>'; 
				} else {
					$output .= '<li class="vt-array"><span class="' . ($unserialized_string ? 'unserialized' : 'key') . '">' . $root_name . '</span>';
					if (!$unserialized_string) $output .= '<br />' . "\n";
					$output .= '<small><em>type</em>: array</small><br/><small><em>count</em>: ' . count($arr) . '</small><ul>'; 
				}
			}
		
			foreach($arr as $key => $val) {
				if (is_numeric($key)) $key = "[".$key."]"; 
				$vt = gettype($val);
				switch ($vt) {
					case "object":
						$output .= "<li class=\"vt-$vt\"><span class=\"key\">".htmlspecialchars($key)."</span>";
						$output .= "<br/><small><em>type</em>: $vt (".get_class($val).") | <em>count</em>: ".count($val)."</small>"; 
						$output .= "<ul>";
						$output .= $this->get_as_ul_tree($val);
						$output .= "</ul></li>";
					break;
					case "array":
						$output .= "<li class=\"vt-$vt\"><span class=\"key\">".htmlspecialchars($key)."</span>";
						$output .= "<br/><small><em>type</em>: $vt | <em>count</em>: ".count($val)."</small>"; 
						$output .= "<ul>";
						$output .= $this->get_as_ul_tree($val);
						$output .= "</ul></li>";
					break;
					case "boolean":
						$output .= "<li class=\"vt-$vt\"><span class=\"key\">".htmlspecialchars($key)."</span>";
						$output .= "<br/><small><em>type</em>: $vt</small><br/><small><em>value</em>: </small><span class=\"value\">".($val?"true":"false")."</span></li>";
					break;
					case "integer":
					case "double":
					case "float":
						$output .= "<li class=\"vt-$vt\"><span class=\"key\">".htmlspecialchars($key)."</span>";
						$output .= "<br/><small><em>type</em>: $vt</small><br/><small><em>value</em>: </small><span class=\"value\">$val</span></li>";
					break;
					case "string":
						$obj = @unserialize($val);
						$is_serialized = ($obj !== false && preg_match("/^(O:|a:)/", $val));
						$output .= "<li class=\"vt-$vt\"><span class=\"key\">".htmlspecialchars($key)."</span>";
						$output .= "<br/><small><em>type</em>: $vt | <em>size</em>: ".strlen($val)." | <em>serialized</em>: ".($is_serialized !== false?"true":"false")."</small><br/>";
						if ($is_serialized) {
							$output .= $this->get_as_ul_tree($obj, "<small><em>value</em>:</small> <span class=\"value\">[unserialized]</span>", true);
						}
						else {
							$output .= '<small><em>value</em>: </small><span class="value">' . htmlspecialchars($val) . '</span>';
						}
						$output .= '</li>';
					break;
					default: //what the hell is this ?
						$output .= "<li class=\"vt-$vt\"><span class=\"key\">".htmlspecialchars($key)."</span>";
						$output .= "<br/><small><em>type</em>: $vt</small><br/><small><em>value</em>:</small><span class=\"value\">".@htmlspecialchars($val)."</span></li>";
					break;
				}
			}
			
			if ($root_name) $output .= "\t" . '</ul>' . "\n\t" . '</li>' . "\n" . '</ul>' . "\n";
			
			return $output;
		}	
		
		
		/**
		 * display options page in backend
		 */
		function display_page() {
			global $wp_object_cache, $wpdb, $wp_version;
			
			if ( isset($_POST['action']) && 'clearcache' == $_POST['action'] ) {
				check_admin_referer('wpci_cache_form');
				if ( current_user_can('manage_cache') ) {
					wp_cache_flush();
					?>
					<div id="message" class="updated fade"><p><img src="<?php echo $this->get_resource_url('wp-cache-management-empty.gif') ?>" alt="" /> <?php _e('Removes all cache items.', 'wp-cache-inspect'); ?></p></div>
					<?php
				} else {
					?>
					<div id="message" class="error"><p><?php _e('Cache not removed - you don&lsquo;t have the privilidges to do this!.', 'wp-cache-inspect'); ?></p></div>
					<?php
				}
			}
			
			if ( isset($_POST['action']) && 'update' == $_POST['action'] ) {
				check_admin_referer('wpci_settings_form');
				if ( current_user_can('manage_options') ) {
				
					// init value
					$update_options = array();
					
					// set value
					foreach ($this->options_array as $key => $value) {
						$update_options[$key] = stripslashes_deep(trim($_POST[$key]));
					}
					
					// save value
					if ($update_options) {
						$GLOBALS['WPoption']->update_option($update_options);
					}
					
					?>
					<div id="message" class="updated fade"><p><?php _e('Options update.', 'wp-cache-inspect'); ?></p></div>
					<?php
				} else {
					?>
					<div id="message" class="error"><p><?php _e('Options not update - you don&lsquo;t have the privilidges to do this!', 'wp-cache-inspect'); ?></p></div>
					<?php
				}
			}
			
			if ( isset($_POST['action']) && 'deinstall' == $_POST['action'] ) {
				check_admin_referer('wpci_cache_form');
				if ( current_user_can('manage_options') && isset($_POST['deinstall_yes']) ) {
					$this->deactivate();
					?>
					<div id="message" class="updated fade"><p><?php _e('All entries in the database was cleared.', 'wp-cache-inspect'); ?></p></div>
					<?php
				} else {
					?>
					<div id="message" class="error"><p><?php _e('Entries was not delleted - check the checkbox or you don&lsquo;t have the privilidges to do this!', 'wp-cache-inspect'); ?></p></div>
					<?php
				}
			}
			
			$wpci_settings_footer     = $GLOBALS['WPoption']->get_option('wpci_settings_footer');
			$wpci_settings_fav        = $GLOBALS['WPoption']->get_option('wpci_settings_fav');
			$wpci_settings_data       = $GLOBALS['WPoption']->get_option('wpci_settings_data');
			$wpci_settings_front      = $GLOBALS['WPoption']->get_option('wpci_settings_front');
			$wpci_settings_front_ajax = $GLOBALS['WPoption']->get_option('wpci_settings_front_ajax');
			$wpci_settings_win_config = $GLOBALS['WPoption']->get_option('wpci_settings_win_config');
			$wpci_settings_win_man    = $GLOBALS['WPoption']->get_option('wpci_settings_win_man');
			$wpci_settings_win_opt    = $GLOBALS['WPoption']->get_option('wpci_settings_win_opt');
			$wpci_settings_win_data   = $GLOBALS['WPoption']->get_option('wpci_settings_win_data');
			$wpci_settings_win_about  = $GLOBALS['WPoption']->get_option('wpci_settings_win_about');
			
		?>
		<div class="wrap">
			<h2><?php _e('WordPress Cache Management', 'wp-cache-inspect'); ?></h2>
			<br class="clear" />
			
			<div id="poststuff" class="ui-sortable">
				<div id="wpci_settings_win_config" class="postbox <?php echo $wpci_settings_win_config ?>" >
					<h3><?php _e('Configuration', 'wp-cache-inspect'); ?></h3>
					<div class="inside">
			
						<form name="wpci_config-update" method="post" action="">
							<?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('wpci_settings_form'); ?>
							
							<table class="form-table">
							
							<?php if ( version_compare( $wp_version, '2.4.999', '>' ) ) { ?>
								<tr valign="top">
									<th scope="row">
										<label for="wpci_settings_footer"><?php _e('Link in Footer', 'wp-cache-inspect'); ?></label>
									</th>
									<td>
										<input type="checkbox" name="wpci_settings_footer" id="wpci_settings_footer" value="1" <?php if ( $wpci_settings_footer == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Activate to display a link in your backend footer for clearing cache of all pages.', 'wp-cache-inspect'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ( version_compare( $wp_version, '2.6.999', '>' ) ) { ?>
								<tr valign="top">
									<th scope="row">
										<label for="wpci_settings_fav"><?php _e('Link in Favorits', 'wp-cache-inspect'); ?></label>
									</th>
									<td>
										<input type="checkbox" name="wpci_settings_fav" id="wpci_settings_fav" value="1" <?php if ( $wpci_settings_fav == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Activate for show a link to clear cache von all pages in backend in favorite actions.', 'wp-cache-inspect'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ( function_exists('wp_enqueue_style') ) { ?>
								<tr valign="top">
									<th scope="row">
										<label for="wpci_settings_front"><?php _e('Data in Frontend', 'wp-cache-inspect'); ?></label>
									</th>
									<td>
										<input type="checkbox" name="wpci_settings_front" id="wpci_settings_front" value="1" <?php if ( $wpci_settings_front == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Activate to show a selection of data about the cache in your blog frontend. Only for logged in admin viewable.', 'wp-cache-inspect'); ?>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="wpci_settings_front_ajax"><?php _e('Link in Frontend', 'wp-cache-inspect'); ?></label>
									</th>
									<td>
										<input type="checkbox" name="wpci_settings_front_ajax" id="wpci_settings_front_ajax" value="1" <?php if ( $wpci_settings_front_ajax == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Activate for show a link to clear cache in your blog frontend. Only for logged in admin viewable.', 'wp-cache-inspect'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<tr valign="top">
									<th scope="row">
										<label for="wpci_settings_data"><?php _e('Cache Data', 'wp-cache-inspect'); ?></label>
									</th>
									<td>
										<input type="checkbox" name="wpci_settings_data" id="wpci_settings_data" value="1" <?php if ( $wpci_settings_data == '1') { echo "checked='checked'"; } ?> />
										<?php _e('Activate to display all datas of the cache.', 'wp-cache-inspect'); ?>
									</td>
								</tr>
							</table>
							
							<p class="submit">
								<input type="hidden" name="action" value="update" />
								<input type="submit" name="Submit" value="<?php _e('Save Changes', 'wp-cache-inspect'); ?> &raquo;" />
							</p>
						</form>

					</div>
				</div>
			</div>
			
			<div id="poststuff" class="ui-sortable">
				<div id="wpci_settings_win_man" class="postbox <?php echo $wpci_settings_win_man ?>" >
					<h3><?php _e('Management', 'wp-cache-inspect'); ?></h3>
					<div class="inside">
						
						<table class="form-table">
							<tr valign="top">
								<th scope="row">
									<?php _e('Info', 'wp-cache-inspect'); ?>
								</th>
								<td>
									<?php _e('Use this button to clear standard WordPress Cache or add same function in the footer on in the favorite actions (only WP 2.7 and greater).', 'wp-cache-inspect'); ?>
								</td>
							</tr>
						</table>
						
						<form name="clearcache" method="post" action="">
							<?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('wpci_cache_form'); ?>
							<p class="submit">
								<input type="hidden" name="action" value="clearcache" />
								<input type="submit" value="<?php _e('Removes all cache items.', 'wp-cache-inspect'); ?> &raquo;" />
							</p>
						</form>
						
					</div>
				</div>
			</div>
			
			<?php if ( $wpci_settings_data == '1' ) { ?>
			<div id="poststuff" class="ui-sortable">
				<div id="wpci_settings_win_data" class="postbox <?php echo $wpci_settings_win_data ?>" >
					<h3><?php _e('Cache Data', 'wp-cache-inspect'); ?></h3>
					<div class="inside">
					
						<div>
							<?php echo $this->get_as_ul_tree($wp_object_cache, __('WordPress Object Cache', 'wp-cache-inspect') ); ?>
						</div>
					
					</div>
				</div>
			</div>
			<?php } ?>
	
			<div id="poststuff" class="ui-sortable">
				<div id="wpci_settings_win_opt" class="postbox <?php echo $wpci_settings_win_opt ?>" >
					<h3 id="uninstall"><?php _e('Clear Options', 'wp-cache-inspect') ?></h3>
					<div class="inside">
						
						<p><?php _e('Click this button to delete settings of this plugin. Deactivating WordPress Cache Management plugin remove any data that may have been created, such as the cache options.', 'wp-cache-inspect'); ?></p>
						<form name="deinstall_options" method="post" action="">
							<?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('wpci_cache_form'); ?>
							<p id="submitbutton">
								<input type="hidden" name="action" value="deinstall" />
								<input type="submit" value="<?php _e('Delete Options', 'wp-cache-inspect'); ?> &raquo;" class="button-secondary" /> 
								<input type="checkbox" name="deinstall_yes" />
							</p>
						</form>
	
					</div>
				</div>
			</div>
			
			<div id="poststuff" class="ui-sortable">
				<div id="wpci_settings_win_about" class="postbox <?php echo $wpci_settings_win_about ?>" >
					<h3><?php _e('About the plugin', 'wp-cache-inspect') ?></h3>
					<div class="inside">
					
						<p><?php _e('Further information: Visit the <a href="http://bueltge.de/wordpress-cache-steuern-plugin/819/">plugin homepage</a> for further information or to grab the latest version of this plugin.', 'wp-cache-inspect'); ?><br />&copy; Copyright 2008 - <?php echo date("Y"); ?> <a href="http://bueltge.de">Frank B&uuml;ltge</a> | <?php _e('You want to thank me? Visit my <a href="http://bueltge.de/wunschliste/">wishlist</a>.', 'wp-cache-inspect'); ?></p>
						
					</div>
				</div>
			</div>
			
		</div>
		<?php
		}
	}
}


if ( !class_exists('WPoption') ) {
	require_once('inc/WPoption.php');
}


/* Initialise outselves */
if ( class_exists('wp_cache_inspect') && class_exists('WPoption') && function_exists('is_admin') ) {
	$wp_cache_injector = new wp_cache_inspect();
}


if ( isset($wp_cache_injector) && function_exists( 'add_action' ) ) {
	add_action( 'WPCacheInjector',  array(&$swp_injector, 'init') );
}
?>