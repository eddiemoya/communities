<?php
/**
 * Template Name: batman
 * @package WordPress
 * @subpackage White Label
 */

//get_template_part('parts/header');

// loop();
?>
<script type='text/javascript'>
/* <![CDATA[ */
var ajaxdata = {"ajaxurl":"http:\/\/localhost:100\/wp-admin\/admin-ajax.php","template_dir_uri":"http:\/\/localhost:100\/wp-content\/themes\/Eris","home_url":"http:\/\/localhost:100"};
/* ]]> */
</script>
<script type='text/javascript' src='/wp-content/themes/Eris/assets/js/vendor/jquery-1.7.2.min.js?ver=1.7.2'></script>
<script type='text/javascript' src='/wp-content/themes/Eris/assets/js/vendor/modernizr-2.5.3.min.js?ver=2.5.3'></script>
<script type='text/javascript' src='/wp-content/themes/Eris/assets/js/shc-jsl.js?ver=1.0'></script>
	<section class="span12">	
		
		<section class="span8"> <!-- BEGIN MAIN CONTENT: SPAN 8	-->
			
		<div id="test">	
			<ul>
				<li><a href="#" shc:gizmo="moodle" shc:gadget="test" shc:gizmo:options='{"moodle": {"key": "value"}}'>Test 1 (Gizmo and Gadget)</a></li>
				<li><a href="#" shc:gizmo="moodle" shc:gizmo:options='{"moodle": {"width":"480", "data":{"action": "get_template_ajax", "template": "page-login"}}}'>Test 2 (Gizmo Only)</a></li>
				<li><a href="#" shc:gadget="test2" shc:gadget:sprocket="catcher">Test 3 (Gadget Only, Sprocket)</a></li>
				<li><a href="#" shc:gizmo="[moodle, openID]" shc:gizmo:options='{"moodle": {"key": "value"}, "openID": {"key":"value"}}'>Test 4 (Gizmo in Array)</a></li>
				<li><a href="#" shc:gadget="test2" shc:gadget:sprocket="receiver">Test 5 (Gadget Only, Sprocket)</a></li>
				<li><a href="#" shc:gadget="test3">Test 6 (Gadget, No Sprocket)</a></li>
				<li><form method="post" action="" shc:gizmo="valid8"><input type="submit" value="submit"></form></li>
			</ul>
		</div>	
			
		<!-- TEST REGISTER FORM -->	
			
		<article class="content-container register span12">
			
			<section class="content-body clearfix">

				
				<h6 class="content-headline">Register</h6>

				<form class="form_register" id="register-form" method="post" action="#" shc:gizmo="valid8" shc:gizmo:options='{"valid8":{"login":"true"}}'>
            <ul class="form-fields">
                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="loginId">Email:</label></dt>
                        <dd class="span9"><input type="text" name="loginId" autocomplete="off" class="input_text" id="loginId" data-required="true" data-type="email" value="<?php echo $email; ?>"/></dd>
                    </dl>
                </li>
                
                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="login_confirm-email">Confirm Email:</label></dt>
                        <dd class="span9">
                        	<input type="text" name="login_confirm-email" autocomplete="off" class="input_text" id="login_confirm-email" data-required="true" data-type="confirm-email" />
                        </dd>
                    </dl>
                </li>
                
                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="logonPassword">Password:</label></dt>
                        <dd class="span9">
                        	<input type="password" name="logonPassword" autocomplete="off" class="input_text input_password" id="logonPassword" data-required="true" data-type="password" />
                        </dd>
                    </dl>
                </li>
                
                <li>
                    <dl class="clearfix">
                        <dt class="span3"><label for="zipcode">ZIP Code:</label></dt>
                        <dd class="span9"><input type="text" name="zipcode" autocomplete="off" class="input_text input_password" id="zipcode" data-required="true" data-type="zip-code" value="<?php echo $zipcode;?>" /></dd>
                    </dl>
                </li>
                
                <li class="clearfix">
                    <dl>
                        <dd class="span3">&nbsp;</dd>
                        <dd class="span9">
                            <p>
                                <input type="checkbox" name="offers" id="offers" value="True" class="input_checkbox" checked="checked"/> I would like to receive offers, updates and sale alerts from Sears
                            </p>
                        </dd>
                    </dl>
                </li>
                
                <li class="clearfix">
                	<dl>
                		<dd class="span3">&nbsp;</dd>
                		<dd class="span9">
                			<fieldset name="frequency">
                				<legend>Contact Frequency: </legend>
                				<ul>
                					<li><input type="checkbox" name="frequency"</li>
                				</ul>
                			</fieldset>
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
                                By clicking &quot;Register&quot;, I agree to the <a href="<?php echo (theme_option("brand") == "sears")? "http://www.sears.com/shc/s/nb_10153_12605_NB_CStermsofservice":"http://www.kmart.com/csterms/nb-100000000000005?adCell=WF"; ?>" target="_blank" title="Terms of Use">Terms of Use</a> and <a href="<?php echo (theme_option("brand") == "sears")? "http://www.sears.com/shc/s/nb_10153_12605_NB_CSprivacy":"http://www.kmart.com/csprivacy/nb-100000000000006?adCell=WF"; ?>" target="_blank" title="Privacy Policy">Privacy Policy</a>.
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
                            Existing Customer? <a href="/login/" title="Sign In" shc:gizmo="moodle" shc:gizmo:options='{"moodle": {"width":"480", "data":{"action": "get_template_ajax", "template": "page-login"}}}'>Sign In</a>
                        </p>
                    </dd>
                </dl>
            </li>
        </ul>
			</section>
		</article>	
			
		<!-- END TEST REGISTER FORM -->	
			
		</section> <!-- END MAIN CONTENT: SPAN 8 -->
		

		<section class="span4"> <!-- BEGIN RIGHT RAIL: SPAN 4	-->
				
			
		</section> <!-- END RIGHT RAIL: SPAN 4 -->
 
 		
	</section>	
	
	<script type="text/javascript">
		// $("#test").on('click','a',function(event){
			// console.log(event);
			// console.log(this);
			// console.log($(this));
			// event.preventDefault();
		// })
	</script>
	
<?php
//get_template_part('parts/footer');

?>