<?php if ($instance["widget_title"] != "") : ?>
	<hgroup class="content-header">
		<h3><?php echo $instance["widget_title"]; ?></h3>
	</hgroup>
<?php endif; ?>
<section class="content-body weather-widget-content clearfix <?php echo $instance["datafeature"]; ?> unknown-location">
	<div class="select-location">
		<p class="bold">Well.. this is awkward.</p>
		<p>We weren't able to figure out your location.</p>
		<p><a href="<?php echo get_site_url(); ?>/location/" title="Change Location" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-location'}}}">Where are you?</a></p>
	</div>
	<div class="weather-today">
		<div class="large-icon"><img src="<?php bloginfo('template_directory'); ?>/assets/img/large_weather_icons/clear.png"></div>
		<div class="conditions-today">
			<p class="day-title"><?php echo date("D n/j")?></p>
			<p class="today-high-low">
				<span class="today-high">85&deg;</span><span class="today-low">/ 76&deg;</span>
			</p>
			<p class="conditions-string">Clear</p>
			<p class="location">Chicago, IL</p>
			<p class="change-location"><a href="<?php echo get_site_url(); ?>/location/" title="Change Location" class="bold" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-location'}}}">Change Location</a></p>
		</div>
   	</div>
	<div class="extended-forecast">
		<div class="daily-forecast">
			<p class="day-title"><?php echo date("D", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))); ?></p>
			<div class="conditions-icon weather-rain"></div>
			<p class="high">87&deg;</p>
			<p class="low">78&deg;</p>
		</div>
		<div class="daily-forecast">
			<p class="day-title"><?php echo date("D", mktime(0, 0, 0, date("m")  , date("d")+2, date("Y"))); ?></p>
			<div class="conditions-icon weather-clear"></div>
			<p class="high">86&deg;</p>
			<p class="low">77&deg;</p>
		</div>
		<div class="daily-forecast">
			<p class="day-title"><?php echo date("D", mktime(0, 0, 0, date("m")  , date("d")+3, date("Y"))); ?></p>
			<div class="conditions-icon weather-sunny"></div>
			<p class="high">91&deg;</p>
			<p class="low">78&deg;</p>
		</div>
		<div class="daily-forecast">
			<p class="day-title"><?php echo date("D", mktime(0, 0, 0, date("m")  , date("d")+4, date("Y"))); ?></p>
			<div class="conditions-icon weather-rain"></div>
			<p class="high">88&deg;</p>
			<p class="low">80&deg;</p>
		</div>
	</div>
</section>



