<button type="button" id="flag-comment-<?php echo $id; ?>" name="button_flag" <?php echo (!empty($sub_type)) ? "m.$sub_type-action" : "m:gizmo" ; ?>="flag" class="flag<?php if(!empty($actions['flag'])) { ?> active<?php } ?>" value="flag" title="Flag this <?php echo $type; ?>"
<?php if(empty($actions['flag'])) { ?>
<?php if($logged_in) { ?>
shc:gizmo="tooltip"
shc:gizmo:options="
	{
		tooltip: {
			customListener: {
				callBack:
					function(element) {
						element.addClass('active');
					},
				name: 'addActiveToFlag-<?php echo $id; ?>'
			},
			displayData: {
				element: 'flagForm-<?php echo $id; ?>',
				callBack: {
					submit: {
						active: true,
						name: 'submit',
						method:
							function(event) {
								if(jQuery(event.target).children('textarea').val() == '') {
									return false;
								}

								var sendData = jQuery(event.target).children().serialize();

								jQuery.post(
									ajaxurl + '?action=flag_me',
									sendData,
									function() {
										var success = '<p>Thanks for your concern. We\'ll look into it as soon as possible.</p>';

										jQuery('.tooltip').children('.middle').html('');
										jQuery('.tooltip').children('.middle').html(success);

										setTimeout(
											function() {
												jQuery('.tooltip').fadeOut();
											}, 2000
										);
									}
								);

								event.preventDefault();
							}
					}
				}
			},
			arrowPosition: 'top',
			tooltipWidth: 200,
		}
	}
"
<?php } else { ?> 
shc:gizmo:options="{moodle:{width:480,target:ajaxdata.ajaxurl,type:'POST',data:{action:'get_template_ajax',template:'page-login'}}}" shc:gizmo="moodle"
<?php } ?>
<?php } ?>>flag</button>
<div id="flagForm-<?php echo $id; ?>" class="hide">
	<!--<form class="flag-form" id="commentForm-<?php echo $id; ?>" method="post" shc:gizmo="transFormer">-->
		<textarea class="flagField" rows="5" name="comment" aria-required="true" shc:gizmo:form="{required: true}"></textarea>
		<input
				class="<?php echo $brand; ?>_button"
				type="submit"
				value="Flag"
				shc:gizmo="actions"
				shc:gizmo:options="
					{
						actions: {
							customEvent:
								function(element) {
									element.trigger('addActiveToFlag-<?php echo $id; ?>');
								},
							post: {
								blocker: function(target) {
									if(typeof target !== 'undefined' && target.siblings('textarea').val() == '') {
										return false;
									}

									return true;
								},
								id:<?php echo $id; ?>,
								name:'flag',
								sub_type:'<?php echo $sub_type; ?>',
								type:'<?php echo $type; ?>'
							}
						}
					}"
			/>
		<input class="<?php echo $brand; ?>_button azure" type="reset" value="Cancel" onclick="jQuery('.tooltip').hide();" />
		<input name="comment_post_ID" type="hidden" value="<?php echo $post_id; ?>" />
		<input name="comment_parent" type="hidden" value="<?php echo $id; ?>" />
		<input name="comment_type" type="hidden" value="flag" />
	<!--</form>-->
</div>
