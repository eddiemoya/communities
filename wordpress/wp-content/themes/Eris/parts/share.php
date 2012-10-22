<?php
    # OPTIONS
    # $version =    NULL (default) or long - whether to display the long form with links on the page,
    #                                     or short with icons hidden in a rollover.
    # $image =      Path to an optional image to accompany the posting.
    
    $encoded_image = isset( $image ) ? urlencode( $image ) : '';
?>
<script type="text/javascript">
function openSYWWindow(theURL,winName,features) { 
    newwindow=window.open(theURL,winName,features); 
    if (window.focus) {newwindow.focus()} 
}
</script>
<?php if ( ( isset( $version ) && $version == 'long' ) || ( is_object( is_widget() && function_exists( 'is_widget' ) ) && is_widget()->share_style == 'long' ) ) : ?>
<div class="addthis_toolbox addthis_default_style">
    <a class="addthis_button_facebook_like" addthis:url="<?php the_permalink(); ?>"></a>
    <a class="addthis_button_tweet" addthis:url="<?php the_permalink(); ?>"></a>
    <a href="https://shopyourway.com/sharer/share?title=<?php echo urlencode( get_the_title() ); ?>&amp;link=<?php echo urlencode( get_permalink() ); ?>&amp;sourceSiteUrl=&amp;sourceSiteAlias=&amp;content=&amp;imageUrl=<?php echo $encoded_image; ?>" onclick="openSYWWindow(this.href,'ShopYourWay','width=650,height=350'); return false;" title="Post this to your ShopYourWay account"><img src="<?php echo get_template_directory_uri() ?>/assets/img/shopyourway_large.png" /></a>
    <a class="addthis_button_email" addthis:url="<?php the_permalink(); ?>"><img src="<?php echo get_template_directory_uri() ?>/assets/img/email.png" /></a>
</div>
<?php else: ?>
<ul class="addthis dropmenu">
    <li>
        <span class="sharebutton">Share</span>
        <ul class="sharemenulinks">
            <li><a class="addthis_button_facebook" addthis:url="<?php the_permalink(); ?>">Facebook</a></li>
            <li><a class="addthis_button_twitter" addthis:url="<?php the_permalink(); ?>">Twitter</a></li>
            <li><a href="https://shopyourway.com/sharer/share?title=<?php echo urlencode( get_the_title() ); ?>&amp;link=<?php echo urlencode( get_permalink() ); ?>&amp;sourceSiteUrl=&amp;sourceSiteAlias=&amp;content=&amp;imageUrl=<?php echo $encoded_image; ?>" onclick="javascript:window.open(this.href,'ShopYourWay','width=650,height=350'); return false;" style="cursor:pointer;" title="Post this to your ShopYourWay account"><img src="https://static.shopyourway.com/static/share-buttons/small-light.png" alt="" />ShopYourWay</a></li>
            <li><a class="addthis_button_email" addthis:url="<?php the_permalink(); ?>">Email</a></li>
        </ul>
    </li>
</ul>
<?php endif; ?>