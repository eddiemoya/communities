<?php
/**
 * @package WordPress
 * @subpackage Eris
 */

  get_template_part('parts/header');

  loop();
  
 
  
  $current_tab = "About Me";
  $current_nav = "1";
  
  $seconds_in_day = 24 * 60 * 60;
  
  $actions = array( "", "Asked", "Answered", "Commented", "Followed", "Voted", "Wrote" );
  
  $categories = array( "Appliance", "Kitchen", "Garden", "Automotive", "Industrial Machinery" );
  
  $activities = array(
    "1" => array(
      "action" => "1",
      "category" => "0",
      "author" => "Tom",
      "title" => "Where do babies come from?",
      "excerpt" => "Some snarky remark.",
      "date" => time(),
    ),
    "2" => array(
      "action" => "2",
      "category" => "1",
      "author" => "Dick",
      "title" => "Has anyone seen my keys?",
      "excerpt" => "Yes.",
      "date" => time() - ( 3 * $seconds_in_day ),
    ),
    "3" => array(
      "action" => "3",
      "category" => "2",
      "author" => "Harry",
      "title" => "Blogging is Fun",
      "excerpt" => "No, it isn't.",
      "date" => time() - ( 5 * $seconds_in_day ),
    ),
    "4" => array(
      "action" => "4",
      "category" => "3",
      "author" => "Mary",
      "title" => "Who's got warez?",
      "excerpt" => "noobz.",
      "date" => time() - ( 7 * $seconds_in_day ),
    ),
    "5" => array(
      "action" => "5",
      "category" => "4",
      "author" => "Jane",
      "title" => "Guiding the Buyer",
      "excerpt" => "I've now entered an Earthbound paradise where consumerism has saved my mortal soul. I've now entered an Earthbound paradise where consumerism has saved my mortal soul. I've now entered an Earthbound paradise where consumerism has saved my mortal soul. I've now entered an Earthbound paradise where consumerism has saved my mortal soul.",
      "date" => time() - ( 9 * $seconds_in_day ),
    ),
  );
  
  $a_actions_taken = array( 1, 2, 3, 4, 5 );
?>

  <section class="span8 profile">

    <?php include('parts/profile_nav.php'); ?>
  
<?php
  $is_widget = false;
  
  # logic for header and container
  $header =           '';
  $container_class =  '';
  if ( $is_widget ) {
    $header =         '<hgroup class="content-header"><h3>Recent Activity</h3></hgroup>';
    $container_class = ' class="span9"';
  }
?>


    <section class="content-container recent-activity">
      <?php echo $header; ?>
      <ol class="content-body result clearfix">
        <?php
          foreach ( $activities as $activity ):
            # logic for showing the badge
            $a_start = array();
            $badge = '';
            if ( $is_widget ) {
              $a_start[] = '<a href="#">' . $activity["author"] . '</a>';
              $badge = '<div class="badge span3"><img src="' . get_template_directory_uri() . '/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" /></div>';
            }
          
            # logic for showing the action.
            $a_start[] = in_array( $current_nav, array( 1 ) ) ? $actions[ $activity["action"] ] . ' this:' : NULL;
            $start = !empty( $a_start ) ? '<span>' . implode( $a_start, ' ' ) . '</span>' : '';
          
            # logic for showing an excerpt of the body
            $excerpt = '';
            if ( ( in_array( $current_nav, array( 1, 3, 4, 5, 6 ) ) ) && ( in_array( $activity["action"], array( 2, 3, 5 ) ) ) ) {
              $excerpt = '<article class="excerpt">';
              $excerpt .= strlen( $activity["excerpt"] ) > 180 ? substr( $activity["excerpt"], 0, 180 ) . "&#8230;" : $activity["excerpt"];
              $excerpt .= '</article>';
            }
        ?>
        <li class="clearfix">
          <?php echo $badge; ?>
          <div<?php echo $container_class; ?>>
            <h3>
              <?php echo $start; ?>
              <time class="content-date" datetime="<?php echo date( "Y-m-d", $activity["date"] ); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", $activity["date"] ); ?></time>
              <a href="#" class="category"><?php echo $categories[ $activity["category"] ]; ?></a>
              <a href="#"><?php echo $activity["title"]; ?></a>
            </h3>
            <?php echo $excerpt; ?>
          </div>
        </li>
        <? endforeach; ?>
      </ol>
    </section>

  </section>

<?php
  $is_widget = true;

  # logic for header and container
  $header =           '';
  $container_class =  '';
  if ( $is_widget ) {
    $header =         '<hgroup class="content-header"><h3>Recent Activity</h3></hgroup>';
    $container_class = ' class="span9"';
  }
?>

  <section class="span4">
    <section class="content-container recent-activity">
      <?php echo $header; ?>
      <ol class="content-body result clearfix">
        <?php
          foreach ( $activities as $activity ):
            # logic for showing the badge
            $a_start = array();
            $badge = '';
            if ( $is_widget ) {
              $a_start[] = '<a href="#">' . $activity["author"] . '</a>';
              $badge = '<div class="badge span3"><img src="' . get_template_directory_uri() . '/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" /></div>';
            }
          
            # logic for showing the action.
            $a_start[] = in_array( $current_nav, array( 1 ) ) ? $actions[ $activity["action"] ] . ' this:' : NULL;
            $start = !empty( $a_start ) ? '<span>' . implode( $a_start, ' ' ) . '</span>' : '';
          
            # logic for showing an excerpt of the body
            $excerpt = '';
            if ( ( in_array( $current_nav, array( 1, 3, 4, 5, 6 ) ) ) && ( in_array( $activity["action"], array( 2, 3, 5 ) ) ) ) {
              $excerpt = '<article class="excerpt">';
              $excerpt .= strlen( $activity["excerpt"] ) > 180 ? substr( $activity["excerpt"], 0, 180 ) . "&#8230;" : $activity["excerpt"];
              $excerpt .= '</article>';
            }
        ?>
        <li class="clearfix">
          <?php echo $badge; ?>
          <div<?php echo $container_class; ?>>
            <h3>
              <?php echo $start; ?>
              <time class="content-date" datetime="<?php echo date( "Y-m-d", $activity["date"] ); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", $activity["date"] ); ?></time>
              <a href="#" class="category"><?php echo $categories[ $activity["category"] ]; ?></a>
              <a href="#"><?php echo $activity["title"]; ?></a>
            </h3>
            <?php echo $excerpt; ?>
          </div>
        </li>
        <? endforeach; ?>
      </ol>
    </section>
  </section>

<?php
  get_template_part('parts/footer');