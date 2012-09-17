<?php
    # OPTIONS
    # $timestamp    = the timestamp to create the dates
    # $remove_hms   = false (default), true - whether to display the hour and minutes.
    # $date_format  = string, see php date formats - 'F j, Y' (default - prints as April 1, 2012)
    # $time_format  = string, see php date formats - 'g:i a' (default - prints as 1:35 pm)
    # $separator    = 'bigspace' (default), 'break' or 'space' - the separator between the date and the time
    $hide_time      = ( isset( $remove_hms ) ) || ( $remove_hms == true ) ? true : false;
    $format_date    = isset( $date_format ) ? $date_format : 'F j, Y';
    $format_time    = isset( $time_format ) ? $time_format : 'g:i a';
    $glue           = isset( $separator ) ? $separator : 'bigspace';
    $formatted_time = date( $format_time, $timestamp );

    switch ( $glue ) {
        case 'space':   $print_time = ' ' . $formatted_time; break;
        case 'break':   $print_time = '<br /> ' . $formatted_time; break;
        default :       $print_time = ' <span class="time-stamp">' . date( $format_time, $timestamp ) . '</span>'; break;
    }
?>

<time class="content-date" pubdate datetime="<?php echo date( "Y-m-d", $timestamp ); ?>"><?php

    echo date( $format_date, $timestamp );
    if ( !$hide_time ){ echo $print_time; }

?></time>
