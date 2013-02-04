<?php
require_once ('../../../externals/general/includepaths.php');
if (!isset($_POST['invite'])||(isset($_POST['invite'])&&(!isset($_POST['peeple'])))) {
	$pjs = '<script src="'.$baseincpat.'externalfiles/invite.js" type="text/javascript" charset="utf-8"></script>';
	$pdrjs = 'new Request.JSON({url: \''.$baseincpat.'externalfiles/autocompleter/grabmypeeple-inviter.php\', onSuccess: function(r){
					InviteSearch.setValues(r);
				}}).send();';
	$fullmts = true;
}
include ('../../../externals/header/header-pb.php');

$eid = escape_data($_GET['id']);
$einfo = mysql_fetch_array (mysql_query ("SELECT name, vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Invite</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to invite peeple to "'.$einfo['name'].'"</div>';

//test if can invite or if admin
if (($einfo['vis']=='pub')||($einfo['vis']=='privci')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {

if (isset($_POST['invite'])) {
	
	$errors = NULL;
	
	if (!isset($_POST['peeple'])) {
		$errors[] = 'No new peeple were selected.';
	}
	
	if (empty($errors)) {
		foreach ($_POST['peeple'] as $peepid) {
			$peepid = escape_data($peepid);
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$peepid'"), 0)==0) {
				$insert = mysql_query("INSERT INTO requests (u_id, type, s_id, ref_id, time_stamp) VALUES ('$peepid', 'invtevnt', '$id', '$eid', NOW())");
				$insertattendee = mysql_query("INSERT INTO event_owners (e_id, u_id, time_stamp) VALUES ('$eid', '$peepid', NOW())");
				//email notification !important
			}
		}
		
		echo '<div align="center" class="p18">Your invites have been sent.</div>
		<script type="text/javascript">
			setTimeout("parent.location.reload();", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

}
	
	echo '<form action="'.$baseincpat.'externalfiles/event/invite.php?id='.$eid.'" method="post">
		<div align="left" style="padding-left: 16px; padding-bottom: 12px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
				<div align="center" id="fltrchs" class="topfltrOn" style="width: 140px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltrslctd\').set(\'class\', \'topfltr\');showAll();">
					<div align="center" class="title" style="width: 140px;">choose</div>
					<div align="center" class="bar" style="width: 140px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 12px;">
				<div align="center" id="fltrslctd" class="topfltr" style="width: 140px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltrchs\').set(\'class\', \'topfltr\');showSelected();">
					<div align="center" class="title" style="width: 140px;">selected</div>
					<div align="center" class="bar" style="width: 140px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="right" valign="top" style="padding-top: 2px; padding-left: 72px;">
				<input type="text" id="msrch" name="msrch" size="30px" maxlength="60" onfocus="if (trim(this.value) == \'search\') {this.value=\'\';};this.className=\'inputfocus\'; $(\'fltrchs\').set(\'class\', \'topfltr\');$(\'fltrslctd\').set(\'class\', \'topfltr\');" onblur="if (trim(this.value) == \'\') {this.value=\'search\';this.className=\'inputplaceholder\'; $(\'fltrchs\').set(\'class\', \'topfltrOn\'); showAll();} else {this.className=\'inputplaceholderblur\';}" onkeyup="if(trim(this.value)!=\'search\'){InviteSearch.filter(this.value);} if(trim(this.value)==\'\'){$(\'fltrchs\').set(\'class\', \'topfltrOn\');}else{$(\'fltrchs\').set(\'class\', \'topfltr\');}" class="inputplaceholder" value="search"/>
			</td></tr></table>
		</div>
		
		<div align="left" id="peeparea" style="padding-left: 16px; padding-bottom: 12px; height: 130px; width: 644px; overflow-x: none; overflow-y: scroll;"">';
			$peeple = mysql_query ("SELECT mp.p_id, u.defaultimg_url FROM my_peeple mp INNER JOIN users u ON mp.p_id = u.user_id WHERE mp.u_id='$id' ORDER BY u.last_name ASC");
			while ($person = mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
				$pid = $person['p_id'];
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$pid' LIMIT 1"), 0)>0) {
					echo '<div align="left" id="peep'.$pid.'" class="peepchsrblkHide" style="float: left; width: 150px; margin: 4px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
							<img src="'.$baseincpat.''.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" />
						</td><td align="left" valign="top" style="padding-left: 4px;">
							<div align="left">';
								loadpersonnamenolink($pid);
							echo '</div><div align="left" style="padding: 2px; visibility: hidden;">
								<input type="checkbox" id="peepchk'.$pid.'" name="peeple['.$pid.']" value="'.$pid.'"/>
							</div>
						</td></tr></table>
					</div>';
				} else {
					echo '<div align="left" id="peep'.$pid.'" class="peepchsrblk" style="float: left; width: 150px; margin: 4px;" onclick="if($(\'peepchk'.$pid.'\').get(\'checked\') == false){$(\'peepchk'.$pid.'\').set(\'checked\',true); $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblkOn\');}else{$(\'peepchk'.$pid.'\').set(\'checked\',false);  $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblk\');}">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
							<img src="'.$baseincpat.''.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" />
						</td><td align="left" valign="top" style="padding-left: 4px;">
							<div align="left">';
								loadpersonnamenolink($pid);
							echo '</div><div align="left" style="padding: 2px;">
								<input type="checkbox" id="peepchk'.$pid.'" name="peeple['.$pid.']" value="'.$pid.'" onclick="if($(\'peepchk'.$pid.'\').get(\'checked\') == false){$(\'peepchk'.$pid.'\').set(\'checked\',true); $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblkOn\');}else{$(\'peepchk'.$pid.'\').set(\'checked\',false);  $(\'peep'.$pid.'\').set(\'class\', \'peepchsrblk\');}"/>
							</div>
						</td></tr></table>
					</div>';
				}
			}
		echo '</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="invite" name="invite" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
	</form>';

} else { //if not event admin
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You are not allowed to invite peeple to this event.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>