<?php/** * Template Name: Facilities Plans */get_header(); ?>	<div id="primary" class="content-area">		<main id="main" class="site-main" role="main">	<div class="hfeed content old_portal">		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">			<img src="/_i/icons/<?php echo get_post_meta($post->ID, 'title_icon', true); ?>" class="h2title" />			<h2><?php the_title(); ?></h2>			<hr />			<div class="entry">				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>			</div><?php	// Connect to the databaseif ( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ) {
	require_once ('db_info.php');
} else {
	require_once ('/usr/local/www/apache22/data/db_info.php');
}$dbc = db_connect ($db_info['facility_plans']);// Some sorting and searching functionsfunction create_complex_dropdown($dbc) {	echo "<select name=\"complex\">\n<option value=\"\">Choose one...</option>\n<option value=\"\">&nbsp;</option>\n";	// Query the db	$query = "SELECT * FROM complex ORDER BY COMPLEX_NAME ASC";	$result = @mysql_query ($query);	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {		echo "<option value=\"".$row['COMPLEX_ID']."\"";		if ($_POST['complex'] == $row['COMPLEX_ID']) {			echo ' selected="selected"';		}		echo ">".$row['COMPLEX_NAME']."</option>\n";	}	echo "</select>";	return;}echo "<div id=\"search_sort\">\n";echo "<form action=\"\" method=\"post\">\n<p><strong>Sort by Complex</strong><br />";echo create_complex_dropdown($dbc);echo "<input type=\"hidden\" value=\"TRUE\" name=\"complex_submitted\" /><input type=\"submit\" value=\"Sort\" name=\"complex_submit\" /></p></form>\n\n";echo "<p>Show <a href=\"\">all plans</a>.</p>";echo "</div>\n\n";if (isset($_POST['complex_submitted'])) {	$query2 = "SELECT * FROM facility WHERE COMPLEX_ID = ".$_POST['complex']."";	$result2 = @mysql_query($query2);	$num2 = mysql_num_rows ($result2);	if ($num2 == 1) {		$row2 = mysql_fetch_array($result2, MYSQL_ASSOC);		$id = 'plans.BUILDING_ID = '.$row2['BUILDING_ID'].'';	} else {		while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {			$id_pre .= 'plans.BUILDING_ID = '.$row2['BUILDING_ID'].' OR ';		}		$id = substr($id_pre, 0, -4);	}	$query3 = "SELECT * FROM complex WHERE COMPLEX_ID = ".$_POST['complex']."";	$result3 = @mysql_query($query3);	$row3 = mysql_fetch_array($result3, MYSQL_ASSOC);	$num3 = mysql_num_rows ($result3);	$building = $row3['COMPLEX_NAME'].' complex';	$sql = ' WHERE '.$id.'';	// Query the db	$query4 = "SELECT * FROM plans LEFT JOIN facility USING (BUILDING_ID) LEFT JOIN complex USING (COMPLEX_ID)".$sql."";	$result4 = @mysql_query ($query4);	$num4 = mysql_num_rows ($result4);	if ($result4 AND $num4 > 0) 	{		echo "<p>Below are the available floorplans for the <strong>".$building."</strong>.</p>";		echo "<ul>";		while ($row4 = mysql_fetch_array ($result4, MYSQL_ASSOC)) 		{			echo "<li><a href=\"http://borax/gisscans/plans".$row4['FILE']."\">".$row4['PLAN_NAME']."</a></li>";		} // end while loop		echo "</ul>";	} else {// end if $result AND $num > 0		echo "<p>No floorplans are currently available for the <strong>".$building."</strong>.</p>";	}} else { // end if the forms were submitted	// Query the db	$query = "SELECT * FROM complex ORDER BY COMPLEX_NAME ASC";	$result = @mysql_query ($query);	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 	{		echo "<h4>".$row['COMPLEX_NAME']."</h4>\n";		// Run a query to return all the facilities with a COMPLEX_ID from above		$query2 = "SELECT * FROM facility WHERE COMPLEX_ID = ".$row['COMPLEX_ID']."";		$result2 = @mysql_query($query2);		$num2 = mysql_num_rows($result2);		echo "<ul>\n";		while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {			// Run a query to return all the plans associated with the BUILDING_ID			$query3 = "SELECT * FROM plans WHERE BUILDING_ID = ".$row2['BUILDING_ID']."";			$result3 = @mysql_query($query3);			$num3 = mysql_num_rows($result3);			while ($row3 = mysql_fetch_array($result3, MYSQL_ASSOC)) 			{				echo "<li><a href=\"http://borax/gisscans/plans".$row3['FILE']."\">".$row3['PLAN_NAME']."</a></li>\n";			}		}		echo "</ul>\n\n";	} // End while} // End elsemysql_close(); // Close the db connection?>		</div>		<?php endwhile; endif; ?>	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>	</div>		</main><!-- #main -->	</div><!-- #primary --><?php get_sidebar(); ?><?php get_footer(); ?>