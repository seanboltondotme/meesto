<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
}

$type = escape_data($_GET['type']);
$param = escape_data($_GET['param']);

if ($type=='all') {
	//to be made
	if (mysql_result (mysql_query("SELECT COUNT(*) FROM mc_vis WHERE u_id='$id' LIMIT 1"), 0)>0) {
		$result = mysql_query("DELETE FROM mc_vis WHERE u_id='$id'");
	} else {
		$strms = array('mb', 'frnd', 'fam', 'prof', 'edu', 'aqu');
		foreach ($strms as $strm) {
			$result = mysql_query("INSERT INTO mc_vis (u_id, type, sub_type, time_stamp) VALUES ('$id', 'strm', '$strm' , NOW())");
		}
	}
} else {
	if (mysql_result (mysql_query("SELECT COUNT(*) FROM mc_vis WHERE u_id='$id' AND type='strm' AND sub_type='$param' LIMIT 1"), 0)>0) {
		$result = mysql_query("DELETE FROM mc_vis WHERE u_id='$id' AND type='strm' AND sub_type='$param'");
	} else {
		$result = mysql_query("INSERT INTO mc_vis (u_id, type, sub_type, time_stamp) VALUES ('$id', 'strm', '$param' , NOW())");
	}	
}

include('grablist.php');

session_write_close();
exit();
?>