<?php

//include SHCSSO_CONFIG_DIR . 'errors.php';

//if user is logged in, send them to home page.
if(is_user_logged_in()) {

    wp_redirect(get_site_url());
    exit;
}

//If origin param is set use it, otherwise if HTTP_REFERER is set, use it; otherwise use current page
$origin = (isset($_GET['origin'])) ? $_GET['origin'] : ((isset($_SERVER['HTTP_REFERER']) && (! isset($_POST['loginId']) && ! isset($_POST['zipcode']))) ? urlencode($_SERVER['HTTP_REFERER']) : get_site_url() . '/');

//If error is set
$error = (isset($_GET['err'])) ? wp_kses(strip_tags($sso_errors[urldecode($_GET['err'])])) : false;

//CSAT Post
$email = (isset($_POST['loginId'])) ? urldecode($_POST['loginId']) : null;
$zipcode = (isset($_POST['zipcode'])) ? urldecode($_POST['zipcode']) : null;
//$opts = new SSO_Options;
/**
* @package WordPress
* @subpackage White Label
*/

if(! is_ajax()):

    get_template_part('parts/header'); ?>
    <section class="span8">
<?php endif; ?>

        <?php 
        	get_partial('parts/register', array('error' => $error, 'origin' => $origin, 'opts' => $opts, 'email' => $email, 'zipcode' => $zipcode));
        ?>

<?php if(! is_ajax()): ?>
    </section>

<?php get_template_part('parts/footer');

endif;
