<?php
    function get_google_analtyics_id() {
        $option = get_option('Yoast_Google_Analytics');

        echo $option['uastring'];
    }
    

/**
 * select_it() - This function is used exclusively with WP Polls. It accepts input string of label and 
 * checkbox or radio button as used and formatted in polls and adds the checked attribute to the input.
 * 
 * @param string $input
 * @return string - input with the checked attribute added.
 * @author Dan Crimmins
 * 
 * @see comm_display_pollvote() -- located in filters-hooks.php. This is the only file where this function
 * is being used.
 * 
 */
function select_it($input) {
	
	$pos = (strrpos($input, '">') !== false) ? (strrpos($input, '">') + 1) : false;

	if($pos !== false) {
		
		$new_input = substr_replace($input, ' checked="checked"', $pos, 0);
	
		return $new_input;
	}
	
	return $input;
	
}


function strip_tags_attributes($sSource, $aAllowedTags = array(), $aDisabledAttributes = array('class', 'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload', 'style'))
{
        if (empty($aDisabledAttributes)) return strip_tags($sSource, implode('', $aAllowedTags));

        return preg_replace('/<(.*?)>/ie', "'<' . preg_replace(array('/javascript:[^\"\']*/i', '/(" . implode('|', $aDisabledAttributes) . ")[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", strip_tags($sSource, implode('', $aAllowedTags)));
}
    
function update_zipcode_cookie($zip) {
	setcookie("weather_location", $zip, time()+86400);
}

if ($_POST["update_zipcode"] != "") {
	update_zipcode_cookie($_POST["update_zipcode"]);
}

//This is to remove generator meta tag
remove_action('wp_head', 'wp_generator');