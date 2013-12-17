<?php
$origin = (isset($_GET['origin'])) ? urldecode($_GET['origin']) : ((isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : get_site_url() . '/');

/**
 * @package WordPress
 * @subpackage White Label
 */

if(! is_ajax()):

get_template_part('parts/header'); ?>
	<section class="span8">
<?php endif;?>		
<article class="content-container update-zipcode span12">
	
	<section class="content-body clearfix">

		<h6 class="content-headline">Change Location</h6>
		
		<form class="form_update_zipcode" method="post" action="<?php echo $origin;?>" shc:gizmo="transFormer" id="changeZipCodeForm">
      <ul class="form-fields">
          
          <li>
              <dl class="clearfix">
                  <dt class="span3"><label for="update_zipcode">Zipcode:</label></dt>
                  <dd class="span9"><input type="text" name="update_zipcode" class="input_text" id="zipcode" shc:gizmo:form="{required:true, trim:true, pattern: /(^\d{5})(-\d{4})?$/, message: 'Please enter a valid ZIP code'}" /></dd>
              </dl>
          </li>
          
          <li class="clearfix">
              <dl>
                  <dd class="span3">&nbsp;</dd>
                  <dd class="span9">
                      <button type="submit" class="<?php echo theme_option("brand"); ?>_button">Update</button>
                  </dd>
              </dl>
          </li>
          
      </ul>
		</form>
	</section>

</article>
			<?php if(! is_ajax()):?>
	</section>


	<section class="span4">
	</section>
	
<?php
get_template_part('parts/footer');

endif;
?>