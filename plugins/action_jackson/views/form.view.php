<div class="wrap clearfix">
	<div class="icon32" id="icon-options-general"><br></div>
	<h2>User Actions Statistics</h2>
</div>

<form action="options.php" method="post">
	<table class="wp-list-table widefat fixed posts">
        <thead>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Action Type:</td>
                <td>
                    <select name="action">
                        <option value="downvote">Downvote</option>
                        <option value="flat">Flag</option>
                        <option value="follow">Follow</option>
                        <option value="upvote">Upvote</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Post Type:</td>
                <td>
                    <select name="type">
                        <option>Optional</option>
                        <option value="posts">All Posts</option>
                        <optgroup label="Posts">
                            <?php
                                foreach(get_post_types() as $key=>$type):
                            ?>
                                    <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                            <?php
                                endforeach;
                            ?>
                        </optgroup>
                        <option value="categories">All Categories</option>
                        <optgroup label="Categories">
                            <?php
                                foreach(get_terms('category') as $key=>$category):
                            ?>
                                    <option value="<?php echo $category->slug; ?>"><?php echo $category->name; ?></option>
                            <?php
                                endforeach;
                            ?>
                        </optgroup>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Start Date Range:</td>
                <td>
                    <input type="text" class="datepicker" name="startDate" id="startDate" />
                </td>
            </tr>
            <tr>
                <td>End Date Range:</td>
                <td>
                    <input type="text" class="datepicker" name="endDate" id="endDate" />
                </td>
            </tr>
        </tbody>
	</table>
	<p class="submit">
		<input type="submit" class="button-primary" value="Submit" />
	</p>
</form>