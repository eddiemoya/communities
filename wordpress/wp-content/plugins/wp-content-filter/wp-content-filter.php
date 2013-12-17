<?php
/*
Plugin Name: WP Content Filter
Plugin URI: http://www.presscoders.com/plugins/wp-content-filter/
Description: Filter out profanity, swearing, abusive comments and any other keywords from your site.
Version: 2.31
Author: David Gwyer
Author URI: http://www.presscoders.com
*/

/*  Copyright 2009 David Gwyer (email : d.v.gwyer@presscoders.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// @todo
//
// 1. Filter browser title.
// 2. Somehow flag/identify/segregate the posts and comments that have the offending content. That way the admin could deal with the offender, and modify the post or comment content as they see fit. As it stands now, the only way to find the bad content is to visually see it on the site.

// pccf_ prefix is derived from [p]ress [c]oders [c]ontent [f]ilter
register_activation_hook( __FILE__, 'pccf_add_defaults' );
register_uninstall_hook( __FILE__, 'pccf_delete_plugin_options' );
add_action( 'admin_init', 'pccf_init' );
add_action( 'admin_menu', 'pccf_add_options_page' );
add_action( 'plugins_loaded', 'pccf_contfilt' );
add_filter( 'plugin_action_links', 'pccf_plugin_action_links', 10, 2 );

add_action( 'activated_plugin','pccf_save_error' );
function pccf_save_error(){
    update_option( 'pc_plugin_error',  ob_get_contents() );
}

add_action( 'shutdown','pc_show_plugin_error' );
function pc_show_plugin_error(){
    echo get_option('plugin_error');
}


// ***************************************
// *** START - Create Admin Options    ***
// ***************************************

// delete options table entries ONLY when plugin deactivated AND deleted
function pccf_delete_plugin_options() {
	delete_option('pccf_options');
}

// Define default option settings
function pccf_add_defaults() {
	$tmp = get_option('pccf_options');

	if( ( (isset($tmp['chk_default_options_db']) && $tmp['chk_default_options_db']=='1')) || (!is_array($tmp)) ) {
		$arr = array("chk_post_title" => "1", "chk_post_content" => "1", "chk_comments" => "1", "txtar_keywords" => "Saturn, Jupiter, Pluto", "rdo_word" => "all", "drp_filter_char" => "star", "rdo_case" => "insen", "chk_default_options_db" => "", "rdo_strict_filtering" => "strict_on");
		update_option('pccf_options', $arr);
	}
}

// Init plugin options to white list our options
function pccf_init(){
	// put the below into a function and add checks all sections (especially radio buttons) have a valid choice (i.e. no section is blank)
	// this is primarily to check newly added options have correct initial values
	$tmp = get_option('pccf_options');
	if(!$tmp['rdo_strict_filtering'])
	{   // check strict filtering option has a starting value
		$tmp["rdo_strict_filtering"] = "strict_off";
		update_option('pccf_options', $tmp);
	}
	register_setting( 'pccf_plugin_options', 'pccf_options', 'pccf_validate_options' );
}

// Add menu page
function pccf_add_options_page() {
	add_options_page('WP Content Filter Options Page', 'WP Content Filter', 'manage_options', __FILE__, 'pccf_render_form');
}

// Draw the menu page itself
function pccf_render_form() {
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>WP Content Filter Options</h2>
		<p>Configure the content filter Plugin options below.</p>

		<form method="post" action="options.php">
			<?php settings_fields('pccf_plugin_options'); ?>
			<?php $options = get_option('pccf_options'); ?>
			<table class="form-table">
				<tr>
					<th scope="row">Keywords to Remove</th>
					<td>
						<textarea name="pccf_options[txtar_keywords]" rows="7" cols="50" type='textarea'><?php echo $options['txtar_keywords']; ?></textarea><br /><span style="color:#888;margin-left:2px;font-style: italic;">Separate keywords with commas</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Strict Filtering</th>
					<td>
						<label><input name="pccf_options[rdo_strict_filtering]" type="radio" value="strict_off" <?php checked('strict_off', $options['rdo_strict_filtering']); ?> /> Strict Filtering OFF <span style="color:#888;margin-left:119px;">['ass' => 'p***able']</span></label><br />
						<label><input name="pccf_options[rdo_strict_filtering]" type="radio" value="strict_on" <?php checked('strict_on', $options['rdo_strict_filtering']); ?> /> Strict Filtering ON (recommended) <span style="color:#888;margin-left:32px;">['ass' => 'passable']</span></label><br /><span style="color:#888;font-style: italic;">Note: When strict filtering is ON, embedded keywords are no longer filtered.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Filter main content</th>
					<td>
						<label><input name="pccf_options[chk_post_content]" type="checkbox" value="1" <?php if (isset($options['chk_post_content'])) { checked('1', $options['chk_post_content']); } ?> /> Post Content<?php if (class_exists('bbPress')) { echo " (including bbPress content)"; } ?></label><br />
						<label><input name="pccf_options[chk_post_title]" type="checkbox" value="1" <?php if (isset($options['chk_post_title'])) { checked('1', $options['chk_post_title']); } ?> /> Post Titles<?php if (class_exists('bbPress')) { echo " (including bbPress titles)"; } ?></label><br />
						<label><input name="pccf_options[chk_comments]" type="checkbox" value="1" <?php if (isset($options['chk_comments'])) { checked('1', $options['chk_comments']); } ?> /> Comments (filters comment author names too)</label><br />
						<label><input name="pccf_options[chk_tags]" type="checkbox" value="1" <?php if (isset($options['chk_tags'])) { checked('1', $options['chk_tags']); } ?> /> Tags</label><br />
						<label><input name="pccf_options[chk_tag_cloud]" type="checkbox" value="1" <?php if (isset($options['chk_tag_cloud'])) { checked('1', $options['chk_tag_cloud']); } ?> /> Tag Cloud</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Word Rendering</th>
					<td>
						<label><input name="pccf_options[rdo_word]" type="radio" value="first" <?php checked('first', $options['rdo_word']); ?> /> First letter retained <span style="color:#888;margin-left:39px;">[dog => d**]</span></label><br />
						<label><input name="pccf_options[rdo_word]" type="radio" value="all" <?php checked('all', $options['rdo_word']); ?> /> All letters removed <span style="color:#888;margin-left:40px;">[dog => ***]</span></label><br />
						<label><input name="pccf_options[rdo_word]" type="radio" value="firstlast" <?php checked('firstlast', $options['rdo_word']); ?> /> First/last letter retained <span style="color:#888;margin-left:16px;">[dog => d*g]</span></label>
					</td>
				</tr>
				<tr>
					<th scope="row">Filter Character</th>
					<td>
						<select name='pccf_options[drp_filter_char]'>
							<option value='star' <?php selected('star', $options['drp_filter_char']); ?>>[*] Asterisk</option>
							<option value='dollar' <?php selected('dollar', $options['drp_filter_char']); ?>>[$] Dollar</option>
							<option value='question' <?php selected('question', $options['drp_filter_char']); ?>>[?] Question</option>
							<option value='exclamation' <?php selected('exclamation', $options['drp_filter_char']); ?>>[!] Exclamation</option>
							<option value='hyphen' <?php selected('hyphen', $options['drp_filter_char']); ?>>[-] Hyphen</option>
							<option value='hash' <?php selected('hash', $options['drp_filter_char']); ?>>[#] Hash</option>
							<option value='tilde' <?php selected('tilde', $options['drp_filter_char']); ?>>[~] Tilde</option>
							<option value='blank' <?php selected('blank', $options['drp_filter_char']); ?>>[ ] Blank</option>
						</select>
						<span style="color:#888;margin-left:2px;font-style: italic;">[ ] Blank - completely removes the filtered keywords from view.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Case Matching</th>
					<td>
						<label><input name="pccf_options[rdo_case]" type="radio" value="sen" <?php checked('sen', $options['rdo_case']); ?> /> Case Sensitive</label><br />
						<label><input name="pccf_options[rdo_case]" type="radio" value="insen" <?php checked('insen', $options['rdo_case']); ?> /> Case Insensitive (recommended)</label><br /><span style="color:#888;font-style: italic;">Note: 'Case Insensitive' matching type is better as it captures more words!</span>
					</td>
				</tr>
				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Database Options</th>
					<td>
						<label><input name="pccf_options[chk_default_options_db]" type="checkbox" value="1" <?php if (isset($options['chk_default_options_db'])) { checked('1', $options['chk_default_options_db']); } ?> /> Restore defaults upon plugin deactivation/reactivation</label>
						<br /><span style="color:#888;margin-left:2px;font-style: italic;">Only check this if you want to reset plugin settings upon reactivation</span>
					</td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>

		<div style="margin-top:15px;">
			<p style="margin-bottom:10px;">If you use this Plugin on your website <b><em>please</em></b> consider making a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9B8X2UGPKCLQ2" target="_blank">donation</a> to support continued development. Thanks.&nbsp;&nbsp;:-)</p>
		</div>

		<div style="clear:both;">
			<p>
				<a href="http://www.facebook.com/PressCoders" title="Our Facebook page" target="_blank"><img src="<?php echo plugins_url(); ?>/simple-sitemap/images/facebook.png" /></a><a href="http://www.twitter.com/dgwyer" title="Follow on Twitter" target="_blank"><img src="<?php echo plugins_url(); ?>/simple-sitemap/images/twitter.png" /></a>&nbsp;<input class="button" style="vertical-align:12px;" type="button" value="Visit Our Site" onClick="window.open('http://www.presscoders.com')">&nbsp;<input class="button" style="vertical-align:12px;" type="button" value="Free Responsive Theme!" onClick="window.open('http://www.presscoders.com/designfolio')">
			</p>
		</div>

	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function pccf_validate_options($input) {
	 // strip html from textboxes
	$input['txtar_keywords'] =  wp_filter_nohtml_kses($input['txtar_keywords']);
	return $input;
}

// ***************************************
// *** END - Create Admin Options    ***
// ***************************************

// ---------------------------------------------------------------------------------

// ***************************************
// *** START - Plugin Core Functions   ***
// ***************************************

function pccf_contfilt() {
	$tmp = get_option('pccf_options');
	
	if (isset($tmp['chk_post_content'])) {
		if($tmp['chk_post_content']=='1'){ 
			
			add_filter('the_content', 'pccf_filter'); 
			add_filter('the_excerpt', 'pccf_filter');
		}

		/* bbPress specific filtering (only if bbPress is present). */
		if (class_exists('bbPress')) {
			add_filter('bbp_get_topic_content', 'pccf_filter');
			add_filter('bbp_get_reply_content', 'pccf_filter');
		}
	}
	if (isset($tmp['chk_post_title'])) {
		if($tmp['chk_post_title']=='1'){ add_filter('the_title', 'pccf_filter'); }
	}
	if (isset($tmp['chk_comments'])) {
		if($tmp['chk_comments']=='1'){ add_filter('comment_text','pccf_filter'); }
	}
	if (isset($tmp['chk_comments'])) {
		if($tmp['chk_comments']=='1'){ add_filter( 'preprocess_comment',  'pccf_custom_filter'); }
	}
	if (isset($tmp['chk_comments'])) {
		if($tmp['chk_comments']=='1'){ add_filter('get_comment_author', 'pccf_filter'); }
	}
	if (isset($tmp['chk_tags'])) {
		if($tmp['chk_tags']=='1'){ add_filter('term_links-post_tag', 'pccf_filter' ); }
	}
	if (isset($tmp['chk_cloud'])) {
		if($tmp['chk_cloud']=='1'){ add_filter('wp_tag_cloud', 'pccf_filter'); }
	}
}

/**
 * Custom function used to solve issue with filter not being applied 
 * to reply to comments/answers.
 * 
 * @author Dan Crimmins
 * @param array $commentdata
 */
function pccf_custom_filter($commentdata) {

	$text = pccf_filter($commentdata['comment_content']);
	$commentdata['comment_content'] = $text;
	
	return $commentdata;

}


function pccf_filter($text) {
	
	$tmp = get_option('pccf_options');
	$wildcard_filter_type = $tmp['rdo_word'];
	$wildcard_char = $tmp['drp_filter_char'];

	if($wildcard_char == 'star'){
		$wildcard = '*';
	} else if($wildcard_char == 'dollar') {
		$wildcard = '$';
	} else if($wildcard_char == 'question') {
		$wildcard = '?';
	} else if($wildcard_char == 'exclamation') {
		$wildcard = '!';
	} else if($wildcard_char == 'hyphen') {
		$wildcard = '-';
	} else if($wildcard_char == 'hash') {
		$wildcard = '#';
	} else if($wildcard_char == 'tilde') {
		$wildcard = '~';
	} else {
		$wildcard = '';
	}

	$filter_type = $tmp['rdo_case'];
	$db_search_string = $tmp['txtar_keywords'];
	$search = explode(",", $db_search_string);
	$search = array_unique($search); // get rid of duplicates in the keywords textbox

	if($tmp['rdo_strict_filtering']=='strict_off')
	{
		// If strict filtering is OFF - use the standard str_ireplace, and str_ireplace functions
		foreach($search as $sub_search)
		{
			$sub_search = trim($sub_search); // remove whitespace chars from start/end of string
			if(strlen($sub_search) > 2)
			{
				if($wildcard_filter_type == 'first')
				{
					$tmp_search = substr($sub_search, 0, 1).str_repeat($wildcard, strlen(substr($sub_search, 1)));
				}
				else if($wildcard_filter_type == 'all')
				{
					$tmp_search = str_repeat($wildcard, strlen(substr($sub_search, 0)));
				}
				else
				{
					$tmp_search = substr($sub_search, 0, 1).str_repeat($wildcard, strlen(substr($sub_search, 2))).substr($sub_search, -1, 1);
				}
				if($filter_type == "insen")
				{
					$text = str_ireplace($sub_search, $tmp_search, $text);
				}
				else
				{ $text = str_replace($sub_search, $tmp_search, $text); }
			}
		}
	}
	else
	{
		// If strict filtering is ON - use regular expressions for more powerful seach and replace
		foreach($search as $sub_search)
		{
			$sub_search = trim($sub_search); // remove whitespace chars from start/end of string
			if(strlen($sub_search) > 2)
			{
				if($wildcard_filter_type == 'first')
				{
					$tmp_search = substr($sub_search, 0, 1).str_repeat($wildcard, strlen(substr($sub_search, 1)));
				}
				else if($wildcard_filter_type == 'all')
				{
					$tmp_search = str_repeat($wildcard, strlen(substr($sub_search, 0)));
				}
				else
				{
					$tmp_search = substr($sub_search, 0, 1).str_repeat($wildcard, strlen(substr($sub_search, 2))).substr($sub_search, -1, 1);
				}
				if($filter_type == "insen")
				{
					$text = str_replace_word_i($sub_search, $tmp_search, $text);
				}
				else
				{ $text = str_replace_word($sub_search, $tmp_search, $text); }
			}
		}
	}

return $text;
}

// case insensitive
function str_replace_word_i($needle,$replacement,$haystack){
	$needle = str_replace('/','\\/', preg_quote($needle)); // allow '/' in keywords
	$pattern = "/\b$needle\b/i";
    $haystack = preg_replace($pattern, $replacement, $haystack);
    return $haystack;
}

// case sensitive
function str_replace_word($needle,$replacement,$haystack){
	$needle = str_replace('/','\\/',preg_quote($needle)); // allow '/' in keywords
	$pattern = "/\b$needle\b/";
    $haystack = preg_replace($pattern, $replacement, $haystack);
    return $haystack;
}

// Display a Settings link on the main Plugins page
function pccf_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$posk_links = '<a href="'.get_admin_url().'options-general.php?page=wp-content-filter/wp-content-filter.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $posk_links );
	}

	return $links;
}

// ***************************************
// *** END - Plugin Core Functions     ***
// ***************************************