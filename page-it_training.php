<?php
/**
 * Template Name: IT Training
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

<p>Sign-up for any training courses being offered below. If you are an administrator and you wish to view the current list of participants for a given course, use the <a href="/it/training/admin/">administrative interface</a> ( <img src="/_i/icons/login_sm.gif" /> <span class="login_text">login required</span> ).</p>
<?php
$today = date("Y-m-d");
// Connect to the database
if ( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ) {
	require_once ('db_info.php');
} else {
	require_once ('/usr/local/www/apache22/data/db_info.php');
}
$dbc = db_connect ($db_info['training']);

// Query the db
$query = "SELECT * FROM categories ORDER BY cat_id ASC";
$result = mysql_query($query);
$num = mysql_num_rows($result);

// Query the db
$query3 = "SELECT * FROM classes WHERE date >= NOW() - INTERVAL 1 DAY";
$result3 = mysql_query($query3);
$num3 = mysql_num_rows($result3);

if ($num3 == 0)
{
	echo "<h4>Training Sessions</h4>\n<p class=\"results\">There are currently no training sessions scheduled at this time. Please check back later.</p>";
}
else
{
	echo "<h4>Training Sessions</h4>";

	// Loop through the categories
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC))
	{
		// Only show recent classes
		// Query the db
		$query2 = "SELECT * FROM classes WHERE date >= NOW() - INTERVAL 1 DAY AND cat_id=".$row['cat_id']."";
		$result2 = @mysql_query ($query2);
		$num2 = mysql_num_rows ($result2);
		
		if ($num2 != 0)
		{
			echo '
<h5 class="training_title">'.$row['cat_title'].'</h5>';

			if (!empty($row['cat_desc']))
			{
				echo '<p>'.$row['cat_desc'].'</p>';
			}

			echo '
<table id="training">
<thead>
<tr>
<th width="150" class="tleft">Class Date</th>
<th width="167" class="tcenter">Time</th>
<th width="168" class="tcenter">Location</th>
<th width="145" class="tcenter">Seats Available</th>
<th width="120" class="tcenter">Actions</th>
</tr>
</thead>
<tbody>';

			// Query the db
			$query3 = "SELECT *, DATE_FORMAT(date, '%a, %b %D, %Y') AS class_date, TIME_FORMAT(start_time, '%l:%i %p') AS s_time, TIME_FORMAT(end_time, '%l:%i %p') AS e_time FROM classes WHERE date >= NOW() - INTERVAL 1 DAY AND cat_id=".$row['cat_id']." ORDER BY date ASC, start_time ASC";
			$result3 = @mysql_query ($query3);
			$num3 = mysql_num_rows ($result3);

			// If it ran okay, display the records
			if ($result3)
			{

				// Loop through the records
				while ($row3 = mysql_fetch_array ($result3, MYSQL_ASSOC))
				{
					// Set the row colors and start the row count at 0
					$even = '"even"'; 
					$odd = '"odd"';
			
					$row_color = ($row_count % 2) ? $even : $odd;

					$time = $row3['s_time']." to ".$row3['e_time'];
			
					echo "
<tr class=".$row_color.">
<td class=\"tleft\">".$row3['class_date']."</td>
<td class=\"tcenter\">".$time."</td>
<td class=\"tcenter\">".$row3['location']."</td>
<td class=\"tcenter\">";

					// Lets find out how many seats are still available
					// Query the db
					$query4 = "SELECT * FROM students WHERE class_id = ".$row3['class_id']."";
					$result4 = @mysql_query ($query4);
					$num4 = mysql_num_rows ($result4);

					$available = ($row3['seats'] - $num4);

					if ($available == 0)
					{
						echo "<span class=\"none\"><em>class full</em></span>";
					}
					else
					{
						echo $available." left";
					}

					echo "
</td>
<td class=\"tcenter\">";

					if ($available > 0)
					{
						echo "<a href=\"/it/training/sign_up.php?class=".$row3['class_id']."&cat=".$row3['cat_id']."\" onclick=\"return new_training_window(this.href);\">Sign up</a> ";
					}
					else
					{
						echo "<span class=\"none\">Sign-up</span> ";
					}

					echo "
| <a href=\"/it/training/delete.php?class=".$row3['class_id']."&cat=".$row3['cat_id']."\" onclick=\"return new_training_window(this.href);\">Cancel</a></td>
</tr>";

					// Add 1 to every row
					$row_count++;

				} // End while loop

			} // End if result3

			echo '
</tbody>
</table>';

		} // End if $num != 0

	} // End while (categories)

}
	
mysql_free_result($result); // Free up the resources

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