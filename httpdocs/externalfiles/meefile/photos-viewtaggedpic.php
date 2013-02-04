<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

echo '<div align="left" id="maincontent" style="margin-left: 80px;"></div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>