<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$rid = escape_data($_GET['rid']);
$resp = escape_data($_GET['resp']);

if (mysql_result (mysql_query("SELECT COUNT(*) FROM requests WHERE r_id='$rid' AND u_id='$id' LIMIT 1"), 0)>0) { //test for owner
	
	$req = mysql_fetch_array (mysql_query ("SELECT type, s_id, sub, params, ref_id, xref_id FROM requests WHERE r_id='$rid'"), MYSQL_ASSOC);
	$sid = $req['s_id'];
	$type = $req['type'];
	$sub = $req['sub'];
	$params = $req['params'];
	$refid = $req['ref_id'];
	$xrefid = $req['xref_id'];
	
	if ($type=='peepcnct') {
		if ($resp=='a') {
			$mb = escape_data($_GET['mb']);
			$frnd = escape_data($_GET['frnd']);
			$fam = escape_data($_GET['fam']);
			$prof = escape_data($_GET['prof']);
			$edu = escape_data($_GET['edu']);
			$aqu = escape_data($_GET['aqu']);
			$params = explode(";", $params);
			$addedcncts = array();
			$reqcncts = array();
				//test each stream
					//special for my bubble
					if (($mb=='true')&&(mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='mb' LIMIT 1"), 0)==0)) {
						$add = mysql_query("INSERT INTO peep_streams (u_id, p_id, stream, time_stamp) VALUES ('$id', '$sid', 'mb', NOW())");
						$addedcncts[] = 'mb';
					}
					if ((in_array('mb', $params))&&(mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$sid' AND p_id='$id' AND stream='mb' LIMIT 1"), 0)==0)) {
						$add = mysql_query("INSERT INTO peep_streams (u_id, p_id, stream, time_stamp) VALUES ('$sid', '$id', 'mb', NOW())");
					}
				if (($frnd=='true')&&(mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='frnd' LIMIT 1"), 0)==0)) {
					if(in_array('frnd', $params)) { //make connection
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$sid' LIMIT 1"), 0)==0) {
							$add = mysql_query("INSERT INTO my_peeple (u_id, p_id, time_stamp) VALUES ('$id', '$sid', NOW()), ('$sid', '$id', NOW())");
						}
						$add = mysql_query("INSERT INTO peep_streams (u_id, p_id, stream, time_stamp) VALUES ('$id', '$sid', 'frnd', NOW()), ('$sid', '$id', 'frnd', NOW())");
						$addedcncts[] = 'frnd';
					} else { //request connection
						$reqcncts[] = 'frnd';
					}
				}
				if (($fam=='true')&&(mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='fam' LIMIT 1"), 0)==0)) {
					if(in_array('fam', $params)) { //make connection
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$sid' LIMIT 1"), 0)==0) {
							$add = mysql_query("INSERT INTO my_peeple (u_id, p_id, time_stamp) VALUES ('$id', '$sid', NOW()), ('$sid', '$id', NOW())");
						}
						$add = mysql_query("INSERT INTO peep_streams (u_id, p_id, stream, time_stamp) VALUES ('$id', '$sid', 'fam', NOW()), ('$sid', '$id', 'fam', NOW())");
						$addedcncts[] = 'fam';
					} else { //request connection
						$reqcncts[] = 'fam';
					}
				}
				if (($prof=='true')&&(mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='prof' LIMIT 1"), 0)==0)) {
					if(in_array('prof', $params)) { //make connection
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$sid' LIMIT 1"), 0)==0) {
							$add = mysql_query("INSERT INTO my_peeple (u_id, p_id, time_stamp) VALUES ('$id', '$sid', NOW()), ('$sid', '$id', NOW())");
						}
						$add = mysql_query("INSERT INTO peep_streams (u_id, p_id, stream, time_stamp) VALUES ('$id', '$sid', 'prof', NOW()), ('$sid', '$id', 'prof', NOW())");
						$addedcncts[] = 'prof';
					} else { //request connection
						$reqcncts[] = 'prof';
					}
				}
				if (($edu=='true')&&(mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='edu' LIMIT 1"), 0)==0)) {
					if(in_array('edu', $params)) { //make connection
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$sid' LIMIT 1"), 0)==0) {
							$add = mysql_query("INSERT INTO my_peeple (u_id, p_id, time_stamp) VALUES ('$id', '$sid', NOW()), ('$sid', '$id', NOW())");
						}
						$add = mysql_query("INSERT INTO peep_streams (u_id, p_id, stream, time_stamp) VALUES ('$id', '$sid', 'edu', NOW()), ('$sid', '$id', 'edu', NOW())");
						$addedcncts[] = 'edu';
					} else { //request connection
						$reqcncts[] = 'edu';
					}
				}
				if (($aqu=='true')&&(mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='aqu' LIMIT 1"), 0)==0)) {
					if(in_array('aqu', $params)) { //make connection
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$sid' LIMIT 1"), 0)==0) {
							$add = mysql_query("INSERT INTO my_peeple (u_id, p_id, time_stamp) VALUES ('$id', '$sid', NOW()), ('$sid', '$id', NOW())");
						}
						$add = mysql_query("INSERT INTO peep_streams (u_id, p_id, stream, time_stamp) VALUES ('$id', '$sid', 'aqu', NOW()), ('$sid', '$id', 'aqu', NOW())");
						$addedcncts[] = 'aqu';
					} else { //request connection
						$reqcncts[] = 'aqu';
					}
				}
				//make notification for added
					if (count($addedcncts)>0) {
						if (substr($addedcncts, 0, 3)=='mb;') {
							$addedcncts = substr($addedcncts, 3);
						}
						$addedcncts_str = implode(";", $addedcncts);
						$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, params, time_stamp) VALUES ('$sid', 'pcntresp', '$id', '$addedcncts_str', NOW())");
							//check to send email
							if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$sid' AND reqa_cnct='y' LIMIT 1"), 0)>0) {
								//send email
								$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$sid' LIMIT 1"), 0);
								
								//params
								$subject = returnpersonnameasid($id, $sid).' has accepted your connection request';
								$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $sid).'</a> has accepted your request to connection through your ';
									$i = 0;
									$streamct = count($addedcncts)-1;
									 foreach ($addedcncts as $streamnm) {
										if ($streamnm != 'mb') {
											if (($i==$streamct)&&($i==1)) {
												$emailercontent .= ' and ';	
											} elseif (($i==$streamct)&&($i>0)) {
												$emailercontent .= ', and ';	
											} elseif ($i>0) {
												$emailercontent .= ', ';	
											}
											if ($streamnm == 'frnd') {
												$emailercontent .= 'friends';
											} elseif ($streamnm == 'fam') {
												$emailercontent .= 'family';
											} elseif ($streamnm == 'prof') {
												$emailercontent .= 'professional';
											} elseif ($streamnm == 'edu') {
												$emailercontent .= 'education';
											} elseif ($streamnm == 'aqu') {
												$emailercontent .= 'just met mee';
											}
											$i++;
										}
									 }
									if ($i>1) {
										$emailercontent .= ' streams.';
									} else {
										$emailercontent .= ' stream.';
									}
								
								include('../../../externals/general/emailer.php');
							}
					}
				//make reqest for like to add
					if (count($reqcncts)>0) {
						$reqcncts_str = implode(";", $reqcncts);
						$insert = mysql_query("INSERT INTO requests (u_id, type, s_id, params, time_stamp) VALUES ('$sid', 'peepcnct', '$id', '$reqcncts_str', NOW())");
						//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$sid' AND req_cnct='y' LIMIT 1"), 0)>0) {
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$sid' LIMIT 1"), 0);
							
							//params
							$subject = 'Connection request from '.returnpersonnameasid($id, $sid);
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $sid).'</a> has requested to connect with you through your ';
								$i = 0;
								$streamct = count($reqcncts)-1;
								 foreach ($reqcncts as $streamnm) {
									if ($streamnm != 'mb') {
										if (($i==$streamct)&&($i==1)) {
											$emailercontent .= ' and ';	
										} elseif (($i==$streamct)&&($i>0)) {
											$emailercontent .= ', and ';	
										} elseif ($i>0) {
											$emailercontent .= ', ';	
										}
										 if ($streamnm == 'frnd') {
											$emailercontent .= 'friends';
										} elseif ($streamnm == 'fam') {
											$emailercontent .= 'family';
										} elseif ($streamnm == 'prof') {
											$emailercontent .= 'professional';
										} elseif ($streamnm == 'edu') {
											$emailercontent .= 'education';
										} elseif ($streamnm == 'aqu') {
											$emailercontent .= 'just met mee';
										}
										$i++;
									}
								 }
								if ($i>1) {
									$emailercontent .= ' streams.';
								} else {
									$emailercontent .= ' stream.';
								}
							
							include('../../../externals/general/emailer.php');
						}
					}
			echo 'You have accepted <a href="'.$baseincpat.'meefile.php?id='.$sid.'" target = "_top">'; loadpersonnamenolink($sid); echo '</a>\'s request to connect.';
				$addedcncts_cttotal = count($addedcncts)-1;
				$i = 0;
				if ($addedcncts_cttotal>=0) {
					echo '<br /><a href="'.$baseincpat.'meefile.php?id='.$sid.'" target = "_top">'; loadpersonnamenolink($sid); echo '</a> has been added to your ';
				}
				foreach ($addedcncts as $addedcnct) {
					if (($i==$addedcncts_cttotal)&&($i==1)) {
						echo ' and ';	
					} elseif (($i==$addedcncts_cttotal)&&($i>0)) {
						echo ', and ';	
					} elseif ($i>0) {
						echo ', ';	
					}
					if ($addedcnct == 'mb') {
						echo 'my bubble';
					} elseif ($addedcnct == 'frnd') {
						echo 'friends';
					} elseif ($addedcnct == 'fam') {
						echo 'family';
					} elseif ($addedcnct == 'prof') {
						echo 'professional';
					} elseif ($addedcnct == 'edu') {
						echo 'education';
					} elseif ($addedcnct == 'aqu') {
						echo 'just met mee';
					}
					$i++;
				}
				if ($addedcncts_cttotal>=0) {
					echo ' stream'; if($addedcncts_cttotal>0){echo's';} echo'.';
				}
				
				$reqcncts_cttotal = count($reqcncts)-1;
				$i = 0;
				if ($reqcncts_cttotal>=0) {
					echo '<br /><br />A request from you to connect through your ';
				}
				foreach ($reqcncts as $reqcnct) {
					if (($i==$reqcncts_cttotal)&&($i==1)) {
						echo ' and ';	
					} elseif (($i==$reqcncts_cttotal)&&($i>0)) {
						echo ', and ';	
					} elseif ($i>0) {
						echo ', ';	
					}
					if ($reqcnct == 'frnd') {
						echo 'friends';
					} elseif ($reqcnct == 'fam') {
						echo 'family';
					} elseif ($reqcnct == 'prof') {
						echo 'professional';
					} elseif ($reqcnct == 'edu') {
						echo 'education';
					} elseif ($reqcnct == 'aqu') {
						echo 'just met mee';
					}
					$i++;
				}
				if ($reqcncts_cttotal>=0) {
					echo ' stream'; if($reqcncts_cttotal>0){echo's';} echo' has been sent to <a href="'.$baseincpat.'meefile.php?id='.$sid.'" target = "_top">'; loadpersonnamenolink($sid); echo '</a>.';
				}
				
		} else {
			$notif = mysql_query("INSERT INTO notifications (u_id, type, sub, s_id, params, time_stamp) VALUES ('$sid', 'pcntresp', 'deny', '$id', '$params', NOW())");
			//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$sid' AND reqa_cnct='y' LIMIT 1"), 0)>0) {
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$sid' LIMIT 1"), 0);
							
							//params
							$subject = returnpersonnameasid($id, $sid).' has denied your connection request';
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $sid).'</a> has denied your request to connection through your ';
								$i = 0;
								$params_ary = explode(";", $params);
								$streamct = count($params_ary)-1;
								 foreach ($params_ary as $streamnm) {
									if ($streamnm != 'mb') {
										if (($i==$streamct)&&($i==1)) {
											$emailercontent .= ' and ';	
										} elseif (($i==$streamct)&&($i>0)) {
											$emailercontent .= ', and ';	
										} elseif ($i>0) {
											$emailercontent .= ', ';	
										}
										 if ($streamnm == 'frnd') {
											$emailercontent .= 'friends';
										} elseif ($streamnm == 'fam') {
											$emailercontent .= 'family';
										} elseif ($streamnm == 'prof') {
											$emailercontent .= 'professional';
										} elseif ($streamnm == 'edu') {
											$emailercontent .= 'education';
										} elseif ($streamnm == 'aqu') {
											$emailercontent .= 'just met mee';
										}
										$i++;
									}
								 }
								if ($i>1) {
									$emailercontent .= ' streams.';
								} else {
									$emailercontent .= ' stream.';
								}
							
							include('../../../externals/general/emailer.php');
						}
			echo 'You have denied <a href="'.$baseincpat.'meefile.php?id='.$sid.'" target = "_top">'; loadpersonnamenolink($sid); echo '</a>\'s request to connect.';
		}
		$delete = mysql_query("DELETE FROM requests WHERE r_id='$rid'");
	} elseif ($type=='invtevnt') {
		$einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
		if ($resp=='a') {
			$update = mysql_query("UPDATE event_owners SET rsvp='a' WHERE e_id='$refid' AND u_id='$id'");
			echo 'You are attending "<a href="'.$baseincpat.'event.php?id='.$refid.'" target = "_top">'.$einfo['name'].'</a>"';
			$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$sid', 'eiresp', '$id', '$refid', NOW())");
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$sid' AND reqa_eventi='y' LIMIT 1"), 0)>0) {
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$sid' LIMIT 1"), 0);
								
					//params
					$subject = returnpersonnameasid($id, $sid).' has accepted your invite to the event "'.$einfo['name'].'"';
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $sid).'</a> has accepted your invite to the event "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$einfo['name'].'</a>" and is attending.';
								
					include('../../../externals/general/emailer.php');
				}
		} elseif ($resp=='m') {
			$update = mysql_query("UPDATE event_owners SET rsvp='m' WHERE e_id='$refid' AND u_id='$id'");
			echo 'You might attend "<a href="'.$baseincpat.'event.php?id='.$refid.'" target = "_top">'.$einfo['name'].'</a>"';
			$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$sid', 'eiresp', '$id', '$refid', NOW())");
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$sid' AND reqa_eventi='y' LIMIT 1"), 0)>0) {
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$sid' LIMIT 1"), 0);
								
					//params
					$subject = returnpersonnameasid($id, $sid).' has accepted your invite to the event "'.$einfo['name'].'"';
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $sid).'</a> has accepted your invite to the event "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$einfo['name'].'</a>" and might attend.';
								
					include('../../../externals/general/emailer.php');
				}
		} elseif ($resp=='n') {
			$update = mysql_query("UPDATE event_owners SET rsvp='n' WHERE e_id='$refid' AND u_id='$id'");
			echo 'You are not attending "<a href="'.$baseincpat.'event.php?id='.$refid.'" target = "_top">'.$einfo['name'].'</a>"';
			$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$sid', 'eiresp', '$id', '$refid', NOW())");
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$sid' AND reqa_eventi='y' LIMIT 1"), 0)>0) {
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$sid' LIMIT 1"), 0);
								
					//params
					$subject = returnpersonnameasid($id, $sid).' has accepted your invite to the event "'.$einfo['name'].'"';
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $sid).'</a> has accepted your invite to the event "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$einfo['name'].'</a>" but is not attending.';
								
					include('../../../externals/general/emailer.php');
				}
		} elseif ($resp=='dny') {
			$delete = mysql_query("DELETE FROM event_owners WHERE e_id='$refid' AND u_id='$id'");
			echo 'You have denied your invite to the event "<a href="'.$baseincpat.'event.php?id='.$refid.'" target = "_top">'.$einfo['name'].'</a>"';
			$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$sid', 'eirespn', '$id', '$refid', NOW())");
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$sid' AND reqa_eventi='y' LIMIT 1"), 0)>0) {
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$sid' LIMIT 1"), 0);
								
					//params
					$subject = returnpersonnameasid($id, $sid).' has denied your invite to the event "'.$einfo['name'].'"';
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $sid).'</a> has denied your invite to the event "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$einfo['name'].'</a>"';
								
					include('../../../externals/general/emailer.php');
				}
		} else {
			$delete = mysql_query("DELETE FROM event_owners WHERE e_id='$refid' AND u_id='$id'");
			echo 'You have removed "<a href="'.$baseincpat.'event.php?id='.$refid.'" target = "_top">'.$einfo['name'].'</a>" from your events.';	
		}
		$delete = mysql_query("DELETE FROM requests WHERE r_id='$rid'");
	} elseif ($type=='invtcproj') {
		$cpinfo = mysql_fetch_array (mysql_query ("SELECT name, type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
		if ($resp=='a') {
			$update = mysql_query("INSERT INTO commproj_mem (u_id, cp_id, time_stamp) VALUES ('$id', '$refid', NOW())");
			echo 'You are now a supporter of "<a href="'.$baseincpat.'proj.php?id='.$refid.'" target = "_top">'.$cpinfo['name'].'</a>"';
			$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$sid', 'cpiresp', '$id', '$refid', NOW())");
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$sid' AND reqa_proji='y' LIMIT 1"), 0)>0) {
						
						//set correct community task type
						if ($cpinfo['type']=='bug') {
							$cpt_name = 'Meesto Bug';
						} else {
							$cpt_name = 'Meesto Community Project';
						}
					
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$sid' LIMIT 1"), 0);
								
					//params
					$subject = returnpersonnameasid($id, $sid).' has accepted your invite to the '.$cpt_name.' "'.$cpinfo['name'].'"';
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $sid).'</a> has accepted your invite to the '.$cpt_name.' "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$cpinfo['name'].'</a>"';
								
					include('../../../externals/general/emailer.php');
				}
		} else {
			echo 'You have removed your invite to support "<a href="'.$baseincpat.'proj.php?id='.$refid.'" target = "_top">'.$cpinfo['name'].'</a>"';
			$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$sid', 'cpirespn', '$id', '$refid', NOW())");
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$sid' AND reqa_proji='y' LIMIT 1"), 0)>0) {
					
						//set correct community task type
						if ($cpinfo['type']=='bug') {
							$cpt_name = 'Meesto Bug';
						} else {
							$cpt_name = 'Meesto Community Project';
						}
					
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$sid' LIMIT 1"), 0);
								
					//params
					$subject = returnpersonnameasid($id, $sid).' has denied your invite to the '.$cpt_name.' "'.$cpinfo['name'].'"';
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $sid).'</a> has denied your invite to the '.$cpt_name.' "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$cpinfo['name'].'</a>"';
								
					include('../../../externals/general/emailer.php');
				}
		}
		$delete = mysql_query("DELETE FROM requests WHERE r_id='$rid'");
	} elseif ($type=='rs') {
		if ($resp=='a') {
			$update = mysql_query("UPDATE meefile_basic SET status='$params', status_id='$id', status_status='' WHERE u_id='$sid'");
			$update = mysql_query("UPDATE meefile_basic SET status='$params', status_id='$sid', status_status='' WHERE u_id='$id'");
			echo 'You have confirmed <a href="'.$baseincpat.'meefile.php?id='.$sid.'" target="_top">'; loadpersonnamenolink($sid); echo '</a>\'s relationship status of "'.$params.'" with you. You status has also been changed.';
			$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, sub, params, time_stamp) VALUES ('$sid', 'rsresp', '$id', 'a', '$params', NOW())");
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$sid' AND reqa_rltnshp='y' LIMIT 1"), 0)>0) {
					
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$sid' LIMIT 1"), 0);
								
					//params
					$subject = returnpersonnameasid($id, $sid).' approved your relationship status request';
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $sid).'</a> approved your "'.$params.'" relationship status with '; if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$id' AND gender='male'"), 0)>0){$emailercontent .= 'his';}else{$emailercontent .= 'her';} $emailercontent .= '.';
								
					include('../../../externals/general/emailer.php');
				}
		} else {
			$update = mysql_query("UPDATE meefile_basic SET status_id='', status_status='' WHERE u_id='$sid'");
			echo 'You have denied <a href="'.$baseincpat.'meefile.php?id='.$sid.'" target="_top">'; loadpersonnamenolink($sid); echo '</a>\'s relationship status of "'.$params.'" with you. You status has also been changed.';
			$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, sub, params, time_stamp) VALUES ('$sid', 'rsresp', '$id', 'd', '$params', NOW())");
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$sid' AND reqa_rltnshp='y' LIMIT 1"), 0)>0) {
					
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$sid' LIMIT 1"), 0);
								
					//params
					$subject = returnpersonnameasid($id, $sid).' denied your relationship status request';
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $sid).'</a> denied your "'.$params.'" relationship status with '; if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$id' AND gender='male'"), 0)>0){$emailercontent .= 'his';}else{$emailercontent .= 'her';} $emailercontent .= '.';
								
					include('../../../externals/general/emailer.php');
				}
		}
		$delete = mysql_query("DELETE FROM requests WHERE r_id='$rid'");
	}
	

} else { //if not tab owner
	echo '<div align="left" valign="top" style="padding: 6px;">
		You don\'t own this request.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>