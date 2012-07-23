<?php
/**
 * @package WordPress
 * @subpackage Eris
 */

  get_template_part('parts/header');

  loop();

?>
  
  <section class="span8">
    <section class="span12 content-container featured-qstn-columns">
      <hgroup class="content-header">
        <h3>Featured Questions</h3>
      </hgroup>
      <ol class="content-body">
<?php
  $i = 0;
  while ( $i < 2 ):
?>
        <li class="member clearfix">
          <div class="span2 badge labeled">
            <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
            <h4><a href="#">Title</a></h4>
            <div class="badge-tail">&nbsp;</div>
          </div>
          <div class="span10 info">
            <h4><a href="#">screenname</a></h4>
            <address>Chicago, IL</address>
            <article class="content-comments">
              <p>Last posted on <time datetime="2011-09-28" pubdate="pubdate">Sep 28, 2011</time>.</p>
              <ul>
                <li>8 answers</li>
                <li>6 blog posts</li>
                <li>4 comments</li>
              </ul>
            </article>
          </div>
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