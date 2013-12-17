<?php
  /**
    * @package WordPress
    * @subpackage Eris
    */
    
    $absent_info = '&ndash;';
?>

<section class="profile">

    <section class="personal_info clearfix">

        <?php if($profile_type == 'myprofile'):?>

        <div class="span3 badge">
            <?php echo current_user_profile_photo(); ?>
            
            <form enctype="multipart/form-data" method="post" class="upload-photo">
            <?php userphoto_display_selector_fieldset_frontend(); ?>
            <button type="submit" value="save">Save</button>
            </form>
            
        </div>
        <div class="span9 info">
            <h4><?php echo (isset($user_profile['name']) && ! empty($user_profile['name'])) ? $user_profile['name'] : '<span class="faint">We don\'t yet have a name on file for you. Wanna ...</span>'; ?></h4>
            <a href="https://www.sears.com/shc/s/PersonalInfoView?catalogId=12605&amp;langId=-1&amp;storeId=10153" class="edit-profile-link">Edit Your Profile</a>
        </div>
        <dl class="span12 vitals">
            <dt>Screen Name</dt>
            <dd><?php echo (isset($user_profile['screenname']) && ! empty($user_profile['screenname'])) ? $user_profile['screenname'] : $absent_info; ?></dd>
            <dt>Email</dt>
            <dd><?php echo (isset($user_profile['email']) && ! empty($user_profile['email']) ) ? $user_profile['email'] : $absent_info; ?></dd>
            <dt>Location</dt>
            <dd><address><?php echo (return_address($profile_user->ID) != '&nbsp;') ? return_address($profile_user->ID): $absent_info;?></address></dd>
            <dt>Birthday</dt>
            <dd><time datetime="<?php echo (isset($user_profile['dob']) && ! empty($user_profile['dob'])) ? date( "Y-m-d", strtotime($user_profile["dob"])) : ''; ?>" pubdate="pubdate"><?php echo (isset($user_profile['dob']) && ! empty($user_profile['dob'])) ? date( "F j, Y", strtotime($user_profile["dob"])) : $absent_info; ?></time></dd>
        </dl>
        
        <?php else:?>
            <div class="span12 info">
                You do not have permission to view this page.
            </div>
        <?php endif;?>
    </section>
    
</section>

