<?php

//if user is logged in, send them to home page.
if(is_user_logged_in()) {

    wp_redirect(get_site_url());
    exit;
}

//If origin param is set use it, otherwise if HTTP_REFERER is set, use it; otherwise use current page
$origin = (isset($_GET['origin'])) ? $_GET['origin'] : ((isset($_SERVER['HTTP_REFERER']) && (! isset($_POST['loginId']) && ! isset($_POST['zipcode']))) ? urlencode($_SERVER['HTTP_REFERER']) : get_site_url() . '/');

//If error is set
$error = (isset($_GET['err'])) ? wp_kses(strip_tags(urldecode($_GET['err']))) : false;

//CSAT Post
$email = (isset($_POST['loginId'])) ? urldecode($_POST['loginId']) : null;
$zipcode = (isset($_POST['zipcode'])) ? urldecode($_POST['zipcode']) : null;
$opts = new SSO_Options;
/**
* @package WordPress
* @subpackage White Label
*/

$message = array(
	"sears" => array(
		"low" => 
			"<h1>Oops! We fell short&hellip;</h1>
			<p>
				We’re disappointed too! Your reply is our incentive to do better next time.
			</p>
			<p>
				Thank you for your valuable input, and your business.
			</p>
			<p class='signature'>
				The Sears Customer Experience Team
			</p>",
		"medium" => 
		"<h1>So, you’ve seen better&hellip;</h1>
			<p>
				Thanks to your feedback, we know there’s room for improvement.
			</p>
			<p>
				We appreciate your valuable input, and your business!
			</p>
			<p class='signature'>
				The Sears Customer Experience Team
			</p>",
			"high" =>
			"<h1>We’re glad your shopping trip was a success&hellip;for both of us!</h1>
			<p>
				But, we promise not to get too comfortable. We’d like to keep you happy!
			</p>
			<p>
				Thank you for your valuable input, and your business.
			</p>
			<p class='signature'>
				The Sears Customer Experience Team
			</p>"),
	"kmart" => array(
		"low" => 
			"<h1>Oops! We fell short&hellip;</h1>
			<p>
				We’re disappointed too! Your reply is our incentive to do better next time.
			</p>
			<p>
				Thank you for your valuable input, and your business.
			</p>
			<p class='signature'>
				The Kmart Customer Experience Team
			</p>",
		"medium" => 
			"<h1>So, you’ve seen better&hellip;</h1>
			<p>
				Thanks to your feedback, we know there’s room for improvement.
			</p>
			<p>
				We appreciate your valuable input, and your business!
			</p>
			<p class='signature'>
				The Kmart Customer Experience Team
			</p>",
		"high" =>
			"<h1>We’re glad your shopping trip was a success&hellip;for both of us!</h1>
			<p>
				But, we promise not to get too comfortable. We’d like to keep you happy!
			</p>
			<p>
				Thank you for your valuable input, and your business.
			</p>
			<p class='signature'>
				The Kmart Customer Experience Team
			</p>")
);
?>

<?php get_template_part('parts/header'); ?>
    
    <section class="span12">
        
      <article class="widget content-container qualtics-alert span12">
				
				<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/qualtrics.css" />
				
				<section class="content-body clearfix">

					<img src="<?php echo get_template_directory_uri(); ?>/assets/img/qualtrics/<?php echo theme_option("brand"); ?>_we-love-feedback.png" />

        	<?php 
        	
        	if (isset($_GET["rating"])) {
        		$num = $_GET['rating'];
						if ($num < 7) {
							echo $message[theme_option("brand")]['low'];
						} else if ($num < 9) {
							echo $message[theme_option("brand")]['medium'];
						}
						else {
							echo $message[theme_option("brand")]['high'];
						}
        	} else {
        		echo $message[theme_option("brand")]['high'];
        	}
        	
        	?>
				
				</section>

			</article>
                

    </section>
    <section class="span4">
    	<section class="shadow-border-right clear">
    		<article class="widget content-container qualtics-welcome span12">
	      	
	      	<header class="content-header clear">
	        	<h3>Welcome to the</h3>
	        	<h4>Community</h4>
					</header>
					
					<section class="content-body clearfix clear">
						
						<p class="bold">
							Here you'll find valuable information on topics important to you. 
						</p>
						
						<ul class="circles">
							<li>
								<p>
									Share thoughts and ideas with a community that has similar interests
								</p> 
							</li>
							
							<li>
								<p>
									Get feedback and read reviews on products you’re interested in purchasing
								</p>
							</li>
							<li>
								<p>
									Chat with customer care staff, product experts and other community members
								</p>
							</li>
						</ul>
						
						<p>
							We're here to make your every day life a little easier. Let’s get started! 
						</p>
						
	 				</section>
	
				</article>
    	</section>
    </section>
		<section class="span8">
			<article class="content-container register span12">
			    <section class="content-body clearfix">
			        <h6 class="content-headline">Join now!</h6>
					
					  <?php if($error):?>
            			<div><?php echo $error;?></div>
       				  <?php endif;?>
			
			        <form class="form_register" id="register-form" method="post" action="<?php echo $opts->endpoint . 'shcRegistration'; ?>" shc:gizmo="transFormer">
			            <ul class="form-fields">
			                <li>
			                    <dl class="clearfix">
			                        <dt class="span3"><label for="loginId">Email:</label></dt>
			                        <dd class="span9"><input type="text" name="loginId" autocomplete="off" class="input_text" id="loginId" shc:gizmo:form="{required:true, trim:true, pattern: /^.+@.+?\.[a-zA-Z]{2,}$/, message: 'Please enter a valid email address'}" value="<?php if (isset($_GET['loginId'])) {echo $_GET['loginId'];} ?>"/></dd>
			                    </dl>
			                </li>
			
			                <li>
			                    <dl class="clearfix">
			                        <dt class="span3"><label for="login_confirm-email">Confirm Email:</label></dt>
			                        <dd class="span9">
			                            <input type="text" name="login_confirm-email" autocomplete="off" class="input_text" id="login_confirm-email" shc:gizmo:form="{required:true, trim:true, custom: function(self) {if (self.value.toLowerCase() != ($('#loginId').attr('value')).toLowerCase()) return false; else return true;}, message: 'Your email does not match. Please check and try again.'}" value="" />
			                        </dd>
			                    </dl>
			                </li>
			
			                <li>
			                    <dl class="clearfix">
			                        <dt class="span3"><label for="logonPassword">Password:</label></dt>
			                        <dd class="span9">
			                            <input
			                            type="password"
			                            name="logonPassword"
			                            autocomplete="off"
			                            class="input_text input_password"
			                            id="logonPassword"
			                            shc:gizmo:form="{required:true, pattern: /^\w*(?=\w{6,})(?=\w*\d)(?=\w*[a-zA-Z])(?!\w*_)\w*$/, message: 'Please enter a valid password.'}"
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
			                            }"
			                            />
			                        </dd>
			                    </dl>
			                </li>
			
			                <li>
			                    <dl class="clearfix">
			                        <dt class="span3"><label for="zipcode">ZIP Code:</label></dt>
			                        <dd class="span9"><input type="text" name="zipcode" autocomplete="off" class="input_text input_password" id="zipcode" shc:gizmo:form="{required:true, trim:true, pattern: /(^\d{5})(-\d{4})?$/, message: 'Please enter a valid ZIP code'}" value="<?php if (isset($_GET['Zipcode'])) {echo $_GET['Zipcode'];} ?>" /></dd>
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
			                            <button type="submit" class="<?php echo theme_option("brand"); ?>_button">Join</button>
			                        </dd>
			                    </dl>
			                </li>
			
			                <li class="clearfix">
			                    <dl>
			                        <dd class="span3">&nbsp;</dd>
			                        <dd class="span9">
			                            <p>
			                                By clicking &quot;Join&quot;, I agree to the <a href="<?php echo (theme_option("brand") == "sears")? "http://www.sears.com/shc/s/nb_10153_12605_NB_CStermsofservice":"http://www.kmart.com/csterms/nb-100000000000005?adCell=WF"; ?>" target="_blank" title="Terms of Use">Terms of Use</a> and <a href="<?php echo (theme_option("brand") == "sears")? "http://www.sears.com/shc/s/nb_10153_12605_NB_CSprivacy":"http://www.kmart.com/csprivacy/nb-100000000000006?adCell=WF"; ?>" target="_blank" title="Privacy Policy">Privacy Policy</a>.
			                            </p>
			                        </dd>
			                    </dl>
			                </li>
			            </ul>
						<input type="hidden" name="service" value="<?php echo $opts->url_append_qs("origin={$origin}&ssoregister", urldecode($origin));?>" />
						<input type="hidden" name="sourceSiteid" value="<?php echo $opts->sso_site_id;?>" />
			        </form>
			
			        <ul>
			            <li class="clearfix">
			                <dl>
			                    <dd class="span3">&nbsp;</dd>
			                    <dd class="span9">
			                        <p class="bold">
			                            Existing Customer? <a href="/login/" title="Sign In" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}">Sign In</a>
			                        </p>
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
			
			        <section id="login-open-id" class="open-id" shc:gizmo="openID">
			            <span class="or">OR</span>
			            <p>use your account from</p>
			            <ul class="open-id-services clearfix">
			                <li class="open-id_service open-id_facebook"><a href="#" shc:openID="facebook">Facebook</a></li>
			                <li class="open-id_service open-id_yahoo"><a href="#" shc:openID="yahoo">Yahoo!</a></li>
			                <li class="open-id_service open-id_google"><a href="#" shc:openID="google">Google</a></li>
			                <!-- <li class="open-id_service open-id_twitter"><a href="#" shc:openID="twitter">Twitter</a></li> -->
			            </ul>
			        </section>
			
			    </section>
			</article>
		</section>
<?php
    get_template_part('parts/footer');
