<?php
// Departments Taxonomy
function department_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Departments', 'Taxonomy General Name', 'cap_improve_test' ),
		'singular_name'              => _x( 'Department', 'Taxonomy Singular Name', 'cap_improve_test' ),
		'menu_name'                  => __( 'Department', 'cap_improve_test' ),
		'all_items'                  => __( 'All Items', 'cap_improve_test' ),
		'parent_item'                => __( 'Parent Item', 'cap_improve_test' ),
		'parent_item_colon'          => __( 'Parent Item:', 'cap_improve_test' ),
		'new_item_name'              => __( 'New Item Name', 'cap_improve_test' ),
		'add_new_item'               => __( 'Add New Item', 'cap_improve_test' ),
		'edit_item'                  => __( 'Edit Item', 'cap_improve_test' ),
		'update_item'                => __( 'Update Item', 'cap_improve_test' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'cap_improve_test' ),
		'search_items'               => __( 'Search Items', 'cap_improve_test' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'cap_improve_test' ),
		'choose_from_most_used'      => __( 'Choose from the most used items', 'cap_improve_test' ),
		'not_found'                  => __( 'Not Found', 'cap_improve_test' ),
	);
	
	$rewrite = array(
		'with_front' => true,
		'slug' => 'author/department' // Use 'author' (default WP user slug).
	);
	
	$caps = array(
		'manage_terms'           => 'edit_departments',
		'edit_terms'           => 'edit_departments',
		'delete_terms'         => 'edit_departments',
		'assign_terms'          => 'read'
	);
	
	$args = array(
		'labels'                     => $labels,
		'rewrite'                     => $rewrite,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'capabilities'							 => $caps,
	);
	register_taxonomy( 'departments', 'user', $args );
}
add_action( 'init', 'department_taxonomy', 0 );

/* Add department taxonomy page to the user section. */
function my_add_profession_admin_page() {
	$tax = get_taxonomy( 'departments' );
	add_users_page(
		esc_attr( $tax->labels->menu_name ),
		esc_attr( $tax->labels->menu_name ),
		$tax->cap->manage_terms,
		'edit-tags.php?taxonomy=' . $tax->name
	);
}
add_action( 'admin_menu', 'my_add_profession_admin_page' );

// Add department taxonomy section to user page
function my_edit_user_profession_section( $post ) {
	if ( current_user_can('delete_others_capital_improvements') ) {
		$defaults = array( 'taxonomy' => 'category' );
		$tax_name = 'departments';
		$taxonomy = get_taxonomy( 'departments' );
		?>
		<div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv">
			<div id="<?php echo $tax_name; ?>-all" class="tabs-panel">
				<?php
							$name = ( $tax_name == 'category' ) ? 'post_category' : 'tax_input[' . $tax_name . ']';
							echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
							?>
				<ul id="<?php echo $tax_name; ?>checklist" data-wp-lists="list:<?php echo $tax_name; ?>" class="categorychecklist form-no-clear">
					<?php wp_terms_checklist( $post->ID, array( 'taxonomy' => $tax_name, 'popular_cats' => $popular_ids ) ); ?>
				</ul>
			</div>
		<?php if ( current_user_can( $taxonomy->cap->edit_terms ) ) : ?>
				<div id="<?php echo $tax_name; ?>-adder" class="wp-hidden-children">
					<h4>
						<a id="<?php echo $tax_name; ?>-add-toggle" href="#<?php echo $tax_name; ?>-add" class="hide-if-no-js">
							<?php
								/* translators: %s: add new taxonomy label */
								printf( __( '+ %s' ), $taxonomy->labels->add_new_item );
							?>
						</a>
					</h4>
					<p id="<?php echo $tax_name; ?>-add" class="category-add wp-hidden-child">
						<label class="screen-reader-text" for="new<?php echo $tax_name; ?>"><?php echo $taxonomy->labels->add_new_item; ?></label>
						<input type="text" name="new<?php echo $tax_name; ?>" id="new<?php echo $tax_name; ?>" class="form-required form-input-tip" value="<?php echo esc_attr( $taxonomy->labels->new_item_name ); ?>" aria-required="true"/>
						<label class="screen-reader-text" for="new<?php echo $tax_name; ?>_parent">
							<?php echo $taxonomy->labels->parent_item_colon; ?>
						</label>
						<?php wp_dropdown_categories( array( 'taxonomy' => $tax_name, 'hide_empty' => 0, 'name' => 'new' . $tax_name . '_parent', 'orderby' => 'name', 'hierarchical' => 1, 'show_option_none' => '&mdash; ' . $taxonomy->labels->parent_item . ' &mdash;' ) ); ?>
						<input type="button" id="<?php echo $tax_name; ?>-add-submit" data-wp-lists="add:<?php echo $tax_name; ?>checklist:<?php echo $tax_name; ?>-add" class="button category-add-submit" value="<?php echo esc_attr( $taxonomy->labels->add_new_item ); ?>" />
						<?php wp_nonce_field( 'add-' . $tax_name, '_ajax_nonce-add-' . $tax_name, false ); ?>
						<span id="<?php echo $tax_name; ?>-ajax-response"></span>
					</p>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
add_action( 'show_user_profile', 'my_edit_user_profession_section' );
add_action( 'edit_user_profile', 'my_edit_user_profession_section' );

/* Save department taxonomy correctly on user profile page */
function my_save_user_departments_terms( $user_id ) {
	$tax = get_taxonomy( 'departments' );

	/* Make sure the current user can edit the user and assign terms before proceeding. 
	if ( !current_user_can( 'edit_departments', $user_id ) && current_user_can( $tax->cap->assign_terms ) )
		return false;
	*/
	$terms = $_POST['tax_input'];
	
	//$file = '../wp-content/themes/tabula-rasa_monsoon/inc/cap-improvements/newfile.txt';
	//$txt = '';
	
	foreach ($terms['departments'] as $value ) {
		$departments[] = (int)$value;
	}
	wp_set_object_terms( $user_id, $departments, 'departments');
	clean_object_term_cache( $user_id, 'departments' );
	//file_put_contents($file, $txt);
}
add_action( 'personal_options_update', 'my_save_user_departments_terms' );
add_action( 'edit_user_profile_update', 'my_save_user_departments_terms' );

// Makes sure that the structure of the department taxonomy is kept when a department is selected
function my_website_wp_terms_checklist_args( $args, $post_id ) {
	if ( 'departments' == $args[ 'taxonomy' ] ) {
		$args[ 'checked_ontop' ] = false;
		$args[ 'descendants_and_self' ] = 0;
	}
	return $args;
}
add_filter( 'wp_terms_checklist_args', 'my_website_wp_terms_checklist_args', 1, 2 );
?>