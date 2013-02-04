<?php
/*
Plugin Name: WP-Weather
Description: Display current weather conditions with Weather Underground and IPinfoDB
Version: 1.0
Author: Jason Corradino
Author URI: http://imyourdeveloper.com
License: GPL2

Copyright 2012  Jason Corradino  (email : Jason@ididntbreak.it)

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

include_once("wp-weather-widget.php");

class WP_Weather_Admin {
	/**
	 * Initializes Facebook Importer admin functionality
	 *
	 * @author Jason Corradino
	 *
	 */
	function init() {
		add_action('admin_init', array(__CLASS__, "plugin_init"));
		add_action('admin_menu', array(__CLASS__, "setup_pages"));
	}
	
	/**
	 * Initializes the plugin settings pages and fields on admin_init
	 *
	 * @author Jason Corradino
	 *
	 */
	function plugin_init() {
		register_setting( 'wp_weather_options', 'wp_weather_options', array(__CLASS__, "validate_fields"));
		add_settings_section('weather_underground_api', 'Weather Underground API', array(__CLASS__, "wunderground_api_text"), 'wp_weather');
		add_settings_field('weather_underground_api_field', 'API Key', array(__CLASS__, "wunderground_api_textbox"), 'wp_weather', 'weather_underground_api');
		add_settings_section('ipinfodb_api', 'IPinfoDB API', array(__CLASS__, "ipinfodb_api_text"), 'wp_weather');
		add_settings_field('ipinfodb_api_field', 'API Key', array(__CLASS__, "ipinfodb_api_textbox"), 'wp_weather', 'ipinfodb_api');
	}
	
	/**
	 * Validates and saves option information
	 *
	 * @author Jason Corradino
	 *
	 */
	function validate_fields() {
		$options = get_option('wp_weather_options');
		return array(
			"wunderground_api" => $_POST['weather_underground_api_field'],
			"ipinfodb_api" => $_POST['ipinfodb_api_field'],
		);
	}
	
	/**
	 * Sets text to display on options page above profile selection
	 *
	 * @author Jason Corradino
	 *
	 */
	function wunderground_api_text() {
		echo '<p>Your Weather Underground API key, can be found <a href="http://www.wunderground.com/weather/api/">here</a>.</p>';
		return true;
	}

	/**
	 * Sets text to display on options page next to profile selection
	 *
	 * @author Jason Corradino
	 *
	 */
	function wunderground_api_textbox() {
		$options = get_option('wp_weather_options');
		echo '<input type="text" name="weather_underground_api_field" id="wunderground-api" value="'.$options['wunderground_api'].'" />';
	}
	
	
	/**
	 * Sets text to display on options page above profile selection
	 *
	 * @author Jason Corradino
	 *
	 */
	function ipinfodb_api_text() {
		echo '<p>Your IPinfoDB API key, can be found <a href="http://ipinfodb.com/ip_location_api.php">here</a>.</p>';
		return true;
	}

	/**
	 * Sets text to display on options page next to profile selection
	 *
	 * @author Jason Corradino
	 *
	 */
	function ipinfodb_api_textbox() {
		$options = get_option('wp_weather_options');
		echo '<input type="text" name="ipinfodb_api_field" id="ipinfodb-api" value="'.$options['ipinfodb_api'].'" />';
	}
	
	/**
	 * Creates the "Wall Content" menu item and removes "add new" photo
	 *
	 * @author Jason Corradino
	 *
	 */
	function setup_pages() {
		add_options_page('WP Weather', 'WP Weather', 'manage_options', 'wp_weather', array(__CLASS__, "plugin_options"));
	}
	
	/**
	 * Sets up options page
	 *
	 * @author Jason Corradino
	 *
	 */
	function plugin_options() {
		?>
			<div class="wrap">
				<div id="icon-edit" class="icon32 icon32-posts-facebook_images">
					<br>
				</div>
				<h2>WP Weather Settings</h2>
				<form action="options.php" method="post" id="facebookGalleryForm">
					<p><input name="Submit" type="submit" class="facebookGallerySubmit" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
					<?php settings_fields('wp_weather_options'); ?>
					<?php do_settings_sections('wp_weather'); ?>
					<p><input name="Submit" type="submit" class="facebookGallerySubmit" value="<?php esc_attr_e('Save Changes'); ?>" /></p>
				</form>
			</div>
		<?php
	}
}

class WP_Weather {
	function get_current_conditions($zip="") {
		$user = get_current_user_id();
		$city  = get_user_meta( $user, 'user_city', true );
	    $state = get_user_meta( $user, 'user_state', true );
		if ($city != "" && $state != "") { // use user state/city
			$transient = get_transient("conditions-$city-$state");
			if ($transient == "") {
				$contions = $this->wunderground_api("$state/$city");
				set_transient("conditions-$city-$state", $conditions, 900);
			} else {
				$conditions = $transient;
			}
		} elseif ($zip != "") { // use pre-set zip
			$transient = get_transient("conditions-$zip");
			if ($transient == "") {
				$contions = $this->wunderground_api($zip);
				set_transient("conditions-$zip", $conditions, 900);
			} else {
				$conditions = $transient;
			}
		} else { // lookup weather based on IP location
			$location = $this->location_api();
			if ($location->statusCode == "OK") {
				$coords['lon'] = $location->longitude;
				$coords['lat'] = $location->latitude;
				$locationCode = ($location->zipCode != "" && $location->zipCode != "-") ? $location->zipCode : "{$location->countryCode}-{$location->cityName}";
				$transient = get_transient("conditions-$locationCode");
				if ($transient == "") {
					$conditions = $this->wunderground_api("{$coords['lat']},{$coords['lon']}");
					set_transient("conditions-$locationCode", $conditions, 900);
				} else {
					$conditions = $transient;
				}
			}
		}
		
		if ($conditions != "") {
			return $conditions;
		} else {
			return false;
		}
	}
	
	function location_api() {
		$options = get_option('wp_weather_options');
		//$uri = 'http://api.ipinfodb.com/v3/ip-city/?key='.$options["ipinfodb_api"].'&format=xml&ip='.$_SERVER['REMOTE_ADDR'];
		//$uri = 'http://api.ipinfodb.com/v3/ip-city/?key='.$options["ipinfodb_api"].'&format=xml&ip=141.101.116.82'; // London
		$uri = 'http://api.ipinfodb.com/v3/ip-city/?key='.$options["ipinfodb_api"].'&format=xml&ip=98.226.88.41'; // Midlothian
		$data = $this->get_data($uri);
		if(substr_count($data,'ode>ERROR') ){
			return false;
		} else {
			$location = simplexml_load_string($data);
		}
		return $location;
	}
	
	function wunderground_api($query) {
		$options = get_option('wp_weather_options');
		$uri = "http://api.wunderground.com/api/{$options['wunderground_api']}/conditions/q/$query.json";
		$data = json_decode($this->get_data($uri));
		if ($data->response->error != "") {
			return false;
		} else {
			return $data;
		}
	}
	
	function get_data($uri, $timeout=2) {
		if($timeout==0 or !$timeout){$timeout=2;}
		if(ini_get('allow_url_fopen')) {
			$opts = array('http' => array('timeout' => $timeout));
			$context  = stream_context_create($opts);
			$return = @file_get_contents($uri,false,$context);
		} else {
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $uri);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
			$return = @curl_exec($ch);
			curl_close($ch);    
		}
		return $return;
	}
}

if (is_admin()) {
	WP_Weather_Admin::init();
}
?>