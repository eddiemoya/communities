<?php

echo '<h2>Ratings & Reviews Test Page</h2><br><br>';

echo '<pre>';
var_dump(RR_User_Reviews::factory(10007132)->results);
echo '</pre>';
