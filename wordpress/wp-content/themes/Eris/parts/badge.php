<?php
    # OPTIONS
    # $width =          span2 (default), span3, etc. - controls the width of the badge
    # $show_name =      true (default), false - whether to display the user's screenname under the avatar
    # $titling =        false (default), true - whether to display the user's title under the avatar

    $user = get_userdata( $user_id );
    
    $a_classes = array( 'badge' );
    $a_classes[] = ( !isset( $width ) ) ? 'span2' : $width;
    
    $display_name = ( !isset( $show_name ) ) ? true : $show_name;
    $badge_titling = '';
    
    # Default state: Show the user's screenname
    if ( $display_name ) {
        $badge_titling = '
    <h4><a href="' . site_url('') . '/author/' . $user->user_nicename . '">' . $user->user_nicename . '</a></h4>
';
    }
    # Create the address
    
    $sso_user = SSO_User::factory()->get_by_id($user_id);
    
    $address = '';
    $city_state = '';
    
    if($sso_user->guid) {
    	
    	$city_state .= $sso_user->city;
    	$state = $sso_user->state;
    	
    } else {
    
	    $city_state .= get_user_meta( $user_id, 'city', true );
	    $state = get_user_meta( $user_id, 'state', true );
    }
    
    if ( $city_state != '' && $state != ''){ $city_state .= ', '; }
    $city_state .= $state;
    if ( $city_state != '' ) { $address = '<address>' . $city_state . '</address>'; }
    
    # Alter the name's appearance if titling is turned on.
    if ( ( isset( $titling ) ) && ( $titling == true ) ) {
        $a_classes[] = 'labeled';
        $badge_titling = '
    <h4><a href="' . site_url('') . '/author/' . $user->user_nicename . '"">' . $user->roles[0] . '</a></h4>
    <div class="badge-tail">&nbsp;</div>
';
        if ( $display_name ) {
            $badge_titling .= '
    <h5><a href="' . site_url('') . '/author/' . $user->user_nicename . '">' . $user->user_nicename . '</a></h5>
';
        }
    }
    
    # Put it all together
?>

<div class="<?php echo implode( $a_classes, ' ' ); ?>">
    <a href="<?php echo site_url(''); ?>/author/<?php echo $current_user->user_nicename; ?>"><img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="<?php echo $user->user_nicename; ?>" title="<?php echo $user->user_nicename; ?>" /></a>
    <?php echo $badge_titling; ?>
    <?php echo $address; ?>
</div>
