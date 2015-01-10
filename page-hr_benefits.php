<?php
/**
 * Template Name: HR BENEFITS
 *
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
	<div class="hfeed content">
	<?php 
	$title = get_the_title();
	if (strpos($title,'2013') !== false) { 
		$title = "Open Enrollment 2013";
		$category = "benefits-2013";
	}
	if (strpos($title,'2014') !== false) { 
		$title = "Open Enrollment 2014 to 2015";
		$category = "benefits-2014-to-2015";
	}
	?>
	<h1><?php echo $title; ?></h1>
<?php
//$query = new WP_Query( array ( 'category_name' => 'benefits-2013', 'orderby' => 'title', 'order' => 'DESC' ) );
$args = array( 'category_name' => $category, 'meta_key' => 'hr_benefits_order', 'orderby' => 'meta_value', 'order' => 'ASC'  );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();
$hr_url = get_post_meta( get_the_ID(), 'hr_benefits_url', true);
$hr_order = get_post_meta( get_the_ID(), 'hr_benefits_order', true);
$upload_dir = wp_upload_dir();
$upload_dir = $upload_dir['baseurl'];
echo '<div class="hr_benefits">';
the_post_thumbnail();
	echo '<h3><a href="' . $upload_dir . $hr_url .'">';
	the_title();
	echo '</a></h3>';
	the_content();
	echo '</div>';
	
?>
<?php	
endwhile;
?>
		<?php do_atomic( 'after_content' ); // After content hook ?>

	</div><!-- .content .hfeed -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>