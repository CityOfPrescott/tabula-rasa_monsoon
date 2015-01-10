<?php
/**
 * Template Name: Fleet Request
 *
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<div class="hfeed content old_portal">
	<img src="/_i/icons/van.gif" class="h2title" />
	<h2>Fleet Vehicle Request</h2>
	<hr />
<?php
function create_form($errors, $error_msg) { ?>
<form action="" method="post">
<fieldset>
<legend>Your Information</legend>
<p><strong>Name <?php $error_msg['name'] ?></strong><br /><input type="text" name="name" size="35" value="<?php $_POST['name'] ?>" /></p>
<p><strong>Phone Number <?php $error_msg['phone'] ?></strong><br /><input type="text" name="phone" size="35" value="<?php $_POST['phone'] ?>" /></p>
<p><strong>Email Address <?php $error_msg['email'] ?></strong><br /><input type="text" name="email" size="35" value="<?php $_POST['email'] ?>" /><br /><span class="footnote">(e.g. firstname.lastname@prescott-az.gov)</span></p>
</fieldset>

<fieldset>
<legend>Your Request</legend>
<?php 
function create_start_date_dropdown() {
	// Make the months array
	$months = array ('1' => 'January (01)', '2' => 'February (02)', '3' => 'March (03)', '4' => 'April (04)', '5' => 'May (05)', '6' => 'June (06)', '7' => 'July (07)', '8' => 'August (08)', '9' => 'September (09)', '10' => 'October (10)', '11' => 'November (11)', '12' => 'December (12)');

	// Make the months pull-down menu
	echo "<select name=\"start_month\">\n<option value=\"\">Month</option>\n";
	foreach ($months as $key => $value) 	{
		echo "<option value=\"$key\"";
		if ($_POST['start_month'] == $key) {
			echo ' selected="selected"';
		}
		echo ">$value</option>\n";
	}
	echo "</select>";

	// Make the days pull-down menu
	echo "<select name=\"start_day\">\n<option value=\"\">Day</option>\n";
	for ($day = 1; $day <= 31; $day++) {
		echo "<option value=\"$day\"";
		if ($_POST['start_day'] == $day) {
			echo ' selected="selected"';
		}
		echo ">$day</option>\n";
	}
	echo "</select>";

	// Make the years pull-down menu
	echo "<select name=\"start_year\">\n<option value=\"\">Year</option>\n";
	for ($year = date('Y'); $year <= date('Y')+1; $year++) 	{
		echo "<option value=\"$year\"";
		if ($_POST['start_year'] == $year) {
			echo ' selected="selected"';
		}
		echo ">$year</option>\n";
	} 
	echo "</select>";
	
	return;
}

function create_return_date_dropdown() {
	// Make the months array
	$months = array ('1' => 'January (01)', '2' => 'February (02)', '3' => 'March (03)', '4' => 'April (04)', '5' => 'May (05)', '6' => 'June (06)', '7' => 'July (07)', '8' => 'August (08)', '9' => 'September (09)', '10' => 'October (10)', '11' => 'November (11)', '12' => 'December (12)');

	// Make the months pull-down menu
	echo "<select name=\"return_month\">\n<option value=\"\">Month</option>\n";
	foreach ($months as $key => $value) {
		echo "<option value=\"$key\"";
		if ($_POST['return_month'] == $key) {
			echo ' selected="selected"';
		}		
		echo ">$value</option>\n";
	}
	echo "</select>";

	// Make the days pull-down menu
	echo "<select name=\"return_day\">\n<option value=\"\">Day</option>\n";
	for ($day = 1; $day <= 31; $day++) {
		echo "<option value=\"$day\"";
		if ($_POST['return_day'] == $day) {
			echo ' selected="selected"';
		}
		echo ">$day</option>\n";
	}
	echo "</select>";

	// Make the years pull-down menu
	echo "<select name=\"return_year\">\n<option value=\"\">Year</option>\n";
	for ($year = date('Y'); $year <= date('Y')+1; $year++) {
		echo "<option value=\"$year\"";
		if ($_POST['return_year'] == $year) {
			echo ' selected="selected"';
		}
		echo ">$year</option>\n";
	} 
	echo "</select>";

	return;
}
?>
<p><strong>Vehicle <?php $error_msg['vehicle'] ?></strong><br />
<em>Note: #968 Chevy 15 passenger Van & #967 Chevy Handicap Van require the driver to watch a video and take a test at either Fleet or Risk management before using the Van</em><br /><br />
<select name="vehicle" id="vehicle">
<option value=""> Choose one...</option>
<option value="">&nbsp;</option>
<option value="1131 Chevy Astro Van">#1131 Chevy Astro Van- Located at City Hall</option>
<option value="1115 Ford Escape">#1115 Ford Escape- Located at City Hall</option>
<option value="968 Chevy 15 passenger Van">#968 Chevy 15 passenger Van- Located at Fleet</option>
<option value="967 Chevy Handicap Van">#967 Chevy Handicap Van- Located at Fleet</option>
<option value="982 Crown Vic ">#982 Crown Vic- Located at Fleet </option>
<option value="986 Crown Vic ">#986 Crown Vic- Located at Fleet </option>
</select>

<p><strong>Pickup Date <?php $error_msg['start_date'] ?></strong><br />
<?php create_start_date_dropdown();?>

<p><strong>Pick up Time <?php $error_msg['time'] ?></strong><br /><input type="text" name="time" size="35" value="<?php $_POST['time'] ?>" /></p>
<p><strong>Return Date -  <em>if different from Pickup Date </em><?php $error_msg['return_date'] ?></strong><br />
<?php create_return_date_dropdown(); ?>
<p><strong>Return Time <?php $error_msg['rtime'] ?></strong><br /><input type="text" name="rtime" size="35" value="<?php $_POST['rtime'] ?>" /></p>

<p><strong>Are you using the vehicle in-town or out-of-town?<br /><input type="radio" name="intown" value="In-Town" onClick="show_hide('current_yes','current_no')" checked="checked" /> In-Town &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="intown" value="Out-of-Town" onClick="show_hide('current_no','current_yes')" /> Out-of-Town</p>

<p><input type="checkbox" name="cancel" value="Cancel this Vehicle Request!" /> I am Canceling the Request for this vehicle on the dates indicated above</p>
<p><strong>Notes or additonal information regarding your request: </strong><br /><textarea name="description" cols="55" rows="12"><?php $_POST['description'] ?></textarea></p>


</fieldset>

<fieldset class="submit">
<p>
<input type="submit" name="submit" value="Submit Request" />
<input type="hidden" name="submitted" value="TRUE" />
</p>
</fieldset>
</form>
<?php
 return;
}
?>
<?php
// If submit was pressed
if (isset($_POST['submitted'])) {
	
	$name = secure_data($_POST['name']);
	$phone = secure_data($_POST['phone']);
	$email = secure_data($_POST['email']);
	
	$time = secure_data($_POST['time']);
	$rtime = secure_data($_POST['rtime']);
	$intown = secure_data($_POST['intown']);
	
	$start_year = $_POST['start_year'];
	$start_month = $_POST['start_month'];
	$start_day = $_POST['start_day'];	
	$start_date = $start_month."/".$start_day."/".$start_year;
	
	$return_year = $_POST['return_year'];
	$return_month = $_POST['return_month'];
	$return_day = $_POST['return_day'];	
	$return_date = $return_month."/".$return_day."/".$return_year;
	
	$vehicle = secure_data($_POST['vehicle']);
	$description = secure_data($_POST['description']);
	$cancel = secure_data($_POST['cancel']);
	
	// Send an email to the fleet department
	$to = 'webmaster@prescott-az.gov';
	$subject = 'Vehicle Request';
	$body = "\n\n".$cancel."\n\nDate Submitted: ".date('l, F d, Y')."\n\nSubmitted by: ".$name."(".$phone.", ".$email.")\n\nVehicle Requested:  ".$vehicle."\n\nPick up Date:  ".$start_date."\n\nPick up Time:  ".$time."\n\nReturn Date(if not same as pick up date):  ".$return_date."\n\nReturn Time:  ".$rtime."\n\nUse will be:  ".$intown."\n\nAdditional Info:  ".$description."
	\n";
	
	$headers = 'From: '.$email.'';
	mail ($to, $subject, $body, $headers);

	// Print a message to the user
	echo "<div id=\"success_box\">\n<h3>Request Sent</h3>\n<p>Your request has been sent. </p>\n<p>Would you like to <a href=\"/fleet/request.php\">submit another vehicle request</a>?</p>\n</div>\n";
	
} else { // end if isset(submit)
// If submit wasn't pressed
	echo '<p>Use the form below to request a fleet vehicle.  Please fill in all the information so we can process your request properly.  To cancel a request complete the form and check the canceling request box near the bottom of the form.</p>';
	echo create_form();	
}
?>
</div><!-- .content .hfeed -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>