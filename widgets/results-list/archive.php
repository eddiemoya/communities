      <header class="content-header">
        <form method="post" action="<?php echo (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]==”on”) ? "https://" : "http://" . $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI']; ?>">
          <label for="sort-results">Sort By</label>
          <select id="sort-results">
            <option value="">Select a category</option>
            <option value="1">Cat 1</option>
            <option value="2">Cat 2</option>
          </select>
          <input type="submit" value="submit" name="submit" />
        </form>
      </header>
      <ol class="content-body">

    
      		<?php loop_by_type('results-list'); ?>
     


      </ol>
    </section>