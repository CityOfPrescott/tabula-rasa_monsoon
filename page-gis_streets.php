<?php
/**
 * Template Name: GIS - Street Info
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
<?php	
// Connect to the database
if ( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ) {
	require_once ('db_info.php');
} else {
	require_once ('/usr/local/www/apache22/data/db_info.php');
}
$dbc = db_connect ($db_info['gis_streets']);
?>
<div id="search_properties">
	<!-- Search by Street Name -->
	<form action="" method="post">
		<p>
			<strong>Search by Street Name</strong><br />
			<input type="text" size="30" maxlength="250" name="street" />
			<input type="hidden" value="TRUE" name="submitted" />
			<input type="submit" value="Search" name="submit" />
		</p>
		<p>
			<a href="http://monsoon.ad.cityofprescott.org/?page_id=8460">Show all streets</a>
		</p>
	</form>
</div>
<?php
if (isset($_POST['submitted'])) {
	$sql = " WHERE NAME LIKE '%".$_POST['street']."%'";
}
	// Query the db
	$query = "SELECT * FROM streets".$sql." ORDER BY NAME ASC";
	$result = @mysql_query ($query);
	$num = mysql_num_rows ($result);
	// Set the row colors and start the row count at 0
	$even = 'even'; 
	$odd = 'odd'; 
	$row_count = 0;
	// If it ran okay, display the records
	if ($result) {
		// If there were no records found
		if (!$num) 	{
			echo '<p class="results">We\'re sorry, your search for <strong>' . $search_by . '</strong> returned no results. Please try again.</p>';
		} else {
			$plural = ($num == 1) ? 'result' : 'results';
			
			if (isset($_POST['submitted'])) {
				echo '<p class="results">Your search for <strong>' .$_POST['street']. '</strong> returned <strong>' .$num. '</strong> ' .$plural. '.</p>';
			}
?>	
<table id="property_results">
<thead>
<tr>
<th width="360" class="tleft">Street Name</th>
<th width="360">Type</th>
<th width="30">Map</th>
</tr>
</thead>
<tbody>
<?php
			// Loop through the records
			while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			
				$row_color = ($row_count % 2) ? $even : $odd;

				if ($row['TYPE'] == 'COP') {
					$type = 'Prescott';
				} elseif ($row['TYPE'] == 'YAV') {
					$type = 'Yavapai County';
				} elseif ($row['TYPE'] == 'PRV') {
					$type = 'Private';
				}

				$street_name = str_replace(' ', '%20', $row['NAME']);

echo '				
<tr class="' .$row_color. '" onclick="window.open(\'http://maps.google.com/maps?q=' .$street_name. ' Prescott, AZ\')">
<td>' .$row['NAME']. '</td>
<td class="tleft">' .$row['TYPE']. '</td>
<td class="tcenter"><span class="fake_link">Map</span></td>
</tr>';
				// Add 1 to every row
				$row_count++;
			} // End while loop
		} // End else
?>		
</tbody>
</table>
<?php
	mysql_free_result($result); // Free up the resources
	} else { // If it did not run okay
		echo '<p class="error">The records could not be displayed due to a system error. We apologize for any inconvenience.</p><p>'.mysql_error().'</p>';
	}
mysql_close(); // Close the db connection
?>	
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>