<?php defined('SHCSSO_PATH') or die('Cannot be accessed directly');

return array(
    'production'    => array(
        'endpoint'  => 'https://accounts.ch4.intra.sears.com/universalservices/v3/',
    ),
    'integration'   => array(
        'endpoint'  => 'http://toad.ecom.sears.com:8180/universalservices/v3/',
    ),
    'qa'            => array(
        'endpoint'  => 'http://151.149.119.44:8180/universalservices/v3/',
    ),
)
