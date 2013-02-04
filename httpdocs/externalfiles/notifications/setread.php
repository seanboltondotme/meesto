<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$snid = escape_data($_GET['snid']);

if ($snid>0) {
	$update = mysql_query("UPDATE notifications SET viewed='y' WHERE u_id='$id' AND n_id<='$snid'");
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>