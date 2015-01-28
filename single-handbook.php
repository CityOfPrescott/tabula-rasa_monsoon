<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();
?>

	<div class="hfeed content<?php 
	$user_id = get_current_user_id();
	if ( $user_id == 11 ) { 
	echo ' shawni'; 
	} 
	?>"> 
<h3 class="toc_link"><a href="http://monsoon.ad.cityofprescott.org/?page_id=7882">Return to Table of Contents</a></h3>	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<!--
		<div class="navigation">
			<div class="alignleft"><?php previous_post_link_plus( array(
                         'order_by' => 'post_title',
                         'format' => '%link',
                         'link' => '%title',
                         'in_same_cat' => true,
                         'order' => 'ASC'
                    ) ); ?></div>
			<div class="alignright"><?php next_post_link_plus( array(
                         'order_by' => 'post_title',
                         'format' => '%link',
                         'link' => '%title',
                         'in_same_cat' => true,
                         'order' => 'DESC'
                    ) );  ?></div>
		</div>	
		
		<div class="handbook_header">
		<img src="<?php echo get_bloginfo( 'stylesheet_directory' );?>/images/handbook_citylogo_sm<?php if ( $user_id == 11 ) { echo '_pink'; } ?>.png" />
		<p>CITY OF PRESCOTT, AZ</p>
		<p>Administrative Regulation</p>
		<p><?php 
		$upper_title = get_the_title(); 
		$upper_title = preg_split("/[0-9]*\.[0-9]*\s/", $upper_title);
		echo strtoupper( $upper_title[1] ); ?></p>
		</div>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?></h2>
		<?php if(function_exists('pf_show_link')){echo pf_show_link();} ?>
		<?php if (function_exists("wpptopdf_display_icon")) echo wpptopdf_display_icon();?>
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>

				<p class="postmetadata alt">
					<small>
						This entry was updated
						<?php /* This is commented, because it requires a little adjusting sometimes.
							You'll need to download this plugin, and follow the instructions:
							http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
							/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
						on <?php the_time('l, F jS, Y') ?>.

					</small>
				</p>

			</div>
		</div>
		-->
		<h4><?php echo the_title(); ?></h4>
		<div style="clear:both"></div>
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
		<div class="navigation">
			<div class="alignleft"><?php previous_post_link_plus( array(
                         'order_by' => 'post_title',
                         'format' => '%link',
                         'link' => '%title',
                         'in_same_cat' => true,
                         'order' => 'ASC'
                    ) ); ?></div>
			<div class="alignright"><?php next_post_link_plus( array(
                         'order_by' => 'post_title',
                         'format' => '%link',
                         'link' => '%title',
                         'in_same_cat' => true,
                         'order' => 'DESC'
                    ) );  ?></div>
		</div>		

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

	</div>

<?php get_footer();?>