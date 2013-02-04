<?php
include ('../../../externals/header/header-pb.php');

$eid = escape_data($_GET['id']);
$einfo = mysql_fetch_array (mysql_query ("SELECT name, vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Message Attendees</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to message attendees of "'.$einfo['name'].'"</div>';

//test if can invite or if admin
if (($einfo['vis']=='pub')||($einfo['vis']=='privci')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {

if (isset($_POST['send'])) {
	
	$errors = NULL;
	
	if (isset($_POST['sndto']) && ($_POST['sndto'] != '')) {
		$sndto = escape_form_data($_POST['sndto']);
	} else {
		$errors[] = 'You must choose who you would like to send this to.';
	}
	
	if (isset($_POST['description']) && ($_POST['description'] != 'type a message here')) {
		$msg = escape_form_data($_POST['description']);
	} else {
		$errors[] = 'You cannot send a blank message.';
	}
	
	if (empty($errors)) {
		$createthread = mysql_query("INSERT INTO msg_threads (msg, ref_id, ref_type, time_stamp) VALUES ('$msg', '$eid', 'evntmsg', NOW())");
		$tid = mysql_insert_id();
		
		//make sender
		$sndr = mysql_query("INSERT INTO msg_owners (t_id, u_id, type, time_stamp) VALUES ('$tid', '$id', 's', NOW())");
		
		//make receiver and nofiy them
		if ($sndto=='all') {
			$mattnds = mysql_query ("SELECT u_id FROM event_owners WHERE e_id='$eid'");
		} elseif ($sndto=='nr') {
			$mattnds = mysql_query ("SELECT u_id FROM event_owners WHERE e_id='$eid' AND rsvp=''");
		} else {
			$mattnds = mysql_query ("SELECT u_id FROM event_owners WHERE e_id='$eid' AND rsvp='$sndto'");
		}
		while ($mattnd = mysql_fetch_array ($mattnds, MYSQL_ASSOC)) {
			$ruid = $mattnd['u_id'];
			if (($ruid!=$uid)&&($ruid!=$id)) {
				$sndr = mysql_query("INSERT INTO msg_owners (t_id, u_id, type, time_stamp) VALUES ('$tid', '$ruid', 'r', NOW())");
				//make notif
				$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$ruid', 'msg', '$id', '$tid', NOW())");
				$notifid = mysql_insert_id();
					//check to send email
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$ruid' AND msg='y' LIMIT 1"), 0)>0) {				
						//send email
						$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$ruid' LIMIT 1"), 0);
											
						//params
						$subject = returnpersonnameasid($id, $ruid).' set you a message about the event "'.$einfo['name'].'"';
						$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $ruid).'</a> set you <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a message</a> about the event "<a href="'.$baseincpat.'event.php?id='.$eid.'">'.$einfo['name'].'</a>".<br /><br />"'.escape_emailcont_data($_POST['description']).'"';
												
						include('../../../externals/general/emailer.php');
					}
			}
		}
		
		echo '<div align="center" class="p18">Your messages have been sent!</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

}
	
	echo '<form action="'.$baseincpat.'externalfiles/event/msgatndees.php?id='.$eid.'" method="post">
		<div align="left" style="padding-left: 16px;">
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">to</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<select id="sndto" name="sndto" onfocus="resizepb=window.clearInterval(resizepb);" onblur="resizepb=setInterval(\'resize_pb();\', 100);">
						<option value="all" style="font-size: 13px;" SELECTED>all attendees</option>';
						$types = array('a' => 'attending', 'm' => 'might attend', 'n' => 'not attending', 'nr' => 'have not replied');
						foreach ($types as $value => $name) {
							echo '<option value="'.$value.'" style="font-size: 13px;">'.$name.'</option>';
						}
					echo '</select>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">message</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<textarea name="description" cols="44" rows="3" onfocus="if (trim(this.value) == \'type a message here\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type a message here\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'5000\', \'infovertxtalrt\');"';
				if ($msg){echo'>'.$msg;}else{echo' class="inputplaceholder">type a message here';}
			echo '</textarea>
				<div id="infovertxtalrt" align="left" class="palert"></div>
				</td></tr></table>
			</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="send" name="send" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

} else { //if not event admin
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You are not allowed to invite peeple to this event.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>