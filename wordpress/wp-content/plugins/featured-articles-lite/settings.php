<?php
/**
 * @package Featured articles PRO - Wordpress plugin
 * @author CodeFlavors ( http://www.codeflavors.com )
 * @version 2.4
 */

/* set the current page url */
$current_page = menu_page_url('featured-articles-lite/settings.php', false);


if( isset( $_POST['fa_options'] ) && !empty($_POST['fa_options']) ){
	if( !wp_verify_nonce($_POST['fa_options'],'featured-articles-set-options') ){
		die(__('Sorry, your action is invalid.', 'falite'));
	}else{
			
		$plugin_options = array(
			'complete_unistall'=>0
		);		
		foreach( $plugin_options as $option=>$value ){
			if( isset( $_POST[$option] ) ){
				$plugin_options = $_POST[$option];
			}	
		}
		
		// get wordpress roles
		$roles = $wp_roles->get_names();
		foreach( $roles as $role=>$name ){
			// administrator has default access so skip this role
			if( 'administrator' == $role ) continue;
			// add/remove editing capabilities
			if( isset( $_POST['role'][$role] ) ){
				$wp_roles->add_cap($role, FA_CAPABILITY);
			}else{
				$wp_roles->remove_cap($role, FA_CAPABILITY);
			}
		}
		FA_plugin_options();
		wp_redirect($current_page);
		exit();
	}	
}

// Folder path processing
if( isset($_POST['fa_themes_folder_nonce']) && wp_verify_nonce($_POST['fa_themes_folder_nonce'], 'update_fa_themes_folder') ){
	$folder_change_result = FA_set_themes_folder( $_POST['fa_themes_folder'] );
}

$options = FA_plugin_options();
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br></div>
    <h2 id="add-new-user"><?php _e('Featured Articles - plugin settings', 'falite');?></h2>
    <form method="post" action="<?php echo $current_page;?>&noheader=true">
        <?php wp_nonce_field('featured-articles-set-options', 'fa_options');?>
        <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row">
            	<label for=""><?php _e('Set plugin access', 'falite');?>: </label>
            </th>
            <td>            	
                <?php
					$roles = $wp_roles->get_names();
					foreach( $roles as $role=>$name ):
						if( 'administrator' == $role ){
							continue;
						}	
						$r = $wp_roles->get_role( $role );
						$checked = array_key_exists( FA_CAPABILITY, $r->capabilities ) ? ' checked="checked"' : '';							
				?>
                	<label><input type="checkbox" name="role[<?php echo $role;?>]" value="1"<?php echo $checked;?> style="width:auto;" /> <?php echo $name;?></label><br />
                 
                <?php endforeach;?>
            </td>
            <td>
            	<span class="description">
            		<?php _e('You can grant permissions to users depending on their role. Admins have default access to all plugin areas.', 'falite');?>
            	</span>
            </td>
        </tr>
        <tr valign="top">
        	<th scope="row">
            	<label for="complete_uninstall"><?php _e('Enable full uninstall', 'falite');?>:<br />
            </th>
            <td><input type="checkbox" name="complete_uninstall" id="complete_uninstall" value="1"<?php if($options['complete_uninstall']):?> checked="checked"<?php endif;?> /></td>
        	<td>
        		<?php if($options['complete_uninstall']):?>
                <span style="color:red;">
                	<?php _e("'While we don't expect anything bad to happen we recommended that you first back-up your database before completely removing the plugin.", 'falite');?><br />
                	<?php _e('Complete plugin removal will be performed after you deactivate the plugin and delete it from Wordpress Plugins page.', 'falite');?><br />
                </span>
                <?php else:?>
            	<span class="description"><?php _e('If checked, when the plugin is uninstalled from plugins page all data (sliders, slides, options and meta fields) will also be removed from database.', 'falite');?></span>
                <?php endif;?>
        	</td>
        </tr>
        <tr valign="top">
        	<th scope="row">
            	<label for="complete_uninstall"><?php _e('Enable automatic slider insertion', 'falite');?>:<br />                                
            </th>
            <td><input type="checkbox" name="auto_insert" id="complete_uninstall" value="1"<?php if($options['auto_insert']):?> checked="checked"<?php endif;?> /></td>
            <td>
            	<span class="description">
					<?php _e('When enabled it will display on slider editing/creation a new panel that allows insertion into category pages, home page and pages of slides without the need of additional code.', 'falite');?><br />
					<?php _e('Please note that this kind of slider insertion in your pages will display the slider before the loop you have in those pages. For more precise display into your pages we recommend using the manual insertion or the shortcode insertion.', 'falite');?>
				</span>
            </td>
        </tr>
        <?php 
        	// Display option to show slideshows in WPtouch themes only if plugin is installed.
        	if( class_exists('WPtouchPlugin') ):
        ?>        
        <tr valign="top">
        	<th scope="row">
            	<label for="load_in_wptouch"><?php _e('Allow slideshows in WPtouch themes', 'falite');?>:<br />                                
            </th>
            <td><input type="checkbox" name="load_in_wptouch" id="load_in_wptouch" value="1"<?php if($options['load_in_wptouch']):?> checked="checked"<?php endif;?> /></td>
            <td>
            	<span class="description">
					<?php _e('By enabling this option you will allow slideshows to be displayed and run into your WPtouch mobile version website.', 'falite');?><br />
					<?php _e('Please note that if you have enabled WPtouch <strong>Restricted Mode</strong> setting, no slideshows will be displayed into your website mobile version.', 'falite');?>
				</span>
            </td>
        </tr>
        <?php endif; ?>
        </tbody>
        </table>
<p class="submit">
    <input type="submit" value="<?php _e('Save settings', 'falite');?>" class="button-primary" id="addusersub" name="adduser">
</p>        
    </form>
    
    <h2><?php _e('Change FeaturedArticles themes folder', 'falite');?></h2>
    
    <p><?php _e("Do this only if you created custom themes for your plugin. If you're using the default themes, you can leave this setting as is.", 'falite');?></p>
    
    <ol>
    	<li>
    		<?php _e('Create a folder in this location', 'falite');?> : <strong><?php echo WP_CONTENT_DIR;?></strong><br /> 
    		<?php _e('Give the folder a web-safe name (only letters and underscore will keep you safe; for example you could use', 'falite');?> : <strong><em>fa_themes</em></strong>);</li>
    	<li><?php _e('Copy all themes from the current location to the new folder you created on step 1.', 'falite');?></li>
    	<li>
    		<?php _e("Add folder path here and save. You don't need the whole server path, only path from inside wp_content folder.", 'falite');?><br /> 
    		<?php _e("We'll do all checking before setting anything. If something is wrong, we'll let you know.", 'falite');?></li>
    	<li><?php _e("After successful save, you'll see the new path in text field. You can now remove all themes from the old location if you want to.", 'falite');?></li>
    </ol>
    
    <form method="post" action="">
    	<?php wp_nonce_field('update_fa_themes_folder', 'fa_themes_folder_nonce');?>
    	<label for="fa_themes_folder" style="font-weight:bold;"><?php _e('Enter only the path from within your current wp_content folder', 'falite');?>:</label><br />
    	<?php echo WP_CONTENT_DIR;?>/<input type="text" name="fa_themes_folder" id="fa_themes_folder" value="<?php FA_themes_path(false, true);?>" size="50" />    
    	
    	<?php if( isset($folder_change_result) ):?>
    		<ul>
    		<?php if( is_wp_error( $folder_change_result ) ):?>
    			<?php 
    				$codes = $folder_change_result->get_error_codes();
    				foreach( $codes as $err_code ):?>
    				<li style="color:red;"><?php echo $folder_change_result->get_error_message($err_code);?></li>
    				<?php endforeach;
    			?>
    		<?php else:?>
    			<li style="color:green;"><?php _e('Done, folder path changed. See if everything is OK.', 'falite');?></li>
    		<?php endif;?>
    		</ul>
    	<?php endif;?>
    	
    	<?php submit_button(__('Save new path', 'falite'));?>
    </form>
</div>