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
		if ( $_POST['tax_input']['departments']  ) {
			$categories = $_POST['tax_input']['departments'];
			//$cat_id =  $cat_id_arr['departments'][0];
			//$categories[] = $cat_id;
			//echo 'kenny';
		} else {
			//echo 'scott';
			$cat_args = array(
				//'child_of' => $current_term->term_id,
				'hierarchical'  => true, 
				//'orderby'  => 'term_group',
				'order'    => 'ASC',
				'parent' => 0,
				'hide_empty' => 1
			);
			$categories = get_terms('departments', $cat_args);		
		}
		//echo '<pre>' . print_r( $_POST['tax_input'] ) . '</pre>';
		echo '<pre>' . print_r($categories) . '</pre>';

		foreach( $categories as $category ) {
			if ( $_POST['tax_input']['departments']  ) {
				$cat_id = $category;
			} else {
				echo 'this is the cat: ' .$cat_id = $category->term_id;
			}
	//print_r($category);
	
		
    $args = array(
			'post_type' => 'capital_improvement',
			'posts_per_page' => -1,
			//'orderby' => $category->slug,
			'order' => 'ASC',

			'tax_query'=>array(
				array(
				'taxonomy' => 'departments',
				'field'=> 'term_id',
				'terms'=> $cat_id,
				)
			)
    );
		$query = new WP_Query($args); 
?> 

		<?php if ( $query->have_posts() ) : ?>



			<?php /* Start the Loop */ ?>
			<?php while ($query->have_posts()) : $query->the_post();?>

				<?php
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
<?php } ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>
