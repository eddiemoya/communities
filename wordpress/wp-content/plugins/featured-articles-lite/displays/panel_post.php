<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors@codeflavors.com )
 * @version 2.4
 */
?>
<div class="misc-pub-section misc-pub-section-last" style="width:98%; max-width:98%; border-bottom:1px #DFDFDF solid;">
	<?php wp_nonce_field('fa_article_featured', 'fa_nonce');?>
    <table cellspacing="10" style="width:100%;">
    	<tr>
        	<td rowspan="3" width="200">
            	<div id="FA-curr-img-wrap" style="background:#FFF; width:150px; height:150px; border:1px #CCC solid; margin-bottom:10px;">
				<?php if(isset($current_image)):?>
                	<img src="<?php echo $current_image;?>" alt="<?php _e('Current Featured Articles image set for this post.', 'falite');?>" id="FA-current-image" />
                <?php endif;?>
                </div>
           		
                <?php $visibility = !isset( $current_image ) ? 'style="display:none"' : 'style="display:inline;"' ;?>
                <div id="FA_rem_image" <?php echo $visibility;?>>
                	<a href="#" class="button"><?php _e('Remove image', 'falite');?></a>
                </div>
                
                <?php $visibility = isset( $current_image ) ? 'style="display:none"' : 'style="display:inline;"' ;?>
            	<a <?php echo $visibility;?> href="media-upload.php?post_id=<?php echo $post->ID;?>&type=image&edit-falite=1&tab=library&TB_iframe=1" class="thickbox button" id="FA_new_image" title="<?php _e('Add new image for Featured Articles','wp_featured_articles', 'falite')?>"><?php _e('Set image', 'falite');?></a>
            </td>
            <td>
            	<label for="fa_cust_title"><strong><?php _e('Slide Title', 'falite');?></strong></label><br />
				<input type="text" name="fa_cust_title" value="<?php echo $fields['_fa_cust_title'];?>" style="width:98%" /><br />
            </td>
        </tr>
        <tr>
        	<td>
            	<label for="fa_cust_txt"><strong><?php _e('Slide Text', 'falite');?></strong></label><br />
				<textarea id="fa_cust_txt" tabindex="6" name="fa_cust_txt" cols="40" rows="1" style="width:98%;"><?php echo $fields['_fa_cust_txt'];?></textarea>
            </td>
        </tr>
        <tr>
        	<td>
            	<label for="fa_cust_url"><strong><?php _e('Link text', 'falite');?></strong></label><br />
				<input type="text" name="fa_cust_link" value="<?php echo $fields['_fa_cust_link'];?>" style="width:98%" /><br />
            </td>
        </tr>
    </table>
</div>
<div class="misc-pub-section misc-pub-section-last" style="width:98%; max-width:98%; border-bottom:1px #DFDFDF solid;">
	<strong><?php _e('Custom slide CSS class', 'falite');?></strong>
    <p><?php _e('You can change this particular slide CSS styles by styling the class you put in here.', 'falite');?></p>
    <input type="text" name="fa_cust_class" value="<?php echo $fields['_fa_cust_class'];?>" />
</div>

<div class="misc-pub-section misc-pub-section-last" style="width:98%; max-width:98%; border-bottom:1px #DFDFDF solid;">
	<strong><?php _e('Slide background color', 'falite');?></strong>
    <p><?php _e('When placed into a slider, this item will display the background color you set below.', 'falite');?></p>
    <input type="text" name="fa_bg_color" id="fa_bg_color" value="<?php echo $fields['_fa_bg_color'];?>" size="10" /> <a id="color-sample" href="#" style="padding:2px 10px; margin:0px 5px; background-color:<?php echo $fields['_fa_bg_color'];?>">&nbsp;</a> <a class="button" href="#" id="toggle_color_picker"><?php _e('Choose color', 'falite');?></a>
    <div id="colorPickerDiv" style="display:none;"></div>
    <script language="javascript" type="text/javascript">
    	jQuery(document).ready(function(){
			//*
			jQuery(document).click( function() {
				jQuery('#colorPickerDiv').hide();
			});			
			//*/
			///*
			jQuery('#toggle_color_picker').click(function(e){
				e.preventDefault();
				e.stopPropagation();
				jQuery('#colorPickerDiv').toggle();
			});		
			//*/
			jQuery('#colorPickerDiv').farbtastic(function(color){
				jQuery('#fa_bg_color').val(color);
				jQuery('#color-sample').css('background-color', color);
			});
		})
    </script>
</div>

<div class="misc-pub-section misc-pub-section-last" style="width:98%; max-width:98%;">
	<strong><?php _e('Insert a FA slider into this post/page', 'falite');?></strong>
    <p><?php _e('Just choose the slider from the list and hit insert. The shortcode will be placed into the editor automatically.', 'falite');?></p>
    <select name="fa_lite_shortcode" id="fa_lite_shortcode">
        <option value=""><?php _e('Choose slider', 'falite');?></option>
        <?php 
            $original_post = $post;
        	if ( $loop->have_posts() ) : 
                while ( $loop->have_posts() ) :
                    $loop->the_post();
        ?>
        <option value="<?php the_ID();?>"><?php the_title();?> [<?php the_ID();?>]</option>
        <?php
                endwhile;
            endif;	
            wp_reset_query();
            $post = $original_post;
        ?>	
    </select>
    <input type="button" id="add_fa_slider" value="insert" class="button-primary" />
</div>
<script language="javascript" type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#add_fa_slider').click(function(){
			var v = jQuery('#fa_lite_shortcode').val();
			if(''==v){
				alert('<?php _e('Please select a slider first.', 'falite');?>');
				return;
			}
			send_to_editor(' [FA_Lite id="'+v+'"] ');
		})
		
		jQuery()
		
	})
	
	var FA_post_id = '<?php echo $post->ID;?>';
	var nonce = '<?php echo wp_create_nonce( "featured-articles-post-image-remove" );?>';
	
	jQuery(document).ready(function(){
		
		jQuery('#FA_rem_image .button').click(function(e){
			e.preventDefault();
			FA_remove_image();
		})
		
		function FA_remove_image(){
			jQuery.post(ajaxurl, {
				action:"FA-remove-post-thumbnail", post_id: FA_post_id, _ajax_nonce: nonce, cookie: encodeURIComponent(document.cookie)
			}, function(r){
					if( r !== '1' ) return;
					jQuery('#FA_rem_image').css('display', 'none');
					jQuery('#FA_new_image').css('display', 'inline');
					jQuery('#FA-curr-img-wrap').empty();	
				}
			);
		}
		
	})	
</script>
<br class="clear" />