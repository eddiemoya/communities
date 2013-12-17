<?php
/*
Plugin Name: User Screen Name Block
Plugin URI: 
Description: Plugin gives option to block User's screen name from view
Author: Dan Crimmins
Version: 1.0
Author URI:
*/


//add_action('show_user_profile', 'block_screen_name_field');
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
    <?php 
    	//if(! get_user_meta($user->id, 'sso_guid', true)):
     $sso_user = SSO_User::factory()->get_by_id($user->id);
    	
     if(! $sso_user->guid):
    ?>
    <tr>
    <td>
    <label for="s_name"><?php _e("Screen Name")?></label>
    </td>
    <td>
    <input type="text" id="s_name" name="s_name" value="<?php echo $user->user_nicename;?>" />
    </td>
    </tr>
    <?php endif;?>
    </table>
	<?php
}
	
//add_action('personal_options_update', 'save_block_sn_field');
add_action('edit_user_profile_update', 'save_block_sn_field');

function save_block_sn_field($user_id) {
	
    $value = isset($_POST['screen_name_block']) ? 'yes' : 'no';
    update_user_meta($user_id, 'block_screen_name', $value);
    
}

add_action('admin_init', 'update_screen_name');

function update_screen_name() {
	
	if(isset($_POST['s_name']) && strlen(trim($_POST['s_name'])) > 0) {
		
		//update_user_meta($_POST['user_id'], 'profile_screen_name', $_POST['s_name']);
		update_user_nicename($_POST['user_id'], $_POST['s_name']);
	
	}
}
	
