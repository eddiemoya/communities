<div class="wrap">

    <div id="icon-users" class="icon32"><br/></div>
    <h2><?php echo $comment->labels['name']; ?></h2>


    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="movies-filter" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <!-- Now we can render the completed list table -->
        <?php $comments_table->display() ?>
    </form>

</div>