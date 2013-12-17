<?php
/**
 * Backwards compatibility functions for older versions of WP
 */

if( ! function_exists( 'submit_button' ) ) {

	function submit_button( $text = NULL, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = NULL ) {
		$class = 'button-' . $type;

		$button = '<input type="submit" name="' . esc_attr( $name ) . '" class="' . esc_attr( $class );
		$button	.= '" value="' . esc_attr( $text ) . ' />';
		$button = '<p class="submit">' . $button . '</p>';
	
		echo $button;
	}
	
}

?>