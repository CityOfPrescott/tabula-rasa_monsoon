<?php
if ( isset($_POST['Submit']) ) {
	$scott = $_POST['tax_input'];
	echo '<pre>';
	print_r($scott);
	echo '</pre>';
	$tax =  $scott['departments'][0];
	
	
	$args = array(
		'post_type' => 'capital_improvement',
		'posts_per_page' => -1,
		//'orderby' => $category->slug,
		//'order' => 'ASC',
		'tax_query'=>array(
			array(
			'taxonomy' => 'departments',
			'field'=> 'term_id',
			'terms'=> $tax,
			)
		)		
	);

	query_posts($args);//-1 is for all posts
	global $post;
	if ( have_posts() ) :
		while (have_posts()) : the_post();

		get_template_part( 'single', 'capital_improvement' );

		endwhile;
		wp_reset_query();
	endif;

} else { // If Not submitted 
	global $network_admin, $form_action;
	$network_admin = 0;
	//$form_action = admin_url("edit.php?post_type=capital_improvement&page=print_select");	
	$form_action = home_url("?post_type=capital_improvement" );	
?>	
	<form name="export" action="<?php echo $form_action; 
	//$_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validate_form();">
	<div class="department">
		<h2>Project Department</h2>
		<div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv">
			<!--<div id="<?php echo $tax_name; ?>-all" class="tabs-panel">-->
			<div id="<?php echo $tax_name; ?>-all">
				<?php
							$name = ( $tax_name == 'category' ) ? 'post_category' : 'tax_input[' . $tax_name . ']';
							echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
							?>
				<ul id="<?php echo $tax_name; ?>checklist" data-wp-lists="list:<?php echo $tax_name; ?>" class="categorychecklist form-no-clear">
					<?php 
					
					$taxonomy = 'departments';
					if ( current_user_can('delete_others_capital_improvements') ) {
						wp_terms_checklist( get_the_ID(), array( 'taxonomy' => $taxonomy ) );
					} else {
						$userid = get_current_user_id();
						//echo $userid;
						$test = wp_get_object_terms( $userid, 'departments');
						//print_r($test);
						foreach ( $test as $value ) {
							if ( $value->parent == 0 ) {
								$depts_parents_array[] = $value->term_id;
							} else {
								$depts_parents_array[] = $value->parent;
							}
						}
						$depts_parents_array = array_unique($depts_parents_array);
						//print_r($depts_parents_array);
						//print_r($depts_children_array);
						
						$post_taxs = wp_get_post_terms( get_the_ID(), 'departments');
						$post_tax_array = array();
						foreach ($post_taxs as $post_tax) {
							$post_tax_array[] = $post_tax->term_id;
						}						
						foreach( $depts_parents_array as $dept ) {
							$tax_id = $dept;
							$tax_name = get_term( $tax_id, $taxonomy );
							$tax_name = $tax_name->name;
							//$test = wp_get_post_terms( 11976, 'departments');
							//print_r( $test );
							if ( in_array($tax_id, $post_tax_array) ) {
								$checked = ' checked';
							} else {
								$checked = '';
							}
							$test = get_term( $tax_id, $taxonomy );
							$test->term_id;
							echo '<li><label class="selectit"><input value="' .$tax_id . '" type="checkbox" name="tax_input[departments][]" id="in-departments-' . $tax_id . '"' . $checked . '> ' . $tax_name . '</label><ul>';

						//$tax_ids = array(257);

							$args = array(
								//'include' => 257,
								'parent' => $tax_id,
								'hide_empty' => 0,
								//'hierarchical' => false
							);
					$categories = get_terms( $taxonomy, $args );
							foreach( $categories as $key ) {
								$cat_id = $key->term_id;
							if ( in_array($cat_id, $post_tax_array) ) {
								$checked = ' checked';
							} else {
								$checked = '';
							}						
								//$test = get_term( $cat_id, $taxonomy );
								//echo $test;
								$cat_name = $key->name;
								echo '<li id="departments-' . $cat_id . '"><label class="selectit"><input value="' .$cat_id . '" type="checkbox" name="tax_input[departments][]" id="in-departments-' . $cat_id . '"' . $checked . '> ' . $cat_name . '</label></li>';
							}
								echo '</ul>';
						}	
					}
					?>
				</ul>
			</div>
		</div>			
	</div>
	

		<div class="selection_criteria" >
			<div class="popupmain" style="float:left;">
				<div class="formfield">
					<p class="row1">
						<label>&nbsp;</label>
						<em>
							<input type="submit" class="button-primary" name="Submit" value="Submit" />
						</em>
					</p>
				</div>
			</div>
		</div>
	</form> <?php
}
?>