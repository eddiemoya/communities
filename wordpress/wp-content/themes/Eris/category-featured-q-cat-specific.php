<?php
/**
 * @package WordPress
 * @subpackage Eris
 */

  get_template_part('parts/header');

  loop();

?>
  
  <section class="span8">
    <section class="span12 content-container featured-question columns">
      <hgroup class="content-header">
        <h3>Featured Questions</h3>
      </hgroup>
      <ol class="content-body clearfix">
<?php
  $i = 0;
  while ( $i < 2 ):
?>
        <li class="featured">
          <section class="column clearfix">
            <div class="span4 badge">
              <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
              <h4><a href="#">screenname</a></h4>
              <address>Chicago, IL</address>
            </div>
            <div class="span8 info content-details">
              <time class="content-date" datetime="<?php echo date( "Y-m-d" ); ?>" pubdate="pubdate"><?php echo date( "F jS, Y g:ia" ); ?></time>
              <h4 class="content-headline"><a href="#">Post's Title</a></h4>
              <ul class="content-comments">
                <li>x answers</li>
                <li>y community answers</li>
              </ul>
              </div>
            </section>
        </li>
<?php
  $i++;
  endwhile;
?>
      </ol>
    </section>
  </section>

<?php
  get_template_part('parts/footer');