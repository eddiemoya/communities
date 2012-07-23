<?php
/**
 * @package WordPress
 * @subpackage White Label
 */

  get_template_part('parts/header');

  loop();

?>
  <section class="span8">
    <section class="span12 content-container featured-members">
        <hgroup class="content-header">
          <h3>Featured Team Members</h3>
        </hgroup>
        <section class="content-body">
<?php
  $i = 0;
  while ( $i < 3 ):
?>
        <section class="span4">
          <div class="member clearfix">
            <div class="badge labeled">
              <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
              <h4>Associate</h4>
              <div class="badge-tail">&nbsp;</div>
            </div>
            <article class="info">
              <h4><a href="#">screenname</a></h4>
              <address>Chicago, IL</address>
              <h5>Specializes in</h5>
              <ul>
                <li><a href="#">Cat <?php echo ( $i * 2 + 1); ?></a></li>
                <li><a href="#">Cat <?php echo ( $i * 2 + 2); ?></a></li>
              </ul>
            </article>
            <article class="content-comments">
              <p>Last posted on <time datetime="2011-09-28" pubdate="pubdate">Sep 28, 2011</time>.</p>
              <ul>
                <li>8 answers</li>
                <li>6 blog posts</li>
                <li>4 comments</li>
              </ul>
            </article>
          </div>
        </section>
<?php
  $i++;
  endwhile;
?>
      </section>
    </section>
  </section>
  
  <section class="span4">
    <section class="span12 content-container featured-members">
        <hgroup class="content-header">
          <h3>Meet the Team</h3>
          <h4>Whatever your question or issue, we're here to help</h4>
        </hgroup>
        <section class="content-body">
<?php
  $i = 0;
  while ( $i < 3 ):
?>
        <section class="span4">
          <div class="member clearfix">
            <div class="badge labeled">
              <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
              <h4>Associate</h4>
              <div class="badge-tail">&nbsp;</div>
            </div>
            <article class="info">
              <h4><a href="#">screenname</a></h4>
              <address>Chicago, IL</address>
              <h5>Specializes in</h5>
              <ul>
                <li><a href="#">Cat <?php echo ( $i * 2 + 1); ?></a></li>
                <li><a href="#">Cat <?php echo ( $i * 2 + 2); ?></a></li>
              </ul>
            </article>
            <article class="content-comments">
              <p>Last posted on <time datetime="2011-09-28" pubdate="pubdate">Sep 28, 2011</time>.</p>
              <ul>
                <li>8 answers</li>
                <li>6 blog posts</li>
                <li>4 comments</li>
              </ul>
            </article>
          </div>
        </section>
<?php
  $i++;
  endwhile;
?>
      </section>
    </section>
  </section>

<?php
  get_template_part('parts/footer');