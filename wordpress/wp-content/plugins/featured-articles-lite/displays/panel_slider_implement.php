<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors@codeflavors.com )
 * @version 2.4
 */
?>
<?php if(!$slider_id):?>
<p><?php _e('Make sure that you first save the slider. Once that is done, the code needed to place the slider into your theme files will be displayed here.', 'falite');?></p>
<?php else:?>
<p><?php _e('If you want to display this Slider into your Wordpress theme template, simply copy and paste the code below in the theme file where you want it displayed.', 'falite');?></p>
<p style="padding:10px; border:1px #ccc solid; background:#FFFFCC;">
	&lt;?php<br />
    if( function_exists('FA_display_slider') ){<br />
    &nbsp;&nbsp;&nbsp;&nbsp;FA_display_slider(<?php echo $slider_id;?>);<br />
    }<br />
    ?&gt;
</p>
<?php endif;?>