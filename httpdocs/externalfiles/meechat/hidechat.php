<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!$cid) {
	$cid = escape_data($_GET['cid']);
}

$update = mysql_query("UPDATE mc_open SET open=NULL WHERE u_id='$id' and c_id='$cid'");

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>