<?php 

global $post_question_data;


$data = $post_question_data;


include (get_template_directory() . '/parts/forms/post-a-question-step-'. $data['step'] . '.php');


