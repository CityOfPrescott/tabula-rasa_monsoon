<?php
/**
 * Template Name: PRCC Handbook Table of Contents
 *
 */

get_header(); ?>	<div id="primary" class="content-area">		<main id="main" class="site-main" role="main">
	<div class="hfeed content">
	<h1>PRCC Handbook Table of Contents</h1>
	<?php if (! post_password_required()) { ?>
<?php
$query = new WP_Query( array ( 'orderby' => 'title', 'order' => 'DESC' ) );
$args = array( 'post_type' => 'prcc_manual', 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => 35 );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();
	echo '<h3><a href="' . get_permalink() .'">';
	echo get_the_title();
	echo '</a></h3>';
?>
<?php	
endwhile;?>
		<?php do_atomic( 'after_content' ); // After content hook ?>
	<?php } else { echo get_the_password_form(); }?> 
	</div><!-- .content .hfeed -->		</main><!-- #main -->	</div><!-- #primary --><?php get_sidebar(); ?><?php get_footer(); ?>