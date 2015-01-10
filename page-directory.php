<?php
/**
 * Template Name: Employee Directory
 *
 */
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
	<div class="hfeed content old_portal">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<img src="/_i/icons/<?php echo get_post_meta($post->ID, 'title_icon', true); ?>" class="h2title" />
			<h2><?php the_title(); ?></h2>
			<hr />
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
			<form action="" method="get" id="directory_search">
				<p><strong>Search by Employee Name</strong></p>
				<input type="hidden" name="page_id" id="page_id" value="8469" /> 
				<input type="text" size="35" name="employee" id="employee" autocomplete="off" /> 
				<input type="hidden" name="search_type" id="search_type1" value="by_name" checked="checked" /> 
				<input type="hidden" name="search_type" id="search_type2" value="by_dept" /> 
				<input type="hidden" value="TRUE" name="submitted">
				<input type="submit" value="Search" name="name_submit" id="name_submit" /> &nbsp;<strong>or</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
				// Make the departments array
					$departments = array ('2' => 'Adult Center', '3' => 'Airport', '4' => 'Animal Control', '5' => 'City Clerk', '6' => 'City Court', '7' => 'City Government', '8' => 'City Manager', '9' => 'Community Development', '12' => 'Engineering Public Works', '13' => 'Facilities Management Division',  '14' => 'Field and Facilities Services Department',  '15' => 'Finance',  '16' => 'Fire',  '17' => 'Fleet Services Division',  '18' => 'Golf Course',  '19' => 'Human Resources',  '20' => 'IT',  '21' => 'Legal',  '22' => 'Library', '23' => 'Recreation Services', '24' => 'Police', '25' => 'Regional Dispatch', '26' => 'Solid Waste - Transfer Station', '27' => 'Utilities', '28' => 'Tourism', '29' => 'Street Maintenance Division');
					asort($departments);
				?>
				<select name="dept_list" onChange="if(this.selectedIndex!=0)self.location=this.options[this.selectedIndex].value">
					<option value="" selected>Show me a whole department...</option>
					<?php
						foreach ($departments as $key => $value) {
							echo "<option value=\"http://monsoon.ad.cityofprescott.org/?page_id=8469&department=$value*&search_type=by_dept&submitted=TRUE\"";
							echo ">$value</option>\n";
						}
					?>	
				</select>
			</form>
<p><img src="/_i/search.gif" alt="Show full listing" title="Show full listing" /> <a href="http://monsoon.ad.cityofprescott.org/?page_id=8469&list=full">Show full listing</a></p>

<div id="search_result">
<?php include('results.php'); ?>
</div><!-- /#results -->

			
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div>
	
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
