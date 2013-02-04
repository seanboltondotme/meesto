<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$cpid = escape_data($_GET['id']);

if ($id>0) {//test vis

$peeple = @mysql_query ("SELECT u_id, type FROM commproj_mem WHERE cp_id='$cpid'");
while ($person = @mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
	$uid = $person['u_id'];
	$name = returnpersonname($uid).' '.returncleanrealname($uid);
	if ($person['type']=='a') {
		$response[] = array($uid, $name, 'a');
	} else {
		$response[] = array($uid, $name, 's');	
	}
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