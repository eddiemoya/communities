<?php
// watch for a userphoto change, and apply before pageload
if ($_FILES["userphoto_image_file"]["name"] != "") {
    userphoto_profile_update(wp_get_current_user()->ID);
}

function profile_photo($user) {
    return userphoto($user, '', '', array(), get_template_directory_uri().'/assets/img/avatar.jpg');
}

function profile_thumbnail($user) {
    return userphoto_thumbnail($user, '', '', array(), get_template_directory_uri().'/assets/img/icon_avatar.png');
}

function current_user_profile_photo() {
    $currentUser = wp_get_current_user()->ID;
    return userphoto($currentUser, '', '', array(), get_template_directory_uri().'/assets/img/avatar.jpg');
}

function current_user_profile_thumbnail() {
    $currentUser = wp_get_current_user()->ID;
    return userphoto_thumbnail($currentUser, '', '', array(), get_template_directory_uri().'/assets/img/icon_avatar.png');
}