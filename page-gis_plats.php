<?php
/**
 * Template Name: GIS - Map Plats
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
$dbc = db_connect ($db_info['gis_plats']);

if (isset($_POST['map_plats_submitted'] ) ) {
	$search_by = $_POST['sub_name'];
	$subdivision = ' WHERE SUBNAME LIKE \'%'.$search_by.'%\'';
}

if (isset($_POST['plat_num'])) {
	$search_by = $_POST['plat_num'];
	//$search_by = "MP".$_POST['plat_num_1']."-".$_POST['plat_num_2'];
	$plat_num = ' AND GISID LIKE \'%'.$search_by.'%\'';
}
if (isset($_POST['rec_num'])) {
	$search_by = $_POST['rec_num'];
	$rec_num = ' AND RECORD_NUM LIKE \'%'.$search_by.'%\'';
}
/*
if (isset($_GET['name'])) {
	$search_by = $_GET['name'];
	$sql = ' WHERE SUBNAME LIKE \'%'.$search_by.'%\' ORDER BY GISID ASC';
}
*/
?>
<div id="search_properties">
<!-- Search by Parcel # -->
	<form action="" method="post" name="subdivision" style="width:49%; float: left;">
		<p>
			<strong>Search by Subdivision Name</strong><br />
			<input type="text" size="35" maxlength="100" name="sub_name" value="<?php echo $_POST['sub_name']; ?>"/>
		</p>
	<!-- Search by Map Plat -->
		<p>
			<strong>Search by Map Plat Number</strong><br />
			<!--
			MP<input type="text" size="2" maxlength="2" name="plat_num_1" onKeyup="autotab(this, document.plat.plat_num_2)" />-
			<input type="text" size="3" maxlength="3" name="plat_num_2" id="plat_num_2" />
			-->
			<input type="text" size="35" maxlength="100" name="plat_num" value="<?php echo $_POST['plat_num']; ?>"/>
		</p>
		<p>
			<strong>Search by Record Number</strong><br />
			<input type="text" size="35" maxlength="100" name="rec_num" value="<?php echo $_POST['rec_num']; ?>"/>
		</p>		
					<input type="hidden" value="TRUE" name="map_plats_submitted" />	
					<input type="submit" value="Search" name="map_plats_submit" />			
	</form>
	<p style="margin-top: 20px; background: #eee; width: 49%; display: inline-block;padding: 5px;border: 1px solid black;">Enter any portion of the name or number you know into the search boxes.<br />
All results that contain any portion of what you enter will be returned.<br />
Limit results by putting search criteria in more than one box.</p>	
</div>
<?php
if (isset($_POST['map_plats_submitted'] ) ) {
	
	// Query the db
	$query = 'SELECT * FROM plats' . $subdivision . '' . $rec_num . '' . $plat_num . '' . ' ORDER BY GISID ASC';
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
			echo '<p><p class="results">Your search for <strong>' . $search_by . '</strong> returned <strong>' . $num . '</strong> ' . $plural . '.</p>';
?>	
<table id="property_results">
<thead>
<tr>
<th width="360" class="tleft">Subdivision Name</th>
<th width="360" class="tcenter">Plat Number</th>
<th width="360" class="tcenter">Record Number</th>
<th width="30" class="tcenter">PDF</th>
</tr>
</thead>
<tbody>
<?php
			// Loop through the records
			while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			
				$row_color = ($row_count % 2) ? $even : $odd;
echo '
<tr class="' . $row_color . '" onclick="window.open(\'http://borax/gisscans/mapplats/' . $row['RECORD_NUM'] . '.pdf\')">
<td class="tleft">' . $row['SUBNAME'] . '</td>
<td class="tcenter">' . $row['GISID'] . '</td>
<td class="tcenter">' . $row['RECORD_NUM'] . '</td>
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
} else { // End if isset
	echo '<p class="tcenter" style="clear: both;">Please search for property information using the form above.</p>';
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