<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$cpid = escape_data($_GET['id']);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {//test for admin

$peeple = @mysql_query ("SELECT u_id FROM commproj_mem WHERE cp_id='$cpid' AND u_id!='$id'");
while ($person = @mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
	$uid = $person['u_id'];
	$name = returnpersonname($uid).' '.returncleanrealname($uid);
	$response[] = array($uid, $name);
}

} else { //if not able to view
	$response[] = array();	
}

header('Content-type: application/json');
echo json_encode($response);

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>