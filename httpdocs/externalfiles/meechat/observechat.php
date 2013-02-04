<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$cid = escape_data($_GET['cid'][0]); //idk why this comes in as an array
$mcmid = escape_data($_GET['mcmid']);

$msg = @mysql_fetch_array (@mysql_query("SELECT mcm_id FROM mc_msgs WHERE (s_id='$cid' AND u_id='$id') OR (s_id='$id' AND u_id='$cid') ORDER BY mcm_id DESC LIMIT 1"), MYSQL_ASSOC);

if($msg['mcm_id'] > $mcmid){
	$newct = mysql_result (mysql_query("SELECT COUNT(*) FROM mc_msgs WHERE ((s_id='$cid' AND u_id='$id') OR (s_id='$id' AND u_id='$cid')) AND mcm_id>'$mcmid'"), 0);
	$result['action'] = 'new'.$newct;
}else{
	$result['action'] = 'none';
}

header('Content-type: application/json');
echo json_encode($result);

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>