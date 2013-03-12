<?php


echo '<h2>Ratings & Reviews Test Page</h2><br><br>';

$rr = RR_User_Reviews::factory(10007132);

$results = $rr->results;


echo '<pre>';
var_dump($rr);
echo '</pre>';
