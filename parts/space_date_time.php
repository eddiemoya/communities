<?php
    # OPTIONS
    # $timestamp =  the timestamp to create the dates
    # $remove_hms = false (default), true - whether to display the hour and minutes.
    $hide_time = ( isset( $remove_hms ) ) || ( $remove_hms == true ) ? true : false;
?>

<time class="content-date" pubdate datetime="<?php echo date( "Y-m-d", $timestamp ); ?>"><?php echo date( "F j, Y", $timestamp ); ?>
    <?php if ( !$hide_time ): ?><span class="time-stamp"><?php echo date( "g:i a", $timestamp ); ?></span><?php endif; ?>
</time>
