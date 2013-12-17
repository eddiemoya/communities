<?php
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( codeflavors@codeflavors.com )
 * @version 2.4
 */


/**
 * Add function to widgets_init that'll load our widget.
 */
add_action( 'widgets_init', 'fa_load_widgets' );

/**
 * Register widgets.
 */
function fa_load_widgets() {
	register_widget( 'FA_Slideshow_Widget' );
}

/**
 * Slideshow Widget class. Needed for multiple instance widgets.
 */
class FA_Slideshow_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function FA_Slideshow_Widget() {
		/* Widget settings. */
		$widget_ops = array( 
			'classname' => 'fa_slideshow', 
			'description' => __('Add a FeaturedArticles Slideshow widget', 'falite') );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'fa-slideshow-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'fa-slideshow-widget', 'FeaturedArticles slideshow', $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );
		
		if( !isset($instance['fa_slider_widget']) || !$instance['fa_slider_widget'] ){
			return;
		}
		
		/* Variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		

		/* Before widget (defined by themes). */
		echo $before_widget;
		
		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
		
		/* display the slideshow */	
		FA_display_slider($instance['fa_slider_widget']);			
			
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
	
		$instance['fa_slider_widget'] = (int) $new_instance['fa_slider_widget'];
		$instance['title'] = strip_tags( $new_instance['title'] );
		
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * get_field_id() and get_field_name() functions handle naming of fields and ids
	 */
	function form( $instance ) {
		
		$defaults = array( 'title' => '',  'fa_slider_widget'=>0);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$active = $instance['fa_slider_widget'];
		/* get the posts */
		$args = array(
	        'post_type' => 'fa_slider',
	        'posts_per_page' => -1,
	        'orderby' => 'date',
	        'order' => 'DESC'
	    );
		$loop = new WP_Query( $args );
		?>
		<p>
			<label for=<?php echo $this->get_field_id('title')?>"><?php _e('Title', 'falite');?></label>
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('title')?>" id="<?php $this->get_field_id('title')?>" value="<?php echo $instance['title'];?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fa_slider_widget' ); ?>"><?php _e('Slideshow', 'falite');?></label>
			<select name="<?php echo $this->get_field_name( 'fa_slider_widget' ); ?>" id="<?php echo $this->get_field_id( 'fa_slider_widget' ); ?>">
				<option value=""><?php _e('Choose slider to display', 'falite');?></option>
			    <?php 
					if ( $loop->have_posts() ) : 
						while ( $loop->have_posts() ) : 
							$loop->the_post();
				?>
			    <option value="<?php the_ID();?>"<?php if($active == get_the_ID()):?> selected="selected"<?php endif;?>><?php the_title();?></option>
			    <?php
						endwhile;
					endif;	
					wp_reset_query();
				?>	
			</select>
		</p>
		<?php		
	}
}
?>