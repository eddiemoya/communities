<div id="quickview" class="modal">
    
    <div id="quickview_ribbon">
        <h2>Product Quickview</h2> 
        <a href="<?php the_permalink(); ?>"	class="product-link">Go to the Product Page</a>
        <img id="close" src="<?php echo get_template_directory_uri(); ?>/images/remove.gif" alt="Close Product Quickview" />
    </div>
    
    <div id="quickview_body">  
        
        <div class="product-images">
            <img src="<?php product_image(); ?>" class="product-image" alt="Product Image" />
            
            <ul class="product-thumbnails"><?php product_swatch_list(); ?></ul> 
        </div>
        
        <div class="product-header">
            <h3 class="product-title">
                <a href="<?php the_permalink(); ?>" class="product-link"><?php the_title(); ?></a>
            </h3>
            <span class="product-rating">
                <?php product_ratings(); ?>
            </span>
            
            <?php get_product_merchant(); ?>
            
            <span class="item-number">Item# <?php product_detail('partnumber'); ?></span>
        </div>
        
        <div class="product-overview">
            
            <div class="pricing">
                <?php $prices = product_price_info();?>
                <?php if ($prices['savings']) : ?>

                    <span class="regular">Reg Price:
                        <span class="dollar-amount">$<?php echo $prices['regular']; ?></span>
                    </span>

                    <span class="savings">Savings: 
                        <span class="dollar-amount">$<?php echo $prices['savings']; ?></span>
                    </span>

                <?php endif; ?>

                <span class="price dollar-amount">$<?php echo $prices['price'] ?></span>
            </div>  
        
            <div class="add-to-cart">

               <?php if(is_in_stock()): ?>
                    <a data-post_id="<?php the_ID(); ?>" class="add-to-cart" onclick="s.linkTrackVars='prop12';s.prop12=' Add to Cart > Cart'; s.tl(true,'o',s.prop12);">&nbsp;</a>
                <?php else: ?>
                    <div class="out-of-stock">Out of Stock</div>
                <?php endif; ?>

            </div>
            
        </div> <!-- .product-overview -->
        
        <div class="product-description"><?php product_detail('shortdescription'); ?></div>
           
        <div class="product-specs"><?php get_product_specs(); ?></div>
	        
    </div> <!-- #quickview_body -->
    
</div>