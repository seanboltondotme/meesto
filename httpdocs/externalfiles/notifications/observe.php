<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$nct = mysql_result(mysql_query("SELECT COUNT(*) FROM notifications WHERE u_id='$id' AND viewed IS NULL"), 0);
$rct = mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE u_id='$id'"), 0);
$result = $nct + $rct;

header('Content-type: application/json');
echo json_encode($result);

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>