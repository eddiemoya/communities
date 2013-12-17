<?php
/*
 * Template Name: user_profile
 */
get_template_part('parts/header'); ?>

<form enctype="multipart/form-data" method="post">
<?php userphoto_display_selector_fieldset_frontend(); ?>
<button type="submit" value="submit">Submit</button>
</form>
<?php 
echo userphoto(wp_get_current_user()->ID);

get_template_part('parts/footer');