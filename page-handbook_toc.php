<?php
/**
 * Template Name: Employee Handbook Table of Contents
 *
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
	<div class="hfeed content">
	<h1>Employee Handbook</h1>
	<h2>Table of Contents</h2>

<?php
$query = new WP_Query( array ( 'orderby' => 'title', 'order' => 'DESC' ) );
$args = array( 'post_type' => 'handbook', 'orderby' => 'title', 'order' => 'ASC' );
$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();
	echo '<h3><a href="' . get_permalink() .'">';
	the_title();
	echo '</a></h3>';
?>
<ul class="handbook_articles">
<?php
	$args = array(
	'post_type' => 'attachment',
	'numberposts' => null,
	'post_status' => null,
	'orderby' => 'title',
	'order' => 'ASC',
	'post_parent' => $post->ID
	); 
	$attachments = get_posts($args);
	if ($attachments) {
		foreach ($attachments as $attachment) {
			echo '<li>';
			echo the_attachment_link($attachment->ID, false);
			echo '</li>';
		}
	}
?>
</ul>
	
<?php	
endwhile;
?>
		<?php do_atomic( 'after_content' ); // After content hook ?>

	</div><!-- .content .hfeed -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>