<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses2 = true;
}

if (!isset($apid)) {
	$apid = escape_data($_GET['apid']);
	$action = escape_data($_GET['action']);
	$uid = mysql_result(mysql_query("SELECT pa.u_id FROM photo_albums pa INNER JOIN album_photos ap ON ap.pa_id=pa.pa_id AND ap.ap_id='$apid' LIMIT 1"), 0);
}

if ($action=='add') {

	//test to see if tag will be added
	if (($uid==$id)) { //let people tagged in album or photo tag other people? !important
		$newaptuid = escape_data($_GET['uid']);
		if (($newaptuid==$id)||(mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$newaptuid' LIMIT 1"), 0)>0)) { //test to make sure person tagged is in my peeple
			$x = escape_data($_GET['x']);
			$y = escape_data($_GET['y']);
			if (isset($_GET['method'])&&($_GET['method']=='edtalbmp')) {
				$x = $x*2;
				$y = $y*2;
			}
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE ap_id='$apid' AND u_id='$newaptuid' LIMIT 1"), 0)>0) {
				$update = mysql_query("UPDATE ap_tags SET x='$x', y='$y' WHERE ap_id='$apid' AND u_id='$newaptuid'");
			} else {
				$paid = mysql_result(mysql_query("SELECT pa.pa_id FROM photo_albums pa INNER JOIN album_photos ap ON ap.pa_id=pa.pa_id AND ap.ap_id='$apid' LIMIT 1"), 0);
				$insert = mysql_query("INSERT INTO ap_tags (pa_id, ap_id, u_id, x, y, tgr_id, time_stamp) VALUES ('$paid', '$apid', '$newaptuid', '$x', '$y', '$id', NOW())");
				$aptid = mysql_insert_id();
				
					// tests for and make activity post
					if (mysql_result(mysql_query ("SELECT COUNT(*) FROM user_activityposts WHERE u_id='$newaptuid' AND ptags='y' LIMIT 1"), 0)>0) {
						if (mysql_result(mysql_query ("SELECT COUNT(*) FROM feed WHERE u_id='$id' AND type='actvapt' AND ref_id='$paid' LIMIT 1"), 0)>0) {
							$createpost = mysql_query("UPDATE feed SET ref_type=ref_type+1 WHERE u_id='$id' AND type='actvapt' AND ref_id='$paid'");
						} else {
							$createpost = mysql_query("INSERT INTO feed (u_id, type, ref_id, ref_type, time_stamp) VALUES ('$id', 'actvapt', '$paid', 1, NOW())");
						}
					}
				
				if ($newaptuid!=$id) {
					$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$newaptuid', 'apt', '$id', '$apid', '$aptid', NOW())");
					$notifid = mysql_insert_id();
	
					// email notif
							//check to send email
							if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$newaptuid' AND tag_photo='y' LIMIT 1"), 0)>0) {
								//send email
								$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$newaptuid' LIMIT 1"), 0);
								
								//params
								$subject = returnpersonnameasid($id, $newaptuid).' has tagged you in a photo';
								$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $newaptuid).'</a> has tagged you in <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a photo</a>.';
								
								include('../../../externals/general/emailer.php');
							}
				}
			}
			$naptid = mysql_insert_id();
		}
	}
}

if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE ap_id='$apid' AND u_id='$id' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='strm' AND ps.stream=apv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='chan' AND apv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
	//load in tags test for vis !important
	if ($uid==$id) {
		$tags = mysql_query("SELECT apt_id, u_id, type FROM ap_tags WHERE ap_id='$apid' ORDER BY apt_id ASC");
	} else {
		$tags = mysql_query("(SELECT DISTINCT apt.apt_id, apt.u_id, apt.type FROM ap_tags apt INNER JOIN defvis_apt dvapt ON apt.u_id=dvapt.u_id LEFT JOIN my_peeple mp ON mp.u_id=apt.u_id AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id=apt.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN defvis_apt dvapt2 ON apt.u_id=dvapt2.u_id AND dvapt2.type='user' AND dvapt2.ref_id='$id' WHERE (apt.ap_id='$apid') AND ((dvapt.type='pub' AND dvapt.sub_type='y') OR (((dvapt.type='strm' AND dvapt.sub_type=ps.stream) OR (dvapt.type='chan' AND dvapt.ref_id=mpc.mpc_id)) AND (dvapt2.aptvis_id IS NULL)))) UNION (SELECT DISTINCT apt_id, u_id, type FROM ap_tags WHERE ap_id='$apid' AND u_id='$id') ORDER BY apt_id ASC");
	}
	$i = 0;
	$finalaptct = mysql_num_rows($tags)-1;
	while ($tag = mysql_fetch_array ($tags, MYSQL_ASSOC)) {
		$aptuid = $tag['u_id'];
		if (($i==$finalaptct)&&($i==1)) {
			echo ' and ';	
		} elseif (($i==$finalaptct)&&($i>0)) {
			echo ', and ';	
		} elseif ($i>0) {
			echo ', ';	
		}
			echo '<span onmouseover="$(\''.$apid.'apt'.$aptuid.'\').set(\'styles\',{\'display\':\'block\'});" onmouseout="$(\''.$apid.'apt'.$aptuid.'\').set(\'styles\',{\'display\':\'none\'});">'; loadpersonname($aptuid); echo '<span class="subtext" style="font-size: 14px;"> ['; if(($uid==$id)||($aptuid==$id)){echo'<span onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/deleteaptag.php?id='.$tag['apt_id'].'\', size: {x: 660, y: 340}, handler:\'iframe\'});">delete</span> | ';} echo'photos]</span></span>';
		$i++;
	}
	if ($i==0) {
		echo 'no tags';	
	}

}

if (isset($minses2)) {
	session_write_close();
	exit();	
}
?>