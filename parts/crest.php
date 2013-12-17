<?php
    global $wp_roles;
    # OPTIONS
    # $width =          span2 (default), span3, etc. - controls the width of the crest
    # $show_name =      true (default), false - whether to display the user's screenname under the avatar
    # $titling =        false (default), true - whether to display the user's title under the avatar
    # $show_address =   true (default), false - whether to display the user's city and state under the avatar

    $user = get_userdata( $user_id );

    $display_name      = ( !isset( $show_name ) ) ? true : $show_name;
    $display_address   = ( !isset( $show_address ) ) ? true : $show_address;
    $display_specialty = ( !isset( $specializations ) )? false : $specializations;
    $display_recent    = ( !isset( $last_posted ) )? false : $last_posted;
    $display_stats     = ( !isset( $stats ) )? false : $stats;

    $return_address    = rtrim(return_address( $user_id ));		
		
?>

<ul class="member_details<?php if (isset( $width )) {echo " " . $width;} ?>">

    <li class="member_avatar span6">
        <a href="<?php echo get_profile_url( $user_id ); ?>">
            <?php echo profile_photo( $user_id ); ?>
            <?php if ( ( ( isset( $titling ) ) && ( $titling == true ) ) || ( user_can( $user_id, "show_badge" ) ) ): ?>
                <span class="badge"><?php echo $wp_roles->roles[$user->roles[0]]["name"]; ?></span>
            <?php endif; ?>
        </a>
    </li>

    <?php
    if ( $display_name || $display_address || $display_specialty ):
        ?>
        <li class="member_profile span6">
            <ul>
                <?php if ($display_name): ?>
                    <li class="member_screen-name"><?php echo return_screenname_link( $user_id ); ?></li>
                <?php endif; 
                if ( $display_address && !empty( $return_address ) ):
                ?>
                    <li class="member_location"><?php echo return_address( $user_id ); ?></li>
                <?php endif;
                if ( $display_specialty ):
                ?>
                    <li class="member_specialties">
                        Specializes in:
                        <ul>
                            <?php foreach ( $display_specialty as $category ): ?>
                                <li><a href="<?php echo get_category_link( $category->term_id ); ?>"><?php echo $category->name; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </li>

    <?php 
    endif;
    if ( $display_recent || $display_stats ): 
    ?>
        <li class="member_content span12">
            <ul>
                <?php if ($display_recent): ?>
                    <li class="member_last-posted">Last Posted on <?php echo $display_recent; ?></li>
                <?php endif; ?>
                <?php if ($display_stats): ?>
                    <li class="member_answers"><?php echo $display_stats['answers']; ?></li>
                    <li class="member_posts"><?php echo $display_stats['posts']; ?></li>
                    <li class="member_comments"><?php echo $display_stats['comments']; ?></li>
                    <li class="member_comments"><?php echo $display_stats['reviews']; ?></li>
                <?php endif; ?>
            </ul>
        </li>
    <?php endif; ?>
</ul>
