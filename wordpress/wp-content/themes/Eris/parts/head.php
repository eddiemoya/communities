<?php
/**
 * @author Tim Steele 
 */
?>
<header id="header" class="<?php echo theme_option("brand"); ?> clearfix">
	
	<div class="<?php echo (theme_option("brand") == "sears")? "span4":"span3"; ?>">
		<a href="<?php echo site_url(''); ?>" id="logo" class="invisible"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/<?php echo ucfirst(theme_option("brand")); ?>/logo.png" /></a>
	</div>
	
	<div class="<?php echo (theme_option("brand") == "sears")? "span5":"span6"; ?>">
		<form id="search">
			<div>
				<input type="text" class="input_text icon_search" name="search" id="input_search" />
				<button type="submit" id="button_search">Go</button>
			</div>
		</form>
	</div>
	
	<aside class="span3">
		<ul>
			<!-- LOGGED IN 
			<li></li>-->
			<!-- LOGGED OUT -->
			<li>
				
				<ul>
					<li id="header_login"><a href="/login/" title="Login" class="bold" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480}}">Login</a></li>
					<li id="header_login"><a href="/wp-content/themes/Eris/assets/ajax/login.php" title="Login" class="bold" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480}}">Login</a></li>
					<li id="header_join"><a href="#" title="Sign Up: Join the Community" class="bold" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:'join', method:'local'}}">Join the community</a></li>
				</ul>
				
			</li>
			
			<li>
				
				<ul>
					<li id="header_feedback"><a href="#" title="Feedback">Feedback</a></li>
					<li id="header_shopping">Go Shopping: <a href="http://www.<?php echo ucfirst(theme_option("brand")); ?>.com" title="<?php echo ucfirst(theme_option("brand")); ?>" rel="external" title="<?php echo ucfirst(theme_option("brand")); ?>" class="bold"><?php echo ucfirst(theme_option("brand")); ?></a></li>
				</ul>
				
			</li>
		</ul>
	</aside>
	
</header>