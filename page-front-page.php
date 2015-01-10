<?php
/**
 * Template Name: Front Page
 *
 * Useful for sites that need a news-type front page.
 *
 * @package HybridNews
 * @subpackage Template
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
	<div class="hfeed content">
page front
		<?php do_atomic( 'before_content' ); // Before content hook ?>

		<!-- Begin feature slider. -->
		<div id="slider-container">

			<div id="slider">

			<?php
				if ( hybrid_get_setting( 'feature_category' ) )
					$feature_query = array( 'cat' => hybrid_get_setting( 'feature_category' ), 'showposts' => hybrid_get_setting( 'feature_num_posts' ), 'ignore_sticky_posts' => true );
				else
					$feature_query = array( 'post__in' => get_option( 'sticky_posts' ), 'showposts' => hybrid_get_setting( 'feature_num_posts' ) );
			?>

				<?php $loop = new WP_Query( $feature_query ); ?>

				<?php while ( $loop->have_posts() ) : $loop->the_post(); $do_not_duplicate[] = $post->ID; ?>

					<div class="<?php hybrid_entry_class( 'feature' ); ?>">

						<?php get_the_image( array( 'meta_key' => array( 'Medium', 'Feature Image' ), 'size' => 'medium' ) ); ?>

						<?php do_atomic( 'before_entry' ); ?>

						<div class="entry-summary">
							<?php the_excerpt(); ?>
							<a class="more-link" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php _e( 'Full Story &raquo;', 'hybrid-news' ); ?></a>
						</div>

						<?php do_atomic( 'after_entry' ); ?>

					</div>

				<?php endwhile; ?>

			</div>

			<div class="slider-controls">
				<a class="slider-prev" title="<?php esc_attr_e( 'Previous Post', 'hybrid-news' ); ?>"><?php _e( 'Previous', 'hybrid-news' ); ?></a>
				<a class="slider-pause" title="<?php esc_attr_e( 'Pause', 'hybrid-news' ); ?>"><?php _e( 'Pause', 'hybrid-news' ); ?></a>
				<a class="slider-next" title="<?php esc_attr_e( 'Next Post', 'hybrid-news' ); ?>"><?php _e( 'Next', 'hybrid-news' ); ?></a>
			</div>

		</div>
		<!-- End feature slider. -->

		<!-- Begin excerpts section. -->
		<div id="excerpts">

			<?php $loop = new WP_Query( array( 'cat' => hybrid_get_setting( 'excerpt_category' ), 'showposts' => hybrid_get_setting( 'excerpt_num_posts' ), 'ignore_sticky_posts' => true, 'post__not_in' => $do_not_duplicate ) ); ?>

			<?php while ( $loop->have_posts() ) : $loop->the_post(); $do_not_duplicate[] = $post->ID; ?>

				<div class="<?php hybrid_entry_class(); ?>">

					<?php get_the_image( array( 'meta_key' => 'Thumbnail', 'size' => 'thumbnail' ) ); ?>

					<?php do_atomic( 'before_entry' ); ?>

					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div>

					<?php do_atomic( 'after_entry' ); ?>

				</div>

			<?php endwhile; ?>

		</div>
		<!-- End excerpts section. -->

		<?php $categories = hybrid_get_setting( 'headlines_category' ); ?>

		<?php if ( !empty( $categories ) ) : $i = 0; $alt = 'odd'; ?>

			<!-- Begin category headlines section. -->
			<div id="headlines">

			<?php foreach ( $categories as $category ) : ?>

				<?php $headlines = get_posts( array(
					'numberposts' => hybrid_get_setting( 'headlines_num_posts' ), 
					'category' => $category, 
					'post__not_in' => $do_not_duplicate
				) ); ?>

				<?php if ( !empty( $headlines ) ) : ?>

					<div class="section <?php echo $alt; ?>">

						<?php $cat = get_category( $category ); ?>

						<h3 class="section-title"><a href="<?php echo get_category_link( $category ); ?>" title="<?php echo esc_attr( $cat->name ); ?>"><?php echo $cat->name; ?></a></h3>

						<ul>
						<?php foreach ( $headlines as $post ) : $do_not_duplicate[] = $post->ID; ?>
							<li><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
						<?php endforeach; ?>
						</ul>

					</div>

					<?php $alt = ( ( $i++ % 2 == 0 ) ? 'even' : 'odd' ); ?>

				<?php endif; ?>

			<?php endforeach; ?>

			</div>
			<!-- End category headlines section. -->

		<?php endif; // End check if headline categories were selected. ?>

		<?php do_atomic( 'after_singular' ); // After singular hook ?>

		<?php do_atomic( 'after_content' ); // After content hook ?>

	</div><!-- .content .hfeed -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>