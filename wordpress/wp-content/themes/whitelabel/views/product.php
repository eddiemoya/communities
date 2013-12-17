    <li id="shcproduct-<?php the_ID(); ?>" <?php post_class('product'); ?>>
        <?php ?>
        <div class="product-image">
            <a href="<?php echo product_url(); ?>" target="_blank" class="add-to-cart">
                <img src="<?php product_image(150); ?>" alt="product-image" />
            </a>
        </div>
    </li>  
