<?php
  ini_set('memory_limit', '256M');
  ini_set('max_execution_time', 300); //300 seconds = 5 minutes
  $extensions = array('xls' => '.xls', 'xlsx' => '.xlsx');
  $args = array (
      'public'   => true
  );
  $output = 'objects';
  $post_types = get_post_types($args, $output);

  if ( isset($_POST['Submit']) ) {
		$post_type = 'capital_improvement';
		$ext = 'xls';
		$str = '';
		
		if ( is_multisite() && $network_admin ) {
			$blog_info = get_blog_list(0, 'all');
			foreach ($blog_info as $blog) {
				switch_to_blog($blog['blog_id']);
				include('loop.php');
				restore_current_blog();
			}
		} else {

			query_posts(array('posts_per_page' => -1, 'order'=>'DESC', 'post_type' => 'capital_improvement'));//-1 is for all posts
			$str = '<table>';
			if (have_posts()) {

				$str .= '
					<tr>
						<th>Department/Division</th>
						<th>Title</th>
						<th>Project Type</th>
						<th>FY15 Budget</th>
						<th>FY15 Est Ending</th>
						<th>Carryover</th>
						<th>FY16 New</th>
						<th>FY16 Budget</th>
						<th>FY17 Projection</th>
						<th>FY18 Projection</th>
						<th>FY19 Projection</th>
						<th>FY20 Projection</th>
						<th>FY21 Projection</th>
						<th>Total</th>
						<th>Ranking</th>
						<th>Project Account No.</th>
					</tr>';
					
				while (have_posts()) {
					the_post();
					global $post;
					$post_id = $post->ID;

					//Expenditure Table
					$design = get_post_meta( $post_id, 'capital-improvements_design', true );
					$land = get_post_meta( $post_id, 'capital-improvements_land', true );
					$construction = get_post_meta( $post_id, 'capital-improvements_construction', true );
					$equipment = get_post_meta( $post_id, 'capital-improvements_equipment', true );
					$other = get_post_meta( $post_id, 'capital-improvements_other', true );
					
					//Funding Table
					$one = get_post_meta( $post_id, 'capital-improvements_one', true );
					$two = get_post_meta( $post_id, 'capital-improvements_two', true );
					$three = get_post_meta( $post_id, 'capital-improvements_three', true );
					$four = get_post_meta( $post_id, 'capital-improvements_four', true );
					$five = get_post_meta( $post_id, 'capital-improvements_five', true );
					
					//Operating Table
					$personal = get_post_meta( $post_id, 'capital-improvements_personal', true );
					$non_personal = get_post_meta( $post_id, 'capital-improvements_non-personal', true );
					$capital = get_post_meta( $post_id, 'capital-improvements_capital', true );

					// All table combines into one array
					$capital_improvement_tables = array ( 'expenditure' => array ( 'Design/Arch/Eng' => $design, 'Land (or Row) Purchase' => $land, 'Construction' => $construction, 'Equipment Purchase' => $equipment, 'Other' => $other ),
					'funding' => array( 'One' => $one, 'Two' => $two, 'Three' => $three, 'Four' => $four, 'Five' => $five ),
					'operating' => array( 'Personnel Service' => $personal, 'Non-Personnel' => $non_personal, 'Capital' => $capital )
					);

					foreach ( $capital_improvement_tables as $table => $table_info) {
						if ( $table == 'expenditure' ) {
							$table_title = 'Expenditure Plan';		
							$table_class = 'plan-expenditure';		
						}
						if ( $table == 'funding' ) {
							$table_title = 'Funding Plan';		
							$table_class = 'plan-funding';		
						}
						if ( $table == 'operating' ) {
							$table_title = 'Operating (Maintenance) Budget Impact';		
							$table_class = 'plan-operating';		
						}
						$goahead = '';
						foreach ( $table_info as $key => $value) {
							$goahead .= array_sum($value);
						}
						if ($goahead == 0 )  { continue; }

						$data_total_vert = array();
						$total_total = array();				
							
						foreach ( $table_info as $key => $value) {
							$data_total = array();
							$i = 1;
							if ( !empty($value)) {
								$str .= '<tr>';
								$data = '';
								$data1 = 0;
								$data2 = 0;
								$carryover = 0;
								$data4 = 0;
								$budget_total = 0;				

								$term = wp_get_post_terms( $post_id, 'departments');
								$str .= '<td>' . $term[0]->name . '</td>';
								$str .= '<td>' . get_the_title($post_id) . '</td>';
								$str .= '<td>' . get_post_meta( $post_id, 'capital-improvements_type', true ) . '</td>';
							
								while ( $i <= 10 ) { 
									if ( $i == 0 ) {
										if ( $table == 'funding') {
											if ( !empty( $value[$i] ) ) { 
												$data = $value[$i];
											} else {
												$data = '';
											}							
										} else {
											if ( !empty( $key ) ) { 
												$data = $key ;
											} else {
												$data = '';
											}
										}
									} else {
										if ( !empty( $value[$i]) ) { 
											$data = $value[$i]; 
											$data = str_replace ( ',', '', $data );
											settype($data, 'int');
										} else {
											$data = '';
										}
									}
						
									if ( $i == 1 ) { $data1 = $data; }
									if ( $i == 2 ) { $data2 = $data; }
									if ( $i == 3 ) { 
										$carryover = $data1 - $data2; 	
										if ( $carryover <= 0 ) {
											$carryover = '';
										}
										$data_total_vert[3][] = $carryover;
									}
									if ( $i == 4 ) { $data4 = $data; }
									if ( $i == 5 ) { 
										$budget_total = $data4 + $carryover; 
										$data_total[] = $budget_total;
										$data_total_vert[5][] = $budget_total;
									}	
									if ( $i == 5 ) {  }
									if ( $i > 5 ) { $data_total[] = $data; }
									$data_total_vert[$i][] = $data;

									//Output list
									if (! empty( $data ) ) {
										if ( $i == 0 ) {
											$str .=  '<td>' . $data . '</td>';
										} else {
											$str .= '<td>' . number_format( $data ) . '</td>';
										}
									} elseif ( $i == 0 ) {
										$str .= '<td>' . $data . '</td>';
									} elseif ( $i == 3 ) {
										if ( $carryover == '' ) {
											$str .= '<td>&nbsp;</td>';
										} else {
											$str .= '<td>' . number_format( $carryover ) .'</td>';
										}
									} elseif ( $i == 5 ) {
										$str .= '<td>' . number_format( $budget_total ) .'</td>';
									}	else {
										$str .= '<td>&nbsp;</td>';
									}	
									$i++;					
								} // end while

								$total_total[] = array_sum( $data_total );
								$str .= '<td>' . number_format( array_sum( $data_total ) ) . '</td>';
								$ranking = get_post_meta( $post_id, 'capital-improvements_ranking', true );
								if ( $ranking ) {
									$str .= '<td>' . $ranking[0] . ' of ' . $ranking[1] . '</td>';
								} else {
									$str .= '<td>Not Ranked</td>';
								}
								
								$pro_num = get_post_meta( $post_id, 'capital-improvements_number', true );
								foreach ($pro_num as $pro_nums ) {
									if ( $pro_nums[0] ) {
										$pro_num_string = $pro_nums;
									} else {
										$pro_num_string .= ', ' . $pro_nums;
									}
								}
								$str .= '<td>' . $pro_num_string . '</td>';								
								$str .= '</tr>';
							} // end if ( !empty($value)) 
				
						} // end foreach ( $table_info as $key => $value)
/*
						$str .= '
							<tr>
								<td>Total</td>';

						$i = 1;
						while ( $i < 11 ) {
							if ( !empty( $data_total_vert[$i] ) ) {
								$str .= '<td>' . number_format( array_sum( $data_total_vert[$i] ) ) . '</td>';
							} else {
								$str .= '<td></td>';
							}
							$i++;
						}
						$str .= '
							<td>tktktk'.  number_format( array_sum( $total_total ) ) . '</td>
						</tr>';
*/
							

					}//foreach ( $capital_improvement_tables as $table => $table_info) {
				} //while (have_posts()) {
				wp_reset_query();
			} else { //if (have_posts()) {
				$str .= '<tr colspan="8"><td>No post found.</td></tr>';
			}
			$str.= '</table><br/></br>';				
		}//is_multisite() && $network_admin )
		
		$filename = sanitize_file_name(get_bloginfo('name') ) . '.' . $ext;
		header("Content-type: application/vnd.ms-excel;");
		header("Content-Disposition: attachment; filename=" . $filename);
		print $str;//$str variable is used in loop.php
		exit();
  } else { // If Not submitted ?>
	<?php
    global $network_admin, $form_action;
    $network_admin = 0;
    $form_action = admin_url('edit.php?post_type=capital_improvement&page=excel_fun_stuff&noheader=true');	
	?>	
    <form name="export" action="<?php echo $form_action; ?>" method="post" onsubmit="return validate_form();">
      <div class="selection_criteria" >
        <div class="popupmain" style="float:left;">
          <div class="formfield">
            <p class="row1">
              <label>&nbsp;</label>
              <em>
                <input type="submit" class="button-primary" name="Submit" value="Download capital_improvement Data" />
              </em>
            </p>
          </div>
        </div>
      </div>
    </form> <?php
  } 

/*
$str.= '
	<tr>
		<td>' . mb_convert_encoding($post_id, 'HTML-ENTITIES', 'UTF-8') . '</td>
		<td>' . mb_convert_encoding(get_the_title(), 'HTML-ENTITIES', 'UTF-8') . '</td>
		<td>' . mb_convert_encoding($name, 'HTML-ENTITIES', 'UTF-8') . '</td>
		<td>' . mb_convert_encoding($type_name, 'HTML-ENTITIES', 'UTF-8') . '</td>
		<td>' . mb_convert_encoding($sponsor, 'HTML-ENTITIES', 'UTF-8') . '</td>
		<td>' . mb_convert_encoding($edited_on, 'HTML-ENTITIES', 'UTF-8') . '</td>
		<td>' . mb_convert_encoding($edited_by, 'HTML-ENTITIES', 'UTF-8') . '</td>						
	</tr>';
	*/
					
?>