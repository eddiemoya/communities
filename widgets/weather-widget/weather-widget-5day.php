<?php if ($instance["widget_title"] != "") : ?>
	<hgroup class="content-header">
		<h3><?php echo $instance["widget_title"]; ?></h3>
	</hgroup>
<?php endif; ?>
<section class="content-body weather-widget-content clearfix <?php echo $instance["datafeature"]; ?>">
	<div class="weather-today">
		<div class="large-icon"><img src="<?php bloginfo('template_directory'); ?>/assets/img/large_weather_icons/<?php
			echo $conditions->forecast->simpleforecast->forecastday[0]->icon;
		?>.png"></div>
		<div class="conditions-today">
			<p class="day-title"><?php
				echo $conditions->forecast->simpleforecast->forecastday[0]->date->weekday_short." ".
					$conditions->forecast->simpleforecast->forecastday[0]->date->month."/".
					$conditions->forecast->simpleforecast->forecastday[0]->date->day;
			?></p>
			<p class="today-high-low">
				<span class="today-high"><?php
					echo $conditions->forecast->simpleforecast->forecastday[0]->high->fahrenheit;
				?>&deg;</span><span class="today-low"><?php
					echo "/ ".$conditions->forecast->simpleforecast->forecastday[0]->low->fahrenheit;
				?>&deg;</span>
			</p>
			<p class="conditions-string"><?php
				echo $conditions->forecast->simpleforecast->forecastday[0]->conditions;
			?></p>
			<p class="location"><?php
				echo $weather->city_state;
			?></p>
			<p class="change-location"><a href="<?php echo get_site_url(); ?>/location/" title="Change Location" class="bold" shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-location'}}}">Change Location</a></p>
		</div>
   	</div>
	<div class="extended-forecast">
		<?php for ($i=1; $i<5; $i++) : ?>
			<div class="daily-forecast">
				<p class="day-title"><?php
					echo $conditions->forecast->simpleforecast->forecastday[$i]->date->weekday_short;
				?></p>
				<div class="conditions-icon weather-<?php
					echo $conditions->forecast->simpleforecast->forecastday[$i]->icon;
				?>"></div>
				<p class="high"><?php
					echo $conditions->forecast->simpleforecast->forecastday[$i]->high->fahrenheit;
				?>&deg;</p>
				<p class="low"><?php
					echo $conditions->forecast->simpleforecast->forecastday[$i]->low->fahrenheit;
				?>&deg;</p>
			</div>
		<?php endfor; ?>
	</div>
	<div class="wunderground-img"><a href="http://www.wunderground.com" target="_blank"><img src="<?php echo plugins_url().'/wp-weather/assets/wundergroundlogo.png'; ?>"></a></div>
</section>




