<?php
/**
 * @package tabula-rasa
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="archive-thumb">
		<?php echo get_the_post_thumbnail( $post->ID, 'archive-thumb' ); ?>
	</div>
	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php tr_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="entry-content">	
		<?php
		$post_categories = wp_get_post_categories( $post->ID );
		$cats = array();
			
		foreach($post_categories as $c){
			$cat = get_category( $c );
			$cats[] = $cat->slug;
		}
		if (in_array( 'bday', $cats)) {
			echo '';
		} else {		
		?>
		<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'tabula-rasa' ) ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'tabula-rasa' ),
				'after'  => '</div>',
			) );
		?>
		<?php 
		}
		?>
	</div><!-- .entry-content -->
	<footer class="entry-footer">
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->