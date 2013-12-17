<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors@codeflavors.com )
 * @version 2.4
 */
?>
<div id="submitpost" class="submitbox">
	<div id="minor-publishing">
		<?php if( $slider_id ): ?> 
		<div id="minor-publishing-actions">
			<div id="preview-action"> 
				<a id="fa-slider-preview" target="wp-preview" href="admin.php?page=featured-articles-lite/preview.php&slider_id=<?php echo $slider_id;?>&noheader=true" class="preview button FA_dialog"><?php _e('Preview Slider', 'falite');?></a>
			</div>
			<div class="clear"></div>
		</div>
		<?php endif;?>
		<div id="misc-publishing-actions">
			<div class="misc-pub-section">
				<label for="section_title" title="<?php _e('Choose a name for this slider.');?>"><strong><?php _e('Slider name', 'falite');?>:</strong></label>
				<input type="text" id="section_title" name="section_title" value="<?php echo $options['section_title']; ?>" style="margin:5px 0px; width:100%;" /><br />
				
				<input type="checkbox" name="section_display" id="section_display" value="1" <?php if($options['section_display']): ?> checked="checked"<?php endif;?> class="FA_optional"<?php if($fields['section_display'] == 0):?> disabled="disabled"<?php endif;?> /> <label class="FA_optional<?php if($fields['section_display'] == 0):?> disabled<?php endif;?>" for="section_display" title="<?php _e('Choose whether to display the slider title or not', 'falite');?>"><?php _e('Display slider name', 'falite');?> </label><br />
			</div>
			
			<div class="misc-pub-section last">
				<strong><?php _e('Slider size', 'falite');?></strong><br>
				<label for="slider_width"><?php _e('Width', 'falite');?>: </label> <input type="text" name="slider_width" id="slider_width" value="<?php echo $options['slider_width']; ?>" class="small-text" /><br />
				<label for="slider_height"><?php _e('Height', 'falite');?>: </label> <input type="text" name="slider_height" id="slider_height" value="<?php echo $options['slider_height']; ?>" class="small-text" /><br />
				<span class="note"><?php _e('Note: to disable size control, enter 0 for size.', 'falite');?></span>
			</div>					
		</div>
		<div class="clear"></div>
	</div>
	<div id="major-publishing-actions">
		<?php if( $slider_id ): ?>        
        <div id="delete-action">
        	<a href="<?php echo wp_nonce_url( $current_page.'&noheader=true&action=delete&item_id='.$slider_id );?>" class="submitdelete deletion" onclick="return confirm('<?php _e('Are you sure you want to delete this slideshow?', 'falite');?>');"><?php _e('Delete', 'falite');?></a>
        </div>
        <?php endif;?>
        <div id="publishing-action">
        	<input type="submit" value="<?php $slider_id ? _e('Update', 'falite') : _e('Save', 'falite');?>" accesskey="p" tabindex="5" id="publish" class="button-primary" name="save">
        </div>
        <div class="clear"></div>
	</div>
</div>

<script language="javascript" type="text/javascript">
	/* display the color option if selected theme has any */
	(function($){
		$(document).ready(function(){
			$('#FA_active_theme').change(function(e){
				$('.colors_selector').css('display', 'none');
				var id = $(this).val()+'-colors';
				try{
					$('#'+id).css('display', 'block');
				}catch(err){
					console.log(err);
				}
			})
		})			
	})(jQuery)
</script>