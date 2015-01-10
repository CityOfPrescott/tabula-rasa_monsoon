<?php
// Register Custom Post Type
function capital_improvements() {

	$labels = array(
		'name'                => _x( 'Capital Improvements', 'Post Type General Name', 'cap_improve_test' ),
		'singular_name'       => _x( 'Capital Improvement', 'Post Type Singular Name', 'cap_improve_test' ),
		'menu_name'           => __( 'Capital Improvement', 'cap_improve_test' ),
		'parent_item_colon'   => __( 'Capital Improvement Item:', 'cap_improve_test' ),
		'all_items'           => __( 'Capital Improvement Items', 'cap_improve_test' ),
		'view_item'           => __( 'View Capital Improvement Item', 'cap_improve_test' ),
		'add_new_item'        => __( 'Add New Capital Improvement Item', 'cap_improve_test' ),
		'add_new'             => __( 'Add New Capital Improvement', 'cap_improve_test' ),
		'edit_item'           => __( 'Edit Capital Improvement Item', 'cap_improve_test' ),
		'update_item'         => __( 'Update Capital Improvement Item', 'cap_improve_test' ),
		'search_items'        => __( 'Search Capital Improvements', 'cap_improve_test' ),
		'not_found'           => __( 'Not found', 'cap_improve_test' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'cap_improve_test' ),
	);
	
	$capabilities = array(
		'edit_post'           => 'edit_capital_improvement',
		'read_post'           => 'read_capital_improvement',
		'delete_post'         => 'delete_capital_improvement',
		'edit_posts'          => 'edit_capital_improvements',
		'edit_others_posts'   => 'edit_others_capital_improvements',
		'publish_posts'       => 'publish_capital_improvements',
		'read_private_posts'  => 'read_private_capital_improvements',
	);
	
	$args = array(
		'labels'              => $labels,
		'supports'            => array( 'title', 'author', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'taxonomies'            => array( 'departments', ),
		'capability_type'     => 'capital_improvement',
    //'map_meta_cap'        => true,
	);
	register_post_type( 'capital_improvement', $args );

}

// Hook into the 'init' action
add_action( 'init', 'capital_improvements', 0 );

function custom_taxonomy() {
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
add_action( 'init', 'custom_taxonomy', 0 );

function add_categories_to_cpt(){
    register_taxonomy_for_object_type('departments', 'capital_improvement');
}
add_action('init','add_categories_to_cpt');


/* Adds the taxonomy page in the admin. */
add_action( 'admin_menu', 'my_add_profession_admin_page' );
function my_add_profession_admin_page() {

	$tax = get_taxonomy( 'departments' );

	add_users_page(
		esc_attr( $tax->labels->menu_name ),
		esc_attr( $tax->labels->menu_name ),
		$tax->cap->manage_terms,
		'edit-tags.php?taxonomy=' . $tax->name
	);
}
add_action( 'show_user_profile', 'my_edit_user_profession_section' );
add_action( 'edit_user_profile', 'my_edit_user_profession_section' );

function my_edit_user_profession_section( $post ) {
	$defaults = array( 'taxonomy' => 'category' );
	/*
	if ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) ) {
		$args = array();
	} else {
		$args = $box['args'];
	}
	$r = wp_parse_args( $args, $defaults );
	*/
	$tax_name = 'departments';
	$taxonomy = get_taxonomy( 'departments' );
	?>
	<div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv">
	<!--
		<ul id="<?php echo $tax_name; ?>-tabs" class="category-tabs">
			<li class="tabs"><a href="#<?php echo $tax_name; ?>-all"><?php echo $taxonomy->labels->all_items; ?></a></li>
			<li class="hide-if-no-js"><a href="#<?php echo $tax_name; ?>-pop"><?php _e( 'Most Used' ); ?></a></li>
		</ul>

		<div id="<?php echo $tax_name; ?>-pop" class="tabs-panel" style="display: none;">
			<ul id="<?php echo $tax_name; ?>checklist-pop" class="categorychecklist form-no-clear" >
				<?php $popular_ids = wp_popular_terms_checklist( $tax_name ); ?>
			</ul>
		</div>
-->
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


/* Update the profession terms when the edit user page is updated. */
add_action( 'personal_options_update', 'my_save_user_departments_terms' );
add_action( 'edit_user_profile_update', 'my_save_user_departments_terms' );
function my_save_user_departments_terms( $user_id ) {

	$tax = get_taxonomy( 'departments' );
	print_r($tax);

	/* Make sure the current user can edit the user and assign terms before proceeding. 
	if ( !current_user_can( 'edit_departments', $user_id ) && current_user_can( $tax->cap->assign_terms ) )
		return false;
*/
	$terms = $_POST['tax_input'];
	//$file = '../wp-content/themes/tabula-rasa_monsoon/inc/cap-improvements/newfile.txt';
//$txt = '';
	/* Sets the terms (we're just using a single term) for the user. */
	foreach ($terms['departments'] as $value ) {
		$departments[] = (int)$value;
		//wp_set_object_terms( $user_id, $value, 'departments');
		//$txt .= 'key: ' . $key;
		//$txt .= 'value: ' . $value;
	}
wp_set_object_terms( $user_id, $departments, 'departments');

	clean_object_term_cache( $user_id, 'departments' );
	
	//file_put_contents($file, $txt);
}

add_filter( 'wp_terms_checklist_args', 'my_website_wp_terms_checklist_args', 1, 2 );
function my_website_wp_terms_checklist_args( $args, $post_id ) {

   // obviously replace this with your taxonomy or remove it altogether
   if ( 'departments' == $args[ 'taxonomy' ] ) {
      $args[ 'checked_ontop' ] = false;
      $args[ 'descendants_and_self' ] = 0;
   }

   return $args;

}
?>