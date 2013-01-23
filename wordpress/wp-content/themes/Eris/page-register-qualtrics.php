<?php

//if user is logged in, send them to home page.
if(is_user_logged_in()) {

    wp_redirect(get_site_url());
    exit;
}

//If origin param is set use it, otherwise if HTTP_REFERER is set, use it; otherwise use current page
$origin = (isset($_GET['origin'])) ? $_GET['origin'] : ((isset($_SERVER['HTTP_REFERER']) && (! isset($_POST['loginId']) && ! isset($_POST['zipcode']))) ? urlencode($_SERVER['HTTP_REFERER']) : get_site_url());

//If error is set
$error = (isset($_GET['err'])) ? urldecode($_GET['err']) : false;

//CSAT Post
$email = (isset($_POST['loginId'])) ? urldecode($_POST['loginId']) : null;
$zipcode = (isset($_POST['zipcode'])) ? urldecode($_POST['zipcode']) : null;
/**
* @package WordPress
* @subpackage White Label
*/

$message = array(
	"sears" => array(
		"low" => 
			"<h2>Thank you for sharing your feedback.</h2>
			<h1>We’re sorry we fell short&hellip;</h1>
			<p>
				We&rsquo;re just as disappointed as you. Your comments are our incentive to do so much better next time.
			</p>
			<p>
				Thank you for your valuable input&ndash;and your business.
			</p>
			<p>
				Sears Customer Experience Team
			</p>",
		"medium" => 
		"<h2>Thank you for sharing your feedback.</h2>
			<h1>There&rsquo;s room for improvement&hellip;</h1>
			<p>
				Your feedback takes us that much closer to creating a better experience for you the next time you visit.
			</p>
			<p>
				Thank you for your time&ndash;and your business.
			</p>
			<p>
				Sears Customer Experience Team
			</p>",
			"high" =>
			"<h2>Thank you for sharing your feedback.</h2>
			<h1>You’ve had a successful shopping trip? That’s fantastic!</h1>
			<p>
				We&rsquo;re happy you&rsquo;re happy with your latest Sears visit.
			</p>
			<p>
				Thank you for your valuable input&ndash;and your business.
			</p>
			<p>
				Sears Customer Experience Team
			</p>"),
	"kmart" => array(
		"low" => 
			"<h2>Thank you for sharing your feedback.</h2>
			<h1>We’re sorry we fell short&hellip;</h1>
			<p>
				We&rsquo;re just as disappointed as you. Your comments are our incentive to do so much better next time.
			</p>
			<p>
				Thank you for your valuable input&ndash;and your business.
			</p>
			<p>
				Kmart Customer Experience Team
			</p>",
		"medium" => 
		"<h2>Thank you for sharing your feedback.</h2>
			<h1>There&rsquo;s room for improvement&hellip;</h1>
			<p>
				Your feedback takes us that much closer to creating a better experience for you the next time you visit.
			</p>
			<p>
				Thank you for your time&ndash;and your business.
			</p>
			<p>
				Kmart Customer Experience Team
			</p>",
			"high" =>
			"<h2>Thank you for sharing your feedback.</h2>
			<h1>You’ve had a successful shopping trip? That’s fantastic!</h1>
			<p>
				We&rsquo;re happy you&rsquo;re happy with your latest Sears visit.
			</p>
			<p>
				Thank you for your valuable input&ndash;and your business.
			</p>
			<p>
				Kmart Customer Experience Team
			</p>")
);
?>

<style type="text/css">
	#content .qualtics-alert .content-body {
		border:solid 10px;
	}
	
	.qualtics-alert .content-body img {
		float:left;
	}
	
	.sears #content .qualtics-alert .content-body {
		border-color:#0297d6;
	}
	
	.kmart #content .qualtics-alert .content-body {
		border-color:#b10f1e;
	}
</style>

<?php get_template_part('parts/header'); ?>
    
    <section class="span12">
        
      <article class="widget content-container qualtics-alert span12">
				
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
    	<article class="widget content-container qualtics-welcome">
      	
      	<header class="content-header">
        	<h3>Welcome to the</h3>
        	<h4>Community</h4>
				</header>
				
				<section class="content-body clearfix">
					
					<p>
						Here you have a voice. We encourage you to continue to share your thoughts and ideas. 
					</p>
					
					<p>
						Here you&rsquo;ll find valuable information on topics important to you. We&rsquo;re here to make your every day life a little easier. 
					</p>
					
					<p>
						If you have a question, our customer care staff, product experts and community members are here to help. 
					</p>
					
 				</section>

			</article>
    </section>
		<section class="span8">
			<?php get_template_part('parts/register'); ?>
		</section>
<?php
    get_template_part('parts/footer');
