<?php
/*
Plugin Name: Google sitemap plugin
Plugin URI:  http://bestwebsoft.com/plugin/
Description: Plugin to add google sitemap file in google webmaster tools account.
Author: BestWebSoft
Version: 1.08
Author URI: http://bestwebsoft.com/
License: GPLv2 or later
*/

/*  © Copyright 2011  BestWebSoft  ( admin@bestwebsoft.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//============================================ Function for adding style ====================
if( ! function_exists( 'gglstmp_add_my_stylesheet' ) ) {
	function gglstmp_add_my_stylesheet() {
		wp_register_style( 'google-sitemap-StyleSheets', plugins_url( 'css/stylesheet.css', __FILE__ ) );
		wp_enqueue_style( 'google-sitemap-StyleSheets' );
	}
}

//============================================ Function for adding page in admin menu ====================
if( ! function_exists( 'bws_add_menu_render' ) ) {
	function bws_add_menu_render() {
		global $title;
		$active_plugins = get_option('active_plugins');
		$all_plugins		= get_plugins();

		$array_activate = array();
		$array_install	= array();
		$array_recomend = array();
		$count_activate = $count_install = $count_recomend = 0;
		$array_plugins	= array(
			array( 'captcha\/captcha.php', 'Captcha', 'http://wordpress.org/extend/plugins/captcha/', 'http://bestwebsoft.com/plugin/captcha-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Captcha+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=captcha.php' ), 
			array( 'contact-form-plugin\/contact_form.php', 'Contact Form', 'http://wordpress.org/extend/plugins/contact-form-plugin/', 'http://bestwebsoft.com/plugin/contact-form/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Contact+Form+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=contact_form.php' ), 
			array( 'facebook-button-plugin\/facebook-button-plugin.php', 'Facebook Like Button Plugin', 'http://wordpress.org/extend/plugins/facebook-button-plugin/', 'http://bestwebsoft.com/plugin/facebook-like-button-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Facebook+Like+Button+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=facebook-button-plugin.php' ), 
			array( 'twitter-plugin\/twitter.php', 'Twitter Plugin', 'http://wordpress.org/extend/plugins/twitter-plugin/', 'http://bestwebsoft.com/plugin/twitter-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Twitter+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=twitter.php' ), 
			array( 'portfolio\/portfolio.php', 'Portfolio', 'http://wordpress.org/extend/plugins/portfolio/', 'http://bestwebsoft.com/plugin/portfolio-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Portfolio+bestwebsoft&plugin-search-input=Search+Plugins', '' ),
			array( 'gallery-plugin\/gallery-plugin.php', 'Gallery', 'http://wordpress.org/extend/plugins/gallery-plugin/', 'http://bestwebsoft.com/plugin/gallery-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Gallery+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', '' ),
			array( 'adsense-plugin\/adsense-plugin.php', 'Google AdSense Plugin', 'http://wordpress.org/extend/plugins/adsense-plugin/', 'http://bestwebsoft.com/plugin/google-adsense-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Adsense+Plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=adsense-plugin.php' ),
			array( 'custom-search-plugin\/custom-search-plugin.php', 'Custom Search Plugin', 'http://wordpress.org/extend/plugins/custom-search-plugin/', 'http://bestwebsoft.com/plugin/custom-search-plugin/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Custom+Search+plugin+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=custom_search.php' ),
			array( 'quotes_and_tips\/quotes-and-tips.php', 'Quotes and Tips', 'http://wordpress.org/extend/plugins/quotes-and-tips/', 'http://bestwebsoft.com/plugin/quotes-and-tips/', '/wp-admin/plugin-install.php?tab=search&type=term&s=Quotes+and+Tips+bestwebsoft&plugin-search-input=Search+Plugins', 'admin.php?page=quotes-and-tips.php' )
		);
		foreach($array_plugins as $plugins) {
			if( 0 < count( preg_grep( "/".$plugins[0]."/", $active_plugins ) ) ) {
				$array_activate[$count_activate]['title'] = $plugins[1];
				$array_activate[$count_activate]['link']	= $plugins[2];
				$array_activate[$count_activate]['href']	= $plugins[3];
				$array_activate[$count_activate]['url']	= $plugins[5];
				$count_activate++;
			}
			else if( array_key_exists(str_replace("\\", "", $plugins[0]), $all_plugins) ) {
				$array_install[$count_install]['title'] = $plugins[1];
				$array_install[$count_install]['link']	= $plugins[2];
				$array_install[$count_install]['href']	= $plugins[3];
				$count_install++;
			}
			else {
				$array_recomend[$count_recomend]['title'] = $plugins[1];
				$array_recomend[$count_recomend]['link']	= $plugins[2];
				$array_recomend[$count_recomend]['href']	= $plugins[3];
				$array_recomend[$count_recomend]['slug']	= $plugins[4];
				$count_recomend++;
			}
		}
		?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2><?php echo $title;?></h2>
			<?php if( 0 < $count_activate ) { ?>
			<div>
				<h3><?php _e( 'Activated plugins', 'sitemap' ); ?></h3>
				<?php foreach( $array_activate as $activate_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $activate_plugin['title']; ?></div> <p><a href="<?php echo $activate_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'sitemap'); ?></a> <a href="<?php echo $activate_plugin['url']; ?>"><?php echo __( "Settings", 'sitemap'); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_install ) { ?>
			<div>
				<h3><?php _e( 'Installed plugins', 'sitemap' ); ?></h3>
				<?php foreach($array_install as $install_plugin) { ?>
				<div style="float:left; width:200px;"><?php echo $install_plugin['title']; ?></div> <p><a href="<?php echo $install_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'sitemap'); ?></a></p>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if( 0 < $count_recomend ) { ?>
			<div>
				<h3><?php _e( 'Recommended plugins', 'sitemap' ); ?></h3>
				<?php foreach( $array_recomend as $recomend_plugin ) { ?>
				<div style="float:left; width:200px;"><?php echo $recomend_plugin['title']; ?></div> <p><a href="<?php echo $recomend_plugin['link']; ?>" target="_blank"><?php echo __( "Read more", 'sitemap'); ?></a> <a href="<?php echo $recomend_plugin['href']; ?>" target="_blank"><?php echo __( "Download", 'sitemap'); ?></a> <a class="install-now" href="<?php echo get_bloginfo( "url" ) . $recomend_plugin['slug']; ?>" title="<?php esc_attr( sprintf( __( 'Install %s' ), $recomend_plugin['title'] ) ) ?>" target="_blank"><?php echo __( 'Install now from wordpress.org', 'sitemap' ) ?></a></p>
				<?php } ?>
				<span style="color: rgb(136, 136, 136); font-size: 10px;"><?php _e( 'If you have any questions, please contact us via plugin@bestwebsoft.com or fill in our contact form on our site', 'sitemap' ); ?> <a href="http://bestwebsoft.com/contact/">http://bestwebsoft.com/contact/</a></span>
			</div>
			<?php } ?>
		</div>
		<?php
	}
}

//============================================ Function for adding menu and submenu ====================
if( ! function_exists( 'gglstmp_add_pages' ) ) {
	function gglstmp_add_pages() {
		add_menu_page( __( 'BWS Plugins', 'sitemap' ), __( 'BWS Plugins', 'sitemap' ), 'manage_options', 'bws_plugins', 'bws_add_menu_render', WP_CONTENT_URL."/plugins/google-sitemap-plugin/images/px.png", 1001); 
		add_submenu_page( 'bws_plugins', __( 'Google Sitemap Options', 'sitemap' ), __( 'Google Sitemap', 'sitemap' ), 'manage_options', "google-sitemap-plugin.php", 'gglstmp_settings_page');
		
		global $url_home;
		global $url;
		global $url_send;
		global $url_send_sitemap;
		$url_home = home_url();
		$url = urlencode( $url_home . "/" );
		$url_send = "https://www.google.com/webmasters/tools/feeds/sites/";
		$url_send_sitemap = "https://www.google.com/webmasters/tools/feeds/";
	}
}

//============================================ Function for creating sitemap file ====================
if( ! function_exists( 'gglstmp_sitemapcreate' ) ) {
	function gglstmp_sitemapcreate() {
		global $wpdb, $gglstmp_settings; 
		$str = "";
		foreach( $gglstmp_settings as $val ) {
			if( $str != "")
				$str .= ", ";
			$str .= "'".$val."'";
		}
		$loc = $wpdb->get_results( "SELECT ID, post_modified, post_status, post_type, ping_status FROM $wpdb->posts WHERE post_status = 'publish' AND post_type IN (" . $str . ")" );
		$xml = new DomDocument('1.0','utf-8');
		$xml_stylesheet_path = "wp-content/plugins/google-sitemap-plugin/sitemap.xsl";
		$xslt = $xml->createProcessingInstruction( 'xml-stylesheet', "type=\"text/xsl\" href=\"$xml_stylesheet_path\"" );
		$xml->appendChild($xslt);
		$urlset = $xml->appendChild( $xml->createElementNS( 'http://www.sitemaps.org/schemas/sitemap/0.9','urlset' ) );
		foreach( $loc as $val ) {
			$url = $urlset->appendChild( $xml->createElement( 'url' ) );
			$loc = $url->appendChild( $xml->createElement( 'loc' ) );
			$permalink = get_permalink( $val->ID );
			$loc->appendChild( $xml->createTextNode( $permalink ) );
			$lastmod = $url->appendChild( $xml->createElement( 'lastmod' ) );
			$now = $val->post_modified;
			$date = date( 'Y-m-d\TH:i:sP', strtotime( $now ) );
			$lastmod->appendChild( $xml -> createTextNode( $date ) );
			$changefreq = $url -> appendChild( $xml->createElement( 'changefreq' ) );
			$changefreq->appendChild( $xml->createTextNode( 'monthly' ) );
			$priority = $url->appendChild( $xml->createElement( 'priority' ) );
			$priority->appendChild( $xml->createTextNode( 1.0 ) );
		}
		$xml->formatOutput = true;
		$xml->save( ABSPATH . 'sitemap.xml' );		
	}
}

if( ! function_exists( 'register_gglstmp_settings' ) ) {
	function register_gglstmp_settings() {
		global $wpmu, $gglstmp_settings;

		$gglstmp_option_defaults = array( 'page', 'post' );

		if ( 1 == $wpmu ) {
			if( ! get_site_option( 'gglstmp_settings' ) ) {
				add_site_option( 'gglstmp_settings', $gglstmp_option_defaults );
			}
		} 
		else {
			if( ! get_option( 'gglstmp_settings' ) )
				add_option( 'gglstmp_settings', $gglstmp_option_defaults );
		}
			
		if ( 1 == $wpmu )
			$gglstmp_settings = get_site_option( 'gglstmp_settings' ); 
		else
			$gglstmp_settings = get_option( 'gglstmp_settings' );
	}	
}

if( ! function_exists( 'delete_gglstmp_settings' ) ) {
	function delete_gglstmp_settings() {
		delete_option( 'gglstmp_settings' );
	}
}   

if( ! function_exists( 'gglstmp_settings_global' ) ) {
	function gglstmp_settings_global() {
		global $wpmu, $gglstmp_settings;
		$gglstmp_option_defaults = array( 'page', 'post' );
		$gglstmp_settings = array();
		if ( 1 == $wpmu )
			$gglstmp_settings = get_site_option( 'gglstmp_settings' ); 
		else
			$gglstmp_settings = get_option( 'gglstmp_settings' );
		$gglstmp_settings = array_merge( $gglstmp_option_defaults, $gglstmp_settings );
	}
}   

//============================================ Function for creating setting page ====================
if ( !function_exists ( 'gglstmp_settings_page' ) ) {
	function gglstmp_settings_page () {
		global $url_home, $gglstmp_settings, $url, $wpdb;
		$url_robot = ABSPATH . "robots.txt";
		$url_sitemap = ABSPATH . "sitemap.xml";
		$message = "";
		if( isset( $_POST['gglstmp_new'] ) ) {
			$message =  __( "Your sitemap file was created in the root directory of the site. ", 'sitemap' );
			gglstmp_sitemapcreate();
		}
		if( isset( $_REQUEST['gglstmp_submit'] ) ) {
			$gglstmp_settings = isset( $_REQUEST['gglstmp_settings'] ) ? $_REQUEST['gglstmp_settings'] : array() ;
			update_option( 'gglstmp_settings', $gglstmp_settings );
			$message .= __( "Options saved." , 'sitemap' );	
		}
		$gglstmp_result = $wpdb->get_results( "SELECT post_type FROM ". $wpdb->posts ." WHERE post_type NOT IN ( 'revision', 'attachment', 'nav_menu_item' ) GROUP BY post_type" );	
		?>
		<div class="wrap">
			<div class="icon32 icon32-bws" id="icon-options-general"></div>
			<h2><?php _e( "Google Sitemap options", 'sitemap' ); ?></h2>
			<div class="updated fade" <?php if( ! isset( $_REQUEST['gglstmp_new'] ) ) echo "style=\"display:none\""; ?>><p><strong><?php echo $message; ?></strong></p></div>
			<form action="admin.php?page=google-sitemap-plugin.php" method='post' id="gglstmp_auth" name="gglstmp_auth">
				<?php //=============================== Creating sitemap file ====================================
				if( file_exists( $url_sitemap ) ) {
					echo "<p>". __( "The sitemap file is already exists. If you want to change it for a new sitemap file check the necessary box below. In other case all actions will be performed over an existing file.", 'sitemap' ) . "</p>";
				}
				else {
					gglstmp_sitemapcreate();
					echo "<p>".__( "Your sitemap file was created in the root directory of the site. ", 'sitemap' ) . "</p>";	
				}
				//========================================== Recreating sitemap file ====================================				
				echo '<p>'. __( "If you don't want to add this file automatically you may go through", 'sitemap' ) . " <a href=\"https://www.google.com/webmasters/tools/home?hl=en\">". __( "this", 'sitemap' ) . "</a> ". __( "link, sign in, select necessary site, select 'Sitemaps' and type in necessary field", 'sitemap' ) ." - '". $url_home."/sitemap.xml'.</p>";
				if ( ! function_exists( 'curl_init' ) ) {
					echo '<p class="error">'. __( "This hosting doesn't support CURL, so you can't add sitemap file automatically", 'sitemap' ). "</p>";	
					$curl_exist = 0;
				}
				else {
					$curl_exist = 1;
				}?>
				<table class="form-table">
					<tr valign="top">
						<td colspan="2">
							<input type='checkbox' name='gglstmp_new' value="1" /> <label for="gglstmp_new"><?php _e( "I want to create new sitemap file", 'sitemap' );	?></label>
						</td>
					</tr>
					<tr valign="top">
						<td colspan="2">
							<input type='checkbox' name='gglstmp_checkbox' value="1" /> <label for="gglstmp_checkbox"><?php _e( "I want to add sitemap file path in robots.txt", 'sitemap' );?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" colspan="2"><?php _e( 'Please choose the necessary post types in order to add the links to them in the sitemap:', 'sitemap' ); ?> </th>
					</tr>
					<tr valign="top">
						<td colspan="2">
							<?php 
							foreach ( $gglstmp_result as $key => $value ) { ?>
								<input type="checkbox" <?php echo ( in_array( $value->post_type, $gglstmp_settings ) ?  'checked="checked"' : "" ); ?> name="gglstmp_settings[]" value="<?php echo $value->post_type; ?>"/><span style="text-transform: capitalize; padding-left: 5px;"><?php echo $value->post_type; ?></span><br />
							<?php } ?>
						</td>
					</tr>	
					<?php if ( $curl_exist == 1 ) { ?>
					<tr valign="top">
						<td colspan="2">
							<?php echo __( "Type here your login and password from google webmaster tools account to add or delete site and sitemap file automatically or to get information about this site in google webmaster tools.", 'sitemap' ); ?> 
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Settings for remote work with google webmaster tools', 'sitemap' ); ?></th>
						<td>
							<input type='text' name='gglstmp_email' value="<?php if( isset( $_REQUEST['gglstmp_email'] ) ) echo  $_REQUEST['gglstmp_email']; ?>" /> <label for='gglstmp_email'><?php _e( "Login", 'sitemap' );	?></label><br />
							<input type='password' name='gglstmp_passwd' value="<?php if( isset( $_REQUEST['gglstmp_email'] ) ) echo  $_REQUEST['gglstmp_email']; ?>" /> <label for='gglstmp_passwd'><?php _e( "Password", 'sitemap' );	?></label><br />
							<input type='radio' name='gglstmp_menu' value="ad" /> <label for='gglstmp_menu'><?php _e( "I want to add this site to the google webmaster tools", 'sitemap' );	?></label><br />
							<input type='radio' name='gglstmp_menu' value="del" /> <label for='gglstmp_menu'><?php _e( "I want to delete this site from google webmaster tools", 'sitemap' ); ?></label><br />
							<input type='radio' name='gglstmp_menu' value="inf" /> <label for='gglstmp_menu'><?php _e( "I want to get info about this site in google webmaster tools", 'sitemap' );	?></label>
						</td>
					</tr>
					<?php } ?>
				</table>
				<input type="hidden" name="gglstmp_submit" value="submit" />
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>
		</div>
		<?php
		//============================ Adding location of sitemap file to the robots.txt =============
		if( isset( $_POST['gglstmp_ch1_robots'] ) ){
			if ( file_exists( $url_robot ) ) {		
				$fp = fopen( ABSPATH . 'robots.txt', "a+" );
				fwrite($fp, "\nSitemap: " . $url_home . "/sitemap.xml\n" );
				fclose ( $fp ); 
			}
			else{
				$fp = fopen( ABSPATH . 'robots.txt', "a+" );
				fwrite( $fp, "# User-agent: *\n
# Disallow: /wp-admin/\n 
# Disallow: /wp-includes/\n
# Disallow: /wp-trackback\n
# Disallow: /wp-feed\n
# Disallow: /wp-comments\n
# Disallow: /wp-content/plugins\n
# Disallow: /wp-content/themes\n
# Disallow: /wp-login.php\n
# Disallow: /wp-register.php\n
# Disallow: /feed\n
# Disallow: /trackback\n
# Disallow: /cgi-bin\n
# Disallow: /comments\n
# Disallow: *?s=
\nSitemap: " . $url_home . "/sitemap.xml" );
				fclose ($fp);
			}
		}
		//================================ Different checks for the valid entering data ===================
		if( isset( $_POST['gglstmp_menu'] ) && ( ! isset( $_POST['gglstmp_email'] ) || ! isset( $_POST['gglstmp_passwd'] ) || empty( $_POST['gglstmp_email'] ) || empty( $_POST['gglstmp_passwd'] ) ) ) { ?> 
			<script type = "text/javascript"> alert( "<?php _e( 'You must enter login and password', 'sitemap' );	?>" ) </script>
		<?php }
		else if( isset( $_POST['gglstmp_email'] ) && isset( $_POST['gglstmp_passwd'] ) && isset( $_POST['gglstmp_menu'] ) && $_POST['gglstmp_menu'] != "ad" && $_POST['gglstmp_menu'] != "del" && $_POST['gglstmp_menu'] != "inf" ) { ?>
			<script type = "text/javascript"> alert( "<?php _e( 'You must choose at least one action', 'sitemap' );	?>" ) </script>
		<?php }
		else if( isset( $_POST['gglstmp_email'] ) && isset( $_POST['gglstmp_passwd'] ) && isset( $_POST['gglstmp_menu'] ) && ! empty( $_POST['gglstmp_email'] ) && ! empty( $_POST['gglstmp_passwd'] )) {	
			// =================== Connecting to the google account =================
			$data = array( 'accountType' => 'GOOGLE',
				'Email' => $_POST['gglstmp_email'],
				'Passwd' => $_POST['gglstmp_passwd'],
				'source' =>'PHI-cUrl-Example',
				'service' =>'sitemaps'
			);  
			$ch = curl_init();    
			curl_setopt( $ch, CURLOPT_URL, "https://www.google.com/accounts/ClientLogin" );	
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );  
			curl_setopt( $ch, CURLOPT_POST, true );  
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );  
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );  
			curl_setopt( $ch,  CURLOPT_UNRESTRICTED_AUTH, true ); 
			$hasil = curl_exec( $ch );
			curl_close( $ch );
			$httpResponseAr = explode( "\n", $hasil );
			$httpParsedResponseAr = array();
			foreach ( $httpResponseAr as $i => $rVal ) {
				if( strpos( $rVal, "=" ) !== false ) {
					list( $qKey, $qVal ) = explode ( "=", $rVal );
					$httpParsedResponseAr[$qKey] = $qVal;
				}
			}
			$au = isset( $httpParsedResponseAr["Auth"] ) ? $httpParsedResponseAr["Auth"] : false;
			if ( ! $au && ( $_POST['gglstmp_email'] ) && ( $_POST['gglstmp_passwd'] ) ) {
			?>
				<script type = "text/javascript"> alert( "<?php _e( "Login and password don\'t match, try again, please", 'sitemap' );	?>" ) </script>
			<?php
			}
			else {
				if( $_POST['gglstmp_menu'] == "inf" ) {
					gglstmp_info_site( $au );//getting info about the site in google webmaster tools account
				}
				else if( $_POST['gglstmp_menu'] == "ad" ) {
					gglstmp_add_site( $au ); //adding site and verifying its ownership
					gglstmp_add_sitemap( $au );//adding sitemap file to the google webmaster tools account
				}
				else if( $_POST['gglstmp_menu'] == "del" ) {
					gglstmp_del_site( $au );//deleting site from google webmaster tools
				}
			}	
		}
	}
}

//============================================ Curl function ====================
if( ! function_exists( 'gglstmp_curl_funct' ) ) {
	function gglstmp_curl_funct( $au, $url_send, $type_request, $content ) {
		$headers  =  array ( "Content-type: application/atom+xml; charset=\"utf-8\"",
			"Authorization: GoogleLogin auth=" . $au
		);
		$chx = curl_init(); 
		curl_setopt( $chx, CURLOPT_URL, $url_send );
		curl_setopt( $chx, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $chx, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $chx, CURLOPT_RETURNTRANSFER, true );
		if ( $type_request == "GET" ) {
			curl_setopt( $chx, CURLOPT_HTTPGET, true );
		}
		if ( $type_request == "POST" ) {
			curl_setopt( $chx, CURLOPT_POST, true );
			curl_setopt( $chx, CURLOPT_POSTFIELDS, $content );
		}
		if ( $type_request == "DELETE" ) {
			curl_setopt( $chx, CURLOPT_CUSTOMREQUEST, 'DELETE' );
		}
		if ( $type_request == "PUT" ) {
			curl_setopt( $chx, CURLOPT_CUSTOMREQUEST, 'PUT' );
			curl_setopt( $chx, CURLOPT_POSTFIELDS, $content );
		}
		$hasilx = curl_exec( $chx );
		curl_close( $chx );
		return $hasilx;
	}
}

//============================================ Function to get info about site ====================
if( ! function_exists( 'gglstmp_info_site' ) ) {	
	function gglstmp_info_site( $au ) {
		global $url_home;
		global $url;
		global $url_send;
		global $url_send_sitemap;
		$hasilx = gglstmp_curl_funct( $au, $url_send . $url, "GET", false );
		//========================= Getting info about site in google webmaster tools ====================
		echo "<h2><br />". __( "Info about this site in google webmaster tools", 'sitemap') ."</h2><br />";
		if ( $hasilx == "Site not found" ) {
			echo __( "This site is not added to the google webmaster tools account", 'sitemap');
		}
		else {
			$hasils = gglstmp_curl_funct( $au, $url_send . $url, "GET", false );
			echo "<pre>";
			$p = xml_parser_create();
			xml_parse_into_struct( $p, $hasils, $vals, $index );
			xml_parser_free( $p );  
			  foreach ( $vals as $val ) {
			  if( $val["tag"] == "WT:VERIFIED" )
					$ver = $val["value"];
				}
			$hasils = gglstmp_curl_funct( $au, $url_send_sitemap . $url . "/sitemaps/", "GET", false );
			echo "<pre>";
			$p = xml_parser_create();
			xml_parse_into_struct( $p, $hasils, $vals, $index );
			xml_parser_free( $p );  
			foreach ( $vals as $val ) {
			if( "WT:SITEMAP-STATUS" == $val["tag"] )
				$sit = $val["value"];
			}
			echo __( "Site url: ", 'sitemap') . $url_home . "<br />";
			echo __( "Site verification: ", 'sitemap'); 
			if( "true" == $ver ) 
				echo __( "verificated", 'sitemap') . "<br />"; 
			else 
				echo __( "non verificated", 'sitemap') . "<br />";
			echo __( "Sitemap file: ", 'sitemap');
			if( $sit ) 
				echo __( "added", 'sitemap') . "<br />"; 
			else 
				echo __( "not added", 'sitemap') . "<br />";
		}
	}
}

//============================================ Deleting site from google webmaster tools ====================
if( ! function_exists( 'gglstmp_del_site' ) ) {
	function gglstmp_del_site( $au ) {
		global $url, $url_send;
		$hasil3 = gglstmp_curl_funct( $au, $url_send. $url, "DELETE", false );
	}
}

//============================================ Adding site to the google webmaster tools ====================
if( ! function_exists( 'gglstmp_add_site' ) ) {
	function gglstmp_add_site( $au ) {
		global $url_home, $url, $url_send;
		$content = "<atom:entry xmlns:atom=\"http://www.w3.org/2005/Atom\" xmlns:wt=\"http://schemas.google.com/webmasters/tools/2007\">"
		 ."<atom:content src=\"" . $url_home . "\" />"
		 ."</atom:entry>\n";
		$hasil1 = gglstmp_curl_funct( $au, $url_send, "POST", $content );
		preg_match( '/(google)[a-z0-9]*\.html/', $hasil1, $matches );
		//===================== Creating html file for verifying site ownership ====================
		$m1="../" . $matches[0];
		if( ! ( file_exists ( $m1 ) ) ) {
		$fp = fopen ("../" . $matches[0], "w+" );
		fwrite( $fp, "google-site-verification: " . $matches[0] );
		fclose ( $fp );
		}
		//============================= Verifying site ownership ====================
		$content  = "<atom:entry xmlns:atom=\"http://www.w3.org/2005/Atom\" xmlns:wt=\"http://schemas.google.com/webmasters/tools/2007\">"
		."<atom:category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/webmasters/tools/2007#site-info'/>"
		."<wt:verification-method type=\"htmlpage\" in-use=\"true\"/>"
		."</atom:entry>";
		$hasil2 = gglstmp_curl_funct( $au, $url_send. $url, "PUT", $content );
	}
}

//============================================ Adding sitemap file ====================
if( ! function_exists( 'gglstmp_add_sitemap' ) ) {
	function gglstmp_add_sitemap( $au ) {
		global $url_home;
		global $url;
		global $url_send_sitemap;
		$content  = "<atom:entry xmlns:atom=\"http://www.w3.org/2005/Atom\" xmlns:wt=\"http://schemas.google.com/webmasters/tools/2007\">"
		."<atom:id>" . $url_home . "/sitemap.xml</atom:id>"
		."<atom:category scheme=\"http://schemas.google.com/g/2005#kind\" term=\"http://schemas.google.com/webmasters/tools/2007#sitemap-regular\"/>"
		."<wt:sitemap-type>WEB</wt:sitemap-type>"
		."</atom:entry>";
		$hasil1 = gglstmp_curl_funct( $au, $url_send_sitemap . $url . "/sitemaps/", "POST", $content );
	}
}

//============================================ Adding setting link in activate plugin page ====================
if( ! function_exists( 'gglstmp_action_links' ) ) {
	function gglstmp_action_links( $links, $file ) {
		//Static so we don't call plugin_basename on every plugin row.
		static $this_plugin;
		if ( ! $this_plugin ) 
			$this_plugin = plugin_basename( __FILE__ );
		if ( $file == $this_plugin ) {
			 $settings_link = '<a href="admin.php?page=google-sitemap-plugin.php">' . __( 'Settings', 'sitemap' ) . '</a>';
			 array_unshift( $links, $settings_link );
		}
		return $links;
	}
}

if ( ! function_exists ( 'gglstmp_plugin_init' ) ) {
	function gglstmp_plugin_init() {
		load_plugin_textdomain( 'sitemap', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
	}
}

register_activation_hook( __FILE__, 'register_gglstmp_settings'); // activate plugin
register_uninstall_hook( __FILE__, 'delete_gglstmp_settings'); // uninstall plugin

add_action( 'init', 'gglstmp_settings_global' );

add_action( 'admin_enqueue_scripts', 'gglstmp_add_my_stylesheet' );
add_action( 'wp_enqueue_scripts', 'gglstmp_add_my_stylesheet' );
add_action( 'admin_init', 'gglstmp_plugin_init' );
add_action( 'admin_menu', 'gglstmp_add_pages' );
add_filter( 'plugin_action_links', 'gglstmp_action_links', 10, 2 );
?>