<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$result = mysql_result(mysql_query ("SELECT COUNT(DISTINCT mp.p_id) FROM my_peeple mp INNER JOIN peep_streams ps ON (ps.u_id='$id' AND ps.p_id=mp.p_id) INNER JOIN sessions s ON (mp.p_id=s.u_id) AND s.client='pc' AND (s.last_accessed>SUBDATE(NOW(), INTERVAL 8 SECOND)) INNER JOIN mc_vis mcv ON mcv.u_id=mp.p_id AND mcv.type='strm' AND mcv.sub_type=ps.stream INNER JOIN mc_vis mcv2 ON mcv2.u_id='$id' AND mcv2.type='strm' AND mcv2.sub_type=mcv.sub_type WHERE mp.u_id='$id'"), 0);

header('Content-type: application/json');
echo json_encode($result);

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>