<?php
    function get_google_analtyics_id() {
        $option = get_option('Yoast_Google_Analytics');

        echo $option['uastring'];
    }