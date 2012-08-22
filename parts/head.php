<?php
    /**
    * @author Tim Steele
    */
    $current_user = wp_get_current_user();

    $qualtricsUrl = theme_option('brand') == 'sears' ? 'http://searshc.us2.qualtrics.com/SE/?SID=SV_3QzHxmNKUzYTjNy' : 'http://searshc.us2.qualtrics.com/SE/?SID=SV_9X1h8K6RVZUV7x2';
?>
<header id="header" class="<?php echo theme_option("brand"); ?> clearfix">
	
	<div class="<?php echo (theme_option("brand") == "sears")? "span4":"span3"; ?>">
		<a href="<?php echo site_url(''); ?>" id="logo" class="invisible"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/<?php echo theme_option("brand"); ?>/logo.png" /></a>
	</div>
	
	<div class="<?php echo (theme_option("brand") == "sears")? "span5":"span6"; ?>">
		<form method="get" action="<?php echo home_url( '/' )?>" id="search">
			<div>
				<input type="text" class="input_text icon_search" name="s" id="input_search" />
				<button type="submit" id="button_search">Go</button>
			</div>
		</form>
	</div>
	
	<aside class="span3">
		<ul>
			<li>
				<ul>
				    <?php if ( is_user_logged_in() ): ?>
				        <li>
				            <a href="<?php echo site_url(''); ?>/author/<?php echo $current_user->user_nicename; ?>" title="View your profile" class="bold">
				                <?php echo current_user_profile_thumbnail(); ?>
				                <?php echo $current_user->user_nicename; ?>
				            </a>
				        </li>
				        <li><?php sso_logout_link('Logout');?> </li>
				        
    					<!-- <li><a href="<?php //echo wp_logout_url( get_permalink() ); ?>" title="Logout" class="bold">Logout</a></li> -->
				    <?php else: ?>
    					<li><a href="<?php echo site_url('login'); ?>" title="Login" class="bold" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}">Login</a></li>
							<li><a href="<?php echo site_url('register'); ?>" title="Sign Up: Join the Community" class="bold" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-register'}}}">Join the community</a></li>
					<?php endif; ?>
				</ul>
			</li>
			
			<li>
				
				<ul>
					<li id="header_feedback"><a href="<?php echo $qualtricsUrl; ?>" title="Feedback">Feedback</a></li>
					<li id="header_shopping">Go Shopping: <a href="http://www.<?php echo theme_option("brand"); ?>.com" title="<?php echo ucfirst( theme_option("brand") ); ?>" rel="external" title="<?php echo ucfirst( theme_option("brand") ); ?>" class="bold"><?php echo ucfirst( theme_option("brand") ); ?></a></li>
				</ul>
				
			</li>
		</ul>
	</aside>
	
</header>