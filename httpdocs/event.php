<?php
require_once('../externals/sessions/db_sessions.inc.php');
require_once ('../externals/general/includepaths.php');
require_once ('../externals/general/functions.php');

$eid = escape_data($_GET['id']);
$einfo = mysql_fetch_array (mysql_query ("SELECT name, defaultimg_url, location, about, ntk, wtb, vis, DATE_FORMAT(start_date, '%b %D, %Y at %l:%i%p') AS start, DATE_FORMAT(end_date, '%b %D, %Y at %l:%i%p') AS end FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
if (isset($_SESSION['user_id'])) {
	$id = $_SESSION['user_id'];
} else {
	$id = 0;
}

//test vis
if (($einfo['vis']=='pub')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0)) {
	
$title = $einfo['name'];
$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/event/grabpas.php?eid='.$eid.'&\');';
include ('../externals/header/header.php');

//main content
echo '<div align="left" valign="top" style="margin-left: 72px; width: 928px; margin-bottom: 6px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top"><span style="font-size: 42px;">'.$title.' </span><span class="subtext">| '; if(substr($einfo['vis'], 0, 4)=='priv'){echo'private';}else{echo'public';} echo' event</span></td>';
	//test if can invite or if admin
	if (($einfo['vis']=='pub')||($einfo['vis']=='privci')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
		echo '<td align="right" valign="center" style="padding-left: 32px;">
			<input type="button" value="invite" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/invite.php?id='.$eid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
		</td>';
	}
	//test if admin
	if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {
		echo '<td align="right" valign="center" style="padding-left: 12px;">
			<input type="button" value="message attendees" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/msgatndees.php?id='.$eid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});" style="padding-left: 8px; padding-right: 8px;"/>
		</td>';	
	}
echo '</tr></table></div>
<div align="left" style="margin-left: 72px; width: 928px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
		<div style="width: 186px; height: 140px; background-color: #C5C5C5;"><img src="'.$baseincpat.''.$einfo['defaultimg_url'].'"/></div>
		<div style="border-right: 2px solid #C5C5C5; padding-top: 12px; padding-bottom: 84px;">';
			//test if admin
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {
				echo '<div align="left" style="margin-left: 12px; margin-bottom: 12px;">
					<input type="button" value="edit event photo" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
				</div><div align="left" style="margin-left: 12px; margin-bottom: 12px;">
					<input type="button" value="edit event" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/cal/editevent.php?id='.$eid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
				</div><div align="left" style="margin-left: 12px; margin-bottom: 12px;">
					<input type="button" value="edit attendees" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/editattendees.php?id='.$eid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
				</div>';
			}
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0) {
				echo '<div align="left" style="margin-left: 12px; margin-bottom: 12px;">
					<input type="button" value="remove event" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/removeevent.php?id='.$eid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
				</div>';
			}
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0) {
				$eoinfo = mysql_fetch_array (mysql_query ("SELECT rsvp FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), MYSQL_ASSOC);
				echo '<div align="left" class="p24" style="padding-top: 6px;">My RSVP</div><div align="left" id="rsvpchngmain" style="padding-top: 2px; margin-left: 12px;">
						<select id="chngrsvp'.$eid.'" onchange="if((this.value!=\''.$eoinfo['rsvp'].'\')&&(this.value!=\'\')){$(\'savenewrsvpbtn\').set(\'styles\',{\'display\':\'block\'});}else{$(\'savenewrsvpbtn\').set(\'styles\',{\'display\':\'none\'});}">'; 
						$rsvps = array('a' => 'You are attending.', 'm' => 'You might attend.', 'n' => 'You aren\'t attending.');
						if ($eoinfo['rsvp']=='') {
							echo '<option value="" style="font-size: 13px;" SELECTED>choose...</option>';
						}
						foreach ($rsvps as $rsvp => $rsvpname) { 
							if ($rsvp == $eoinfo['rsvp']) {
								echo '<option value="'.$rsvp.'" style="font-size: 13px;" SELECTED>'.$rsvpname.'</option>';
							} else {
								echo '<option value="'.$rsvp.'" style="font-size: 13px;">'.$rsvpname.'</option>';
							}
						} 
					echo '</select>
				</div><div align="left" style="margin-left: 12px; margin-top: 4px; display: none;"></div><div align="left" id="savenewrsvpbtn" style="margin-left: 12px; padding-top: 6px; display: none;">
					<input type="button" value="save new rsvp" onclick="$(\'savenewrsvpbtn\').set(\'styles\',{\'display\':\'none\'});gotopage(\'rsvpchngmain\', \''.$baseincpat.'externalfiles/event/chngrsvp.php?id='.$eid.'&r=\'+$(\'chngrsvp'.$eid.'\').get(\'value\')); (function(){ $(\'newrsvpstat\').destroy();gotopage(\'attendeelistarea\', \''.$baseincpat.'externalfiles/event/grabattendeesidelist.php?id='.$eid.'\'); }).delay(2000);
"/>
				</div>';
			}
			echo '<div align="left" id="attendeelistarea" style="width: 184px;">';
				include('externalfiles/event/grabattendeesidelist.php');
			echo '</div>
		</div>
	</td><td align="left" valign="top" width="716px" style="padding-left: 18px;">
		<div align="left" class="p24"';
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {echo' onmouseover="$(\'infoeditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'infoeditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';}
			echo '>
			<table cellpadding="0" cellspacing="0" width="690px"><tr><td align="left" valign="top">Information</td><td align="right" valign="bottom">';
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {echo'<div id="infoeditbtn" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="edit info" onclick="$(\'infoeditbtn\').set(\'styles\',{\'display\':\'none\'});gotopage(\'infomain\', \''.$baseincpat.'externalfiles/event/editinfo.php?id='.$eid.'\');"/></div>';}
			echo '</td></tr></table>
		</div>
		<div align="left" id="infomain" class="paragraph" style="padding-left: 30px; padding-top: 18px;"';
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {echo' onmouseover="$(\'infoeditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'infoeditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';}
			echo '>';
			include ('externalfiles/event/grabinfo.php');
		echo '</div>
		<div align="left" style="padding-top: 36px;"><span class="p24">Photo Albums</div>
		<div id="maincontent" style="width: 690px; margin-top: 12px; padding-left: 30px;"adsfasdf>';
			include ('externalfiles/event/grabpas.php');
		echo '</div>
		<div align="left" style="padding-top: 36px;"><span class="p24">Comments </span><span class="subtext">('; if(substr($einfo['vis'], 0, 4)=='priv'){echo'This is visible to everyone invited to this event.';}else{echo'Because this is a public event, this is visible to everyone &mdash; it\'s public.';} echo')</span></div>
		<div  id="cmtsmain" style="width: 690px; margin-top: 12px; padding-left: 30px;">';
			include ('externalfiles/event/grabcmts.php');
		echo '</div>
	</td></tr></table>
</div>';

} else { //unable to view private event

	$title = 'Private Event';
	include ('../externals/header/header.php');
	
	echo '<div align="left" valign="top" style="padding: 24px;">
		This is a private event. You must be invited to be able to view it.
	</div>';
}

include ('../externals/header/footer.php');
?>
