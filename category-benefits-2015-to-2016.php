<?php
/**
 * The template for displaying Archive pages with taxonomy-category-benefits-2015-to-2016 as the category
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
	<div class="hfeed content">
		<?php 
		$category = 'Benefits 2015 to 2016';
		$args = array( 'posts_per_page' => -1, 'category_name' => $category, 'meta_key' => 'hr_benefits_order', 'orderby' => 'meta_value', 'order' => 'ASC'  );
		$loop = new WP_Query( $args );
		?>
		<?php if ( $loop->have_posts() ) : ?>
				<h1>Open Enrollment 2015 to 2016</h1>			
			<?php
			/* Start the Loop */
			while ( $loop->have_posts() ) : $loop->the_post(); ?>
				<?php 
				$args = array(
					'numberposts' => 1,
					'post_mime_type' => 'application/pdf',
					'post_parent' => $post->ID,
					'post_type' => 'attachment',
				);
				$attachments = get_children( $args );	
				if ( !$attachments ) {
					$args = array(
						'numberposts' => 1,
						'post_mime_type' => 'application/msword',
						'post_parent' => $post->ID,
						'post_type' => 'attachment',
					);					
				}
				$attachments = get_children( $args );	
				if ( !$attachments ) {
					$args = array(
						'numberposts' => 1,
						'post_mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
						'post_parent' => $post->ID,
						'post_type' => 'attachment',
					);					
				}
				$attachments = get_children( $args );
				if ( !$attachments ) {
					$args = array(
						'numberposts' => 1,
						'post_mime_type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation	',
						'post_parent' => $post->ID,
						'post_type' => 'attachment',
					);					
				}
				$attachments = get_children( $args );							
				if ( !$attachments ) {
					$args = array(
						'numberposts' => 1,
						'post_mime_type' => 'image/jpeg',
						'post_parent' => $post->ID,
						'post_type' => 'attachment',
					);					
				}
				$attachments = get_children( $args );					
				if ( $attachments ) {
					foreach ( $attachments as $attachment ) {
						$pdf_link = wp_get_attachment_url( $attachment->ID);
					}
				}
				$hr_order = get_post_meta( get_the_ID(), 'hr_benefits_order', true);
				?>
				<div class="hr_benefits">
					<div class="entry-thumbnail">
						<a href="<?php echo $pdf_link; ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
					</div>					
					<h1 class="entry-title"><a href="<?php echo $pdf_link; ?>"><?php the_title(); ?></a></h1>
					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- .entry-summary -->
				</div>
				<?php	
				endwhile;
				?>
				<?php //do_atomic( 'after_content' ); // After content hook ?>
				<?php endif; ?>

	</div><!-- .content .hfeed -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>