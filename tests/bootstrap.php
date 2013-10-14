<?php
// Load WordPress test environment
// https://github.com/nb/wordpress-tests
//
// The path to wordpress-tests
$path = '/srv/www/wp-tests/bootstrap.php';

if (file_exists($path)) {
        // $GLOBALS['wp_tests_options'] = array(
        //     //    'active_plugins' => array('wp-node/wp-node.php')
        // );
        require_once $path;
} else {
        exit("Couldn't find wordpress-tests/bootstrap.phpn");
}