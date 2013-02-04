<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$uid = escape_data($_GET['id']);

//test if can invite or if admin
if (($uid==$id)||(mysql_result (mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$uid' AND p_id='$id' LIMIT 1"), 0)>0)) {

$peeple = @mysql_query ("SELECT p_id FROM my_peeple WHERE u_id='$uid'");
while ($person = @mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
	$uid = $person['p_id'];
	$name = returnpersonname($uid).' '.returncleanrealname($uid);
	if (mysql_result (mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0) {
		$response[] = array($uid, $name, 'm');
	} else {
		$response[] = array($uid, $name, 'p');
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