<?php
  /**
    * @package WordPress
    * @subpackage Eris
    */
    
    if ($_FILES["userphoto_image_file"]["name"] != "") {
        userphoto_profile_update(wp_get_current_user()->ID);
    }
?>

<section class="profile">

    <section class="personal_info clearfix">

        <?php if($profile_type == 'myprofile'):?>

        <div class="span3 badge">
            <?php echo userphoto(wp_get_current_user()->ID);?>
            
            <form enctype="multipart/form-data" method="post">
            <?php userphoto_display_selector_fieldset_frontend(); ?>
            <button type="submit" value="submit">Submit</button>
            </form>
            
        </div>
        <div class="span9 info">
            <h4><?php echo (isset($user_profile['name']) && ! empty($user_profile['name'])) ? $user_profile['name'] : 'n/a';?></h4>
        </div>
        <dl class="span12 vitals">
            <dt>Screen Name</dt>
            <dd><?php echo (isset($user_profile['screenname']) && ! empty($user_profile['screenname'])) ? $user_profile['screenname'] : 'n/a'; ?></dd>
            <dt>Email</dt>
            <dd><?php echo (isset($user_profile['email']) && ! empty($user_profile['email']) ) ? $user_profile['email'] : 'n/a'; ?></dd>
            <dt>Location</dt>
            <dd><address><?php echo (isset($user_profile['zipcode']) && ! empty($user_profile['zipcode'])) ? $user_profile['zipcode']: 'n/a'; ?></address></dd>
            <dt>Birthday</dt>
            <dd><time datetime="<?php echo (isset($user_profile['dob']) && ! empty($user_profile['dob'])) ? date( "Y-m-d", strtotime($user_profile["dob"])) : ''; ?>" pubdate="pubdate"><?php echo (isset($user_profile['dob']) && ! empty($user_profile['dob'])) ? date( "F j, Y", strtotime($user_profile["dob"])) : 'n/a'; ?></time></dd>
        </dl>
        
        <?php else:?>
            <div class="span12 info">
                You do not have permission to view this page.
            </div>
        <?php endif;?>
    </section>
    
</section>

