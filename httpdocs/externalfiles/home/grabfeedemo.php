<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses2 = true;
}

if (!isset($fid)) {
	$fid = escape_data($_GET['id']);	
}

$isvis = false;

$feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id, type, ref_id, ref_type FROM feed WHERE f_id='$fid' LIMIT 1"), MYSQL_ASSOC);
$uid = $feedinfo['u_id'];
$type = $feedinfo['type'];
$ref_id = $feedinfo['ref_id'];
$ref_type = $feedinfo['ref_type'];

if ($type=='actvapt') {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$uid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN defvis_apt dvapt ON (dvapt.u_id='$uid' AND dvapt.type='strm' AND ps.stream=dvapt.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN defvis_apt dvapt ON (dvapt.u_id='$uid' AND dvapt.type='chan' AND dvapt.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$uid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif ($type=='actvap') {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE pa_id='$ref_id' AND u_id='$id' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN photo_album_vis pav ON (pav.pa_id='$ref_id'AND pav.type='strm' AND ps.stream=pav.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN photo_album_vis pav ON (pav.pa_id='$ref_id'AND pav.type='chan' AND pav.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif (($type=='actvmt')&&($ref_type=='mt')) {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$ref_id'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$ref_id'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif (($type=='actvmt')&&($ref_type=='mts')) {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$ref_id'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$ref_id'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif ($type=='actvcev') {
	if (($uid==$id) || (mysql_result (mysql_query("SELECT COUNT(*) FROM events WHERE e_id='$ref_id' AND vis='pub' LIMIT 1"), 0)>0) || (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$ref_id' AND u_id='$id' LIMIT 1"), 0)>0)) {
		$isvis = true;
	}
} else {
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN feed_vis fv ON (fv.f_id='$fid' AND fv.type='strm' AND ps.stream=fv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN feed_vis fv ON (fv.f_id='$fid' AND fv.type='chan' AND fv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
}

if ($isvis==true) {

if (isset($_GET['t'])) {
	$t = escape_data($_GET['t']);
	if ($t=='l') {
		if (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$fid' AND u_id='$id'"), 0)==0) {
			$insert = @mysql_query ("INSERT INTO feed_emo (f_id, u_id, type, time_stamp) VALUES ('$fid', '$id', 'l', NOW())");
			//notifications
				if ($uid!=$id) {
					$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$uid', 'feedeml', '$id', '$fid', NOW())");
					$notifid = mysql_insert_id();
						//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$uid' AND emo_myfeed='y' LIMIT 1"), 0)>0) {					
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$uid' LIMIT 1"), 0);
										
							//params
							$subject = returnpersonnameasid($id, $uid).' liked your feed post';
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $uid).'</a> liked <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">your feed post</a>.';
										
							include('../../../externals/general/emailer.php');
						}
				}
				
				//notify all other emos, likes
				$notif_uids = array();
				$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM feed_emo WHERE f_id='$fid' AND type='l' AND u_id!='$uid' AND u_id!='$id'");
				while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
					$mcuid = $msgcmt['u_id'];
					$notif = mysql_query("INSERT INTO notifications (u_id, type, sub, s_id, ref_id, time_stamp) VALUES ('$mcuid', 'feedemlx', '$id', '$fid', NOW())");
					$notifid = mysql_insert_id();
						//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND emo_onfeed='y' LIMIT 1"), 0)>0) {					
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
										
							//params
							$subject = returnpersonnameasid($id, $mcuid).' also liked '.returnpersonnameasid($uid, $mcuid).'\'s feed post';
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> also liked <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">'.returnpersonnameasid($uid, $mcuid).'\'s feed post</a>.';
										
							include('../../../externals/general/emailer.php');
						}
					$notif_uids[] = $mcuid;
				}
				
				//notify all other emos, dislikes
				$notif_uids = array();
				$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM feed_emo WHERE f_id='$fid' AND type='d' AND u_id!='$uid' AND u_id!='$id'");
				while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
					$mcuid = $msgcmt['u_id'];
					$notif = mysql_query("INSERT INTO notifications (u_id, type, sub, s_id, ref_id, time_stamp) VALUES ('$mcuid', 'feedemlx', '$id', '$fid', NOW())");
					$notifid = mysql_insert_id();
						//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND emo_onfeed='y' LIMIT 1"), 0)>0) {					
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
										
							//params
							$subject = returnpersonnameasid($id, $mcuid).' liked '.returnpersonnameasid($uid, $mcuid).'\'s feed post you disliked';
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> liked <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">'.returnpersonnameasid($uid, $mcuid).'\'s feed post you disliked</a>.';
										
							include('../../../externals/general/emailer.php');
						}
					$notif_uids[] = $mcuid;
				}
				
				//notify all commenters
				$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM feed_cmt WHERE f_id='$fid' AND u_id!='$uid' AND u_id!='$id'");
				while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
					$mcuid = $msgcmt['u_id'];
					if (!in_array($mcuid, $notif_uids)) {
						$notif = mysql_query("INSERT INTO notifications (u_id, type, sub, s_id, ref_id, time_stamp) VALUES ('$mcuid', 'feedemlx', 'cmt', '$id', '$fid', NOW())");
						$notifid = mysql_insert_id();
							//check to send email
							if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND emo_onfeed='y' LIMIT 1"), 0)>0) {					
								//send email
								$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
											
								//params
								$subject = returnpersonnameasid($id, $mcuid).' liked '.returnpersonnameasid($uid, $mcuid).'\'s feed post you commented on';
								$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> liked <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">'.returnpersonnameasid($uid, $mcuid).'\'s feed post you commented on</a>.';
											
								include('../../../externals/general/emailer.php');
							}
					}
					$notif_uids[] = $mcuid;
				}	
		}
	} elseif ($t=='d') {
		if (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$fid' AND u_id='$id'"), 0)==0) {
			$insert = @mysql_query ("INSERT INTO feed_emo (f_id, u_id, type, time_stamp) VALUES ('$fid', '$id', 'd', NOW())");
			//notifications
				if ($uid!=$id) {
					$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$uid', 'feedemd', '$id', '$fid', NOW())");
					$notifid = mysql_insert_id();
						//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$uid' AND emo_myfeed='y' LIMIT 1"), 0)>0) {					
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$uid' LIMIT 1"), 0);
										
							//params
							$subject = returnpersonnameasid($id, $uid).' disliked your feed post';
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $uid).'</a> disliked <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">your feed post</a>.';
										
							include('../../../externals/general/emailer.php');
						}
				}
				
				//notify all other emos, likes
				$notif_uids = array();
				$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM feed_emo WHERE f_id='$fid' AND type='l' AND u_id!='$uid' AND u_id!='$id'");
				while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
					$mcuid = $msgcmt['u_id'];
					$notif = mysql_query("INSERT INTO notifications (u_id, type, sub, s_id, ref_id, time_stamp) VALUES ('$mcuid', 'feedemdx', '$id', '$fid', NOW())");
					$notifid = mysql_insert_id();
						//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND emo_onfeed='y' LIMIT 1"), 0)>0) {					
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
										
							//params
							$subject = returnpersonnameasid($id, $mcuid).' disliked '.returnpersonnameasid($uid, $mcuid).'\'s feed post you liked';
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> disliked <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">'.returnpersonnameasid($uid, $mcuid).'\'s feed post you liked</a>.';
										
							include('../../../externals/general/emailer.php');
						}
					$notif_uids[] = $mcuid;
				}
				
				//notify all other emos, dislikes
				$notif_uids = array();
				$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM feed_emo WHERE f_id='$fid' AND type='d' AND u_id!='$uid' AND u_id!='$id'");
				while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
					$mcuid = $msgcmt['u_id'];
					$notif = mysql_query("INSERT INTO notifications (u_id, type, sub, s_id, ref_id, time_stamp) VALUES ('$mcuid', 'feedemdx', '$id', '$fid', NOW())");
					$notifid = mysql_insert_id();
						//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND emo_onfeed='y' LIMIT 1"), 0)>0) {					
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
										
							//params
							$subject = returnpersonnameasid($id, $mcuid).' also disliked '.returnpersonnameasid($uid, $mcuid).'\'s feed post';
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> also disliked <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">'.returnpersonnameasid($uid, $mcuid).'\'s feed post</a>.';
										
							include('../../../externals/general/emailer.php');
						}
					$notif_uids[] = $mcuid;
				}
				
				//notify all commenters
				$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM feed_cmt WHERE f_id='$fid' AND u_id!='$uid' AND u_id!='$id'");
				while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
					$mcuid = $msgcmt['u_id'];
					if (!in_array($mcuid, $notif_uids)) {
						$notif = mysql_query("INSERT INTO notifications (u_id, type, sub, s_id, ref_id, time_stamp) VALUES ('$mcuid', 'feedemdx', 'cmt', '$id', '$fid', NOW())");
						$notifid = mysql_insert_id();
							//check to send email
							if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND emo_onfeed='y' LIMIT 1"), 0)>0) {					
								//send email
								$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
											
								//params
								$subject = returnpersonnameasid($id, $mcuid).' disliked '.returnpersonnameasid($uid, $mcuid).'\'s feed post you commented on';
								$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> disliked <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">'.returnpersonnameasid($uid, $mcuid).'\'s feed post you commented on</a>.';
											
								include('../../../externals/general/emailer.php');
							}
					}
					$notif_uids[] = $mcuid;
				}	
		}
	} elseif ($t=='u') {
		$delete = @mysql_query ("DELETE FROM feed_emo WHERE f_id='$fid' AND u_id='$id'");
		$delete = @mysql_query ("DELETE FROM notifications WHERE (type='feedeml' OR type='feedemlx' OR type='feedemd' OR type='feedemdx') AND s_id='$id' AND ref_id='$fid'");
	}
}

$lk_ct = mysql_result(mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$fid' AND type='l'"), 0);
$dlk_ct = mysql_result(mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$fid' AND type='d'"), 0);

if ($lk_ct>0) {
	echo '<span class="namelink" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/home/viewemo.php?id='.$fid.'&fltr=l\', size: {x: 660, y: 340}, handler:\'iframe\'});">';
	if (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$fid' AND type='l' AND u_id='$id'"), 0)) {
		echo 'You';
		if ($lk_ct>1) {
			echo' and '.($lk_ct-1).' '; if($lk_ct>2){echo'peeple';}else{echo'person';}
		}
		echo '</span> like';
	} else {
		echo $lk_ct.' '; if($lk_ct>1){echo'peeple';}else{echo'person';} echo '</a> like'; if($lk_ct==1){echo's';}
	}
	echo ' this.';	
}

if ($dlk_ct>0) {
	echo '<span class="namelink" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/home/viewemo.php?id='.$fid.'&fltr=d\', size: {x: 660, y: 340}, handler:\'iframe\'});">';
	if (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$fid' AND type='d' AND u_id='$id'"), 0)) {
		echo 'You';
		if ($dlk_ct>1) {
			echo' and '.$dlk_ct.' '; if($dlk_ct>2){echo'peeple';}else{echo'person';}
		}
		echo '</span> dislike';
	} else {
		echo $dlk_ct.' '; if($dlk_ct>1){echo'peeple';}else{echo'person';} echo '</a> dislike'; if($dlk_ct==1){echo's';}
	}
	echo ' this.';	
}

} else { //if not vis
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';
}

if (isset($minses2)) {
	session_write_close();
	$minses2 = false;
	exit();	
}
?>