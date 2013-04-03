<?php
/*
 * Template Name: Forgot Password
 */

/**
 * @package WordPress
 * @subpackage White Label
 */

//Form handler
if(! empty($_POST)) {
	
	//Only proceed if SSO plugin is active
	if(class_exists('SSO_Profile')) {
	
		$profile = new SSO_Profile;
	
		//Forgot password request
		if(isset($_POST['login_email'])) {
			
			$response = $profile->reset_password($_POST['login_email']);
			
		}
		
		//Enter new password 
		if(isset($_POST['new_password']) && isset($_POST['auth_token'])) {
			
			$response = $profile->authorize_reset($_POST['new_password'], $_POST['auth_token']);
			
		}
	
	}
	
	/**
	 * $response is an array with the response message. 
	 * Access the message via $response['message']
	 */
}
if(! is_ajax()):

get_template_part('parts/header'); ?>
	<section class="span8">
<?php endif;?>	

		<?php if(isset($response)) echo $response['message'] ;?>
		<article class="content-container forgot-password span12">
			<section class="content-body clearfix">
				<h6 class="content-headline"><?php echo (isset($_GET['auth_token'])) ? 'Enter New Password' : 'Forgot Password'?></h6>
				<p>
        	<?php echo (isset($_GET['auth_token'])) ? 'Enter a new password for your account below.' : 'Please verify the email address this account and press continue.';?>
        </p>
				
				<form class="form_login" method="post" action="<?php echo site_url('/forgot-password/');?>">

          <ul class="form-fields">
              <?php if(! isset($_GET['auth_token'])):?>
              <li>
                  <dl class="clearfix">
                      <dt class="span3"><label for="login_email">Email:</label></dt>
                      <dd class="span9"><input type="text" name="login_email" class="input_text" id="login_email" /></dd>
                  </dl>
              </li>
              <?php else:?>
              <li>
                  <dl class="clearfix">
                      <dt class="span3"><label for="login_email">New Password:</label></dt>
                      <dd class="span9"><input type="text" name="new_password" class="input_text" id="new_password" /></dd>
                  </dl>
              </li>
              
               <input type="hidden" name="auth_token" value="<?php echo $_GET['auth_token'];?>" />
               
              <?php endif;?>
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
		
			<?php if(! is_ajax()):?>
	</section>


	<section class="span4">
	</section>
	
<?php
get_template_part('parts/footer');

endif;