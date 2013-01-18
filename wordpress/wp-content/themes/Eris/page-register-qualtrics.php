<?php

//if user is logged in, send them to home page.
if(is_user_logged_in()) {

    wp_redirect(get_site_url());
    exit;
}

//If origin param is set use it, otherwise if HTTP_REFERER is set, use it; otherwise use current page
$origin = (isset($_GET['origin'])) ? $_GET['origin'] : ((isset($_SERVER['HTTP_REFERER']) && (! isset($_POST['loginId']) && ! isset($_POST['zipcode']))) ? urlencode($_SERVER['HTTP_REFERER']) : get_site_url());

//If error is set
$error = (isset($_GET['err'])) ? urldecode($_GET['err']) : false;

//CSAT Post
$email = (isset($_POST['loginId'])) ? urldecode($_POST['loginId']) : null;
$zipcode = (isset($_POST['zipcode'])) ? urldecode($_POST['zipcode']) : null;
/**
* @package WordPress
* @subpackage White Label
*/

    get_template_part('parts/header'); ?>
    <section class="span12">
        
        <?php echo $post->post_content; ?>
        
        <?php get_template_part('parts/register'); ?>

    </section>

<?php
    get_template_part('parts/footer');
