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
				
				<form class="form_login" method="post" action="<?php echo site_url('/forgot-password/') . ((isset($_GET['auth_token'])) ? '?auth_token=' . $_GET['auth_token'] : null); ?>" shc:gizmo="transFormer" />

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
                      <dt class="span3"><label for="new_password">New Password:</label></dt>
                      <dd class="span9"><input type="password" 
                      							name="new_password"
                      							autocomplete="off" 
                      							class="input_text input_password" 
                      							id="new_password"
                      							shc:gizmo:form="{required:true, pattern: /^\w*(?=\w{8,})(?=\w*\d)(?=\w*[a-zA-Z])(?!\w*_)\w*$/, message: 'Please enter a valid password.'}"
                            					shc:gizmo="tooltip"
					                            shc:gizmo:options="
					                            {
					                                tooltip: {
					                                    displayData: {
					                                        element: 'passInfo'
					                                    },
					                                    events: {
					                                        click: {
					                                            active: false
					                                        },
					                                        blur: {
					                                            active: true
					                                        },
					                                        focus: {
					                                            active: true
					                                        }
					                                    },
					                                    arrowPosition: 'left'
					                                }
					                            }" /></dd>
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
	          <div id="passInfo" class="info hide">
	            <p class="bold">Your password must have:</p>
	            <ul>
	                <li>6 or more characters total</li>
	                <li>At least one letter</li>
	                <li>At least one number</li>
	                <li>No space</li>
	                <li>No special characters such as ! or ?</li>
	            </ul>
	            <p>All passwords are cAsE sEnSiTiVe.</p>
	        </div>
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