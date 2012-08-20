<?php
/*
 * Template Name: user_profile
 */
if ($_FILES["userphoto_image_file"]["name"] != "") {
    userphoto_profile_update(wp_get_current_user()->ID);
}
get_template_part('parts/header'); ?>

<form enctype="multipart/form-data" method="post">
<?php userphoto_display_selector_fieldset(); ?>
<button type="submit" value="submit">Submit</button>
</form>
<?php 
echo userphoto(wp_get_current_user()->ID);

get_template_part('parts/footer');