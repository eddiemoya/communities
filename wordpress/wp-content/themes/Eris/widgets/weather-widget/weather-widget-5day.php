<?php 
    get_template_part('parts/header', 'widget');
	if ($instance["widget_title"] != "") : ?>
		<hgroup class="content-header">
			<h3><?php echo $instance["widget_title"]; ?></h3>
		</hgroup>
	<?php endif; ?>
    <section class="content-body weather-widget-content clearfix <?php echo $instance["datafeature"]; ?>">
		<div class="weather-today">
			<div class="large-icon"><img src="<?php bloginfo('template_directory'); ?>/assets/img/large_weather_icons/<?php
				echo $conditions->forecast->simpleforecast->forecastday[0]->icon;
			?>.png"></div>
			<div class="condtions-today">
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
					echo $weather->location;
				?>
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
    </section>
    
<?php get_template_part('parts/footer', 'widget') ;?>




