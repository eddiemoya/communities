        <?php 
        	$last_post = get_posts(array('numberposts'=>1, 'author' => $data->ID)); 
        	$last_post_date = strtotime($last_post[0]->post_date);
        ?>
        <li class="member lone-result clearfix">
          <div class="span2 badge labeled">
            <img src="<?php echo get_template_directory_uri() ?>/assets/img/zzexpert.jpg" alt="Team Player" title="Team Player" />
            <h4><a href="#"><?php echo ucfirst($roles[0]); ?></a></h4>
            <div class="badge-tail">&nbsp;</div>
          </div>
          <div class="span10 info">
            <h4><a href="#"><?php echo $data->user_nicename; ?></a></h4>
            <address>Chicago, IL</address>
            <article class="content-comments">
              <p>Last posted on <time datetime="<?php echo date('Y-m-d',$last_post_date); ?>" pubdate="pubdate"><?php echo date('M d, Y',$last_post_date); ?></time>.</p>
              <ul>
                <li>8 answers</li>
                <li><?php echo count_user_posts($data->ID); ?> blog posts</li>
                <li><?php comments_number(); ?></li>
              </ul>
            </article>
          </div>
        </li>