<?php
/**
 * Template Name: HR - Job POstings
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
$dbc = db_connect ($db_info['employment']);

// Set the row colors and start the row count at 0
$even = '"even"'; 
$odd = '"odd"'; 
$row_count = 0;

// Query the db
$query = "SELECT *, DATE_FORMAT(deadline, '%b %d, %Y') AS date, FORMAT(pay_start, 2) AS start, FORMAT(pay_end, 2) AS end FROM postings WHERE display_live = 'N' AND (deadline >= CURDATE() OR deadline = '0000-00-00') ORDER BY date_posted";
$result = @mysql_query ($query);
$num = mysql_num_rows ($result);

// If it ran okay, display the records
if ($result)
{
	if ($num == 0)
	{
		echo "<p class=\"results\">There are currently no internal job postings at this time. Please check back later. External job postings can be viewed on the <a href=\"http://www.prescott-az.gov/services/employment/\" target=\"_blank\">City web site</a>.</p>";
	}
	else
	{

		echo '
<table summary="A list of the current job openings and their application deadlines." id="jobs">
<thead>
<tr>
<th width="355" colspan="2" class="tleft">Position/<br />Application Packet</th>
<th width="220" class="tcenter">Rate/Salary<br />Range</th>
<th width="175" class="tcenter">Application<br />Deadline</th>
</tr>
</thead>
<tfoot>
<tr>
<td colspan="4">EEO/M/F/V/F/D NSE</td>
</tr>
</tfoot>
<tbody>';

		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) // Loop through the records
		{
			// Using a ternary operator to simplify the conditional
			$row_color = ($row_count % 2) ? $even : $odd;

			//$pay = ($row['start'] == $row['end']) ? $row['start'] : $row['start'].' - $'.$row['end'];
		
			if ($row['start'] == $row['end'])
			{
				$pay = $row['start'];
			}
			elseif ($row['end'] == '0.00')
			{
				$pay = $row['start'];
			}
			else
			{
				$pay = $row['start'].' - $'.$row['end'];
			}
		
			$pay_type = ($row['pay_type'] == 'A') ? 'Annually' : 'Hourly';
		
			echo "
<tr class=".$row_color." onclick=\"window.location='http://www.prescott-az.gov/_d/applications/app_packet_".$row['post_id'].".pdf'\">
<td><a href=\"http://www.prescott-az.gov/_d/applications/app_packet_".$row['post_id'].".pdf\"><img src=\"/_i/icons/pdf_icon_sm.gif\" class=\"employment_dl\" alt=\"Click to download\" title=\"Click to download\" /></a></td>
<td><a href=\"http://www.prescott-az.gov/_d/applications/app_packet_".$row['post_id'].".pdf\"><strong>".$row['title']."</strong></a></td>
<td class=\"tcenter\">$".$pay."<br />(".$pay_type.")</td>
<td class=\"tcenter\">".$deadline = ($row['date'] == '') ? '<em>Open until filled</em>' : $row['date']."</td>
</tr>";

			// Add 1 to every row
			$row_count++;
		}

		echo '</tbody>
</table>

<p>External job postings can be viewed on the <a href="http://www.prescott-az.gov/services/employment/" target="_blank">City web site</a>.</p>';

	} // End else $num == 0

	mysql_free_result($result); // Free up the resources
}
// If it did not run okay
else
{
	echo '<p class="error">The records could not be displayed due to a system error. We apologize for any inconvenience.</p><p class="error">'.mysql_error().'</p>';
}

mysql_close(); // Close the db connection

?></div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>