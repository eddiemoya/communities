<?php
/*
Plugin Name: User Screen Name Block
Plugin URI: 
Description: Plugin gives option to block User's screen name from view
Author: Dan Crimmins
Version: 1.0
Author URI:
*/
add_action('show_user_profile', 'block_screen_name_field');
add_action('edit_user_profile', 'block_screen_name_field');


function block_screen_name_field($user) {
	?>
	<h3><?php _e("Screen Name", "blank"); ?></h3>
    <table class="form-table">
    <tr>
    <td valign="top">
    <input type="checkbox" name="screen_name_block" id="screen_name_block" value="yes" <?php echo (get_user_meta($user->id, 'block_screen_name', true) == 'yes') ? 'checked' : null;?>/><br/>
    </td>
    <td>
    <label for="screen_name_block"><?php _e("Block user's screen name"); ?></label>
 	<br>
    <span class="description"><?php _e("If checked, user's screen name will be displayed as ********"); ?></span>
    </td>
    </tr>
    </table>
	<?php
}
	
add_action('personal_options_update', 'save_block_sn_field');
add_action('edit_user_profile_update', 'save_block_sn_field');

function save_block_sn_field($user_id) {
	
    //if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
    
    $value = isset($_POST['screen_name_block']) ? 'yes' : 'no';
    
    update_user_meta($user_id, 'block_screen_name', $value);
}
	
