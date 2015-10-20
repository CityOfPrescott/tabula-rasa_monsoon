<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package tabula-rasa
 */

get_header(); ?>


	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

					<header class="page-header">
				<h1 class="page-title">
					Capital Improvements
				</h1>
			</header><!-- .page-header -->
			
		<?php
		if (isset($_POST['Orderbynumber']) ) {
			$OBN = true;
		}		
		if ( $_POST['tax_input']['departments']  ) {
			$taxonomy = $_POST['tax_input']['departments'];
		} else {
			$cat_args = array(
				//'child_of' => $current_term->term_id,
				'hierarchical'  => true, 
				'orderby'  => 'term_id',
				'order'    => 'ASC',
				'parent' => 0,
				'hide_empty' => 1
			);
			if ( $OBN ) {
				$unordered = get_terms('departments', $cat_args);	
					foreach ( $unordered as $tax ) {
						$tax_name = $tax->name;
						$tax_name = substr($tax_name, -4);
						$taxonomy[$tax_name] = $tax->term_id;
					}
				ksort($taxonomy);			
			} else {
				$taxonomy = get_terms('departments', $cat_args);	
			}

		}
		//echo '<pre>' . print_r( $taxonomy ) . '</pre>';
		
		foreach( $taxonomy as $parent ) {
		//echo 'RESTART<br />';
			if ( $_POST['tax_input']['departments']  ) {
				$cat_id = $parent;
			} else {
				if ( $OBN ) {
					$cat_id = $parent;
				} else {
					$cat_id = $parent->term_id;
				}
				
				//
			}				
			$children = get_term_children( $cat_id, 'departments' );	
			$categories = array();
			
			foreach ( $children as $child) {
				$names = get_term_by($name, $child, 'departments' );
				//echo $names->name . '<br />';
				if ( $OBN ) {
					$sub_tax_name = substr($names->name, -4);
				} else {
					$sub_tax_name = $names->name;
				}				
				//
				
				//echo '<br />';
				$categories[$sub_tax_name] = $names->term_id;
			}
//echo '<pre>' . print_r( $categories ) . '</pre>';
		ksort($categories);
		//echo '<pre>' . print_r( $categories ) . '</pre>';
		array_unshift( $categories, $cat_id);		
		//echo '<pre>' . print_r( $categories ) . '</pre>';
		//echo 'Children: <br /><pre>' . print_r( $children ) . '</pre>';
		//echo 'Categories: <br /><pre>' . print_r($categories) . '</pre>';

		foreach( $categories as $category ) {
			$cat_id = $category;
	//print_r($category);
	
		
    $args = array(
			'post_type' => 'capital_improvement',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'status' => 'publish',

			'tax_query'=>array(
				array(
				'taxonomy' => 'departments',
				'field'=> 'term_id',
				'terms'=> $cat_id,
				'include_children' => false,
				)
			)
    );
		$query = new WP_Query($args); 
?> 

		<?php if ( $query->have_posts() ) : ?>



			<?php /* Start the Loop */ ?>
			<?php while ($query->have_posts()) : $query->the_post();?>
			
				<?php
				
				//echo get_the_ID() . '<br />';
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					//get_template_part( 'content', get_post_format() );
					get_template_part( 'single', 'capital_improvement' );
				?>

			<?php endwhile; ?>
<?php wp_reset_query(); ?>
			<?php //tr_paging_nav(); ?>

		<?php //else : ?>

			<?php //get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>
<?php } 
		}	

?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>
