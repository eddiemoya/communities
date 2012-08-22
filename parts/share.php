<?php
    # OPTIONS
    # $version =                NULL (default) or long - whether to display the long form with links on the page,
    #                                                   or short with icons hidden in a rollover.
    # $url =                    The targeted url of the shared content.
    
    $add_url = isset( $url ) ? ' addthis:url="' . $url . '"' : '';
?>

<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>
<?php if ( (isset($version) && $version == 'long') /*|| (is_object(is_widget()) && is_widget()->share_style == 'long')*/ ) : ?>
<div class="addthis_toolbox addthis_default_style">
    <a class="addthis_button_facebook_like"<?php echo $add_url; ?>></a>
    <a class="addthis_button_tweet"<?php echo $add_url; ?>></a>
    <a class="addthis_button_email"<?php echo $add_url; ?>><img src="<?php echo get_template_directory_uri() ?>/assets/img/email.png" /></a>
    <a class="addthis_button_www.shopyourway.com" addthis:title="ShopYourWay this"<?php echo $add_url; ?>><img src="<?php echo get_template_directory_uri() ?>/assets/img/shopyourway_large.png" /></a>
</div>
<?php else: ?>
<ul class="addthis dropmenu">
    <li>
        <span class="sharebutton">Share</span>
        <ul class="sharemenulinks">
            <li><a class="addthis_button_email"<?php echo $add_url; ?>>Email</a></li>
            <li><a class="addthis_button_twitter"<?php echo $add_url; ?>>Twitter</a></li>
            <li><a class="addthis_button_facebook"<?php echo $add_url; ?>>Facebook</a></li>
        </ul>
    </li>
</ul>
<?php endif; ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/assets/js/addthis.js"></script>
