<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$eid = escape_data($_GET['id']);
$resp = escape_data($_GET['r']);

if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0) { //test for owner
		
		if (mysql_result (mysql_query ("SELECT rsvp FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)=='') {
			$delete = mysql_query("DELETE FROM requests WHERE u_id='$id' AND type='invtevnt' AND ref_id='$eid'");	
		}
		
		if ($resp=='a') {
			$update = mysql_query("UPDATE event_owners SET rsvp='a' WHERE e_id='$eid' AND u_id='$id'");
		} elseif ($resp=='m') {
			$update = mysql_query("UPDATE event_owners SET rsvp='m' WHERE e_id='$eid' AND u_id='$id'");
		} elseif ($resp=='n') {
			$update = mysql_query("UPDATE event_owners SET rsvp='n' WHERE e_id='$eid' AND u_id='$id'");
		}
		if (mysql_affected_rows()>0) {
			echo '<div align="left" id="newrsvpstat">New RSVP saved!</div>';	
		}
		
		$eoinfo = mysql_fetch_array (mysql_query ("SELECT rsvp FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), MYSQL_ASSOC);
		echo '<select id="chngrsvp'.$eid.'" onchange="if(this.value!=\''.$eoinfo['rsvp'].'\'){$(\'savenewrsvpbtn\').set(\'styles\',{\'display\':\'block\'});}else{$(\'savenewrsvpbtn\').set(\'styles\',{\'display\':\'none\'});}">'; 
		$rsvps = array('a' => 'You are attending.', 'm' => 'You might attend.', 'n' => 'You aren\'t attending.');
		foreach ($rsvps as $rsvp => $rsvpname) { 
			if ($rsvp == $eoinfo['rsvp']) {
				echo '<option value="'.$rsvp.'" style="font-size: 13px;" SELECTED>'.$rsvpname.'</option>';
			} else {
				echo '<option value="'.$rsvp.'" style="font-size: 13px;">'.$rsvpname.'</option>';
			}
		} 
		echo '</select>';
		
} else { //if not tab owner
	echo '<div align="left" valign="top" style="padding: 6px;">
		You can\'t view this event.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>