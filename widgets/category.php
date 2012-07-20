This is an category query.<br />

<?php 
//print_pre($query);
if(is_widget()) {
    echo "This ia a widget.<br />";
}

if(is_widget()->show_title) {
    echo "This widget has title enabled.<br />";
    echo "TITLE: ". is_widget()->widget_title;
}

if(is_widget()->show_subtitle) {
    echo "This widget has subtitles enabled.<br />";
    echo "SUBTITLE: ". is_widget()->widget_subtitle;
}

if(is_widget()->show_category) {
    echo "This widget has category enabled.<br />";
    echo "CATEGORY: loop through and get post categories";
}

if(is_widget()->show_content) {
    echo "This widget is set to display content.<br />";
    echo "Content: loop through and get post content";
}

if(is_widget()->show_comment_count) {
    echo "This widget is set to display content.<br />";
    echo "Count: loop through and get counts";
}

if(is_widget()->show_share) {
    echo "This widget is set to display share icons.<br />";
    echo "SHARE STYLE:" . is_widget()->share_style;
}

loop();

