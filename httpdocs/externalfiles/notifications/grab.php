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

echo '<div align="left">
							<table cellpadding="0" cellspacing="0" width="100%"><tr><td align="left" valign="top" class="p24">notifications'; if($nct>0){echo' ('.$nct.')';} echo'</td><td align="right" valign="center" style="padding-right: 6px;"><a href="'.$baseincpat.'notifs.php?">view all</a></td></tr></table>
						</div>
						<div align="left" id="ponotifcont" style="padding-left: 8px; height: '; if($rct==0){echo'268';}else{echo'208';} echo'px; width: 230px; overflow-x: none; overflow-y: scroll;">';
							//grab notifications
							if ($nct>6) {
								$notif_snid = mysql_result(mysql_query("SELECT n_id FROM notifications WHERE u_id='$id' AND viewed IS NULL ORDER BY n_id ASC LIMIT 1"), 0);
								$notifs = mysql_query ("SELECT n_id, type, s_id, sub, params, ref_id, xref_id, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM notifications WHERE u_id='$id' AND n_id>='$notif_snid' ORDER BY n_id DESC");
							} else {
								$notifs = mysql_query ("SELECT n_id, type, s_id, sub, params, ref_id, xref_id, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM notifications WHERE u_id='$id' ORDER BY n_id DESC LIMIT 6");
							}
							while ($notif = mysql_fetch_array ($notifs, MYSQL_ASSOC)) {
								$nid = $notif['n_id'];
								$sid = $notif['s_id'];
								$type = $notif['type'];
								$sub = $notif['sub'];
								$params = $notif['params'];
								$refid = $notif['ref_id'];
								$xrefid = $notif['xref_id'];
								if ($type=='feedcmt') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid.'">your feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='feedcmtx') {
									$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									$n_feeduid = $n_feedinfo['u_id'];
									if ($sub=='emo') {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a> you '; if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$refid' AND u_id='$id' AND type='d' LIMIT 1"), 0)>0) {echo'dislike';}else{echo'like';} echo'd. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									} else {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									}
								} elseif ($type=='feedeml') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' liked <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid.'">your feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='feedemlx') {
									$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									$n_feeduid = $n_feedinfo['u_id'];
									if ($sub=='cmt') {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' liked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a> you commented on. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									} else {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$refid' AND u_id='$id' AND type='l' LIMIT 1"), 0)>0) {echo' also';} echo' liked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									}
								} elseif ($type=='feedemd') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' disliked <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid.'">your feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='feedemdx') {
									$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									$n_feeduid = $n_feedinfo['u_id'];
									if ($sub=='cmt') {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' disliked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a> you commented on. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									} else {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$refid' AND u_id='$id' AND type='d' LIMIT 1"), 0)>0) {echo' also';} echo' disliked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									}
								} elseif ($type=='msg') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' <a href="'.$baseincpat.'meefile.php?id='.$id.'&#&vid='.$refid.'">sent you a message</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='msgcmt') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' replied to <a href="'.$baseincpat.'meefile.php?id='.$id.'&#&vid='.$refid.'">one of your messages</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='apt') {
									$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id, u_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' tagged you in <a href="'.$baseincpat.'meefile.php?id='.$n_apinfo['u_id'].'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid.'">a photo</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='apcmt') {
									$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid.'">your photo</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='apcmtx') {
									$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id, u_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$n_apinfo['u_id'].'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid.'">a photo you commented on</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='uicmt') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=photos&view=meepic&#uiid='.$refid.'">your MeePic</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='uicmtx') {
									$n_uiinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM user_imgs WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$n_uiinfo['u_id'].'&t=photos&view=meepic&#uiid='.$refid.'">a MeePic you commented on</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='eiresp') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT e.name, eo.rsvp FROM events e INNER JOIN event_owners eo ON e.e_id=eo.e_id AND eo.u_id='$sid' WHERE e.e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn"><a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> accepted your event invite to "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>" and '; if($n_einfo['rsvp']=='a'){echo'is attending';}elseif($n_einfo['rsvp']=='m'){echo'might attend';}else{echo'isn\'t attending';} echo'. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='eirespn') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn"><a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> denied your event invite to "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='evntadm') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">You are now an admin of the event "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='evntadmr') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">You are no longer an admin of the event "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='projadm') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name, type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									if ($n_cpinfo['type']=='bug') {
										$n_cpinfo_name = 'Meesto Bug';
									} else {
										$n_cpinfo_name = 'Meesto Community Project';
									}
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">You are now a team member of the '.$n_cpinfo_name.' "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='projadmr') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name, type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									if ($n_cpinfo['type']=='bug') {
										$n_cpinfo_name = 'Meesto Bug';
									} else {
										$n_cpinfo_name = 'Meesto Community Project';
									}
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">You are no longer a team member of the '.$n_cpinfo_name.' "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='mtscmt') {
									$n_mtsinfo = mysql_fetch_array (mysql_query ("SELECT title, mt_id FROM meefile_tab_sec WHERE mts_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on "<a href="'.$baseincpat.'meefile.php?id='.$id.'&t='.$n_mtsinfo['mt_id'].'&vid='.$refid.'">'.$n_mtsinfo['title'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='mtscmtx') {
									$n_mtsinfo = mysql_fetch_array (mysql_query ("SELECT mts.title, mts.mt_id, mt.u_id FROM meefile_tab_sec mts INNER JOIN meefile_tab mt ON mt.mt_id=mts.mt_id WHERE mts.mts_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' also commented on "<a href="'.$baseincpat.'meefile.php?id='.$n_mtsinfo['u_id'].'&t='.$n_mtsinfo['mt_id'].'&vid='.$refid.'">'.$n_mtsinfo['title'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='cpiresp') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn"><a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> accepted your invite to support "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='cpirespn') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn"><a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> denied your invite to support "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='fdbkcmt') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'community.php?#f=fdbk&vid='.$refid.'">your feedback</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='fdbkcmtx') {
									$n_fdbkuid = mysql_result(mysql_query ("SELECT u_id FROM feedback WHERE fdbk_id='$refid' LIMIT 1"), 0);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'community.php?#f=fdbk&vid='.$refid.'">'; loadpersonnamenolink($n_fdbkuid); echo '\'s feedback</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='evntmcmt') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on "<a href="'.$baseincpat.'event.php?id='.$refid.'&vid='.$xrefid.'">'.$n_einfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='evntcmt') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'event.php?id='.$refid.'&vid='.$xrefid.'&vt=x">your event comment</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='evntcmtx') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'event.php?id='.$refid.'&vid='.$xrefid.'&vt=x">an event comment</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='cprjmcmt') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on "<a href="'.$baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid.'">'.$n_cpinfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='cprjcmt') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid.'&vt=x">your community '; if($n_cpinfo['type']=='bug'){echo'bug';}else{echo'project';} echo' comment</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='cprjcmtx') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid.'&vt=x">a community '; if($n_cpinfo['type']=='bug'){echo'bug';}else{echo'project';} echo' comment</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif (($type=='pcntresp')&&($sub=='deny')) {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' denied your request to connect on your ';
										$params = explode(";", $params);
										$params_ct = count($params)-1;
										foreach ($params as $param) {
											if (($i==$params_ct)&&($i==1)) {
												echo ' and ';	
											} elseif (($i==$params_ct)&&($i>0)) {
												echo ', and ';	
											} elseif ($i>0) {
												echo ', ';	
											}
											if ($param == 'mb') {
												echo 'my bubble';
											} elseif ($param == 'frnd') {
												echo 'friends';
											} elseif ($param == 'fam') {
												echo 'family';
											} elseif ($param == 'prof') {
												echo 'professional';
											} elseif ($param == 'edu') {
												echo 'education';
											} elseif ($param == 'aqu') {
												echo 'just met mee';
											}
											$i++;
										}
									echo ' stream'; if($params_ct>0){echo's';} echo'. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='pcntresp') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' accepted your request to connect on your ';
										$params = explode(";", $params);
										$params_ct = count($params)-1;
										foreach ($params as $param) {
											if (($i==$params_ct)&&($i==1)) {
												echo ' and ';	
											} elseif (($i==$params_ct)&&($i>0)) {
												echo ', and ';	
											} elseif ($i>0) {
												echo ', ';	
											}
											if ($param == 'mb') {
												echo 'my bubble';
											} elseif ($param == 'frnd') {
												echo 'friends';
											} elseif ($param == 'fam') {
												echo 'family';
											} elseif ($param == 'prof') {
												echo 'professional';
											} elseif ($param == 'edu') {
												echo 'education';
											} elseif ($param == 'aqu') {
												echo 'just met mee';
											}
											$i++;
										}
									echo ' stream'; if($params_ct>0){echo's';} echo'. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								}
							}
						echo '</div>
						<div align="left" class="p24" style="padding-top: 12px;">requests/invites'; if($rct>0){echo' ('.$rct.')';} echo'</div>';
						if ($rct>0) {
							echo '<div align="left" id="poreqicont" style="padding-left: 8px; height: 90px; width: 230px; overflow-x: none; overflow-y: scroll;">';
								$rinvtpeepcnct = mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE u_id='$id' AND type='peepcnct'"), 0);
								if ($rinvtpeepcnct>0) {
									echo '<div align="left" class="blockbtn" style="font-size: 18px;" onclick="$(\'potraycont\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/po/reqview.php?t=peepcnct\', handler:\'iframe\'});">'.$rinvtpeepcnct.' peeple connection request'; if($rinvtpeepcnct>1){echo's';} echo'!</div>';
								}
								$rinvtevntct = mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE u_id='$id' AND type='invtevnt'"), 0);
								if ($rinvtevntct>0) {
									echo '<div align="left" class="blockbtn" style="font-size: 18px;" onclick="$(\'potraycont\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/po/reqview.php?t=invtevnt\', handler:\'iframe\'});">'.$rinvtevntct.' event invite'; if($rinvtevntct>1){echo's';} echo'!</div>';
								}
								$rinvtcprojct = mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE u_id='$id' AND type='invtcproj'"), 0);
								if ($rinvtcprojct>0) {
									echo '<div align="left" class="blockbtn" style="font-size: 18px;" onclick="$(\'potraycont\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/po/reqview.php?t=invtcproj\', handler:\'iframe\'});">'.$rinvtcprojct.' Community Project invite'; if($rinvtcprojct>1){echo's';} echo'!</div>';
								}
						echo '</div>';
						} else { //if there are no requests/invites
							echo '<div align="left" id="poreqicont" style="padding-left: 8px;">none</div>';
						}
					echo '</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>