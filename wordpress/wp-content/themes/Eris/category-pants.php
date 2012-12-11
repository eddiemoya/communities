<?php
/**
 * Template Name: batman
 * @package WordPress
 * @subpackage White Label
 */

// get_template_part('parts/header');

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
			
			<script type="text/javascript">
				var turtle = function() {
					var fruits = [];
					
					this.add = function(fruit) {
						fruits[fruits.length] = fruit;
					
						console.log(fruits);
					}
					
				}
			</script>
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
// get_template_part('parts/footer');

?>