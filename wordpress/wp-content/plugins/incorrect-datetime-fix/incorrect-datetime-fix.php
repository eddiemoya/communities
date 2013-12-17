<?php
/*
Plugin Name: Incorrect Datetime fix
Version: 1
Description: Strips out MySQL modes that are incompatible with WordPress timestamps. This does not change the schema or the way WordPress generates timestamps. It takes out SQL Modes that cause zero-value timestamps to be invalid, which breaks author's ability to publish posts and causes this error message: [Incorrect datetime value: '0000-00-00 00:00:00' for column 'post_date_gmt' at row 1] 
Author: Eddie Moya
*/

/*
Copyright (C) 2011 Eddie Moya (eddie.moya+wp[at]gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

class Incorrect_Datetime_Fix {
    function init(){
        add_action('init', array( __CLASS__, 'strip_sql_modes' ) );
    }
    
    function strip_sql_modes(){
        global $wpdb;

        $sql_modes = $wpdb->get_col("SELECT @@SESSION.sql_mode");
        $sql_modes = preg_replace('/(,?NO_ZERO_DATE|,?NO_ZERO_IN_DATE|,?TRADITIONAL)/', '', $sql_modes[0] );
        $wpdb->query("SET SESSION sql_mode = '".$sql_modes."'");          
    }   
		
}
Incorrect_Datetime_Fix::init();


