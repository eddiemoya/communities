<?php
/*
Plugin Name: Disable XMLRPC
Plugin URI: 
Description: Configures Wordpress's xmlrpc.php file to only return a 410 Gone response. This fixes a vulnerability where the server can be used in a DDoS attack.
Author: Zeev Saffir
Version: 1.0
*/

class disabled_xmlrpc_class {
    function serve_request() {
        header("HTTP/1.1 410 Gone");
        die();
    }
}
function disable_xmlrpc($xmlrcp) {
    return 'disabled_xmlrpc_class';
}
add_filter('wp_xmlrpc_server_class', 'disable_xmlrpc');