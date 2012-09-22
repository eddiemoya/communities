<?php
    # OPTIONS
    # $version =    NULL (default) or long - whether to display the long form with links on the page,
    #                                     or short with icons hidden in a rollover.
    # $url =        The targeted url of the shared content.
    # $title =      The string to appear in an optional title field.
    # $image =      Path to an optional image to accompany the posting.

    $add_url = isset( $url ) ? ' addthis:url="' . $url . '"' : '';
    $encoded_title = isset( $title ) ? urlencode( $title ) : '';
    $encoded_image = isset( $image ) ? urlencode( $image ) : '';
?>

<?php if ( ( isset( $version ) && $version == 'long' ) || ( is_object( is_widget() && function_exists( 'is_widget' ) ) && is_widget()->share_style == 'long' ) ) : ?>
<div class="addthis_toolbox addthis_default_style">
    <a class="addthis_button_facebook_like"<?php echo $add_url; ?>></a>
    <a class="addthis_button_tweet"<?php echo $add_url; ?>></a>
    <a onclick="javascript:window.open('http://shopyourway.com/sharer/share?title=<?php echo $encoded_title; ?>&amp;link=<?php echo urlencode( $url ); ?>&amp;sourceSiteUrl=&amp;sourceSiteAlias=&amp;content=&amp;imageUrl=<?php echo $encoded_image; ?>','ShopYourWay','width=650,height=350'); return false;"><img src="<?php echo get_template_directory_uri() ?>/assets/img/shopyourway_large.png" /></a>
    <a class="addthis_button_email"<?php echo $add_url; ?>><img src="<?php echo get_template_directory_uri() ?>/assets/img/email.png" /></a>
</div>
<?php else: ?>
<ul class="addthis dropmenu">
    <li>
        <span class="sharebutton">Share</span>
        <ul class="sharemenulinks">
            <li><a class="addthis_button_facebook"<?php echo $add_url; ?>>Facebook</a></li>
            <li><a class="addthis_button_twitter"<?php echo $add_url; ?>>Twitter</a></li>
            <li><a class="addthis_button_shopyourway" onclick="javascript:window.open('http://shopyourway.com/sharer/share?title=<<?php echo $encoded_title; ?>&amp;link=<?php echo urlencode( $url ); ?>&amp;sourceSiteUrl=&amp;sourceSiteAlias=&amp;content=&amp;imageUrl=<?php echo $encoded_image; ?>','ShopYourWay','width=650,height=350'); return false;"><img src="http://static.shopyourway.com/static/share-buttons/small-light.png" alt=""/> ShopYourWay</a></li>
            <li><a class="addthis_button_email"<?php echo $add_url; ?>>Email</a></li>
        </ul>
    </li>
</ul>
<?php endif; ?>
