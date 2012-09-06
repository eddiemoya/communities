<?php
// watch for a userphoto change, and apply before pageload
if ($_FILES["userphoto_image_file"]["name"] != "") {
    userphoto_profile_update(wp_get_current_user()->ID);
}

function profile_photo($user, $attributes = array()) {
    $attributes["size"] = $attributes["size"] ? $attributes["size"] : "none";
    $attributes["alt"] = $attributes["alt"] ? $attributes["alt"] : "";
    return userphoto($user, '', '', $attributes, get_template_directory_uri().'/assets/img/avatar.jpg');
}

function profile_thumbnail($user, $attributes = array()) {
    $attributes["size"] = $attributes["size"] ? $attributes["size"] : "none";
    $attributes["alt"] = $attributes["alt"] ? $attributes["alt"] : "";
    return userphoto_thumbnail($user, '', '', $attributes, get_template_directory_uri().'/assets/img/icon_avatar.png');
}

function current_user_profile_photo($attributes = array()) {
    $currentUser = wp_get_current_user()->ID;
    $attributes["size"] = $attributes["size"] ? $attributes["size"] : "none";
    $attributes["alt"] = $attributes["alt"] ? $attributes["alt"] : "";
    return userphoto($currentUser, '', '', $attributes, get_template_directory_uri().'/assets/img/avatar.jpg');
}

function current_user_profile_thumbnail($attributes = array()) {
    $currentUser = wp_get_current_user()->ID;
    $attributes["size"] = $attributes["size"] ? $attributes["size"] : "none";
    $attributes["alt"] = $attributes["alt"] ? $attributes["alt"] : "";
    return (function_exists('userphoto_thumbnail')) ? userphoto_thumbnail($currentUser, '', '', $attributes, get_template_directory_uri().'/assets/img/icon_avatar.png') : '';
}