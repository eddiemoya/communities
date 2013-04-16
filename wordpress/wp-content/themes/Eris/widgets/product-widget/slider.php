<?php

$count = 1;
$span = ($pw_template == "products-vertical") ? "span12" : "span12";
$layout = ($pw_template == "products-vertical") ? "vertical" : "horizontal";
$animation = $instance['pw_animation_duration'];
$animation = (!empty($animation)) ?  ",autoSlideInterval:$animation": "" ;
$total = count($data->products);

$prodRows = $data->products;
$flNumber = 1;



 if ($instance["pw_title"] != "") { ?>
	<hgroup class="content-header">
		<h3><?php echo $instance["pw_title"]; ?></h3>
	</hgroup>
<?php } ?>

<section class="content-body product-slider-widget-content clearfix viewport_size_<?php echo($pw_fields_to_show); ?> <?php echo($span); ?>" shc:gizmo="carousel" 
	shc:gizmo:options="{viewportsize:<?php echo($pw_fields_to_show); ?>,itemcount:<?php echo($total); ?><?php echo $animation; ?>}">

	<div class="left-arrow"></div>
	<div class="product-slider-container layout_<?php echo($layout); ?>">

		<div class="product-slider-viewport span12">


		<?php foreach($prodRows as $d) { ?>

		<?php
			if($count <= $flNumber) { 
				$class = "inactive-left";
			} else if($count <= $flNumber + $pw_fields_to_show+2) { 
				$class = "active" ;
			} else { 
				$class = "inactive-right";
			}
	
			get_partial('widgets/product-widget/product', array(
				'class' => $class,
				'd' => $d,
			));
		
			$count++;


		} 
			foreach($unload as $k => $v){
				?>
				<div class="product inactive-right" data-pid="<?php echo $v; ?>" data-loaded="false"></div>
				<?php
			}

		?>





	</div>
	</div>
	<div class="right-arrow"></div>
</section>