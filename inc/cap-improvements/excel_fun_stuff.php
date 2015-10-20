<?php
  ini_set('memory_limit', '256M');
  ini_set('max_execution_time', 300); //300 seconds = 5 minutes
  $extensions = array('xls' => '.xls', 'xlsx' => '.xlsx');
  $args = array (
      'public'   => true
  );
  $output = 'objects';
  $post_types = get_post_types($args, $output);
	$submitAll = '';
	
  if ( isset($_POST['Submit']) || isset($_POST['SubmitAll']) ) {
		if (isset($_POST['SubmitAll']) ) {
			$submitAll = true;
		}
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

			query_posts(array('posts_per_page' => -1, 'order'=>'ASC', 'post_type' => 'capital_improvement', 'post_status' => 'publish'));//-1 is for all posts
			$str = '<table>';
			if (have_posts()) {

				$str .= '
					<tr>
						<th>Department</th>
						<th>Division</th>
						<th>Title</th>
						<th>Project Type</th>
						<th>Project Account No.</th>
						<th>Rankings</th>';
						
						if ( $submitAll == true ) {
							$str .= '
								<th>Table</th>
								<th>Table Heading</th>
							';
						}
						
						$str .= '
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
	//print_r($capital_improvement_tables);							
						if ( $table == 'expenditure' ) {
							$table_title = 'Expenditure Plan';		
							$table_class = 'plan-expenditure';		
						}
						if ( $table == 'funding' ) {
							if ( !$submitAll ) { continue; }
							$table_title = 'Funding Plan';		
							$table_class = 'plan-funding';		
						}
						if ( $table == 'operating' ) {
							if ( !$submitAll ) { continue; }
							$table_title = 'Operating (Maintenance) Budget Impact';		
							$table_class = 'plan-operating';		
						}
						$goahead = '';
						foreach ( $table_info as $key => $value) {
						//print_r($value);
							$goahead .= array_sum($value);
						}
						//echo $goahead . '<br />';
						if ($goahead === 0 )  { continue; }

						$data_total_vert = array();
						$total_total = array();				
						
						$excel_count = 0;				
						foreach ( $table_info as $key => $value) {
	//print_r($table_info);				
				
							$data_total = array();
							$i = 0;
							if ( !empty($value)) {
								//$str .= '<tr>';
								$data = '';
								$data1 = 0;
								$data2 = 0;
								$carryover = 0;
								$data4 = 0;
								$budget_total = 0;			


								
								
								if ( $submitAll == true || $excel_count == 0 ) {
									$str2 = '<tr>';
									$term = wp_get_post_terms( get_the_ID(), 'departments'); 
									if ($term[0]->parent == 0) {
										$str2 .= '<td>' . $term[0]->name . '</td><td></td>';
									} else {
										$division = $term[0]->name;
										$dept = get_term($term[0]->parent, 'departments');
										$str2 .= '<td>' . $dept->name . '</td><td>' . $division . '</td>';
									}
									$str2 .= '<td>'. get_the_title($post_id) . '</td>';
									//$str2 .= '<td>'. $post_id . '</td>';
									$str2 .= '<td>' . get_post_meta( $post_id, 'capital-improvements_type', true ) . '</td>';
			
									$pro_num_str2ing = '';
									$num_i = 0;
									$pro_num = get_post_meta( $post_id, 'capital-improvements_number', true );
									foreach ($pro_num as $pro_nums ) {
										if ( $num_i == 0 ) {
											$pro_num_str2ing = $pro_nums;
										} else {
											$pro_num_str2ing .= '|' . $pro_nums;
										}
										$num_i++;
									}
									$str2 .= '<td>' . $pro_num_str2ing . '</td>';	
									
									$rankinging = '';
									$ranking = get_post_meta( $post_id, 'capital-improvements_ranking', true );
									if ( $ranking ) {
										$str2 .= '<td>' . $ranking[0] . ' of ' . $ranking[1] . '</td>';
									} else {
										$str2 .= '<td>Not Ranked</td>';
									}
									
															
								}
								if ( $submitAll == true ) {
									$str2 .= '<td>' . $table_title . '</td>';
								}
								if ( $submitAll == true || $excel_count == 0 ) {
									$str .= $str2;
								}
								$excel_count++;			
								
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
									if ( $submitAll) {
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
									}
									$i++;					
									
								} // end while
								
								//$str .= '</tr>';
								$total_total[] = array_sum( $data_total );
								if ( $submitAll == true ) {
									$str .= '<td>' . number_format( array_sum( $data_total ) ) . '</td>';
								}

							} // end if ( !empty($value)) 
						} // end foreach ( $table_info as $key => $value)
						

						if ( $submitAll == true ) {
							//$str .= '<tr>';
							$str .= $str2;
							$str .= '<td>Total</td>';
						}
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
								<td>'.  number_format( array_sum( $total_total ) ) . '</td>';
								
$str .= '</tr>';
					}//foreach ( $capital_improvement_tables as $table => $table_info) {
				} //while (have_posts()) {
				//wp_reset_query();
			} else { //if (have_posts()) {
				$str .= '';
			}
			$str.= '</table>';				
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
                <input type="submit" class="button-primary" name="Submit" value="Download Expenditure Only" />
              </em>
              <em>
                <input type="submit" class="button-primary" name="SubmitAll" value="Download Kitchen Sink" />
              </em>							
            </p>
          </div>
        </div>
      </div>
    </form> <?php
  } 

?>