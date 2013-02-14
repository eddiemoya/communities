<?php
/*
Widget class based on work by Eddie Moya (http://eddiemoya.com/)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class WP_Weather_Widget extends WP_Widget {
	  
	/**
	 * Name for this widget type, should be human-readable - the actual title it will go by.
	 * 
	 * @var string [REQUIRED]
	 */
	var $widget_name = 'WP Weather Widget';

	/**
	 * Root id for all widgets of this type. Will be automatically generate if not set.
	 * 
	 * @var string [OPTIONAL]. FALSE by default.
	 */
	var $id_base = 'wp_weather_widget';
	
	/**
	 * Shows up under the widget in the admin interface
	 * 
	 * @var string [OPTIONAL]
	 */
	private $description = 'WP Weather Widget';

	/**
	 * CSS class used in the wrapping container for each instance of the widget on the front end.
	 * 
	 * @var string [OPTIONAL]
	 */
	private $classname = 'weather-widget';
	
	/**
	 * Be careful to consider PHP versions. If running PHP4 class name as the contructor instead.
	 * 
	 * @author Eddie Moya
	 * @return void
	 */
	public function __construct(){
		$widget_ops = array(
			'description' => $this->description,
			'classname' => $this->classname
		);

		parent::WP_Widget($this->id_base, $this->widget_name, $widget_ops);
	}
	
	/**
	 * Self-registering widget method.
	 * 
	 * This can be called statically.
	 * 
	 * @author Eddie Moya
	 * @return void
	 */
	public function register_widget() {
		add_action('widgets_init', create_function( '', 'register_widget("' . __CLASS__ . '");' ));
	}
	
	/**
	 * The front end of the widget. 
	 * 
	 * Do not call directly, this is called internally to render the widget.
	 * 
	 * @author Jason Corradino
	 * 
	 * @param array $args	   [Required] Automatically passed by WordPress - Settings defined when registering the sidebar of a theme
	 * @param array $instance   [Required] Automatically passed by WordPress - Current saved data for the widget options.
	 * @return void 
	 */
	public function widget( $args, $instance ){
		extract($args);
		extract($instance);
		$weather = new wp_weather();
		$options = get_option('wp_weather_options');
		$conditions = $weather->get_current_conditions($instance["zip"]);
		$image_path = ($options['imageset']!="") ? "http://icons-ak.wxug.com/i/c/{$options['imageset']}/" : "http://icons-ak.wxug.com/i/c/k/";

		echo $before_widget;
		

		?>
		<section class="wp_weather">
			<?php if ($instance['widget_title'] != "") { ?>
				<h1 class="content-headline"><?php echo $instance['widget_title']; ?></h1>
			<?php } ?>
			<h3 class="location"><?php echo $conditions->current_observation->display_location->full?></h3>
			<img src="<?php echo $image_path.$conditions->current_observation->icon; ?>.gif" title="<?php echo $conditions->current_observation->weather; ?>">
			<span class="current-conditions"><?php echo $conditions->current_observation->weather; ?></span>
			<span class="temp"><?php echo round($conditions->current_observation->temp_f); ?>&deg; F</span>
		</section>
		<?php

		//print_r($conditions);

		echo $after_widget;
		
	}
	
	/**
	 * Data validation. 
	 * 
	 * Do not call directly, this is called internally to render the widget
	 * 
	 * @author Jason Corradino
	 * 
	 * @uses esc_attr() http://codex.wordpress.org/Function_Reference/esc_attr
	 * 
	 * @param array $new_instance   [Required] Automatically passed by WordPress
	 * @param array $old_instance   [Required] Automatically passed by WordPress
	 * @return array|bool Final result of newly input data. False if update is rejected.
	 */
	public function update($new_instance, $old_instance){
		
		/* Lets inherit the existing settings */
		$instance = $old_instance;
		
		
		
		/**
		 * Sanitize each option - be careful, if not all simple text fields,
		 * then make use of other WordPress sanitization functions, but also
		 * make use of PHP functions, and use logic to return false to reject
		 * the entire update. 
		 * 
		 * @see http://codex.wordpress.org/Function_Reference/esc_attr
		 */
		foreach($new_instance as $key => $value){
			$instance[$key] = esc_attr($value);
			
		}
		
		
		foreach($instance as $key => $value){
			if($value == 'on' && !isset($new_instance[$key])){
				unset($instance[$key]);
			}

		}
		
		return $instance;
	}
	
	/**
	 * Generates the form for this widget, in the WordPress admin area.
	 * 
	 * The use of the helper functions form_field() and form_fields() is not
	 * neccessary, and may sometimes be inhibitive or restrictive.
	 * 
	 * @author Jason Corradino
	 * 
	 * @uses wp_parse_args() http://codex.wordpress.org/Function_Reference/wp_parse_args
	 * @uses self::form_field()
	 * @uses self::form_fields()
	 * 
	 * @param array $instance [Required] Automatically passed by WordPress
	 * @return void 
	 */
	public function form($instance){
		
		/* Setup default values for form fields - associtive array, keys are the field_id's */
		$defaults = array('bp_title' => 'Default Value of Text Field', 'bp_select' => 'option1');
		
		/* Merge saved input values with default values */
		$instance = wp_parse_args((array) $instance, $defaults);

		?><p><strong>General Options:</strong></p><?php

		$fields[] = array(
			'field_id' => 'widget_title',
			'type' => 'text',
			'label' => 'Title'
		);
		
		$fields[] = array(
			'field_id' => 'zip',
			'type' => 'text',
			'label' => 'Fixed Zip Code'
		);
		
		$fields[] = array(
			'field_id' => 'datafeature',
			'type' => 'select',
			'label' => 'Queried Data',
			'options' => array(
				//'alerts' => "Alerts",
				'conditions' => 'Current Conditions',
				//'3day' => 'Three Day Forecast',
				//'5day' => 'Five Day Forecast',
				'forecast-7' => 'Seven Day Forecast',
				'forecast-10' => 'Ten Day Forecast'
			)
		);
		
		$this->form_fields($fields, $instance);
		
		$show_options = array(
			array(
				'field_id' => 'widget_name',
				'type' => 'hidden',
				'label' => ''
			)
		);
		$this->form_fields($show_options, $instance, true);
	}
	

	/**
	 * Helper function - does not need to be part of widgets, this is custom, but 
	 * is helpful in generating multiple input fields for the admin form at once. 
	 * 
	 * This is a wrapper for the singular form_field() function.
	 * 
	 * @author Eddie Moya
	 * 
	 * @uses self::form_fields()
	 * 
	 * @param array $fields	 [Required] Nested array of field settings
	 * @param array $instance   [Required] Current instance of widget option values.
	 * @return void
	 */
	private function form_fields($fields, $instance){
		foreach($fields as &$field){
			extract($field);
			
			$this->form_field($field_id, $type, $label, $instance, $options);
		}
	}
	
	/**
	 * Helper function - does not need to be part of widgets, this is custom, but 
	 * is helpful in generating single input fields for the admin form at once. 
	 *
	 * @author Eddie Moya
	 * 
	 * @uses get_field_id() (No Codex Documentation)
	 * @uses get_field_name() http://codex.wordpress.org/Function_Reference/get_field_name
	 * 
	 * @param string $field_id  [Required] This will be the CSS id for the input, but also will be used internally by wordpress to identify it. Use these in the form() function to set detaults.
	 * @param string $type	  [Required] The type of input to generate (text, textarea, select, checkbox]
	 * @param string $label	 [Required] Text to show next to input as its label.
	 * @param array $instance   [Required] Current instance of widget option values. 
	 * @param array $options	[Optional] Associative array of values and labels for html Option elements.
	 * 
	 * @return void
	 */
	private function form_field($field_id, $type, $label, $instance, $options = array()){
  
		?><p><?php
		
		switch ($type){
			
			case 'text': ?>
			
					<label for="<?php echo $this->get_field_id( $field_id ); ?>"><?php echo $label; ?>: </label>
					<input id="<?php echo $this->get_field_id( $field_id ); ?>" style="<?php echo $style; ?>" class="widefat" name="<?php echo $this->get_field_name( $field_id ); ?>" value="<?php echo $instance[$field_id]; ?>" />
				<?php break;
			
			case 'select': ?>
					<label for="<?php echo $this->get_field_id( $field_id ); ?>"><?php echo $label; ?>: </label>
					<select id="<?php echo $this->get_field_id( $field_id ); ?>" class="widefat" name="<?php echo $this->get_field_name($field_id); ?>">
						<?php
							foreach ( $options as $value => $label ) :  ?>
								<label for="<?php echo $this->get_field_id( $field_id ); ?>"><?php echo $label; ?>: </label>
								<option value="<?php echo $value; ?>" <?php selected($value, $instance[$field_id]) ?>>
									<?php echo $label ?>
								</option><?php
								
							endforeach; 
						?>
					</select>
					
				<?php break;
				
			case 'textarea':
				
				$rows = (isset($options['rows'])) ? $options['rows'] : '16';
				$cols = (isset($options['cols'])) ? $options['cols'] : '20';
				
				?>
					<textarea class="widefat" rows="<?php echo $rows; ?>" cols="<?php echo $cols; ?>" id="<?php echo $this->get_field_id($field_id); ?>" name="<?php echo $this->get_field_name($field_id); ?>"><?php echo $instance[$field_id]; ?></textarea>
				<?php break;
			
			case 'radio' :
				/**
				 * Need to figure out how to automatically group radio button settings with this structure.
				 */
				?>
					
				<?php break;
			
			case 'checkbox' : ?>
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id($field_id); ?>" name="<?php echo $this->get_field_name($field_id); ?>"<?php checked( (!empty($instance[$field_id]))); ?> />
					<label for="<?php echo $this->get_field_id( $field_id ); ?>"><?php echo $label; ?></label>

				<?php break;

		}
		
		?></p><?php
	}
}

WP_Weather_Widget::register_widget();
