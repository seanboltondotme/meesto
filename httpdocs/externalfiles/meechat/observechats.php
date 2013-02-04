<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$mcos = @mysql_query ("SELECT DISTINCT c_id FROM mc_open WHERE u_id='$id' ORDER BY time_stamp ASC");
while ($mco = @mysql_fetch_assoc ($mcos)) {
	$openchats[] = array($mco ['c_id']);
}

header('Content-type: application/json');
echo json_encode($openchats);

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>