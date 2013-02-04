<?php
$fullmts = true;
include ('../../../externals/header/header-pb.php');

$t = escape_data($_GET['t']);

if ($t=='peepcnct') {
	$tname = 'Peeple Connection Requests';
} elseif ($t=='invtevnt') {
	$tname = 'Event Invites';
} elseif ($t=='invtcproj') {
	$tname = 'Community Project Invites';
} elseif ($t=='rs') {
	$tname = 'Relationship Confirmation Requests';
} else {
	$tname = $t;
}

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">View '.$tname.'</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to view and respond to '.strtolower($tname).'.</div>
<div align="left" style="width: 640px;  margin-left: 18px;">';
	
	if ($t=='peepcnct') {
		$notifs = mysql_query ("SELECT r_id, s_id, params, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM requests WHERE u_id='$id' AND type='peepcnct' ORDER BY time_stamp DESC");
		while ($notif = mysql_fetch_array ($notifs, MYSQL_ASSOC)) {
			$rid = $notif['r_id'];
			$sid = $notif['s_id'];
			$params = $notif['params'];
				$params = explode(";", $params);
				$params_ct = count($params);
			echo '<div align="left" id="r'.$rid.'" style="padding-bottom: 24px;">
				<div align="left">
				<div align="left"><a href="'.$baseincpat.'meefile.php?id='.$sid.'" target="_top">'; loadpersonnamenolink($sid); echo '</a> would like to connect with you through the selected stream'; if($params_ct>1){echo's';} echo'...</div>
				<div align="center" style="padding-top: 6px; padding-bottom: 2px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" style="'; if (mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='frnd' LIMIT 1"), 0)>0){echo' display: none;';} echo'">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis_frnd\').get(\'checked\') == false){$(\'streamvis_frnd\').set(\'checked\',true);}else{$(\'streamvis_frnd\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis_frnd" name="streamvis_frnd" value="frnd" onclick="if($(\'streamvis_frnd\').get(\'checked\') == false){$(\'streamvis_frnd\').set(\'checked\',true);}else{$(\'streamvis_frnd\').set(\'checked\',false);}"'; if(in_array('frnd', $params)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">friends</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;'; if (mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='fam' LIMIT 1"), 0)>0){echo' display: none;';} echo'">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis_fam\').get(\'checked\') == false){$(\'streamvis_fam\').set(\'checked\',true);}else{$(\'streamvis_fam\').set(\'checked\',false);}""><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis_fam" name="streamvis_fam" value="fam" onclick="if($(\'streamvis_fam\').get(\'checked\') == false){$(\'streamvis_fam\').set(\'checked\',true);}else{$(\'streamvis_fam\').set(\'checked\',false);}"'; if(in_array('fam', $params)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">family</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;'; if (mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='prof' LIMIT 1"), 0)>0){echo' display: none;';} echo'">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis_prof\').get(\'checked\') == false){$(\'streamvis_prof\').set(\'checked\',true);}else{$(\'streamvis_prof\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis_prof" name="streamvis_prof" value="prof" onclick="if($(\'streamvis_prof\').get(\'checked\') == false){$(\'streamvis_prof\').set(\'checked\',true);}else{$(\'streamvis_prof\').set(\'checked\',false);}"'; if(in_array('prof', $params)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">professional</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px'; if (mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='edu' LIMIT 1"), 0)>0){echo' display: none;';} echo';">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis_edu\').get(\'checked\') == false){$(\'streamvis_edu\').set(\'checked\',true);}else{$(\'streamvis_edu\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis_edu" name="streamvis_edu" value="edu" onclick="if($(\'streamvis_edu\').get(\'checked\') == false){$(\'streamvis_edu\').set(\'checked\',true);}else{$(\'streamvis_edu\').set(\'checked\',false);}"'; if(in_array('edu', $params)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">education</td></tr></table>
					</td><td align="left" valign="center" style="padding-left: 12px;'; if (mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='aqu' LIMIT 1"), 0)>0){echo' display: none;';} echo'">
						<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis_aqu\').get(\'checked\') == false){$(\'streamvis_aqu\').set(\'checked\',true);}else{$(\'streamvis_aqu\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis_aqu" name="streamvis_aqu" value="aqu" onclick="if($(\'streamvis_aqu\').get(\'checked\') == false){$(\'streamvis_aqu\').set(\'checked\',true);}else{$(\'streamvis_aqu\').set(\'checked\',false);}"'; if(in_array('aqu', $params)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">just met mee</td></tr></table>
					</td></tr></table></div>
				</div>
				<div align="center" style="margin-top: 6px;'; if (mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams WHERE u_id='$id' AND p_id='$sid' AND stream='mb' LIMIT 1"), 0)>0){echo'display: none;';} echo'">
					<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis_mb\').get(\'checked\') == false){$(\'streamvis_mb\').set(\'checked\',true);}else{$(\'streamvis_mb\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis_mb" name="streamvis_mb" value="mb" onclick="if($(\'streamvis_mb\').get(\'checked\') == false){$(\'streamvis_mb\').set(\'checked\',true);}else{$(\'streamvis_mb\').set(\'checked\',false);}"></td><td align="left" style="padding-left: 4px;">also add to my bubble <span class="subtext" style="font-size: 14px;">(wont require aproval)</span></td></tr></table>
				</div>
				<div id="loader'.$rid.'" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
				<div id="submitbtns'.$rid.'" align="center" style="padding-top: 8px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left">
						<input type="button" id="accept" value="accept selected" onclick="$(\'submitbtns'.$rid.'\').set(\'styles\',{\'display\':\'none\'});$(\'loader'.$rid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'r'.$rid.'\', \''.$baseincpat.'externalfiles/po/reqresp.php?rid='.$rid.'&resp=a&mb=\'+$(\'streamvis_mb\').get(\'checked\')+\'&frnd=\'+$(\'streamvis_frnd\').get(\'checked\')+\'&fam=\'+$(\'streamvis_fam\').get(\'checked\')+\'&prof=\'+$(\'streamvis_prof\').get(\'checked\')+\'&edu=\'+$(\'streamvis_edu\').get(\'checked\')+\'&aqu=\'+$(\'streamvis_aqu\').get(\'checked\'));"/>
					</td><td align="left" style="padding-left: 12px;">
						<input type="button" id="deny" value="deny" onclick="$(\'submitbtns'.$rid.'\').set(\'styles\',{\'display\':\'none\'});$(\'loader'.$rid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'r'.$rid.'\', \''.$baseincpat.'externalfiles/po/reqresp.php?rid='.$rid.'&resp=d\');"/>
					</td></tr></table>
				</div>
			</div>';
		}
	} elseif ($t=='invtevnt') {
		date_default_timezone_set('America/Los_Angeles');
		$notifs = mysql_query ("SELECT r_id, type, s_id, sub, params, ref_id, xref_id, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM requests WHERE u_id='$id' AND type='invtevnt' ORDER BY time_stamp DESC");
		while ($notif = mysql_fetch_array ($notifs, MYSQL_ASSOC)) {
			$rid = $notif['r_id'];
			$sid = $notif['s_id'];
			$refid = $notif['ref_id'];
			$xrefid = $notif['xref_id'];
			$einfo = mysql_fetch_array (mysql_query ("SELECT name, vis, DATE_FORMAT(start_date, '%b %D, %Y at %l:%i%p') AS start, DATE_FORMAT(end_date, '%b %D, %Y at %l:%i%p') AS end FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
			echo '<div align="left" id="r'.$rid.'" style="padding-bottom: 24px;">
				<div align="left"><a href="'.$baseincpat.'meefile.php?id='.$sid.'" target="_top">'; loadpersonnamenolink($sid); echo '</a> invited you to "<a href="'.$baseincpat.'event.php?id='.$refid.'" target="_top">'.$einfo['name'].'</a>" which takes place '; 
					if (substr($einfo['start'], -7)=='12:00AM') {
						$systerdy = strtotime("-1 day", strtotime(trim(substr($einfo['start'], 0, 14))));
						$sdate = date("M jS, Y", $systerdy);
						$einfo['start'] = $sdate.' at Midnight';
					} elseif (substr($einfo['start'], -7)=='12:00PM') {
						$einfo['start'] = trim(substr($einfo['start'], 0, 14)).' at Noon';
					}
					if (substr($einfo['end'], -7)=='12:00AM') {
						$eysterdy = strtotime("-1 day", strtotime(trim(substr($einfo['end'], 0, 14))));
						$edate = date("M jS, Y", $eysterdy);
						$einfo['end'] = $edate.' at Midnight';
					} elseif (substr($einfo['end'], -7)=='12:00PM') {
						$einfo['end'] = trim(substr($einfo['end'], 0, 14)).' at Noon';
					}
					if(substr($einfo['start'], 0, 14)==substr($einfo['end'], 0, 14)){echo $einfo['start'].' until '.trim(substr($einfo['end'], 16));}else{echo $einfo['start'].' to '.$einfo['end'];} 
				echo'. This is a  '; if(substr($einfo['vis'], 0, 4)=='priv'){echo'private';}else{echo'public';} echo' event.</div>
				<div id="loader'.$rid.'" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
				<div id="submitbtns'.$rid.'" align="center" style="padding-top: 8px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left">
						<input type="button" id="attend" value="attend" onclick="$(\'submitbtns'.$rid.'\').set(\'styles\',{\'display\':\'none\'});$(\'loader'.$rid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'r'.$rid.'\', \''.$baseincpat.'externalfiles/po/reqresp.php?rid='.$rid.'&resp=a\');"/>
					</td><td align="left" style="padding-left: 12px;">
						<input type="button" id="might_attend" value="might attend" onclick="$(\'submitbtns'.$rid.'\').set(\'styles\',{\'display\':\'none\'});$(\'loader'.$rid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'r'.$rid.'\', \''.$baseincpat.'externalfiles/po/reqresp.php?rid='.$rid.'&resp=m\');"/>
					</td><td align="left" style="padding-left: 12px;">
						<input type="button" id="cant_attend" value="can\'t attend" onclick="$(\'submitbtns'.$rid.'\').set(\'styles\',{\'display\':\'none\'});$(\'loader'.$rid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'r'.$rid.'\', \''.$baseincpat.'externalfiles/po/reqresp.php?rid='.$rid.'&resp=n\');"/>
					</td><td align="left" style="padding-left: 12px;">
						<input type="button" id="deny" value="deny" onclick="$(\'submitbtns'.$rid.'\').set(\'styles\',{\'display\':\'none\'});$(\'loader'.$rid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'r'.$rid.'\', \''.$baseincpat.'externalfiles/po/reqresp.php?rid='.$rid.'&resp=dny\');"/>
					</td><td align="left" style="padding-left: 12px;">
						<input type="button" id="remove" value="remove invite" onclick="$(\'submitbtns'.$rid.'\').set(\'styles\',{\'display\':\'none\'});$(\'loader'.$rid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'r'.$rid.'\', \''.$baseincpat.'externalfiles/po/reqresp.php?rid='.$rid.'&resp=rmv\');"/>
					</td></tr></table>
				</div>
			</div>';
		}
	} elseif ($t=='invtcproj') {
		$notifs = mysql_query ("SELECT r_id, type, s_id, sub, params, ref_id, xref_id, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM requests WHERE u_id='$id' AND type='invtcproj' ORDER BY time_stamp DESC");
		while ($notif = mysql_fetch_array ($notifs, MYSQL_ASSOC)) {
			$rid = $notif['r_id'];
			$sid = $notif['s_id'];
			$refid = $notif['ref_id'];
			$cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
			echo '<div align="left" id="r'.$rid.'" style="padding-bottom: 24px;">
				<div align="left"><a href="'.$baseincpat.'meefile.php?id='.$sid.'" target="_top">'; loadpersonnamenolink($sid); echo '</a> invited you to support "<a href="'.$baseincpat.'proj.php?id='.$refid.'" target="_top">'.$cpinfo['name'].'</a>"</div>
				<div id="loader'.$rid.'" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
				<div id="submitbtns'.$rid.'" align="center" style="padding-top: 8px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left">
						<input type="button" id="accept" value="accept" onclick="$(\'submitbtns'.$rid.'\').set(\'styles\',{\'display\':\'none\'});$(\'loader'.$rid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'r'.$rid.'\', \''.$baseincpat.'externalfiles/po/reqresp.php?rid='.$rid.'&resp=a\');"/>
					</td><td align="left" style="padding-left: 12px;">
						<input type="button" id="remove" value="remove invite" onclick="$(\'submitbtns'.$rid.'\').set(\'styles\',{\'display\':\'none\'});$(\'loader'.$rid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'r'.$rid.'\', \''.$baseincpat.'externalfiles/po/reqresp.php?rid='.$rid.'&resp=rmv\');"/>
					</td></tr></table>
				</div>
			</div>';
		}
	} elseif ($t=='rs') {
		$notifs = mysql_query ("SELECT r_id, type, s_id, sub, params, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM requests WHERE u_id='$id' AND type='rs' ORDER BY time_stamp DESC");
		while ($notif = mysql_fetch_array ($notifs, MYSQL_ASSOC)) {
			$rid = $notif['r_id'];
			$sid = $notif['s_id'];
			$params = $notif['params'];
			echo '<div align="left" id="r'.$rid.'" style="padding-bottom: 24px;">
				<div align="left"><a href="'.$baseincpat.'meefile.php?id='.$sid.'" target="_top">'; loadpersonnamenolink($sid); echo '</a> has changed '; if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$sid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} echo' relationship status to "'.$params.'" with you. Please confirm or deny this.</div>
				<div id="loader'.$rid.'" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
				<div id="submitbtns'.$rid.'" align="center" style="padding-top: 8px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left">
						<input type="button" id="accept" value="confirm" onclick="$(\'submitbtns'.$rid.'\').set(\'styles\',{\'display\':\'none\'});$(\'loader'.$rid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'r'.$rid.'\', \''.$baseincpat.'externalfiles/po/reqresp.php?rid='.$rid.'&resp=a\');"/>
					</td><td align="left" style="padding-left: 12px;">
						<input type="button" id="remove" value="deny" onclick="$(\'submitbtns'.$rid.'\').set(\'styles\',{\'display\':\'none\'});$(\'loader'.$rid.'\').set(\'styles\',{\'display\':\'block\'});gotopage(\'r'.$rid.'\', \''.$baseincpat.'externalfiles/po/reqresp.php?rid='.$rid.'&resp=d\');"/>
					</td></tr></table>
				</div>
			</div>';
		}
	}

echo'</div>';
include ('../../../externals/header/footer-pb.php');
?>