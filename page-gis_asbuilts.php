<?php
/**
 * Template Name: GIS - Property Information
 *
 */
get_header(); 
?>
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
		/*
		$whitelist = array(
				'127.0.0.1',
				'::1'
		);
		*/
		

		if ( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ) {
			require_once ('db_info.php');
		} else {
			require_once ('/usr/local/www/apache22/data/db_info.php');
		}
		$dbc = db_connect ($db_info['gis_asbuilts']);

//Set up form variables
		if (isset($_POST['project_name_submitted'])) {
			$search_by = $_POST['pname'];
			$name = ' WHERE name_short LIKE \'%'.$search_by.'%\'';
		}

		if (isset($_POST['street']) && !empty($_POST['street'])) { $street = ' AND street LIKE \'%'. $_POST['street'].'%\''; }
		if (isset($_POST['number']) && !empty($_POST['number'])) { $number = ' AND  ( num_new LIKE \'%'. $_POST['number'].'%\' OR num_old LIKE \'%'. $_POST['number'].'%\' )'; }
		
		$filter = '';
		if (isset($_POST['paving'])) { $filter .= ' AND paving = "Y"'; $paving_checked = ' checked'; }		
		if (isset($_POST['drainage'])) { $filter .= ' AND drainage = "Y"'; $drainage_checked = ' checked'; }		
		if (isset($_POST['water'])) { $filter .= ' AND water = "Y"'; $water_checked = ' checked'; }		
		if (isset($_POST['sewer'])) { $filter .= ' AND sewer = "Y"'; $sewer_checked = ' checked'; }			
		if (isset($_POST['traffic'])) { $filter .= ' AND traffic = "Y"'; $traffic_checked = ' checked'; }			
		if (isset($_POST['site'])) { $filter .= ' AND site = "Y"'; $site_checked = ' checked'; }			
		
		?>
		<div id="search_properties">
			<!-- Search by Subdivision Name -->
			<form action="" method="post" name="project_name">
				<div style="width:49%; float: left;">
				<p>
					<strong>Project Name</strong><br />
					<input type="text" size="35" maxlength="100" name="pname" value="<?php echo $_POST['pname']; ?>"/>
				</p>
				<p>
					<strong>Street Name</strong><br />
					<input type="text" size="35" maxlength="100" name="street" value="<?php echo $_POST['street']; ?>"/>
				</p>
				<p>
					<strong>Project Number (New or Old)</strong><br />
					<input type="text" size="35" maxlength="100" name="number" value="<?php echo $_POST['number']; ?>"/>
				</p>		
				</div>
				<p style="margin-top: 20px; background: #eee; width: 49%; display: inline-block;padding: 5px;border: 1px solid black;">CN files are still being loaded daily, if plan is not available, it will be coming shortly!<br />Enter any portion of the name or number you know into the search boxes.<br />All results that contain any portion of what you enter will be returned.<br />Limit results by checking plan type check boxes.</p>				
				<p style="clear:both">
					<strong>Plans that include:</strong><br />
					<input type="checkbox" name="paving" value="paving"<?php echo $paving_checked; ?>>Paving
					<input type="checkbox" name="drainage" value="drainage"<?php echo $drainage_checked; ?>>Drainage
					<input type="checkbox" name="water" value="water"<?php echo $water_checked; ?>>Water
					<input type="checkbox" name="sewer" value="sewer"<?php echo $sewer_checked; ?>>Sewer
					<input type="checkbox" name="traffic" value="traffic"<?php echo $traffic_checked; ?>>Traffic
					<input type="checkbox" name="site" value="site"<?php echo $site_checked; ?>>Building/Site<br />
					<input type="hidden" value="TRUE" name="project_name_submitted" />	
					<input type="submit" value="Search" name="project_name_submit" />	
				</p>
	</form>
	</div>
		<?php
		if (isset($_POST['project_name_submitted'])) {
			// Query the db
			$query = 'SELECT * FROM asbuiltsCdoc' . $name . '' .  $street . '' . $number . '' . $filter . '' . ' ORDER BY num_new ASC';
			$result = @mysql_query ($query);
			$num = mysql_num_rows ($result);

			// If it ran okay, display the records
			if ($result) {
				// If there were no records found
				if (!$num) 	{
					echo '<p class="results">We\'re sorry, your search for <strong>' . $search_by . '</strong> returned no results. Please try again.</p>';
				} else {
					$plural = ($num == 1) ? 'result' : 'results';
					echo '<div class="results"><p>Your search for <strong>' . $search_by . '</strong> returned <strong>' . $num . '</strong> ' . $plural . '.</p><p>Click on listing for map</p></div>';

			// Loop through the records
			while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		?>
		<div class="asbuilt_results" onclick="window.open('http://borax/gisscans/as-builts_new/<?php echo $row['num_new']; ?>.pdf')">
			<div class="numbers">
				<ul>
					<li><label><?php echo $row['num_new']; ?></label></li>
					<li><?php echo $row['num_old']; ?></li>
				</ul>	
			</div>
			<div class="name_street">
				<ul>
					<li><label><?php echo $row['name_short']; ?></label></li>
					<li><em>Street:  </em><?php echo $row['street']; ?></li>
				</ul>
			</div>
			<div class="options">
				<p><em>Plans include:&nbsp;</em></p>
				<ul>
					<?php 
						if ( $row['paving'] == "Y") { echo '<li>Paving</li>'; }
						if ( $row['drainage'] == "Y") { echo '<li>Drainage</li>'; }
						if ( $row['water'] == "Y") { echo '<li>Water</li>'; }
						if ( $row['sewer'] == "Y") { echo '<li>Sewer</li>'; }
						if ( $row['traffic'] == "Y") { echo '<li>Traffic</li>'; }
						if ( $row['site'] == "Y") { echo '<li>Building/Site</li>'; }
					?>
				</ul>	
			</div>
		</div>
<?php
			} // End while loop
		} // End else

	mysql_free_result($result); // Free up the resources
	} else { // If it did not run okay
		echo '<p class="error">The records could not be displayed due to a system error. We apologize for any inconvenience.</p><p>'.mysql_error().'</p>';
	}
} else { // End if isset
	echo '<p class="tcenter" style="clear:both;">Please search for property information using the form above.</p>';
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