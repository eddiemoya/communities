<?php
/*
 * Template Name: Login
 */

$origin = (isset($_GET['origin'])) ? urldecode($_GET['origin']) : ((isset($_SERVER['HTTP_REFERER'])) ? urlencode($_SERVER['HTTP_REFERER']) : get_permalink($post->ID));
$error = (isset($_GET['error'])) ? urldecode($_GET['error']) : false;

/**
 * @package WordPress
 * @subpackage White Label
 */
ob_start();

if(! is_ajax()):

get_template_part('parts/header'); ?>
	<section class="span8">
<?php endif;?>		
<article class="content-container sign-in span12">
	
	<section class="content-body clearfix">

		<h6 class="content-headline">Sign in</h6>
		
		<form class="form_login" method="post" action="<?php echo '?ssologin&origin=' . $origin;?>">
      <ul class="form-fields">
          
          <li>
              <dl class="clearfix">
                  <dt class="span3"><label for="loginId">Email:</label></dt>
                  <dd class="span9"><input type="text" name="loginId" class="input_text" id="login_email" /></dd>
              </dl>
          </li>
          
          <li>
              <dl class="clearfix">
                  <dt class="span3"><label for="logonPassword">Password:</label></dt>
                  <dd class="span8"><input type="text" name="logonPassword" class="input_text input_password" id="password" /></dd>
                  <dd class="span1"><a href="#" title="Forgot your password?" class="forgot">Forgot?</a></dd>
              </dl>
          </li>
          
          <li class="clearfix">
              <dl>
                  <dd class="span3">&nbsp;</dd>
                  <dd class="span9">
                      <button type="submit" class="<?php echo theme_option("brand"); ?>_button">Sign in</button>
                  </dd>
              </dl>
          </li>
          
      </ul>
		</form>
		

		
		<ul>
      <li class="clearfix">
        <dl>
          <dd class="span3">&nbsp;</dd>
          <dd class="span9">
            <p class="bold">
              New Customer? <a href="#" title="Sign Up">Register Now</a>
            </p>
          </dd>
        </dl>
      </li>
    </ul>
		
		<section id="login-open-id" class="open-id" shc:gizmo="openID" shc:gizmo:options="{openID:{test:'test'}}">
			<span class="or">OR</span>
			<p>use your account from</p>
			<ul class="open-id-services clearfix">
				<li class="open-id_service open-id_facebook"><a href="#" shc:openID="facebook">Facebook</a></li>
				<li class="open-id_service open-id_yahoo"><a href="#" shc:openID="yahoo">Yahoo!</a></li>
				<li class="open-id_service open-id_google"><a href="#" shc:openID="google">Google</a></li>
				<li class="open-id_service open-id_twitter"><a href="#" shc:openID="twitter">Twitter</a></li>
			</ul>
		</section>
		
	</section>

</article>
			<?php if(! is_ajax()):?>
	</section>


	<section class="span4">
	</section>
	
<?php
get_template_part('parts/footer');

endif;
?>