<div class="add-new-thread">
	<a href="#new-topic-<?php bbp_topic_id(); ?>" <?php if(! is_user_logged_in()):?>shc:gizmo="moodle" shc:gizmo:options="{moodle: {width:480, target:ajaxdata.ajaxurl, type:'POST', data:{action: 'get_template_ajax', template: 'page-login'}}}"<?php endif;?>>+ New thread</a>
</div>