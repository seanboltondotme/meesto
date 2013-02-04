<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$peeple = @mysql_query ("SELECT p_id FROM my_peeple mp WHERE u_id='$id'");
while ($person = @mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
	$uid = $person['p_id'];
	$name = returnpersonname($uid).' '.returncleanrealname($uid);
	$response[] = array($uid, $name);
}

header('Content-type: application/json');
echo json_encode($response);

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>