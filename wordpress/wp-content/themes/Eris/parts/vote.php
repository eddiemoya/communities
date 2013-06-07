<label class="metainfo" for="upvote-comment-<?php $id; ?>">(<?php echo (!empty($actions['upvote'])) ? $actions['upvote']->total : 0; ?>)</label>
<button type="button" name="upvote" class="upvote" value="helpful" title="Up vote this <?php echo $type; ?>" id="upvote-comment-<?php echo $id; ?>">helpful</button>

<label class="metainfo" for="downvote-comment-<?php echo $id; ?>">(<?php echo (!empty($actions['downvote'])) ? $actions['downvote']->total : 0; ?>)</label>
<button type="button" name="downvote" class="downvote" value="down vote" title="Down vote this <?php echo $type; ?>" id="downvote-comment-<?php echo $id; ?>">down vote</button>