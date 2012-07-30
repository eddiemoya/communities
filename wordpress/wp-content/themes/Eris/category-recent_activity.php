<?php
/**
 * @package WordPress
 * @subpackage Eris
 */

  get_template_part('parts/header');

  loop();
  
  $self_lookup = true;
  
  $seconds_in_day = 24 * 60 * 60;
  
  $current_tab = "Community Activity";
  $current_nav = "Recent Activity";
  
  $a_tabs = array(
    "Community Activity" => "#",
    "About Me" => "#",
  );
  
  $a_navigation = array(
    "Recent Activity" => "#",
    "Q&A's" => "#",
    "Blog Posts" => "#",
    "Buying Guides" => "#"
  );
  
  $actions = array( "ask", "answer", "comment", "follow", "like", "post" );
  
  $categories = array( "Appliance", "Kitchen", "Garden", "Automotive", "Industrial Machinery" );
  
  $activities = array(
    "1" => array(
      "action" => "0",
      "category" => "0",
      "title" => "Where do babies come from?",
      "excerpt" => "Some snarky remark.",
      "date" => time(),
    ),
    "2" => array(
      "action" => "1",
      "category" => "1",
      "title" => "Has anyone seen my keys?",
      "excerpt" => "Yes.",
      "date" => time() - ( 3 * $seconds_in_day ),
    ),
    "3" => array(
      "action" => "2",
      "category" => "2",
      "title" => "Blogging is Fun",
      "excerpt" => "No, it isn't.",
      "date" => time() - ( 5 * $seconds_in_day ),
    ),
    "4" => array(
      "action" => "3",
      "category" => "3",
      "title" => "Who's got warez?",
      "excerpt" => "noobz.",
      "date" => time() - ( 7 * $seconds_in_day ),
    ),
    "5" => array(
      "action" => "4",
      "category" => "4",
      "title" => "Guiding the Buyer",
      "excerpt" => "I've now entered an Earthbound paradise where consumerism has saved my mortal soul. I've now entered an Earthbound paradise where consumerism has saved my mortal soul. I've now entered an Earthbound paradise where consumerism has saved my mortal soul. I've now entered an Earthbound paradise where consumerism has saved my mortal soul.",
      "date" => time() - ( 9 * $seconds_in_day ),
    ),
  );
  
  $is_widget = true;
?>
  
  <section class="span<?php echo ( $self_lookup ? "12" : "8" ); ?> profile">

    <nav class="clearfix">
      <ul class="tabs clearfix">
        <?php
        foreach ( $a_tabs as $lone_tab => $tab_url ) {
          if ( $lone_tab == $current_tab ) {
            echo '<li class="active">' . $lone_tab . '</li>';
          }
          else {
            echo '<li><a href="' . $tab_url . '">' . $lone_tab . '</a></li>';
          }
        }
        ?>
      </ul>
    </nav>
    <nav class="bar clearfix">
      <ul class="clearfix">
        <?php
        foreach ( $a_navigation as $lone_nav => $nav_url ) {
          if ( $lone_nav == $current_nav ) {
            echo '<li class="active">' . $lone_nav . '</li>';
          }
          else {
            echo '<li><a href="' . $nav_url . '">' . $lone_nav . '</a></li>';
          }
        }
        ?>
      </ul>
    </nav>
    
    <section class="span4">
      <section class="span12 content-container recent-activity">
        <?php if ( $is_widget ): ?>
          <hgroup class="content-header">
            <h3>Recent Activity</h3>
          </hgroup>
        <?php endif; ?>
        <ol class="content-body clearfix">
          <?php
            foreach ($activities as $activity):
              $this_action = substr( $actions[ $activity["action"] ], -1 ) == "e" ? $actions[ $activity["action"] ] . "d" : $actions[ $activity["action"] ] . "ed";
              $excerpt = strlen( $activity["excerpt"] ) > 180 ? substr( $activity["excerpt"], 0, 180 ) . " &#8230;" : $activity["excerpt"];
          ?>
          <li class="clearfix">
            <?php if ( $is_widget ): ?>
              <div class="badge span3">
                <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
              </div>
            <?php endif; ?>
            <div<?php if ( $is_widget ) { echo ' class="span9"'; } ?>>
              <time class="content-date" datetime="<?php echo date( "Y-m-d", $activity["date"] ); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", $activity["date"] ); ?></time>
              <h3>
                <?php if ( $is_widget ): ?><a href="#">screenname</a><?php endif; ?>
                <span><?php echo ucfirst( $this_action ); ?></span>
                <a href="#"><?php echo $activity["title"]; ?></a>
                <span>in <a href="#"><?php echo $categories[ $activity["category"] ]; ?></a><?php if( in_array( $activity["action"], array( 1, 2 ) ) ) { echo ' with'; } ?></span>
              </h3>
              <?php if( in_array( $activity["action"], array( 1, 2, 4 ) ) ): ?>
                <article class="excerpt"><?php echo $excerpt; ?></article>
              <?php endif; ?>
            </div>
          </li>
          <? endforeach; ?>
        </ol>
      </section>
    </section>
<?php

  $is_widget = false;

?>
    <section class="span12 content-container recent-activity">
      <?php if ( $is_widget ): ?>
        <hgroup class="content-header">
          <h3>Recent Activity</h3>
        </hgroup>
      <?php endif; ?>
      <ol class="content-body clearfix">
        <?php
          foreach ($activities as $activity):
            $this_action = substr( $actions[ $activity["action"] ], -1 ) == "e" ? $actions[ $activity["action"] ] . "d" : $actions[ $activity["action"] ] . "ed";
            $excerpt = strlen( $activity["excerpt"] ) > 180 ? substr( $activity["excerpt"], 0, 180 ) . " &#8230;" : $activity["excerpt"];
        ?>
        <li>
          <?php if ( $is_widget ): ?>
            <div class="badge span3">
              <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
            </div>
          <?php endif; ?>
          <div<?php if ( $is_widget ) { echo ' class="span9"'; } ?>>
            <time class="content-date" datetime="<?php echo date( "Y-m-d", $activity["date"] ); ?>" pubdate="pubdate"><?php echo date( "F j, Y g:ia", $activity["date"] ); ?></time>
            <h3>
              <?php if ( $is_widget ): ?><a href="#">screenname</a><?php endif; ?>
              <span><?php echo ucfirst( $this_action ); ?></span>
              <a href="#"><?php echo $activity["title"]; ?></a>
              <span>in <a href="#"><?php echo $categories[ $activity["category"] ]; ?></a><?php if( in_array( $activity["action"], array( 1, 2 ) ) ) { echo ' with'; } ?></span>
            </h3>
            <?php if( in_array( $activity["action"], array( 1, 2, 4 ) ) ): ?>
              <article class="excerpt"><?php echo $excerpt; ?></article>
            <?php endif; ?>
          </div>
        </li>
        <? endforeach; ?>
      </ol>
    </section>
      
  </section>
  
  <?php if ( !$self_lookup ): ?>
  <section class="span4">
    <!-- Widgets Go Here -->
    <div style="border: 1px solid black">&nbsp;</div>
  </section>
  <?php endif; ?>

<?php
  get_template_part('parts/footer');