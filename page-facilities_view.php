<?php
/**
 * Template Name: Facilities View
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
$dbc = db_connect ($db_info['facilities']);

// FOR HIGH PRIORITY
// Query the db
$query = "SELECT *, DATE_FORMAT(date_submitted, '%Y-%m-%d at %l:%i %p') AS submitted FROM work_order WHERE work_status='Open' AND work_priority='High' ORDER BY date_submitted ASC";
$result = @mysql_query ($query);
$num = mysql_num_rows($result);

// Set the row colors and start the row count at 0
$even = '"even1"'; 
$odd = '"odd1"'; 
$row_count = 0;

if ($result)
{
	if ($num >= 1)
	{

		echo "
<h4>High Priority</h4>

<table class=\"facilities\">
<thead>
<tr>
<th width=\"120\">Work Order #</th>
<th width=\"180\">Date Submitted</th>
<th width=\"150\">Submitted By</th>
<th width=\"210\">Type of Work</th>
<th width=\"90\">Status</th>
</tr>
</thead>
<tbody>\n";
	
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC))
		{
			$row_color = ($row_count % 2) ? $even : $odd;

			echo "
<tr class=".$row_color.">
<td class=\"center\">".$row['work_order_num']."</td>
<td class=\"center\">".$row['submitted']."</td>
<td class=\"center\">".$row['req_name']."</td>
<td class=\"center\">".$row['work_type']."</td>
<td class=\"center\">".$row['work_status']."</td>
</tr>\n";

			// Add 1 to every row
			$row_count++;

		}

		echo "</tbody>\n</table>";
	} // end if no records
}
else
{
	echo "<div class=\"error_box\"><h3>Error</h3><p>The records could not be displayed.</p></div>";
}



// FOR MEDIUM PRIORITY
// Query the db
$query2 = "SELECT *, DATE_FORMAT(date_submitted, '%Y-%m-%d at %l:%i %p') AS submitted FROM work_order WHERE work_status='Open' AND work_priority='Medium' ORDER BY date_submitted ASC";
$result2 = @mysql_query ($query2);
$num2 = mysql_num_rows($result2);

// Set the row colors and start the row count at 0
$even2 = '"even2"'; 
$odd2 = '"odd2"'; 
$row_count2 = 0;

if ($result2)
{
	if ($num2 >= 1)
	{

		echo "
<h4>Medium Priority</h4>

<table class=\"facilities\">
<thead>
<tr>
<th width=\"120\">Work Order #</th>
<th width=\"180\">Date Submitted</th>
<th width=\"150\">Submitted By</th>
<th width=\"210\">Type of Work</th>
<th width=\"90\">Status</th>
</tr>
</thead>
<tbody>\n";
	
		while ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC))
		{
			$row_color2 = ($row_count2 % 2) ? $even2 : $odd2;

			echo "
<tr class=".$row_color2.">
<td class=\"center\">".$row2['work_order_num']."</td>
<td class=\"center\">".$row2['submitted']."</td>
<td class=\"center\">".$row2['req_name']."</td>
<td class=\"center\">".$row2['work_type']."</td>
<td class=\"center\">".$row2['work_status']."</td>
</tr>\n";

			// Add 1 to every row
			$row_count2++;

		}

		echo "</tbody>\n</table>";
	} // end if no records
}
else
{
	echo "<div class=\"error_box\"><h3>Error</h3><p>The records could not be displayed.</p></div>";
}



// FOR LOW PRIORITY
// Query the db
$query3 = "SELECT *, DATE_FORMAT(date_submitted, '%Y-%m-%d at %l:%i %p') AS submitted FROM work_order WHERE work_status='Open' AND work_priority='Low' ORDER BY date_submitted ASC";
$result3 = @mysql_query ($query3);
$num3 = mysql_num_rows($result3);

// Set the row colors and start the row count at 0
$even3 = '"even3"'; 
$odd3 = '"odd3"'; 
$row_count3 = 0;

if ($result3)
{
	if ($num3 >= 1)
	{

		echo "
<h4>Low Priority</h4>

<table class=\"facilities\">
<thead>
<tr>
<th width=\"120\">Work Order #</th>
<th width=\"180\">Date Submitted</th>
<th width=\"150\">Submitted By</th>
<th width=\"210\">Type of Work</th>
<th width=\"90\">Status</th>
</tr>
</thead>
<tbody>\n";
	
		while ($row3 = mysql_fetch_array ($result3, MYSQL_ASSOC))
		{
			$row_color3 = ($row_count3 % 2) ? $even3 : $odd3;

			echo "
<tr class=".$row_color3.">
<td class=\"center\">".$row3['work_order_num']."</td>
<td class=\"center\">".$row3['submitted']."</td>
<td class=\"center\">".$row3['req_name']."</td>
<td class=\"center\">".$row3['work_type']."</td>
<td class=\"center\">".$row3['work_status']."</td>
</tr>\n";

			// Add 1 to every row
			$row_count3++;

		}

		echo "</tbody>\n</table>";
	} // end if no records
}
else
{
	echo "<div class=\"error_box\"><h3>Error</h3><p>The records could not be displayed.</p></div>";
}



// FOR PENDING
// Query the db
$query4 = "SELECT *, DATE_FORMAT(date_submitted, '%Y-%m-%d at %l:%i %p') AS submitted FROM work_order WHERE work_status='Pending' ORDER BY date_submitted ASC";
$result4 = @mysql_query ($query4);
$num4 = mysql_num_rows($result4);

// Set the row colors and start the row count at 0
$even4 = '"even4"'; 
$odd4 = '"odd4"'; 
$row_count4 = 0;

if ($result4)
{
	if ($num4 >= 1)
	{

		echo "
<h4>Pending</h4>

<table class=\"facilities\">
<thead>
<tr>
<th width=\"120\">Work Order #</th>
<th width=\"180\">Date Submitted</th>
<th width=\"150\">Submitted By</th>
<th width=\"210\">Type of Work</th>
<th width=\"90\">Status</th>
</tr>
</thead>
<tbody>\n";
	
		while ($row4 = mysql_fetch_array ($result4, MYSQL_ASSOC))
		{
			$row_color4 = ($row_count4 % 2) ? $even4 : $odd4;

			echo "
<tr class=".$row_color4.">
<td class=\"center\">".$row4['work_order_num']."</td>
<td class=\"center\">".$row4['submitted']."</td>
<td class=\"center\">".$row4['req_name']."</td>
<td class=\"center\">".$row4['work_type']."</td>
<td class=\"center\">".$row4['work_status']."</td>
</tr>\n";

			// Add 1 to every row
			$row_count4++;

		}

		echo "</tbody>\n</table>";
	} // end if no records
}
else
{
	echo "<div class=\"error_box\"><h3>Error</h3><p>The records could not be displayed.</p></div>";
}

?>
			
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>