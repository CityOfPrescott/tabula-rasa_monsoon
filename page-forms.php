<?php
/**
 * Template Name: Forms
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
			<p>Below you will find various forms and documents that are usefull for City employees. If you want to add a document, use the <a href="/forms/admin/">administrative interface</a> ( <img src="/_i/icons/login_sm.gif" /> <span class="login_text">login required</span> ) or <a href="mailto:webmaster@prescott-az.gov">contact our Webmaster</a>.</p>
			<?php
			// Connect to the database
			if ( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ) {
				require_once ('db_info.php');
			} else {
				require_once ('/usr/local/www/apache22/data/db_info.php');
			}
			$dbc = db_connect ($db_info['documents']);

			// Set up the drop down menu
			// Query the db
			$query6 = "SELECT * FROM categories ORDER BY dept ASC";
			$result6 = @mysql_query ($query6);
			?>
			<div id="search_sort">
				<form action="" method="post">
					<p>View 
						<select name="doc_dept">
						<option value="">all</option>
						<?php
						while ($row6 = mysql_fetch_array ($result6, MYSQL_ASSOC)) {
							if (isset($_POST['submitted'])) {
								if (!empty($_POST['doc_dept']) AND $_POST['doc_dept'] == $row6['cat_id']) {
									$selected = ' selected="selected"';
								} else {
									$selected = '';
								}
							} else {
								$selected = '';
							}
							echo '<option value="' .$row6['cat_id']. '"' .$selected. '>' .$row6['dept']. '</option>';
						}
						?>
						</select>
						 documents.
						<input type="hidden" value="submitted" name="submitted" />
						<input type="submit" value="View" name="submit" />
					</p>
				</form>
				<form action="" method="post">
					<p>
						<strong>Search by Document Title</strong><br />
						<input type="text" size="30" name="search_var" />
						<input type="hidden" value="TRUE" name="search_submitted" />
						<input type="submit" value="Search" name="search_submit" />
					</p>
				</form>
			</div>
			<?php
			if (isset($_POST['submitted'])) {
				if (!empty($_POST['doc_dept'])) {
					$query = "SELECT * FROM categories WHERE cat_id=".$_POST['doc_dept']." ORDER BY listing ASC";
				} else {
					$query = "SELECT * FROM categories ORDER BY listing ASC";
				}
				$result = @mysql_query ($query);
			} elseif (isset($_POST['search_submitted'])) {
				$query = "SELECT *, DATE_FORMAT(upload_date, '%b %d, %Y') AS date, TIME_FORMAT(upload_date, '%l:%i %p') AS time, ROUND(file_size/1024, 1) AS size FROM uploads WHERE title LIKE '%".$_POST['search_var']."%'";
				$result = @mysql_query ($query);
			} else {
				// Query the db
				$query = "SELECT * FROM categories ORDER BY listing ASC";
				$result = @mysql_query ($query);
			}
			if (isset($_POST['search_submitted'])) {
				$num = mysql_num_rows ($result);
				
				if ($num != 0) {
					$plural = ($num == 1) ? 'result' : 'results';
					echo  '<p>Your search for <strong>"' .$_POST['search_var']. '"</strong> returned <strong>' .$num. '</strong> ' .$plural. '.</p>';
					?>	
					<table class="docs_table">
						<thead>
							<tr>
								<th width="80">Type</th>
								<th width="530" class="tleft">Name/Description</th>
								<th width="100">Size</th>
							</tr>
						</thead>
						<tbody>
						<?php	
						while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {

							// Query the db
							$query2 = "SELECT *, DATE_FORMAT(upload_date, '%b %d, %Y') AS date, TIME_FORMAT(upload_date, '%l:%i %p') AS time, ROUND(file_size/1024, 1) AS size FROM uploads WHERE file_id = '".$row['file_id']."'  ORDER BY title ASC";
							$result2 = @mysql_query ($query2);

							while ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) {

								// Set up the image variable conditional
								if ($row2['file_type'] == "text/rtf") {$image = '<img src="/_i/icons/rtf_icon.gif" alt="Download file" /><br /><span class="mime">RTF</span>';}
								elseif ($row2['file_type'] == "application/pdf") {$image = '<img src="/_i/icons/pdf_icon.gif" alt="Download file" /><br /><span class="mime">PDF</span>';}
								elseif ($row2['file_type'] == "application/msword") {$image = '<img src="/_i/icons/word_icon.gif" alt="Download file" /><br /><span class="mime">DOC</span>';}
								elseif ($row2['file_type'] == "application/vnd.ms-excel") {$image = '<img src="/_i/icons/excel_icon.gif" alt="Download file" /><br /><span class="mime">XLS</span>';}
								else {$image = '<img src="/_i/icons/unknown_icon.gif" alt="Download file" />';}
						
								// Set the row colors and start the row count at 0
								$even = 'even'; 
								$odd = 'odd'; 

								// Using a ternary operator to simplify the conditional
								$row_color = ($row_count % 2) ? $even : $odd;
							
								// Display each record.
								echo '
								<tr class="' .$row_color. '" onclick="window.location=\'/_d/' .$row2['file_name']. '\'">
									<td class="tcenter"><a href="/_d/' .$row2['file_name']. '" class="image">' .$image. '</a></td>
									<td><strong><a href="/_d/' .$row2['file_name']. '">' .$row2['title']. '</a></strong><br />' .$row2['description']. '</td>
									<td class="tcenter">' .$row2['size']. ' KB</td>
								</tr>';
								// Add 1 to every row
								$row_count++;
							} // End of 2nd while loop
						} // End while
					?>
						</tbody>
					</table>	
					<?php
				} else { // End if $num = 0
					echo '<p>Your search for <strong>"' .$_POST['search_var']. '"</strong> returned <strong>no results</strong>. Please try again.</p>';
				}
			} else {
				// Display the categories
				while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		
					if ($row['sub_category'] == 'Y') {
						echo '<a name="' .$row['anchor']. '"></a><h3 class="docs">' .$row['dept']. '</h3>';
						// Query the db
						$query9 = "SELECT * FROM sub_categories WHERE cat_id = ".$row['cat_id']."";
						$result9 = @mysql_query ($query9);
	if ( $row['dept'] == 'Airport' ) {
		echo '<h4><a href="http://www.prcairport.com/airport-administration/documents-and-forms/">View Public Airport Documents</a></h4>';
		//continue;
	}		
						while ($row9 = mysql_fetch_array ($result9, MYSQL_ASSOC)) {
							echo '<h4>' .$row9['sub_name']. '</h4>';
							?>
							<table class="docs_table">
								<thead>
									<tr>
										<th width="80">Type</th>
										<th width="530" class="tleft">Name/Description</th>
										<th width="100">Size</th>
									</tr>
								</thead>
								<tbody>
								<?php
								// Query the db
								$query2 = "SELECT *, DATE_FORMAT(upload_date, '%b %d, %Y') AS date, TIME_FORMAT(upload_date, '%l:%i %p') AS time, ROUND(file_size/1024, 1) AS size FROM uploads WHERE department = '".$row9['sub_label']."'  ORDER BY title ASC";
								$result2 = @mysql_query ($query2);

								while ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) {
									// Set up the image variable conditional
									if ($row2['file_type'] == "text/rtf") {$image = '<img src="/_i/icons/rtf_icon.gif" alt="Download file" /><br /><span class="mime">RTF</span>';}
									elseif ($row2['file_type'] == "application/pdf") {$image = '<img src="/_i/icons/pdf_icon.gif" alt="Download file" /><br /><span class="mime">PDF</span>';}
									elseif ($row2['file_type'] == "application/msword") {$image = '<img src="/_i/icons/word_icon.gif" alt="Download file" /><br /><span class="mime">DOC</span>';}
									elseif ($row2['file_type'] == "application/vnd.ms-excel") {$image = '<img src="/_i/icons/excel_icon.gif" alt="Download file" /><br /><span class="mime">XLS</span>';}
									else {$image = '<img src="/_i/icons/unknown_icon.gif" alt="Download file" />';}
								
									// Set the row colors and start the row count at 0
									$even = 'even'; 
									$odd = 'odd'; 

									// Using a ternary operator to simplify the conditional
									$row_color = ($row_count % 2) ? $even : $odd;
								
									// Display each record.
									echo '
									<tr class="' .$row_color. '" onclick="window.location=\'/_d/' .$row2['file_name']. '\'">
									<td class="tcenter"><a href="/_d/' .$row2['file_name']. '" class="image">' .$image. '</a></td>
									<td><strong><a href="/_d/' .$row2['file_name']. '">' .$row2['title']. '</a></strong><br />' .$row2['description']. '</td>
									<td class="tcenter">' .$row2['size']. ' KB</td>
									</tr>';
									// Add 1 to every row
									$row_count++;
								} // End of 2nd while loop
								?>	
								</tbody>
							</table>		
							<?php
						} // End of while loop			
					} else { // End of if $row['sub_category']
						echo '<a name="' .$row['anchor']. '"></a><h3 class="docs">' .$row['dept']. '</h3>';
						?>
						<table class="docs_table">
							<thead>
								<tr>
									<th width="80">Type</th>
									<th width="530" class="tleft">Name/Description</th>
									<th width="100">Size</th>
								</tr>
							</thead>
							<tbody>		
							<?php
							// Query the db
							$query2 = "SELECT *, DATE_FORMAT(upload_date, '%b %d, %Y') AS date, TIME_FORMAT(upload_date, '%l:%i %p') AS time, ROUND(file_size/1024, 1) AS size FROM uploads WHERE department LIKE '".$row['label']."%'  ORDER BY title ASC";
							$result2 = @mysql_query ($query2);

							while ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) {
								// Set up the image variable conditional
								if ($row2['file_type'] == "text/rtf") {$image = '<img src="/_i/icons/rtf_icon.gif" alt="Download file" /><br /><span class="mime">RTF</span>';}
								elseif ($row2['file_type'] == "application/pdf") {$image = '<img src="/_i/icons/pdf_icon.gif" alt="Download file" /><br /><span class="mime">PDF</span>';}
								elseif ($row2['file_type'] == "application/msword") {$image = '<img src="/_i/icons/word_icon.gif" alt="Download file" /><br /><span class="mime">DOC</span>';}
								elseif ($row2['file_type'] == "application/vnd.ms-excel") {$image = '<img src="/_i/icons/excel_icon.gif" alt="Download file" /><br /><span class="mime">XLS</span>';}
								else {$image = '<img src="/_i/icons/unknown_icon.gif" alt="Download file" />';}
							
								// Set the row colors and start the row count at 0
								$even = 'even'; 
								$odd = 'odd'; 

								// Using a ternary operator to simplify the conditional
								$row_color = ($row_count % 2) ? $even : $odd;
								
								// Display each record.
								echo '
								<tr class=' .$row_color. ' onclick="window.location=\'/_d/' .$row2['file_name']. '\'">
								<td class="tcenter"><a href="/_d/' .$row2['file_name']. '" class="image">' .$image. '</a></td>
								<td><strong><a href="/_d/' .$row2['file_name']. '">' .$row2['title']. '</a></strong><br />' .$row2['description']. '</td>
								<td class="tcenter">' .$row2['size']. ' KB</td>
								</tr>';

								// Add 1 to every row
								$row_count++;
							} // End of 2nd while loop
							?>	
							</tbody>
						</table>
					<?php
					} // End of else
				} // End of 1st while loop
			} // End else
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