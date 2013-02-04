<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
}

if (!$cid) {
	$cid = escape_data($_GET['cid']);
}

$fmv = mysql_result(mysql_query("SELECT fmv FROM mc_open WHERE u_id='$id' and c_id='$cid' LIMIT 1"), 0);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM mc_open WHERE u_id='$id' and c_id='$cid' AND open IS NULL LIMIT 1"), 0)>0) {
	$lmv = mysql_result(mysql_query("SELECT lmv FROM mc_open WHERE u_id='$id' and c_id='$cid' LIMIT 1"), 0);
	if ($fmv==0) {
		$msgsrev = mysql_query("SELECT m.mcm_id, m.s_id, m.body, DATE_FORMAT(m.time_stamp, '%b %D (%l:%i%p)') AS time, u.defaultimg_url FROM mc_msgs m INNER JOIN users u ON u.user_id=m.s_id WHERE ((m.s_id='$cid' AND m.u_id='$id') OR (m.s_id='$id' AND m.u_id='$cid')) AND m.mcm_id>='$fmv' AND mcm_id<='$lmv' ORDER BY m.mcm_id DESC LIMIT 5");
		//test and try to set fmv
			$new_fmv = mysql_result(mysql_query("SELECT mcm_id FROM mc_msgs WHERE (u_id='$id' and s_id='$cid') OR (u_id='$cid' and s_id='$id') ORDER BY mcm_id DESC LIMIT 6, 1"), 0);
			if ($new_fmv!=0) {
				$update = mysql_query("UPDATE mc_open SET fmv='$new_fmv' WHERE u_id='$id' and c_id='$cid'");
			}
	} else {
		$msgsrev = mysql_query("SELECT m.mcm_id, m.s_id, m.body, DATE_FORMAT(m.time_stamp, '%b %D (%l:%i%p)') AS time, u.defaultimg_url FROM mc_msgs m INNER JOIN users u ON u.user_id=m.s_id WHERE ((m.s_id='$cid' AND m.u_id='$id') OR (m.s_id='$id' AND m.u_id='$cid')) AND m.mcm_id>='$fmv' AND mcm_id<='$lmv' ORDER BY m.mcm_id DESC");
	}
	
} else {
	if ($fmv==0) {
		$msgsrev = mysql_query("SELECT m.mcm_id, m.s_id, m.body, DATE_FORMAT(m.time_stamp, '%b %D (%l:%i%p)') AS time, u.defaultimg_url FROM mc_msgs m INNER JOIN users u ON u.user_id=m.s_id WHERE ((m.s_id='$cid' AND m.u_id='$id') OR (m.s_id='$id' AND m.u_id='$cid')) AND m.mcm_id>='$fmv' ORDER BY m.mcm_id DESC LIMIT 5");
		//test and try to set fmv
			$new_fmv = mysql_result(mysql_query("SELECT mcm_id FROM mc_msgs WHERE (u_id='$id' and s_id='$cid') OR (u_id='$cid' and s_id='$id') ORDER BY mcm_id DESC LIMIT 6, 1"), 0);
			if ($new_fmv!=0) {
				$update = mysql_query("UPDATE mc_open SET fmv='$new_fmv' WHERE u_id='$id' and c_id='$cid'");
			}
	} else {
		$msgsrev = mysql_query("SELECT m.mcm_id, m.s_id, m.body, DATE_FORMAT(m.time_stamp, '%b %D (%l:%i%p)') AS time, u.defaultimg_url FROM mc_msgs m INNER JOIN users u ON u.user_id=m.s_id WHERE ((m.s_id='$cid' AND m.u_id='$id') OR (m.s_id='$id' AND m.u_id='$cid')) AND m.mcm_id>='$fmv' ORDER BY m.mcm_id DESC");
	}
}


	while($msgrev = mysql_fetch_array ($msgsrev, MYSQL_ASSOC)){
	  $results[] = $msgrev;
	}
	
	$msgs = array_reverse($results);
	
	$i = 1;
	$lastmsgdate = 0;
	foreach($msgs as $msg){
		$mcmid = $msg['mcm_id'];
		 echo '<div mcmid="'.$mcmid.'" style="padding-bottom: 6px; margin-left: 2px; margin-right: 4px;">
			<div align="left" style="font-size: 13px; height: 16px; border-bottom: 1px solid #D9D9D9; position: relative;">
				<div align="left" style="background-color: #fff; position: absolute; top: 4px; left: 0px;"><img src="'.$baseincpat.substr($msg['defaultimg_url'], 0, -4).'m'.substr($msg['defaultimg_url'], -4).'" /></div>
				<div align="left" style="background-color: #fff; position: absolute; top: 4px; left: 36px; padding-left: 3px;">'; if($msg['s_id']==$id){echo'<span class="namelink">Me</span>';}else{loadpersonnamenolink($msg['s_id']);} echo'</div>
				<div align="left" class="subtext" style="background-color: #fff; position: absolute; top: 6px; right: 0px;">';
					if (substr($msg['time'], 0, 8)==substr($lastmsgdate, 0, 8)){echo trim(substr($msg['time'], 8));}else{echo $msg['time'];}
				echo '</div>
			</div>
			<div align="left" id="msgbody" style="padding-top: 6px; margin-left: 40px;">'.$msg['body'].'</div>
		</div>';
		$lastmsgdate = $msg['time'];
	  $i++;
	}
	//update lmv using leftover mcmid
	$update = mysql_query("UPDATE mc_open SET lmv='$mcmid' WHERE u_id='$id' and c_id='$cid'");
	
	if ($i==1) {
		echo '<div class="chat_msg" mcmid="0">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-top: 2px;">
				no messages yet
			</td></tr><tr><td align="left" valign="top" style="padding-left: 5px;">
				<div style="padding-top: 2px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">checking for new...</td></tr></table></div>
			</td></tr></table>
		</div>';
	}
	
	unset($results);
	unset($msgs);
	
?>