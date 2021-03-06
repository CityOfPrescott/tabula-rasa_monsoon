<?php
/**
 * The Template for displaying all single posts.
 *
 * @package tabula-rasa
 */

get_header(); ?>
<div class="break-here"></div>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
	</header><!-- .entry-header -->
<section class="ci_text">
	<div class="gen-data">
		<h2><?php echo the_title(); ?></h2>
		<h3>General Project Data</h3>
		<p><span>Project Type:</span><?php echo get_post_meta( get_the_ID(), 'capital-improvements_type', true ); ?></p>
		<p><span>Department/Division:</span>
			<?php 
			$term = wp_get_post_terms( get_the_ID(), 'departments'); 
			if ($term[0]->parent == 0) {
				echo $term[0]->name;
			} else {
				$division = $term[0]->name;
				$dept = get_term($term[0]->parent, 'departments');
				echo $dept->name . '/' . $division;
			}
			?>
		</p>
	</div>
	<div class="account_num">
		<h3>Project Account No.:</h3>
		<ul>
			<?php
			$table_array = get_post_meta( get_the_ID(), 'capital-improvements_number', true );
			foreach ( $table_array as $value ) {			
				echo '<li>' . $value . '</li>';
			}
			?>
		</ul>
		<?php
		$data = get_post_meta( get_the_ID(), 'capital-improvements_ranking', true );
		if ( $data) {			?>
		<p><strong>Ranking: </strong><?php echo $data[0]; ?> of <?php echo $data[1]; ?></p>
				<?php } ?>
	</div>
	<?php
	$description = get_post_meta( get_the_ID(), 'capital-improvements_description', true );
	if ( $description ) {
	?>
	<div class="description">
		<h3>Project/Equipment Description</h3>
		<p><?php echo wpautop($description); ?></p>
	</div>
	<?php
	}
	$budget_impact = get_post_meta( get_the_ID(), 'capital-improvements_budget-impact', true );
	if ( $budget_impact ) {
	?>	
	<div class="budget-impact">
		<h3>Operating Budget Impact</h3>
		<p><?php echo wpautop($budget_impact); ?></p>
	</div>
	<?php
	}
	$justification = get_post_meta( get_the_ID(), 'capital-improvements_justification', true ); 
	if ( $justification ) {
	?>		
	<div class="justification">
		<h3>Justification (Benefit to Community or Legal Requirement)</h3>
		<p><?php echo wpautop($justification); ?></p>	
	</div>
	<?php
	}
	$goal = get_post_meta( get_the_ID(), 'capital-improvements_goal-priority', true );
	if ( $goal ) {
	?>	
	<div class="goal">
		<h3>Council Goal or Priority Level</h3>
		<p><?php echo wpautop($goal); ?></p>		
	</div>
	<?php
	}
	?>
</section>
<section class="ci_table">
	<?php
	//$tables_array = array();
	//Expenditure Table
	$design = get_post_meta( get_the_ID(), 'capital-improvements_design', true );
	$land = get_post_meta( get_the_ID(), 'capital-improvements_land', true );
	$construction = get_post_meta( get_the_ID(), 'capital-improvements_construction', true );
	$equipment = get_post_meta( get_the_ID(), 'capital-improvements_equipment', true );
	$other = get_post_meta( get_the_ID(), 'capital-improvements_other', true );
	
	//Funding Table
	$one = get_post_meta( get_the_ID(), 'capital-improvements_one', true );
	$two = get_post_meta( get_the_ID(), 'capital-improvements_two', true );
	$three = get_post_meta( get_the_ID(), 'capital-improvements_three', true );
	$four = get_post_meta( get_the_ID(), 'capital-improvements_four', true );
	$five = get_post_meta( get_the_ID(), 'capital-improvements_five', true );
	
	//Operating Table
	$personal = get_post_meta( get_the_ID(), 'capital-improvements_personal', true );
	$non_personal = get_post_meta( get_the_ID(), 'capital-improvements_non-personal', true );
	$capital = get_post_meta( get_the_ID(), 'capital-improvements_capital', true );

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
			$goahead[] = array_sum( $value );
		}
		$goahead = array_sum( $goahead );
		if (empty( $goahead ) )  { continue; }
	?>
		<div class="<?php echo $table_class; ?>">
			<h4><?php echo $table_title; ?></h4>
			<ul>
				<li>&nbsp;</li>
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
	$data_total_vert = array();
	$total_total = array();				
		foreach ( $table_info as $key => $value) {
	$data_total = array();
			$i = 0;
			if ( !empty($value)) {
				echo '<ul>';
				$data = '';
				$data1 = 0;
				$data2 = 0;
				$carryover = 0;
				$data4 = 0;
				$budget_total = 0;				
				
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
							echo '<li class="section-header">' . $data . '</li>';
						} else {
							echo '<li>' . number_format( $data ) . '</li>';
						}
					} elseif ( $i == 0 ) {
						echo '<li>' . $data . '</li>';
					} elseif ( $i == 3 ) {
						if ( $carryover == '' ) {
							echo '<li>&nbsp;</li>';
						} else {
							echo '<li>' . number_format( $carryover ) .'</li>';
						}
					} elseif ( $i == 5 ) {
						echo '<li>' . number_format( $budget_total ) .'</li>';
					}	else {
						echo '<li>&nbsp;</li>';
					}	
					
					$i++;					
					}
					//print_r($data_total);
				$total_total[] = array_sum( $data_total );
				echo '<li>' . number_format( array_sum( $data_total ) ) . '</li>';
				echo '</ul>';
			}
			
	}
	?>
		<ul>
			<li class="section-header">Total</li>
			<?php
			$i = 1;

			while ( $i < 11 ) {
				if ( !empty( $data_total_vert[$i] ) ) {
					echo '<li>' . number_format( array_sum( $data_total_vert[$i] ) ) . '</li>';
				} else {
					echo '<li></li>';
				}
				$i++;
			}
			?>
			<li><?php echo number_format( array_sum( $total_total ) ); ?></li>
		</ul>
		
	</div>
	<?php
	}
	?>
</section>

	<footer class="entry-footer">
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->


<?php //get_footer();?>