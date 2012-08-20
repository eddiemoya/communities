<?php

    # OPTIONS
    # $width =          span2 (default), span3, etc. - controls the width of the crest
    # $show_name =      true (default), false - whether to display the user's screenname under the avatar
    # $titling =        false (default), true - whether to display the user's title under the avatar

    $user = get_userdata( $user_id );
    
    $a_classes = array( 'crest' );
    $a_classes[] = ( !isset( $width ) ) ? 'span2' : $width;
    
    $display_name = ( !isset( $show_name ) ) ? true : $show_name;
    $crest_titling = '';
    
    # Default state: Show the user's screenname
    if ( $display_name ) {
        $crest_titling = '
    <h4>' . return_screenname_link( $user_id ) . '</h4>
';
    }
    # Create the address
    $address = '';
    $city_state = '';
    $city_state .= get_user_meta( $user_id, 'city', true );
    $state = get_user_meta( $user_id, 'state', true );
    if ( $city_state != '' && $state != ''){ $city_state .= ', '; }
    $city_state .= $state;
    if ( $city_state != '' ) { $address = '<address>' . $city_state . '</address>'; }
    
    # Alter the name's appearance if titling is turned on.
    if ( ( isset( $titling ) ) && ( $titling == true ) ) {
        $a_classes[] = 'labeled';
        $crest_titling = '
    <h4><a href="' . get_author_posts_url( $user_id ) . '">' . $user->roles[0] . '</a></h4>
    <div class="tail">&nbsp;</div>
';
        if ( $display_name ) {
            $crest_titling .= '
    <h5>' . return_screenname_link( $user_id ) . '</h5>
';
        }
    }
    
    # Put it all together
?>

<div class="<?php echo implode( $a_classes, ' ' ); ?>">
    <a href="<?php echo get_author_posts_url( $user_id ); ?>">
        <?php echo userphoto($user_id, '', '', array(),get_template_directory_uri() . '/assets/img/avatar.jpg'); ?>
        
    </a>
    <?php echo $crest_titling; ?>
    <?php echo $address; ?>
</div>
