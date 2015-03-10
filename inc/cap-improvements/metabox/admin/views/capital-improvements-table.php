<section class="capital-improvements-metabox">
	<section class="basic-info">
		<div class="date-expire">
<?php
function mdy($mid = "month", $did = "day", $yid = "year", $mval = null, $dval = null, $yval=null, $hidden = '') {
	$date_exp = get_post_meta( get_the_ID(), 'capital-improvements_date-exp', true );
	if(empty($mval)) $mval = date("m");
	if(empty($dval)) $dval = date("d");
	if(empty($yval)) $yval = date("Y");

	$months = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
	$out = "<select $hidden name='$mid' id='$mid'>";
	foreach($months as $val => $text)
		if($val == $mval) {
			$out .= "<option value='$val' selected>$text</option>";
			$date_exp_month = $val;
		}	else { 
			$out .= "<option value='$val'>$text</option>";
		}
	$out .= "</select> ";
	//$date_exp_month = $val;
	
	$out .= "<select $hidden name='$did' id='$did'>";
	for($i = 1; $i <= 31; $i++)
		if($i == $dval) {
			$out .= "<option value='$i' selected>$i</option>";
			$date_exp_day = $i;
		} else  {
			$out .= "<option value='$i'>$i</option>";
		}
	$out .= "</select> ";
	//$date_exp_day = $i;

	$out .= "<select $hidden name='$yid' id='$yid'>";
	for($i = date("Y"); $i <= date("Y") + 2; $i++)
		if($i == $yval) { 
			$out.= "<option value='$i' selected>$i</option>";
			$date_exp_year = $i;
		} else {
			$out.= "<option value='$i'>$i</option>";
		}
	$out .= "</select>";
	
	//$date_exp_full = $date_exp_year . '-' . $date_exp_month . '-' . $date_exp_day;
	$date_exp_full = $yid . '-' . $mid . '-' . $did;
	
	$out .= '<input type="hidden" name="capital-improvements_date-exp" value="' . $date_exp_full . '"></input>';
	return $out;
}
?>
</div>
<?php
$date_exp = get_post_meta( get_the_ID(), 'capital-improvements_date-exp', true );
if ($date_exp) {
	$date_exp = explode('-', $date_exp);
	$yval = $date_exp[0];
	$mval = $date_exp[1];
	$dval = $date_exp[2];
} else {
	$yval = 2015;
	$mval = 03;
	$dval = 15;	
}
if ( current_user_can('capital-improvements_manager') || current_user_can('administrator') )  {
	echo '<h2>Due Date</h2>';
	echo mdy( 'month', 'day', 'year', $mval, $dval, $yval );
} else {
	echo mdy( 'month', 'day', 'year', $mval, $dval, $yval, 'style="visibility:hidden;"' );
}
?>
		<div class="type">
			<h2>Project Type</h2>
			<input type="text" id="capital-improvements_type" name="capital-improvements_type" value="<?php echo get_post_meta( get_the_ID(), 'capital-improvements_type', true ); ?>">
		</div>	
		<div class="number">
			<h2>Project Account Number</h2>
				<ul>
				<?php $datas = get_post_meta( get_the_ID(), 'capital-improvements_number', true ); ?>
				<?php if ( ! empty( $datas ) ) { 
					$i = 1; 
				?>
					<?php 
					while ( $i < 6 ) { 
						if ( empty( $datas[$i]) ) { 
							$data = ''; 
						} else { 
							$data = $datas[$i]; 				
						}
					?>
					<li><input type="text" id="capital-improvements_number" name="capital-improvements_number[<?php echo $i; ?>]" value="<?php echo $data; ?>" /></li>
					<?php
						$i++;
						} ?>
				<?php } else {
					$i = 1;
					while ( $i < 6 ) {
				?>
					<li><input type="text" name="capital-improvements_number[<?php echo $i; ?>]" placeholder="Enter Number Here" value="" /></li>
				<?php 
					$i++;
					}
				}
				?>
				</ul>
		</div>
		<div class="ranking">
			<h2>Project Ranking</h2>		
			<input type="text" name="capital-improvements_ranking[]" value="<?php $data = get_post_meta( get_the_ID(), 'capital-improvements_ranking', true ); echo $data[0]; ?>"  placeholder="Enter Number Here"> of <input type="text" name="capital-improvements_ranking[]" value="<?php echo $data[1]; ?>"  placeholder="Enter Number Here">
		</div>			
		<div class="department">
			<h2>Project Department</h2>
			<div id="taxonomy-<?php echo $tax_name; ?>" class="categorydiv">
				<div id="<?php echo $tax_name; ?>-all" class="tabs-panel">
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
		<div class="description">
			<h2>Project/Equipment Description</h2>
			<?php
				$args = array( 'media_buttons' => false );
				$content = get_post_meta( get_the_ID(), 'capital-improvements_description', true );;
				$editor_id = 'capital-improvements_description';
				//$editor_id = 'content';
				wp_editor( $content, $editor_id, $args );
			?>
		</div>
		<div class="budget-impact">
			<h2>Operating Budget Impact</h2>
			<?php
				$content = get_post_meta( get_the_ID(), 'capital-improvements_budget-impact', true );;
				$editor_id = 'capital-improvements_budget-impact';
				//$editor_id = 'content';
				wp_editor( $content, $editor_id, $args );
			?>			
		</div>
		<div class="justification">
			<h2>Justification (Benefit to Community or Legal Requirement)</h2>
			<?php
				$content = get_post_meta( get_the_ID(), 'capital-improvements_justification', true );;
				$editor_id = 'capital-improvements_justification';
				//$editor_id = 'content';
				wp_editor( $content, $editor_id, $args );
			?>					
		</div>
		<div class="goal-priority">
			<h2>Council Goal or Priority Level</h2>
			<?php
				$content = get_post_meta( get_the_ID(), 'capital-improvements_goal-priority', true );;
				$editor_id = 'capital-improvements_goal-priority';
				//$editor_id = 'content';
				wp_editor( $content, $editor_id, $args );
			?>					
		</div>	
	</section>
	<section class="budget-table">
	<?php
	$capital_improvement_tables = array ( 'expenditure' => array ( 'Design/Arch/Eng' => 'design', 'Land (or Row) Purchase' => 'land', 'Construction' => 'construction', 'Equipment Purchase' => 'equipment', 'Other' => 'other' ),
	'funding' => array( 'One' => 'one', 'Two' => 'two', 'Three' => 'three', 'Four' => 'four', 'Five' => 'five' ),
	'operating' => array( 'Personnel Service' => 'personal', 'Non-Personnel' => 'non-personal', 'Capital' => 'capital' )
	);

	//$ex_table_array = array( 'Design/Arch/Eng' => 'design', 'Land (or Row) Purchase' => 'land', 'Construction' => 'construction', 'Equipment Purchase' => 'equipment', 'Other' => 'other' );
	$i = 0;
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
	$i++;			
	?>
		<div class="<?php echo $table_class; ?>">
			<h4><?php echo $table_title; ?></h4>
			<ul>
				<li></li>
				<li>BUDGET FY15</li>
				<li>EST END FY15</li>
				<li>CARRYOVER</li>
				<li>FY16 NEW</li>
				<li>FY16 BUDGET</li>
				<li>FY17 PROJECTION</li>
				<li>FY18 PROJECTION</li>
				<li>FY19 PROJECTION</li>
				<li>FY20 PROJECTION</li>
				<li>FY21 PROJECTION</li>
				<li>TOTAL</li>
			</ul>			
			<?php
			$data_total_total = array();
			$final_total = array();
			foreach ( $table_info as $table_heading => $heading_value ) {
			$data_total = array();
			$i = 0;
			?>
			<ul>
				<!--<li><?php echo $table_heading; ?></li>-->
				<?php 
				$datas = get_post_meta( get_the_ID(), 'capital-improvements_' . $heading_value, true );
				$data = '';
				$data1 = 0;
				$data2 = 0;
				$carryover = 0;
				$data4 = 0;
				$budget_total = 0;
				while ( $i <= 10 ) { 
					
					if ( $i == 0 ) {
						if ( $table == 'funding') {
							if ( !empty( $datas[$i] ) ) { 
								$data = $datas[$i];
							} else {
								$data = '';
							}							
						} else {
							if ( !empty( $table_heading ) ) { 
								$data = $table_heading ;
							} else {
								$data = '';
							}
						}
					} else {
						if ( !empty( $datas[$i]) ) { 
							$data = $datas[$i]; 
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
						if ( $carryover < 0 ) { $carryover = 0; }
						$data_total_total[3][] = $carryover;
					}
					if ( $i == 4 ) { $data4 = $data; }
					if ( $i == 5 ) { 
						$budget_total = $data4 + $carryover; 
						$data_total_total[5][] = $budget_total;
					}	
					if ( $i == 5 ) { $data_total[] = $budget_total; }
					if ( $i > 5 ) { $data_total[] = $data; }	
					
					$data_total_total[$i][] = $data;
					
					//Output list
					if (! empty( $data ) ) {
						if ( $i == 0 ) {
							if ( $table == 'funding') {
							echo '<li><input type="text" name="capital-improvements_' . $heading_value . '[' . $i . ']" value="' . $data . '" /></li>';
							} else {
								echo '<li>' . $data . '</li>';
							}
						} else {
							echo '<li><input type="text" name="capital-improvements_' . $heading_value . '[' . $i . ']" value="' .  number_format( $data ) . '" /></li>';
						}
					} elseif ( $i == 0 ) {
						echo '<li><input type="text" name="capital-improvements_' . $heading_value . '[' . $i . ']" value="" placeholder="Enter Item Name Here" /></li>';
					} elseif ( $i == 3 ) {
						echo '<li>' . number_format( $carryover ) .'</li>';
					} elseif ( $i == 5 ) {
						echo '<li>' . number_format( $budget_total ) .'</li>';
					}	else {
						echo '<li><input type="text" name="capital-improvements_' . $heading_value . '[' . $i . ']" value="" /></li>';
					}	
								
					//counter
					$i++;
				}
				$final_total[] = array_sum( $data_total );
			?>
			<li><?php echo number_format( array_sum( $data_total ) ); ?></li>
			</ul>
			<?php
		} //end foreach tables array
		?>
			<ul>
				<li>Total</li>
				<?php
				$i = 1;

				while ( $i < 11 ) {
					if ( !empty( $data_total_total[$i] ) ) {
						echo '<li>' . number_format( array_sum( $data_total_total[$i] ) ) . '</li>';
					} else {
						echo '<li></li>';
					}
					$i++;
				}
				?>
				<li><?php echo number_format( array_sum( $final_total ) ); ?></li>
			</ul>			
		<?php
		echo '</div>';
	} //end foreach big table array
			?>
	</section>
	<?php wp_nonce_field( 'capital-improvements_save', 'capital-improvements_nonce' ); ?>
</section>