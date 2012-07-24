<?php
/**
 * @package WordPress
 * @subpackage White Label
 */

get_template_part('parts/header');

loop();
?>

	<section class="clearfix span8">
		
		<article class="content-container post-your-question span12">
			<section class="content-body clearfix">
				
				<h6 class="content-headline">Sign in</h6>
				
				<form class="form_login">
					<ul class="form-fields">
						
						<li>
							<dl class="clearfix">
								<dt class="span3"><label for="login_email">Email:</label></dt>
								<dd class="span9"><input type="text" name="login_email" class="input_text" id="login_email" /></dd>
							</dl>
						</li>
						
						<li>
							<dl class="clearfix">
								<dt class="span3"><label for="login_password">Password:</label></dt>
								<dd class="span8"><input type="login_password" name="login_password" class="input_text input_password" id="password" /></dd>
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
				
			</section>
		</article>
		
		
		<article class="content-container post-your-question span12">
			<section class="content-body clearfix">
				
				<h6 class="content-headline">Register</h6>
				
				<form class="form_register">
					<ul class="form-fields">
						
						<li>
							<dl class="clearfix">
								<dt class="span3"><label for="login_email">Email:</label></dt>
								<dd class="span9"><input type="text" name="login_email" class="input_text" id="login_email" /></dd>
							</dl>
						</li>
						
						<li>
							<dl class="clearfix">
								<dt class="span3"><label for="login_confirm-email">Confirm Email:</label></dt>
								<dd class="span9"><input type="text" name="login_confirm-email" class="input_text" id="login_confirm-email" /></dd>
							</dl>
						</li>
						
						<li>
							<dl class="clearfix">
								<dt class="span3"><label for="login_password">Password:</label></dt>
								<dd class="span9"><input type="password" name="login_password" class="input_text input_password" id="login_password" /></dd>
							</dl>
						</li>
						
						<li>
							<dl class="clearfix">
								<dt class="span3"><label for="login_zip-code">ZIP Code:</label></dt>
								<dd class="span9"><input type="password" name="login_password" class="input_text input_password" id="login_zip-code" /></dd>
							</dl>
						</li>
						
						<li class="clearfix">
							<dl>
								<dd class="span3">&nbsp;</dd>
								<dd class="span9">
									<p>
										<input type="checkbox" name="offers" id="offers" value="True" class="input_checkbox" /> I would like to receive offers, updates and sale alerts from Sears
									</p>
								</dd>
							</dl>
						</li>
						
						<li class="clearfix">
							<dl>
								<dd class="span3">&nbsp;</dd>
								<dd class="span9">
									<button type="submit" class="<?php echo theme_option("brand"); ?>_button">Register</button>
								</dd>
							</dl>
						</li>
						
						<li class="clearfix">
							<dl>
								<dd class="span3">&nbsp;</dd>
								<dd class="span9">
									<p>
										By clicking &quot;Register&quot;, I agree to the <a href="#" title="Terms of Use">Terms of Use</a> and <a href="#" title="Privacy Policy">Privacy Policy</a>.
									</p>
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
									Existing Customer? <a href="#" title="Sign In">Sign In</a>
								</p>
							</dd>
						</dl>
					</li>
				</ul>
				
			</section>
		</article>
		
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

	</section>
<?php
get_template_part('parts/footer');

?>


Tim: Does category-tim  (post form) need to be a widget to be placed within dropzones?
