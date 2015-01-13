<?php
/*************************************************************
CAPITAL IMPROVEMENTS
**************************************************************/
include_once ('metabox/capital-improvements.php');
include_once ('custom-post-type.php');
//include_once ('roles.php');

// Add css style sheet for capital improvements
function capital_improvements_style() {
	global $post;
	wp_enqueue_style( 'capital-improvements-css', get_stylesheet_directory_uri() . '/inc/cap-improvements/capital-improvements.css', array(), '' );
	
	//wp_enqueue_style( 'google-fonts', 'http://fonts.googleapis.com/css?family=PT+Serif|Open+Sans:400,700|Open+Sans+Condensed:700' );
}
add_action( 'wp_enqueue_scripts', 'capital_improvements_style' );

// Create dropdown to filter results
function taxonomy_filter_restrict_manage_posts() {
	if ( current_user_can('delete_others_capital_improvements') ) {
    global $typenow;

    // If you only want this to work for your specific post type,
    // check for that $type here and then return.
    // This function, if unmodified, will add the dropdown for each
    // post type / taxonomy combination.

    $post_types = get_post_types( array( '_builtin' => false ) );

    if ( in_array( $typenow, $post_types ) ) {
    	$filters = get_object_taxonomies( $typenow );

			foreach ( $filters as $tax_slug ) {
					$tax_obj = get_taxonomy( $tax_slug );
					wp_dropdown_categories( array(
							'show_option_all' => __('Show All '.$tax_obj->label ),
							'taxonomy' 	  => $tax_slug,
							'name' 		  => $tax_obj->name,
							'orderby' 	  => 'name',
							//'selected' 	  => $_GET[$tax_slug],
							'hierarchical' 	  => $tax_obj->hierarchical,
							'show_count' 	  => false,
							'hide_empty' 	  => true
					) );
			}
    }
	}	
}
add_action( 'restrict_manage_posts', 'taxonomy_filter_restrict_manage_posts' );

// Filter the results using dropdown
function taxonomy_filter_post_type_request( $query ) {
  global $pagenow, $typenow;

  if ( 'edit.php' == $pagenow ) {
    $filters = get_object_taxonomies( $typenow );
    foreach ( $filters as $tax_slug ) {
      $var = &$query->query_vars[$tax_slug];
      if ( isset( $var ) ) {
        $term = get_term_by( 'id', $var, $tax_slug );
        $var = $term->slug;
      }
    }
  }
}
add_filter( 'pre_get_posts', 'taxonomy_filter_post_type_request' );

// Add due date column
function add_columns( $columns ) {
	$columns['exp_date'] = __( 'Due' );
	return $columns;
}
add_filter( 'manage_capital_improvement_posts_columns', 'add_columns' );

// Add due date content column
function add_column_content( $column_name, $post_id ) {
	if ($column_name == 'exp_date') {
	echo $exp_date = get_post_meta( $post_id, 'capital-improvements_date-exp', true );
	}
}
add_action( 'manage_capital_improvement_posts_custom_column', 'add_column_content', 10, 2 );

// Only display edit options to admins and managers
function my_action_row($actions, $post){
	global $authordata;
	if( is_admin() AND $post->post_type == 'capital_improvement' AND !current_user_can('delete_others_capital_improvements') ) {
		if ( date('Y-m-d') > get_post_meta( $post->ID, 'capital-improvements_date-exp', true ) ) {
		unset($actions['edit']);
		unset($actions['inline hide-if-no-js']);
		unset($actions['trash']);
		}
	}
	return $actions;
}
add_filter('post_row_actions','my_action_row', 10, 2);

// Remove Metaboxes
function remove_meta_boxes() {
  # Removes meta from Posts #
  remove_meta_box('wpba_meta_box','capital_improvement','advanced');
  remove_meta_box('content-permissions-meta-box','capital_improvement','advanced');
  remove_meta_box('post-stylesheets','capital_improvement','side');
  remove_meta_box('hybrid-core-post-template','capital_improvement','side');
	
	if (!current_user_can('delete_others_capital_improvements')) {
		remove_meta_box('authordiv','capital_improvement','normal');
		remove_meta_box('departmentsdiv','capital_improvement','side');
	}
}
add_action('do_meta_boxes','remove_meta_boxes');

//List Page - Restrict content by date
//http://wordpress.stackexchange.com/questions/69577/restrict-users-from-editing-post-based-on-the-age-of-the-post
function restrict_editing_old_posts( $allcaps, $cap, $args ) {
		// Bail out if we're not asking to edit or delete a post ...
		if( ( 'edit_post' != $args[0] && 'delete_post' != $args[0] )
			// ... or user is admin 
			|| ! empty( $allcaps['manage_options'] )
			// ... or is capitail improvements manager
			|| ! empty( $allcaps['delete_others_capital_improvements'] ) 
			)
				return $allcaps;

		// Load the post data:
		$post = get_post( $args[2] );
	// Only apply this if is a capital improvement post_class
	if ( $post->post_type == 'capital_improvement') {
		// Bail out if the post isn't published:
		if( 'publish' != $post->post_status )
				return $allcaps;

		// by date		
		$date = date('Y-m-d');
		$exp_date = get_post_meta( $post->ID , 'capital-improvements_date-exp', true );
		//if post is older than expires date ...
		if( strtotime ($date ) > strtotime( $exp_date ) ) {
				$allcaps[$cap[0]] = FALSE;
		}		
	}
return $allcaps;
}
add_filter( 'user_has_cap', 'restrict_editing_old_posts', 10, 3 );

// List Page - Only display items if the user is in eth same department of the item
function only_same_departments_allowed ( $query ) {
	if( is_admin() AND $query->query['post_type'] == 'capital_improvement' AND !current_user_can('delete_others_capital_improvements' ) ) {
		global $current_user;
		get_currentuserinfo();
		$user_id = $current_user->ID;
		$depts = wp_get_object_terms( $user_id, 'departments' );
		$user_dept = $depts[0]->slug;
		$query->set( 'departments', $user_dept );
	} else {
	}
}
add_action( 'pre_get_posts','only_same_departments_allowed' );

// Save departments section on capital improvements page_template_dropdown
function save_departments_capital_improvements( $post_id ) {
	$tax = get_taxonomy( 'departments' );

	/* Make sure the current user can edit the user and assign terms before proceeding. 
	if ( !current_post_can( 'edit_departments', $post_id ) && current_post_can( $tax->cap->assign_terms ) )
		return false;
	*/
	$terms = $_POST['departments'];
	
	//$file = '../wp-content/themes/tabula-rasa_monsoon/inc/cap-improvements/newfile.txt';
	//$txt = '';
	
	foreach ($terms as $value ) {
		$departments[] = (int)$value;
	}
	wp_set_object_terms( $post_id, $departments, 'departments');
	clean_object_term_cache( $post_id, 'departments' );
	//file_put_contents($file, $txt);
}
add_action( 'save_post', 'save_departments_capital_improvements' );
?>