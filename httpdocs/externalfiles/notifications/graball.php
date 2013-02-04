<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

		$display = 24;
		$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM notifications WHERE u_id='$id'"), 0);
					
		if ($num_records > $display) {
			$num_pages = ceil ($num_records/$display);
		} else {
			$num_pages = 1;
		}
					
		if (isset($_GET['pg'])&&is_numeric($_GET['pg'])) {
			$page = escape_data($_GET['pg']);
			$start = ($display*($page-1));
		} else {
			$page = 1;
			$start = 0;
		}
		
			//grab notifications
			$j = 0;
			$notifs = mysql_query ("SELECT n.n_id, n.type, n.s_id, n.sub, n.params, n.ref_id, n.xref_id, DATE_FORMAT(n.time_stamp, '%b %D, %Y at %l:%i%p') AS time, u.defaultimg_url FROM notifications n INNER JOIN users u ON u.user_id=n.s_id WHERE n.u_id='$id' ORDER BY n.n_id DESC LIMIT $start, $display");
			while ($notif = mysql_fetch_array ($notifs, MYSQL_ASSOC)) {
				$nid = $notif['n_id'];
				$sid = $notif['s_id'];
				$type = $notif['type'];
				$sub = $notif['sub'];
				$params = $notif['params'];
				$refid = $notif['ref_id'];
				$xrefid = $notif['xref_id'];
				//update counter if viewing notifs
					if (($j==0)&&($page==1)) {
						$update = mysql_query("UPDATE notifications SET viewed='y' WHERE u_id='$id' AND n_id<='$nid'");
					}
				echo '<div align="left" style="margin-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px"><a href="'.$baseincpat.'meefile.php?id='.$sid.'"><img src="'.$baseincpat.$notif['defaultimg_url'].'" /></a></td><td align="left" valign="top" style="padding-left: 6px; padding-top: 2px;"><div align="left">';
								if ($type=='feedcmt') {
									loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid.'">your feed post</a>.';
								} elseif ($type=='feedcmtx') {
									$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									$n_feeduid = $n_feedinfo['u_id'];
									if ($sub=='emo') {
										loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a> you '; if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$refid' AND u_id='$id' AND type='d' LIMIT 1"), 0)>0) {echo'dislike';}else{echo'like';} echo'd.';
									} else {
										loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a>.';
									}
								} elseif ($type=='feedeml') {
									loadpersonname($sid); echo ' liked <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid.'">your feed post</a>.';
								} elseif ($type=='feedemlx') {
									$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									$n_feeduid = $n_feedinfo['u_id'];
									if ($sub=='cmt') {
										loadpersonname($sid); echo ' liked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a> you commented on.';
									} else {
										loadpersonname($sid); if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$refid' AND u_id='$id' AND type='l' LIMIT 1"), 0)>0) {echo' also';} echo' liked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a>.';
									}
								} elseif ($type=='feedemd') {
									loadpersonname($sid); echo ' disliked <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid.'">your feed post</a>.';
								} elseif ($type=='feedemdx') {
									$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									$n_feeduid = $n_feedinfo['u_id'];
									if ($sub=='cmt') {
										loadpersonname($sid); echo ' disliked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a> you commented on.';
									} else {
										loadpersonname($sid); if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$refid' AND u_id='$id' AND type='d' LIMIT 1"), 0)>0) {echo' also';} echo' disliked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a>.';
									}
								} elseif ($type=='msg') {
									loadpersonname($sid); echo ' <a href="'.$baseincpat.'meefile.php?id='.$id.'&#&vid='.$refid.'">sent you a message</a>.';
								} elseif ($type=='msgcmt') {
									loadpersonname($sid); echo ' replied to <a href="'.$baseincpat.'meefile.php?id='.$id.'&#&vid='.$refid.'">one of your messages</a>.';
								} elseif ($type=='apt') {
									$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id, u_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									loadpersonname($sid); echo ' tagged you in <a href="'.$baseincpat.'meefile.php?id='.$n_apinfo['u_id'].'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid.'">a photo</a>.';
								} elseif ($type=='apcmt') {
									$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid.'">your photo</a>.';
								} elseif ($type=='apcmtx') {
									$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id, u_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$n_apinfo['u_id'].'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid.'">a photo you commented on</a>.';
								} elseif ($type=='uicmt') {
									loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=photos&view=meepic&#uiid='.$refid.'">your MeePic</a>.';
								} elseif ($type=='uicmtx') {
									$n_uiinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM user_imgs WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$n_uiinfo['u_id'].'&t=photos&view=meepic&#uiid='.$refid.'">a MeePic you commented on</a>.';
								} elseif ($type=='eiresp') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT e.name, eo.rsvp FROM events e INNER JOIN event_owners eo ON e.e_id=eo.e_id AND eo.u_id='$sid' WHERE e.e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> accepted your event invite to "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>" and '; if($n_einfo['rsvp']=='a'){echo'is attending';}elseif($n_einfo['rsvp']=='m'){echo'might attend';}else{echo'isn\'t attending';} echo'.';
								} elseif ($type=='eirespn') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> denied your event invite to "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>"';
								} elseif ($type=='evntadm') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo 'You are now an admin of the event "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>"';
								} elseif ($type=='evntadmr') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo 'You are no longer an admin of the event "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>"';
								} elseif ($type=='projadm') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name, type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									if ($n_cpinfo['type']=='bug') {
										$n_cpinfo_name = 'Meesto Bug';
									} else {
										$n_cpinfo_name = 'Meesto Community Project';
									}
									echo 'You are now a team member of the '.$n_cpinfo_name.' "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>"';
								} elseif ($type=='projadmr') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name, type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									if ($n_cpinfo['type']=='bug') {
										$n_cpinfo_name = 'Meesto Bug';
									} else {
										$n_cpinfo_name = 'Meesto Community Project';
									}
									echo 'You are no longer a team member of the '.$n_cpinfo_name.' "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>"';
								} elseif ($type=='mtscmt') {
									$n_mtsinfo = mysql_fetch_array (mysql_query ("SELECT title, mt_id FROM meefile_tab_sec WHERE mts_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									loadpersonname($sid); echo ' commented on "<a href="'.$baseincpat.'meefile.php?id='.$id.'&t='.$n_mtsinfo['mt_id'].'&vid='.$refid.'">'.$n_mtsinfo['title'].'</a>"';
								} elseif ($type=='mtscmtx') {
									$n_mtsinfo = mysql_fetch_array (mysql_query ("SELECT mts.title, mts.mt_id, mt.u_id FROM meefile_tab_sec mts INNER JOIN meefile_tab mt ON mt.mt_id=mts.mt_id WHERE mts.mts_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									loadpersonname($sid); echo ' also commented on "<a href="'.$baseincpat.'meefile.php?id='.$n_mtsinfo['u_id'].'&t='.$n_mtsinfo['mt_id'].'&vid='.$refid.'">'.$n_mtsinfo['title'].'</a>"';
								} elseif ($type=='cpiresp') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> accepted your invite to support "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>"';
								} elseif ($type=='cpirespn') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> denied your invite to support "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>"';
								} elseif ($type=='fdbkcmt') {
									loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'community.php?#f=fdbk&vid='.$refid.'">your feedback</a>.';
								} elseif ($type=='fdbkcmtx') {
									$n_fdbkuid = mysql_result(mysql_query ("SELECT u_id FROM feedback WHERE fdbk_id='$refid' LIMIT 1"), 0);
									loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'community.php?#f=fdbk&vid='.$refid.'">'; loadpersonnamenolink($n_fdbkuid); echo '\'s feedback</a>.';
								} elseif ($type=='evntmcmt') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									loadpersonname($sid); echo ' commented on "<a href="'.$baseincpat.'event.php?id='.$refid.'&vid='.$xrefid.'">'.$n_einfo['name'].'</a>"';
								} elseif ($type=='evntcmt') {
									loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'event.php?id='.$refid.'&vid='.$xrefid.'&vt=x">your event comment</a>.';
								} elseif ($type=='evntcmtx') {
									loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'event.php?id='.$refid.'&vid='.$xrefid.'&vt=x">an event comment</a>.';
								} elseif ($type=='cprjmcmt') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									loadpersonname($sid); echo ' commented on "<a href="'.$baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid.'">'.$n_cpinfo['name'].'</a>"';
								} elseif ($type=='cprjcmt') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid.'&vt=x">your community '; if($n_cpinfo['type']=='bug'){echo'bug';}else{echo'project';} echo' comment</a>.';
								} elseif ($type=='cprjcmtx') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid.'&vt=x">a community '; if($n_cpinfo['type']=='bug'){echo'bug';}else{echo'project';} echo' comment</a>.';
								} elseif (($type=='pcntresp')&&($sub=='deny')) {
									loadpersonname($sid); echo ' denied your request to connect on your ';
										$params = explode(";", $params);
										$params_ct = count($params)-1;
										$i = 0;
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
									echo ' stream'; if($params_ct>0){echo's';} echo'.';
								} elseif ($type=='pcntresp') {
									loadpersonname($sid); echo ' accepted your request to connect on your ';
										$params = explode(";", $params);
										$params_ct = count($params)-1;
										$i = 0;
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
									echo ' stream'; if($params_ct>0){echo's';} echo'.';
								}
					echo '</div><div align="left" style="padding-top: 2px;"><span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div></td></tr></table>
					</div>';
				$j++;
			}
							
	//paginations
	echo '<div align="center">
		<table cellpadding="0" cellspacing="0"><tr>';
			if ($num_pages > 1) {
							
				if ($page != 1) {
					echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="backcontrol.setState(\'pg=' . ($page-1) . '\');">previous</td> ';
				}
							
				for ($i = 1; $i <= $num_pages; $i++) {
					if ($i != $page) {
						echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="backcontrol.setState(\'pg=' . $i . '\');">' . $i . '</td>';
					} else {
						echo '<td style="padding-right: 3px;" class="paginationlinkOn">' .$i . '</td> ';
					}
				}
				if ($page != $num_pages) {
					echo '<td class="paginationlinks" onclick="backcontrol.setState(\'pg=' . ($page+1) . '\');">next</td>';
				}
			}
		echo '</tr></table>
		</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>