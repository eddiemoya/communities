<!-- NOT IN USE - FOR DEV PURPOSES ONLY -->

<article class="content-container subnav-widget span4">
    <?php if(function_exists('is_widget')) : ?>
    <?php if (is_widget()->show_title && !empty(is_widget()->widget_title) || true) : ?>

        <header class="content-header">
            <h3>
                Find More Questions About...
                <?php echo is_widget()->widget_title; ?>
            </h3>
        </header>

    <?php endif; ?>

    <section class="content-body clearfix">

        <div class="subnav-items">
            
            <ul>
                <li><a href="#">Repairs, Maintenance &amp; Parts</a></li>
                <li><a href="#">Product Issues &amp; Questions</a></li>
                <li><a href="#">Protection Agreements</a></li>
                <li><a href="#">In-Store Orders</a></li>
                <li><a href="#">Marketplace Orders</a></li>
                <li><a href="#">Online Orders</a></li>
            </ul>
            
            <div class="subcat-selector-container">
                <select id="subcat-selector">
                    <option value="All">All Subcategories</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                    <option value="Subcategory">Subcategory</option>
                </select>
            </div>
            
        </div>

    </section>
    <?php endif; ?>
</article>