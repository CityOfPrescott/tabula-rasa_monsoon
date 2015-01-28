<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<div class="header_top">	
				<h3 class="toc_link"><a href="http://monsoon.ad.cityofprescott.org/?page_id=8351">Return to Table of Contents</a></h3>
				<form id="searchform" action="<?php bloginfo('url'); ?>/" method="get">
					<input class="inlineSearch" type="text" name="s" value="Enter a keyword" onblur="if (this.value == '') {this.value = 'Enter a keyword';}" onfocus="if (this.value == 'Enter a keyword') {this.value = '';}" />
					<input type="hidden" name="post_type" value="prcc_manual" />
					<input class="inlineSubmit" id="searchsubmit" type="submit" alt="Search" value="Search" />
				</form>
			</div>
			
			<?php tr_post_nav(); ?>
			
			<div class="handbook_header">
				<div class="prcc_logo">
					<img src="<?php echo get_bloginfo( 'stylesheet_directory' );?>/images/handbook_citylogo_sm<?php if ( $user_id == 11 ) { echo '_pink'; } ?>.png" />
				</div>
				<div class="prcc_header_title">
					<p>CITY OF PRESCOTT<br />
					REGIONAL COMMUNICATIONS CENTER<br />
					POLICE & FIRE PROCEDURAL MANUAL</p>
				</div>
				<div class="manual_title">
					<p><?php 
					$upper_title = get_the_title(); 
					$title_a = preg_match("/^[0-9]+\.[0-9]+\s/", $upper_title, $matches);
					$title_b = preg_split("/[0-9]*\.[0-9]*/", $upper_title);
					$full_title = $title_b[1] . ' ' . $matches[0];
					echo strtoupper( $full_title );?></p>
				</div>
			</div>
			
			<table>
				<td>Supersedes:<br /><?php echo get_post_meta($post->ID, 'cp_supercedes', true); ?></td>
				<td>Approved by:<br /><?php echo get_post_meta($post->ID, 'cp_approved', true); ?></td>
				<td>Effective Date:<br /><?php echo get_post_meta($post->ID, 'cp_date', true); ?></td>
			</table>
			
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<?php if(function_exists('pf_show_link')){echo pf_show_link();} ?>
				<?php if (function_exists("wpptopdf_display_icon")) echo wpptopdf_display_icon();?>
				<div class="entry">
					<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
					<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>

					<p class="postmetadata alt">
						<small>This entry was updated on <?php the_time('l, F jS, Y') ?>.</small>
					</p>

				</div>
			</div>
			<?php tr_post_nav(); ?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_footer('prcc_manual');?>
<?php get_footer(); ?>