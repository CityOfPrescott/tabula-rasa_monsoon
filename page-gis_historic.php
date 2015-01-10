<?php
/**
 * Template Name: GIS - Historic Assessors Parcels
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
$dbc = db_connect ($db_info['gis_assessor']);
?>
<!-- Set up the iframe where the map will reside 
<p>Use the map below to select an area that you would like to find a map for. You may also use the search box below to search by book and page if you have that information avaialable to you. When you have made a selection on the map, the maps available for that area will be listed at the bottom of this page.</p>
<iframe class="map" src="http://coahuila/website/streets/viewer.htm?ActiveLayer=2&Layers=111111110&Query=STREET_NAM%3D%27smith%27&QueryZoom=Yes"></iframe>-->

<div id="search_properties">
	<!--  Search by Book -->
	<form action="" method="post" name="book">
		<p>
			<strong>Search by Book and Map Number</strong><br />
			<span class="footnote">This is the first 5 digits of the parcel you are trying to get historic plat information for (e.g. if the parcel you want to know about is 108-03-002 then enter 108-03 in the box below) and all Historic Assessors Maps for that area will be returned.</span><br />
			<input type="text" size="3" maxlength="3" name="book_num_1" onKeyup="autotab(this, document.book.book_num_2)" />-<input type="text" size="2" maxlength="2" name="book_num_2" />
			<input type="hidden" value="TRUE" name="book_submitted" />
			<input type="submit" value="Search" name="book_submit" />
		</p>
		<p><a href="http://monsoon.ad.cityofprescott.org/?page_id=8467">Show all parcels</a></p>
		<p>
			<a href="/_i/book_areas.jpg" target="_blank" /><img src="/_i/book_areas_thumb.jpg" class="book_areas" /></a> <a href="/_i/book_areas.jpg" target="_blank" />See map to determine location of the City of Prescott Book Regions</a>
		</p>
		<br class="clear" />
	</form>
</div>

<?php
if (isset($_POST['book_submitted'])) {
	$search_by = $_POST['book_num_1']."-".$_POST['book_num_2'];
	$sql = ' WHERE BOOK_MAP LIKE \'%'.$search_by.'%\' ORDER BY BOOK_MAP ASC';
}
// Query the db
$query = "SELECT * FROM BOOKMAP".$sql."";
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
		if (isset($_POST['book_submitted'])) 		{
			echo '<p class="results">Your search for <strong>' . $search_by . '</strong> returned <strong>' . $num . '</strong> ' . $plural . '.</p>';
		}
?>	

<a name="results"></a>
<table id="property_results">
<thead>
<tr>
<th width="360" class="tleft">Book and Map Number</th>
<th width="360">File</th>
<th width="30">Map</th>
</tr>
</thead>
<tbody>
<?php
			// Loop through the records
			while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			
				$row_color = ($row_count % 2) ? $even : $odd;
echo '
<tr class="' .$row_color. '" onclick="window.open(\'http://borax/gisscans/assessor/' .$row['FILE']. '\')">
<td>' .$row['BOOK_MAP']. '</td>
<td class="tcenter">' .$row['FILE'].  '</td>
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