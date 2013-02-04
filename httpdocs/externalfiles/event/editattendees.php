<?php
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');
$eid = escape_data($_GET['id']);
if (!isset($_POST['save'])) {
	$pjs = '<script src="'.$baseincpat.'externalfiles/event/editatndee.js" type="text/javascript" charset="utf-8"></script>';
	$pdrjs = 'new Request.JSON({url: \''.$baseincpat.'externalfiles/event/editatndee-search.php?id='.$eid.'\', onSuccess: function(r){
					PeepSearch.setValues(r);
				}}).send();';
	$fullmts = true;
}
include ('../../../externals/header/header-pb.php');	
$einfo = mysql_fetch_array (mysql_query ("SELECT name, vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit Attendees</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to set attendees admin ability or remove attendees <span class="subtext" style="font-size: 13px;">(by clicking the "[x]")</span>.</div>';

//test if can invite or if admin
if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {
	
if (isset($_POST['save'])) {
	
	$errors = NULL;
	
	if (empty($errors)) {
		//check for peeple to delete
		if (isset($_POST['peepdlt'])) {
			foreach ($_POST['peepdlt'] as $peepdltid) {
				$peepdltid = escape_data($peepdltid);
				$delete = mysql_query("DELETE FROM event_owners WHERE e_id='$eid' AND u_id='$peepdltid'");
				$delete = mysql_query("DELETE FROM requests WHERE u_id='$peepdltid' AND type='invtevnt' AND ref_id='$eid'");
			}
		}
		
		//change admin ability
		if (isset($_POST['peeple'])) {
			foreach ($_POST['peeple'] as $peepid) {
				$peepid = escape_data($peepid);
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$peepid'"), 0)>0) {
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$peepid' AND type='a'"), 0)==0) {
						$notif = mysql_query("INSERT INTO notifications (u_id, type, ref_id, time_stamp) VALUES ('$peepid', 'evntadm', '$eid', NOW())");
							//check to send email
							if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$peepid' AND mkadmin_event='y' LIMIT 1"), 0)>0) {
								//send email
								$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$peepid' LIMIT 1"), 0);
								
								//params
								$subject = 'You are now an admin of the event "'.$einfo['name'].'"';
								$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $peepid).'</a> has made you an admin of the event "<a href="'.$baseincpat.'event.php?id='.$eid.'">'.$einfo['name'].'</a>"';
								
								include('../../../externals/general/emailer.php');
							}
					}
					$update = mysql_query("UPDATE event_owners SET type='a' WHERE e_id='$eid' AND u_id='$peepid' ");	
				}
			}
		}
		$eos = mysql_query ("SELECT u_id FROM event_owners WHERE e_id='$eid' AND u_id!='$id'");
		while ($eo = @mysql_fetch_array ($eos, MYSQL_ASSOC)) {
			if (!@in_array($eo['u_id'], $_POST['peeple'])) {
				$eouid = $eo['u_id'];
				$update = mysql_query("UPDATE event_owners SET type=NULL WHERE e_id='$eid' AND u_id='$eouid'");
				$notif = mysql_query("INSERT INTO notifications (u_id, type, ref_id, time_stamp) VALUES ('$eouid', 'evntadmr', '$eid', NOW())");
							//check to send email
							if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$eouid' AND mkadmin_event='y' LIMIT 1"), 0)>0) {
								//send email
								$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$eouid' LIMIT 1"), 0);
								
								//params
								$subject = 'You are no longer an admin of the event "'.$einfo['name'].'"';
								$emailercontent = 'You are no longer an admin of the event "<a href="'.$baseincpat.'event.php?id='.$eid.'">'.$einfo['name'].'</a>"';
								
								include('../../../externals/general/emailer.php');
							}
			}
		}
		
		echo '<div align="center" class="p18">Your attendees have been saved.</div>
		<script type="text/javascript">
			setTimeout("';
			if (isset($_POST['peepdlt'])) {
				echo 'parent.location.reload();';
			} else {
				echo 'parent.PopBox.close();';
			}
			echo '", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

}
	
	echo '<form action="'.$baseincpat.'externalfiles/event/editattendees.php?id='.$eid.'" method="post">
		<div align="left" style="padding-left: 16px; padding-bottom: 12px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
				<div align="center" id="fltrchs" class="topfltrOn" style="width: 140px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltrslctd\').set(\'class\', \'topfltr\');showAll();">
					<div align="center" class="title" style="width: 140px;">all attendees</div>
					<div align="center" class="bar" style="width: 140px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 12px;">
				<div align="center" id="fltrslctd" class="topfltr" style="width: 140px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltrchs\').set(\'class\', \'topfltr\');showSelected();">
					<div align="center" class="title" style="width: 140px;">admins</div>
					<div align="center" class="bar" style="width: 140px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="right" valign="top" style="padding-top: 2px; padding-left: 72px;">
				<input type="text" id="msrch" name="msrch" size="30px" maxlength="60" onfocus="if (trim(this.value) == \'search\') {this.value=\'\';};this.className=\'inputfocus\'; $(\'fltrchs\').set(\'class\', \'topfltr\');$(\'fltrslctd\').set(\'class\', \'topfltr\');" onblur="if (trim(this.value) == \'\') {this.value=\'search\';this.className=\'inputplaceholder\'; $(\'fltrchs\').set(\'class\', \'topfltrOn\'); showAll();} else {this.className=\'inputplaceholderblur\';}" onkeyup="if(trim(this.value)!=\'search\'){PeepSearch.filter(this.value);} if(trim(this.value)==\'\'){$(\'fltrchs\').set(\'class\', \'topfltrOn\');}else{$(\'fltrchs\').set(\'class\', \'topfltr\');}" class="inputplaceholder" value="search"/>
			</td></tr></table>
		</div>
		
		<div align="left" id="peeparea" style="padding-left: 16px; padding-bottom: 12px; height: 130px; width: 644px; overflow-x: none; overflow-y: scroll;"">';
			$peeple = mysql_query ("SELECT eo.u_id, eo.type, u.defaultimg_url FROM event_owners eo INNER JOIN users u ON eo.u_id=u.user_id WHERE eo.e_id='$eid' AND eo.u_id!='$id' ORDER BY u.last_name ASC");
			while ($person = mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
				$uid = $person['u_id'];
				echo '<div align="left" id="peep'.$uid.'" style="padding-right: 12px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top"><div style="cursor: pointer;" onclick="if($(\'peep'.$uid.'inr\').get(\'class\')==\'peepchsrblkHide\'){$(\'peep'.$uid.'inr\').set(\'class\', \'peepchsrblk\');$(\'peepdlt'.$uid.'\').destroy();checkForDelete();}else{$(\'peep'.$uid.'inr\').set(\'class\', \'peepchsrblkHide\'); var newElem = new Element(\'input\', {\'type\': \'hidden\', \'id\': \'peepdlt'.$uid.'\', \'class\': \'tobedeleted\', \'name\': \'peepdlt['.$uid.']\', \'value\': \''.$uid.'\'});newElem.inject($(\'peep'.$uid.'frmelms\'), \'bottom\'); $(\'rmvatndealrt\').set(\'styles\',{\'display\':\'block\'});}">[x]</div></td><td align="left" valign="top">
				<div align="left" id="peep'.$uid.'inr" class="peepchsrblk'; if($person['type']=='a'){echo'On';} echo'" style="float: left; width: 150px; margin: 4px;" onclick="if($(\'peepdlt'.$uid.'\')){$(\'peepdlt'.$uid.'\').destroy();checkForDelete();} if($(\'peepchk'.$uid.'\').get(\'checked\') == false){$(\'peepchk'.$uid.'\').set(\'checked\',true); $(\'peep'.$uid.'inr\').set(\'class\', \'peepchsrblkOn\');$(\'admstat'.$uid.'\').set(\'styles\',{\'display\':\'block\'});}else{$(\'peepchk'.$uid.'\').set(\'checked\',false);  $(\'peep'.$uid.'inr\').set(\'class\', \'peepchsrblk\');$(\'admstat'.$uid.'\').set(\'styles\',{\'display\':\'none\'});}">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
						<img src="'.$baseincpat.''.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" />
					</td><td align="left" valign="top" style="padding-left: 4px;">
						<div align="left">';
							loadpersonnamenolink($uid);
						echo '</div><div align="left" id="peep'.$uid.'frmelms" style="padding: 2px;">
							<div align="left"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="peepchk'.$uid.'" name="peeple['.$uid.']" value="'.$uid.'" onclick="if($(\'peepdlt'.$uid.'\')){$(\'peepdlt'.$uid.'\').destroy();checkForDelete();} if($(\'peepchk'.$uid.'\').get(\'checked\') == false){$(\'peepchk'.$uid.'\').set(\'checked\',true); $(\'peep'.$uid.'inr\').set(\'class\', \'peepchsrblkOn\');$(\'admstat'.$uid.'\').set(\'styles\',{\'display\':\'block\'});}else{$(\'peepchk'.$uid.'\').set(\'checked\',false);  $(\'peep'.$uid.'inr\').set(\'class\', \'peepchsrblk\');$(\'admstat'.$uid.'\').set(\'styles\',{\'display\':\'none\'});}" '; if($person['type']=='a'){echo' CHECKED';} echo'/></td><td align="left" valign="center" id="admstat'.$uid.'" style="padding-left: 4px; font-size: 13px;'; if($person['type']!='a'){echo' display: none;';} echo'">is admin</td></tr></table></div>
						</div>
					</td></tr></table>
				</div>
				</td></tr></table></div>';
			}
		echo '</div>
		
		<div align="center" id="sbmtbtns" style="padding-top: 8px;">
			<div align="center" id="rmvatndealrt" style="padding-top: 8px; display: none;">
			You have chosen to remove some attendees, are you sure you want to remove them?<br /><span class="subtext" style="font-size: 13px;">(They have been dimmed so you can review them.)</span>
			</div><div align="center" style="padding-top: 8px;">
				<input type="submit" id="submit" class="end" value="save" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
	</form>';

} else { //if not able to view
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You can\'t view this event.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>