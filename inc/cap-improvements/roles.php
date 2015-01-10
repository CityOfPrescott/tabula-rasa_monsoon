<?php
add_role('capital-improvements',
	'Capital Improvements',
	array(
		'read' => true,
		'edit_posts' => false,
		'delete_posts' => false,
		'publish_posts' => false,
		'upload_files' => true,
	)
);
add_role('capital-improvements_manager',
	'Capital Improvements Manager',
	array(
		'read' => true,
		'edit_posts' => true,
		'delete_posts' => true,
		'publish_posts' => true,
		'upload_files' => true,
	)
);

function ci_add_role_caps() {
	// Add the roles you'd like to administer the custom post types
	$roles = array('capital-improvements', 'capital-improvements_manager', 'administrator');

	// Loop through each role and assign capabilities
	foreach($roles as $the_role) { 
		$role = get_role($the_role);
		$role->add_cap( 'read' );
		$role->add_cap( 'read_capital_improvement');
		$role->add_cap( 'publish_capital_improvements' );
		$role->add_cap( 'edit_capital_improvement' );
		$role->add_cap( 'edit_capital_improvements' );
		if ( $role != 'capital-improvements') {
			$role->add_cap( 'read_private_capital_improvements' );
			$role->add_cap( 'edit_others_capital_improvements' );
			$role->add_cap( 'edit_published_capital_improvements' );
			$role->add_cap( 'delete_others_capital_improvements' );
			$role->add_cap( 'delete_private_capital_improvements' );
			$role->add_cap( 'delete_published_capital_improvements' );
		}
	}
}
add_action('admin_init','ci_add_role_caps',999);
?>