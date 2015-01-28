<?php
 
/**
 * Represents the Author's Commentary Meta Box.
 *
 * @link       http://code.tutsplus.com/tutorials/creating-maintainable-wordpress-meta-boxes-the-layout--cms-22208
 * @since      0.2.0
 *
 * @package    Author_Commentary
 * @subpackage Author_Commentary/admin
 */
 
/**
 * Represents the Author's Commentary Meta Box.
 *
 * Registers the meta box with the WordPress API, sets its properties, and renders the content
 * by including the markup from its associated view.
 *
 * @package    Author_Commentary
 * @subpackage Author_Commentary/admin
 * @author     Tom McFarlin <tom@tommcfarlin.com>
 */
class Capital_Improvements_Meta_Box {
 
	// Register this class with the WordPress API
	public function initialize_hooks() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}

		// The function responsible for creating the actual meta box.
		public function add_meta_box() {
			add_meta_box(
				'capital-improvements',
				"Capital Improvements",
				array( $this, 'display_meta_box' ),
				'capital_improvement',
				'normal',
				'default'
			);
		}
 
	// Renders the content of the meta box.
	public function display_meta_box() {
		include_once( 'views/capital-improvements-table.php' );
	}
	
	// Sanitizes and serializes the information associated with this post.
	public function save_post( $post_id ) {
		// If we're not working with a 'post' post type or the user doesn't have permission to save, then we exit the function.
		if ( ! $this->user_can_save( $post_id, 'capital-improvements_nonce', 'capital-improvements_save' ) ) {
			return;
		}
		
		if ( $this->value_exists( 'capital-improvements_date-exp' ) ) {
			$exp_year = $_POST[ 'year' ];
			$exp_month = $_POST[ 'month' ];
			$exp_day = $_POST[ 'day' ];
			$date_exp = $exp_year . '-' . $exp_month . '-' . $exp_day;
			$this->update_post_meta(
				$post_id,
				'capital-improvements_date-exp',
				$date_exp
			);
			} else {
				$this->delete_post_meta( $post_id, 'capital-improvements_date-exp');
			}
		
		$text_boxes = array('capital-improvements_type', 'capital-improvements_ranking','capital-improvements_description', 'capital-improvements_budget-impact', 'capital-improvements_justification', 'capital-improvements_goal-priority');
		foreach ( $text_boxes as $text_box ) {
			if ( $this->value_exists( $text_box ) ) {
				$this->update_post_meta(
					$post_id,
					$text_box,
					$this->sanitize_data( $text_box )
				);
			} else {
				$this->delete_post_meta( $post_id, $text_box );
			}
		}
		
		$table1 = array( 'capital-improvements_number', 'capital-improvements_design', 'capital-improvements_land', 'capital-improvements_construction', 'capital-improvements_equipment', 'capital-improvements_other');
		foreach ( $table1 as $data ) {
			if ( $this->value_exists( $data ) ) {
				$this->update_post_meta(
					$post_id,
					$data,
					$this->sanitize_data( $data, true )
				);
			} else {
				$this->delete_post_meta( $post_id, $data );
			}
		}

		$table2 = array( 'capital-improvements_one', 'capital-improvements_two', 'capital-improvements_three', 'capital-improvements_four', 'capital-improvements_five');
		foreach ( $table2 as $data ) {
			if ( $this->value_exists( $data ) ) {
				$this->update_post_meta(
					$post_id,
					$data,
					$this->sanitize_data( $data, true )
				);
			} else {
				$this->delete_post_meta( $post_id, $data );
			}
		}		

		$table3 = array( 'capital-improvements_personal', 'capital-improvements_non-personal', 'capital-improvements_capital');
		foreach ( $table3 as $data ) {
			if ( $this->value_exists( $data ) ) {
				$this->update_post_meta(
					$post_id,
					$data,
					$this->sanitize_data( $data, true )
				);
			} else {
				$this->delete_post_meta( $post_id, $data );
			}
		}		
		
	}
		
	// Determines whether or not a value exists in the $_POST collection identified by the specified key.
	private function value_exists( $key ) {
		return ! empty( $_POST[ $key ] );
	}
	
	// Deletes the specified meta data associated with the specified post ID based on the incoming key.
	private function delete_post_meta( $post_id, $meta_key ) {
		if ( '' !== get_post_meta( $post_id, $meta_key, true ) ) {
			delete_post_meta( $post_id, $meta_key );
		}
	}
	
	// Updates the specified meta data associated with the specified post ID based on the incoming key.
	
	private function update_post_meta( $post_id, $meta_key, $meta_value ) {
		if ( is_array( $_POST[ $meta_key ] ) ) {
			$meta_value = array_filter( $_POST[ $meta_key ] );
		}
		update_post_meta( $post_id, $meta_key, $meta_value );
	}
	
	// Sanitizes the data in the $_POST collection identified by the specified key based on whether or not the data is text or is an array.
	private function sanitize_data( $key, $is_array = false ) {
		$sanitized_data = null;
		if ( $is_array ) {
			$resources = $_POST[ $key ];
			$sanitized_data = array();
			foreach ( $resources as $resource ) {
				$resource = esc_url( strip_tags( $resource ) );
				if ( ! empty( $resource ) ) {
					$sanitized_data[] = $resource;
				}
			}
		} else {
			$sanitized_data = '';
			$sanitized_data = trim( $_POST[ $key ] );
			$sanitized_data = esc_textarea( strip_tags( $sanitized_data ) );
		}
		return $sanitized_data;
	}		
	
	// Verifies that the post type that's being saved is actually a post (versus a page or another custom post type.
	private function is_valid_post_type() {
		return ! empty( $_POST['post_type'] ) && 'capital_improvement' == $_POST['post_type'];
	}
	
	// Determines whether or not the current user has the ability to save meta data associated with this post.
	private function user_can_save( $post_id, $nonce_action, $nonce_id ) {		 
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $nonce_action ] ) && wp_verify_nonce( $_POST[ $nonce_action ], $nonce_id ) );
 
		// Return true if the user is able to save; otherwise, false.
		return ! ( $is_autosave || $is_revision ) && $this->is_valid_post_type() && $is_valid_nonce;
		//return true;
	}				
}
?>