<?php
// Capital Improvement Post Type
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
		//'taxonomies'            => array( 'departments', ),
		'capability_type'     => 'capital_improvement',
    //'map_meta_cap'        => true,
	);
	register_post_type( 'capital_improvement', $args );

}
add_action( 'init', 'capital_improvements', 0 );

// Add departments taxonomy to capital improvements post type
function add_categories_to_cpt(){
    register_taxonomy_for_object_type('departments', 'capital_improvement');
}
add_action('init','add_categories_to_cpt');
?>