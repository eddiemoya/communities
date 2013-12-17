<?php

$actions = new ActionJacksonQuery();

echo '<pre>';
var_dump($actions->getUserActions($profile_user->data->ID, null, null, null, 1, 10, true));