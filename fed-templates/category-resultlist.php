<?php
/**
 * @package WordPress
 * @subpackage Eris
 */

  get_template_part('parts/header');

  loop();

?>
  
  <section class="span8">
    <section class="span12 content-container result-list">
      <header class="content-header">
        <form method="post" action="<?php echo (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]==”on”) ? "https://" : "http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']; ?>">
          <label for="sort-results">Sort By</label>
          <select id="sort-results">
            <option value="">Select a category</option>
            <option value="1">Cat 1</option>
            <option value="2">Cat 2</option>
          </select>
          <input type="submit" value="submit" name="submit" />
        </form>
      </header>
      <ol class="content-body">
<?php
  $i = 0;
  while ( $i < 3 ):
?>
        <li class="member lone-result clearfix">
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
    
    <section class="span12 content-container result-list">
      <header class="content-header">
        <form method="post" action="<?php echo (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]==”on”) ? "https://" : "http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']; ?>">
          <label for="sort-results">Sort By</label>
          <select id="sort-results">
            <option value="">Select a category</option>
            <option value="1">Cat 1</option>
            <option value="2">Cat 2</option>
          </select>
          <input type="submit" value="submit" name="submit" />
        </form>
      </header>
      <ol class="content-body">
<?php
  $i = 0;
  while ( $i < 3 ):
?>
        <li class="post lone-result clearfix">
          <div class="span2 badge">
            <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
            <h4><a href="#">screenname</a></h4>
            <address>Chicago, IL</address>
          </div>
          <div class="span10">
            <time class="content-date" datetime="<?php echo date( "Y-m-d" ); ?>" pubdate="pubdate"><?php echo date( "F n, Y g:ia" ); ?></time>
            <hgroup>
              <h3 class="content-category"><a href="#">Post's Category</a></h3>
              <h2 class="content-headline"><a href="#">Post's Title</a></h2>
            </hgroup>
            <article>
              <p class="excerpt">This is the excerpt or description</p>
              <p class="content-comments">x answers | y replies | z comments</p>
            </article>
            <section class="post-actions">
              <div class="flag"><a href="#"><img src="<?php echo get_template_directory_uri() ?>/assets/img/icon-flag.png" alt="Flag this post" title="Flag this post" /></a></div>
              <div class="share"><a href="#">Share</a></div>
            </section>
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