<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 */

$slider_id = (int)$_GET['slider_id'];	
FA_set_slider_options($slider_id);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php _e('FeaturedArticles Lite - Slider preview', 'falite');?></title>
<?php wp_head();?>
</head>
<body>

<?php $s = FA_style_size($slider_id);?>

<div class="backend-preview" style="<?php echo $s['x'];?>; margin:20px auto;">
	<?php FA_display_slider($slider_id);?>
</div>

<?php wp_footer();?>
</body>
</html>
<?php exit();?>