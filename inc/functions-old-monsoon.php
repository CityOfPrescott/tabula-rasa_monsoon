<?php
function my_custom_post_handbook() {
	$labels = array(
		'name'               => _x( 'handbooks', 'post type general name' ),
		'singular_name'      => _x( 'handbook', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New handbook' ),
		'edit_item'          => __( 'Edit handbook' ),
		'new_item'           => __( 'New handbook' ),
		'all_items'          => __( 'All handbooks' ),
		'view_item'          => __( 'View handbook' ),
		'search_items'       => __( 'Search handbooks' ),
		'not_found'          => __( 'No handbooks found' ),
		'not_found_in_trash' => __( 'No handbooks found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'handbooks'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds our handbooks and handbook specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
		'has_archive'   => true,
	);
	register_post_type( 'handbook', $args );	
}
add_action( 'init', 'my_custom_post_handbook' );

function my_custom_post_prcc_manual() {
	$labels = array(
		'name'               => _x( 'PRCC manuals', 'post type general name' ),
		'singular_name'      => _x( 'PRCC manual', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New PRCC manual' ),
		'edit_item'          => __( 'Edit PRCC manual' ),
		'new_item'           => __( 'New PRCC manual' ),
		'all_items'          => __( 'All PRCC manuals' ),
		'view_item'          => __( 'View PRCC manual' ),
		'search_items'       => __( 'Search PRCC manuals' ),
		'not_found'          => __( 'No PRCC manuals found' ),
		'not_found_in_trash' => __( 'No PRCC manuals found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'PRCC manuals'
	);

	$capabilities = array(
	
		'edit_post' => 'edit_prcc_manual',
		'read_post' => 'read_prcc_manual',
		'delete_post' => 'delete_prcc_manual',
		'edit_posts' => 'edit_prcc_manuals',
		'edit_others_posts' => 'edit_others_prcc_manuals',
		'publish_posts' => 'publish_prcc_manuals',
		'read_private_posts' => 'read_private_prcc_manuals',
		'read' => 'read',		
		'delete_posts' => 'delete_prcc_manuals',
		'delete_private_posts' => 'delete_private_posts',
		'delete_published_posts' => 'delete_prcc_manuals',
		'delete_others_posts' => 'delete_others_prcc_manuals',
		'edit_private_posts' => 'edit_private_prcc_manuals',
        'edit_published_posts' => 'edit_prcc_manuals'
	); 
	
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds our PRCC manuals and PRCC manual specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor' ),
		'has_archive'   => true,
		'capability_type' => 'prcc_manual',
		'capabilities' => $capabilities,
	);
	register_post_type( 'prcc_manual', $args );	
	
	//remove_post_type_support( 'prcc_manual', 'post-stylesheets' );
}
add_action( 'init', 'my_custom_post_prcc_manual' );



//remove_role('dispatch_editor');
add_role( 'dispatch_editor', 'Dispatch Editor', 
	array( 
		'edit_prcc_manual' => true,
		'read_prcc_manual' => true,
		//'delete_prcc_manual' => true,
		'edit_prcc_manuals' => true,
		'edit_others_prcc_manuals' => true,
		'publish_prcc_manuals' => true,
		'read_private_prcc_manuals' => true,
		//'read' => true,		
		//'delete_prcc_manuals' => true,
		//'delete_private_prcc_manuals' => true,
		//'delete_published_prcc_manuals' => true,
		//'delete_others_prcc_manuals' => true,
		'edit_private_prcc_manuals' => true,
        'edit_published_prcc_manuals' => true
	)
);

function prcc_add_role_caps() {
	// Add the roles you'd like to administer the custom post types
	$roles = array('dispatch_editor', 'administrator');

	// Loop through each role and assign capabilities
	foreach($roles as $the_role) { 
		$role = get_role($the_role);
		$role->add_cap( 'read' );
		$role->add_cap( 'read_prcc_manual');
		$role->add_cap( 'publish_prcc_manuals' );
		$role->add_cap( 'edit_prcc_manual' );
		$role->add_cap( 'edit_prcc_manuals' );
		if ( $role != 'capital-improvements') {
			$role->add_cap( 'read_private_prcc_manuals' );
			$role->add_cap( 'edit_others_prcc_manuals' );
			$role->add_cap( 'edit_published_prcc_manuals' );
			$role->add_cap( 'delete_others_prcc_manuals' );
			$role->add_cap( 'delete_private_prcc_manuals' );
			$role->add_cap( 'delete_published_prcc_manuals' );
		}
	}
}
//add_action('admin_init','prcc_add_role_caps',999);

// Connect to the database





function docs_table($dbc, $docs_dept)

{

require_once ('/usr/local/www/apache22/data/db_info.php');

$dbc = db_connect ($db_info['documents']);



	// Set the row colors and start the row count at 0

	$even = '"even"'; 

	$odd = '"odd"'; 

	$row_count = 0;



	// Query the db

	$query = "SELECT *, ROUND(file_size/1024, 1) AS size FROM uploads WHERE department = '$docs_dept' ORDER BY title ASC";

	$result = @mysql_query ($query);



	echo "

<table class=\"small_docs_table\">

<tbody>";



	// Display the categories

	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC))

	{

		// Using a ternary operator to simplify the conditional

		$row_color = ($row_count % 2) ? $even : $odd;



		// Set up the image variable conditional

		if ($row['file_type'] == "text/rtf") {$image = "<img src=\"/_i/icons/rtf_icon.gif\" alt=\"Download file\" /><br /><span class=\"mime\">RTF</span>";}

		elseif ($row['file_type'] == "application/pdf") {$image = "<img src=\"/_i/icons/pdf_icon.gif\" alt=\"Download file\" /><br /><span class=\"mime\">PDF</span>";}

		elseif ($row['file_type'] == "application/msword") {$image = "<img src=\"/_i/icons/word_icon.gif\" alt=\"Download file\" /><br /><span class=\"mime\">DOC</span>";}

		elseif ($row['file_type'] == "application/vnd.ms-excel") {$image = "<img src=\"/_i/icons/excel_icon.gif\" alt=\"Download file\" /><br /><span class=\"mime\">XLS</span>";}

		else {$image = "<img src=\"/_i/icons/unknown_icon.gif\" alt=\"Download file\" />";

	}

			

		// Display each record.

		echo "

<tr class=".$row_color." onclick=\"window.location='/_d/".$row['file_name']."'\">

<td width=\"80\" class=\"tcenter\"><a href=\"/_d/".$row['file_name']."\" class=\"image\">".$image."</a></td>

<td width=\"530\"><strong><a href=\"/_d/".$row['file_name']."\">".$row['title']."</a></strong><br />".$row['description']."</td>

<td width=\"100\" class=\"tcenter\">".$row['size']." KB</td>

</tr>";



		// Add 1 to every row

		$row_count++;



	} // End of while loop



	echo "

</tbody>

</table>\n\n";



} // END docs_table()

?>

<?php

// Changes default message for dropdowns with contact form 7

function my_wpcf7_form_elements($html) {

	$text = 'Choose one...';

	$html = str_replace('<option value="">---</option>', '<option value="">' . $text . '</option>', $html);

	return $html;

}

add_filter('wpcf7_form_elements', 'my_wpcf7_form_elements');

?>

<?php

// contact form 7 Add field to non wordpress database

function wpcf7_do_something ($WPCF7_ContactForm) {

	if ( 8444 == $WPCF7_ContactForm->id ) {

		//require_once ('/usr/local/www/apache22/data/db_info.php');

		if ( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ) {
			require_once ('db_info.php');
		} else {
			require_once ('/usr/local/www/apache22/data/db_info.php');
		}	

$submission = WPCF7_Submission::get_instance();
if ( $submission ) {
  $posted_data = $submission->get_posted_data();
	
	
		$date = date("Y-m-d H:i:s"); 

		$name = $posted_data["your-name"];

		$phone = $posted_data["your-phone"];

		$email = $posted_data["your-email"];

		$dbc = db_connect ($db_info['central']);

		$department = $posted_data["dept"];

		$query = "SELECT * FROM departments ORDER by dept_id ASC";

		$result = @mysql_query ($query);

		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) 	{

		if ($row['dept_name'] == $department) { $department = $row['dept_id'];}

		}

		$location = $posted_data["location"];

		$query = "SELECT * FROM buildings WHERE facilities_display = 'Y' ORDER by bldg_name ASC";

		$result = @mysql_query ($query);

				while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) 	{

		if ($row['bldg_name'] == $location) { $location = $row['bldg_id'];}

		}

		$types_array = $posted_data["type"];

		$types = implode(',', $types_array);

		$import = $posted_data["import"];
		$import = implode(',', $import);

		$description = $posted_data["your-message"];



		
		$dbc = db_connect ($db_info['facilities_a']);	

		// Query the db

		$query = "INSERT INTO work_order (date_submitted, req_name, req_phone_ext, req_email, req_dept, work_location, work_type, work_description, work_status, import) VALUES ('$date', '$name', '$phone', '$email', '$department', '$location', '$types', '$description', 'Pending', '$import')";

		$result = @mysql_query($query);

		

		if ($result) {

			$uid = mysql_insert_id();

			$work_order_num = "WO-".date('Ymd')."";

			$number = $work_order_num."-".$uid;



			$query2 = "UPDATE work_order SET work_order_num = '$number' WHERE work_order_id = $uid";

			$result2 = @mysql_query($query2);

		}	
}
	}

}

add_action("wpcf7_before_send_mail", "wpcf7_do_something");
?>