<?php
/*
 * Template Name: Forgot Password
 */

/**
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header'); ?>
	<section class="span8">
		
		<article class="content-container post-your-question span12">
			<section class="content-body clearfix">
				
				<h6 class="content-headline">Forgot Password</h6>
				<p>
        	Please verify the email address this account and press continue.
        </p>
				
				<form class="form_login">

          <ul class="form-fields">
              
              <li>
                  <dl class="clearfix">
                      <dt class="span3"><label for="login_email">Email:</label></dt>
                      <dd class="span9"><input type="text" name="login_email" class="input_text" id="login_email" /></dd>
                  </dl>
              </li>
              <li class="clearfix">
                  <dl>
                      <dd class="span3">&nbsp;</dd>
                      <dd class="span9">
                          <button type="submit" class="<?php echo theme_option("brand"); ?>_button">Continue</button>
                      </dd>
                  </dl>
              </li>
          </ul>
				</form>
				
			</section>
	
		</article>
		
		
	</section>


	<section class="span4">
		Tim: empty section... is it actually needed?
	</section>

<?php get_template_part('parts/footer'); ?>
