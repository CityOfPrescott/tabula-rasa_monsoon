<?php
/**
 * Template Name: Facilities - Request
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

	$date = date('Y\-m\-d H:i:s');
	//echo $date;
	
// Connect to the database
if ( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ) {
	require_once ('db_info.php');
} else {
	require_once ('/usr/local/www/apache22/data/db_info.php');
}
$dbc = db_connect ($db_info['central']);

function create_buildings_dropdown($select_name_buildings, $dbc)
{
	// Query the db
	$query = "SELECT * FROM buildings WHERE facilities_display = 'Y' ORDER by bldg_name ASC";
	$result = @mysql_query ($query);

	echo "\n";

	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC))
	{
		echo "<option value=\"".$row['bldg_id']."\"";

		if ($_POST[''.$select_name_buildings.''] == $row['bldg_id'])
		{
			echo ' selected="selected"';
		}

		echo ">".$row['bldg_name']."</option>";
	}
}

function create_departments_dropdown($select_name_departments, $dbc)
{
	// Query the db
	$query = "SELECT * FROM departments ORDER by dept_id ASC";
	$result = @mysql_query ($query);

	echo "\n";

	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC))
	{
		echo "<option value=\"".$row['dept_id']."\"";

		if ($_POST[''.$select_name_departments.''] == $row['dept_id'])
		{
			echo ' selected="selected"';
		}

		echo ">".$row['dept_name']."</option>";
	}
}
	

function create_request_form($errors, $error_msg)
{
	echo '
<form action="" method="post">
<fieldset>
<legend>Requestor Information</legend>
<p><strong>Your Name <span class="asterisk">&#42;</span>'.$error_msg['name'].'</strong><br /><input type="text" name="name" id="name" size="35" maxlength="150" value="'.$_POST['name'].'" /></p>
<p><strong>Your Phone Extension <span class="asterisk">&#42;</span>'.$error_msg['phone'].'</strong><br /><input type="text" name="phone" id="phone" size="4" maxlength="4" value="'.$_POST['phone'].'" /></p>
<p><strong>Your Email Address <span class="asterisk">&#42;</span>'.$error_msg['email'].'</strong><br /><input type="text" name="email" id="email" size="35" maxlength="150" value="'.$_POST['email'].'" /></p>
<p><strong>Your Department <span class="asterisk">&#42;</span>'.$error_msg['department'].'</strong><br />
<select name="department" id="department">
<option value="">Choose one...</option>
<option value="">&nbsp;</option>';

	$select_name_departments = 'department';
	echo create_departments_dropdown($select_name_buildings, $dbc);

	echo '
</select>
</p>
</fieldset>

<fieldset>
<legend>Work Order Informations</legend>
<p><strong>Date of Request</strong><br />'.date('F d, Y').' at '.date('g:i a').'</p>
<p><strong>Location of Work <span class="asterisk">&#42;</span>'.$error_msg['location'].'</strong><br />
<select name="location" id="location">
<option value="">Choose one...</option>
<option value="">&nbsp;</option>';

	$select_name_buildings = 'location';
	echo create_buildings_dropdown($select_name_buildings, $dbc);

	echo '
</select>
</p>
<p><strong>Importance</strong><br /><input type="radio" name="importance" id="importance" value="Routine"'; if (!isset($_POST['submitted'])) { echo ' checked="checked"';} elseif ($_POST['importance'] == 'Routine') { echo ' checked="checked"';} echo ' /> Routine &nbsp;&nbsp;&nbsp; <input type="radio" name="importance" id="importance" value="Emergency"'; if ($_POST['importance'] == 'Emergency') { echo ' checked="checked"';} echo ' /> Emergency</p>
<p><strong>Type of Work <span class="asterisk">&#42;</span>'.$error_msg['type'].'</strong></p>
<p class="col_1">
<input type="checkbox" name="electrical" id="electrical" value="Electrical/Lighting"'; if($_POST['electrical']) { echo ' checked="checked"';} echo ' /> Electrical/Lighting<br />
<input type="checkbox" name="plumbing" id="plumbing" value="Plumbing"'; if($_POST['plumbing']) { echo ' checked="checked"';} echo ' /> Plumbing<br />
<input type="checkbox" name="temp" id="temp" value="Heating/Cooling"'; if($_POST['temp']) { echo ' checked="checked"';} echo ' /> Heating/Cooling<br />
</p>
<p class="col_2">
<input type="checkbox" name="painting" id="painting" value="Painting"'; if($_POST['painting']) { echo ' checked="checked"';} echo '/> Painting/Wall Repair<br />
<input type="checkbox" name="maintenance" id="maintenance" value="Maintenance"'; if($_POST['maintenance']) { echo ' checked="checked"';} echo ' /> Trouble Report Maintenance<br />
</p>
<br />
<p class="clear"><strong>Description of Work</strong><br /><textarea name="description" id="description" rows="12" cols="52">'.$_POST['description'].'</textarea></p>
</fieldset>

<fieldset class="submit">
<p class="sumbit"><input type="submit" name="submit" value="Submit Request" /></p>
<input type="hidden" name="submitted" value="TRUE" />
</fieldset>
</form>';
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