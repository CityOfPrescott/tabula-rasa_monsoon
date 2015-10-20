<?php

define(LDAP_BASE, "CN=Users,DC=ad,DC=cityofprescott,DC=org" , "(uniquemember=user)");


// LDAP variables
$ldaphost = "ldap://boron.ad.cityofprescott.org";


// connect to ldap server
$ldapconn = ldap_connect($ldaphost)
	or die("Could not connect to LDAP server.");


if ($ldapconn) {
	// binding to ldap server
	$ldapbind = ldap_bind($ldapconn,"vendor@ad.cityofprescott.org","mega?11")
        or die("Couldn't bind to AD!");
}

// Form variables
$submitted = $_GET['submitted'];
$name = $_GET['employee'];
$department = $_GET['department'];


// If the form was submitted then get the variables
if($submitted) {
	if($name AND empty($department)) {
		$filter = '(&(displayName=*'.$name.'*)(objectClass=person)(|(title=Conference Room)(employeeNumber>=1)))';
		$searched_for = '<strong>'.$name.'</strong>';
	} elseif(empty($name) AND $department) {
		$filter = 'department='.$department.'';
		$searched_for = '<strong>'.stripslashes($department).'</strong>';
	} else {
		$filter = '';		
	}
} elseif($_GET['list'] == 'full') {
	$filter = '(&(objectClass=*)(employeeNumber=*)(name=*))';
} else {
	$filter = '';

	

}

// verify binding
if ($ldapbind) {
	// set up some variables
	$basedn = 'CN=Users,DC=ad,DC=cityofprescott,DC=org';
	$attributes = array('displayname','givenName','sn','mail','department','telephonenumber','facsimiletelephonenumber','employeenumber','streetaddress','title');

	// Search the directory
    $ldapsearch = ldap_search($ldapconn, $basedn, $filter, $attributes);
	ldap_sort($ldapconn ,$ldapsearch,"sn");
	$row_count = 0;


	
	if ($ldapsearch) {

		if ($submitted) {
			$num_results = ldap_count_entries($ldapconn,$ldapsearch);
			$result_lang = ($num_results > 1 OR $num_results == 0) ? 'results' : 'result';
			echo "<p class=\"results\">Your search for " .$searched_for. "  returned <strong>".$num_results."</strong> ".$result_lang.".</p>";
		}
		
		$info = ldap_get_entries($ldapconn, $ldapsearch);
		
		echo "<table>
<thead>
<tr>
<th width=\"66\" class=\"tleft\">Name</th>
<th width=\"374\">&nbsp;</th>
<!--<th width=\"274\">&nbsp;</th>
<th width=\"200\">Department</th>-->
<th width=\"350\">Address</th>
<th width=\"150\">Phone Number</th>
</tr>
</thead>
<tbody>\n";

		unset($info['count']);
		
		foreach($info as $item) {
			$row_color = ($row_count % 2) ? '' : ' class="odd"';
			
			// set up the variables you get from ldap
			$name = (empty($item["givenname"][0]) AND empty($item["sn"][0])) ? '' : $item["givenname"][0].' '.$item["sn"][0];
	        $email = (empty($item["mail"][0])) ? '' : "<br /><a href=\"mailto:".$item["mail"][0]."\" title=\"".$item["mail"][0]."\">".$item["mail"][0]."</a>";
			$department = (empty($item["department"][0])) ? '' : "<br />".stripslashes($item["department"][0]);
			$title = (empty($item["title"][0])) ? '' : ", <em>".$item["title"][0]."</em>";
			$address = (empty($item["streetaddress"][0])) ? '' : $item["streetaddress"][0];
			$phone = (empty($item["telephonenumber"][0])) ? '' : $item["telephonenumber"][0];
			$number = (empty($item["employeenumber"][0])) ? '' : $item["employeenumber"][0];
			
			//$onlynums = preg_replace('/[^0-9]/','',$phone);
			$onlynums = preg_replace('[\D]','',$phone);
				
			if (strlen($onlynums) == 7) {
				$exch = substr($onlynums,0,3);
				$num = substr($onlynums,3,4);
				$newphone = $exch."-".$num;
			} elseif(strlen($onlynums) == 10) {
				$areacode = substr($onlynums, 0,3);
				$exch = substr($onlynums,3,3);
				$num = substr($onlynums,6,4);
				$newphone = $areacode."-".$exch."-".$num;
			} elseif(strlen($onlynums) == 4) {
				$exch = substr($onlynums,0,4);
				$newphone = '<span class="ext">x</span>'.$exch;
			} else {
				$newphone = $phone;
			}
			
			// check to see if an image exists for the user
			$image_path = '/usr/local/www/apache22/data/monsoon/_i/employee-photo/';
			
			if (file_exists($image_path.''.$number.'.jpg')) {
				$photo = '<img src="/_i/employee-photo/'.$number.'.jpg" height="58" width="50" alt="" title="" />';
			} elseif($number == 'department') {
				$photo = '<img src="/_i/department.jpg" alt="Department" title="Department" />';
			} elseif  ($item["department"][0] == 'IT ' ) { 
				$photo = '<img src="/_i/helpdesk.png" alt="Help Desk" title="Help Desk" />'; 
			} else {
				$photo = '<img src="'.$number . '.jpg" alt="No photo" title="No photo" />';
				$no_photo_array[] = $name . ' - ' . $number;
			}
			if ( $email == '<br /><a href="mailto:help@prescott-az.gov" title="help@prescott-az.gov">help@prescott-az.gov</a>' ) {
				$photo = '<img src="/_i/helpdesk.png" alt="Help Desk" title="Help Desk" />';
			}
			
//remove emp. num once done testing			
			echo "<tr".$row_color.">\n";
			echo "<td>".$photo."</td>\n";
			echo "<td><strong>".$name."</strong><span class=\"more\">".$email."".$department." ".$title."</span></td>\n";
			//echo "<td class=\"tcenter\">".$department."</td>\n";
			echo "<td class=\"tcenter\">".$address."</td>\n";
			echo "<td class=\"tcenter phone\">".$newphone."</td>\n";
			echo "</tr>\n";
			
			$title = str_replace(', ', '', $title);
			$full_lists[] = $number . '|' . $name . '|' . $item["department"][0]. '|' .$title;
			$row_count++;
	    }
		echo "</tbody>\n</table>\n";
	
	//print_r( $no_photo_array );
	/*
	foreach ( $no_photo_array as $missing ) {
		echo $missing . '<br />';
	}
	*/
	
	/*
	foreach ( $full_lists as $list_item ) {
		echo $list_item . '<br />';
	}
	*/
	
	//build department list
/*$depts = array();
		foreach($info as $item) {
		array_push($depts, $item["department"][0]);
		}
	$deptlist = array_unique($depts);
	sort($deptlist);
	print_r($deptlist);*/
	}
}

?>
