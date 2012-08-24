<?php
    global $current_user;
    get_currentuserinfo();

    $buttons = array();

    $acts = array('upvote', 'downvote', 'follow');

    $acts['follow']['myaction'] = 'follow';

    if(isset($actions) && !empty($actions)) {
        foreach($actions as $action) {

            switch($action->action) {
                case 'upvote':
                    $acts['upvote']['action'] = $action;

                    break;
                case 'downvote':
                    $acts['downvote']['action'] = $action;

                    break;
                case 'follow':
                    $acts['follow']['action'] = $action;

                    break;
                default:
                    break;
            }

            if(isset($action->user) && !is_string($action->user) && get_class($action->user) === 'UserAction') {
                if(is_user_logged_in()) {
                    if($current_user->id == $action->user->userId) {
                        switch($action->action) {
                            case 'upvote':
                                $acts['upvote']['myaction'] = ' active';

                                break;
                            case 'downvote':
                                $acts['downvote']['myaction'] = ' active';

                                break;
                            case 'follow':
                                $acts['follow']['myaction'] = 'followed';

                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
    }

    $downvoteTotal = isset($acts['downvote']['action']->total) ? $acts['downvote']['action']->total : 0;
    $upvoteTotal = isset($acts['upvote']['action']->total) ? $acts['upvote']['action']->total : 0;

    $myActionDownvote = isset($acts['downvote']['myaction']) ? $acts['downvote']['myaction'] : '';
    $myActionFollow = isset($acts['follow']['myaction']) ? $acts['follow']['myaction'] : '';
    $myActionUpvote = isset($acts['upvote']['myaction']) ? $acts['upvote']['myaction'] : '';

    if ( isset( $options ) && ( !empty( $options ) ) ) {
        if ( in_array( "reply", $options ) ) {
            $buttons[] = '<div class="reply link-emulator">Reply</div>';
        }
        if ( in_array( "follow", $options ) ) {
            $buttons[] = '<button type="button" shc:gizmo="actions" name="button1" value="follow" title="Follow this ' . $type . '" id="follow-question-' . $id . '" class="follow" shc:gizmo="actions" shc:gizmo:options="{actions:{post:{id:'.$id.',name:\'follow\',sub_type:\''.$sub_type.'\',type:\''.$type.'\'}}}">'.$acts['follow']['myaction'].'</button>';
        }
        if ( in_array( "share", $options ) ) {
            $buttons[] = return_partial( 'parts/share', array( "url" => $url ) );
        }
        if ( in_array( "flag", $options ) ) {
            $buttons[] = '<button type="button" name="button1" value="flag" title="Flag this ' . $type . '" id="flag-comment-' . $id . '" class="flag">flag</button>';
        }
        if ( in_array( "downvote", $options ) ) {
            $buttons[] = '<label class="metainfo" for="downvote-comment-' . $id . '">('.$downvoteTotal.')</label><button shc:gizmo="actions" shc:gizmo:options="{actions:{post:{id:'.$id.',name:\'downvote\',sub_type:\''.$sub_type.'\',type:\''.$type.'\'}}}" type="button" name="button1" value="down vote" title="Down vote this ' . $type . '" id="downvote-comment-' . $id . '" class="downvote'.$myActionDownvote.'">down vote</button>';
        }
        if ( in_array( "upvote", $options ) ) {
            $buttons[] = '<label class="metainfo" shc:gizmo="actions" for="upvote-comment-' . $id . '">('.$upvoteTotal.')</label><button shc:gizmo="actions" shc:gizmo:options="{actions:{post:{id:'.$id.',name:\'upvote\',sub_type:\''.$sub_type.'\',type:\''.$type.'\'}}}" type="button" name="button1" value="helpful" title="Up vote this ' . $type . '" id="upvote-comment-' . $id . '" class="upvote'.$myActionUpvote.'">helpful</button>';
        }
    }
    
    if ( !empty( $buttons ) ) {
?>

<form class="actions clearfix" id="comment-<?php echo $id; ?>" method="post" action="">

    <?php echo implode( $buttons, "\r    " ) ?>

</form>

<?php
    }
?>