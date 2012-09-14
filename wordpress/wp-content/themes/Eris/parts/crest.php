<?php

    # OPTIONS
    # $width =          span2 (default), span3, etc. - controls the width of the crest
    # $show_name =      true (default), false - whether to display the user's screenname under the avatar
    # $titling =        false (default), true - whether to display the user's title under the avatar
    # $show_address =   true (default), false - whether to display the user's city and state under the avatar

    $user = get_userdata( $user_id );
    
    $a_classes = array( 'crest' );
    $a_classes[] = ( !isset( $width ) ) ? 'span2' : $width;
    
    $display_name    = ( !isset( $show_name ) ) ? true : $show_name;
    $display_address = ( !isset( $show_address ) ) ? true : $show_address;
    $crest_titling   = '';
    $address         = '';
    
    # Default state: Show the user's screenname
    if ( $display_name ) {
        $crest_titling = '
    <h4>' . return_screenname_link( $user_id ) . '</h4>
';
    }
    # Default state: Show the user's city and state
    if ( $display_address ) {
        $address = '<address>' . return_address( $user_id ) . '</address>';
    }
    
    # Alter the name's appearance if titling is turned on.
    # Or if they're an expert.
    if ( 
            ( ( isset( $titling ) ) && ( $titling == true ) )
            ||
            ( in_array( 'expert', $user->roles ) )
        ) {
        $a_classes[] = 'labeled';
        $crest_titling = '
    <h4><a href="' . get_profile_url( $user_id ) . '">' . ucfirst( $user->roles[0] ) . '</a></h4>
';
        # removing the tail per client's request. -CAB 9.14.2012
        # <div class="tail">&nbsp;</div>
        
        if ( $display_name ) {
            $crest_titling .= '
    <h5>' . return_screenname_link( $user_id ) . '</h5>
';
        }
    }
    
    # Put it all together
?>

<div class="<?php echo implode( $a_classes, ' ' ); ?>">
    <a href="<?php echo get_profile_url( $user_id ); ?>">
        <?php echo profile_photo( $user_id ); ?>
    </a>
    <?php echo $crest_titling; ?>
    <?php echo $address; ?>
</div>
