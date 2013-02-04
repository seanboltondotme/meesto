<?php
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');
$eid = escape_data($_GET['id']);
$pjs = '<script src="'.$baseincpat.'externalfiles/event/atndeeview.js" type="text/javascript" charset="utf-8"></script>';
$pdrjs = 'new Request.JSON({url: \''.$baseincpat.'externalfiles/event/atndeeview-search.php?id='.$eid.'\', onSuccess: function(r){
				PeepSearch.setValues(r);
			}}).send();';
$fullmts = true;
include ('../../../externals/header/header-pb.php');

if (isset($_GET['fltr'])) {
	$fltr = escape_data($_GET['fltr']);
} else {
	$fltr = 'a';
}	
$einfo = mysql_fetch_array (mysql_query ("SELECT name, vis FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">View Event Attendees</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to view attendees of "'.$einfo['name'].'"</div>';

//test if can invite or if admin
if (($einfo['vis']=='pub')||(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0)) {
	
	echo '<div align="left" style="padding-left: 16px; padding-bottom: 12px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
				<div align="center" id="fltra" class="topfltr'; if($fltr=='a'){echo'On';} echo'" style="width: 120px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltrm\').set(\'class\', \'topfltr\');$(\'fltrn\').set(\'class\', \'topfltr\');$$(\'#peeparea div.subtext\').set(\'styles\',{\'display\':\'none\'});showA();">
					<div align="center" class="title" style="width: 120px;">attending</div>
					<div align="center" class="bar" style="width: 120px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 12px;">
				<div align="center" id="fltrm" class="topfltr'; if($fltr=='m'){echo'On';} echo'" style="width: 120px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltra\').set(\'class\', \'topfltr\');$(\'fltrn\').set(\'class\', \'topfltr\');$$(\'#peeparea div.subtext\').set(\'styles\',{\'display\':\'none\'});showM();">
					<div align="center" class="title" style="width: 120px;">might attend</div>
					<div align="center" class="bar" style="width: 120px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 12px;">
				<div align="center" id="fltrn" class="topfltr'; if($fltr=='n'){echo'On';} echo'" style="width: 120px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltra\').set(\'class\', \'topfltr\');$(\'fltrm\').set(\'class\', \'topfltr\');$$(\'#peeparea div.subtext\').set(\'styles\',{\'display\':\'none\'});showN();">
					<div align="center" class="title" style="width: 120px;">not attending</div>
					<div align="center" class="bar" style="width: 120px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="right" valign="top" style="padding-top: 2px; padding-left: 48px;">
				<input type="text" id="msrch" name="msrch" size="22px" maxlength="60" onfocus="if (trim(this.value) == \'search\') {this.value=\'\';};this.className=\'inputfocus\'; $(\'fltra\').set(\'class\', \'topfltr\');$(\'fltrm\').set(\'class\', \'topfltr\');$(\'fltrn\').set(\'class\', \'topfltr\'); $$(\'#peeparea div.subtext\').set(\'styles\',{\'display\':\'block\'});" onblur="if (trim(this.value) == \'\') {this.value=\'search\';this.className=\'inputplaceholder\'; $(\'fltra\').set(\'class\', \'topfltrOn\'); $$(\'#peeparea div.subtext\').set(\'styles\',{\'display\':\'none\'}); showA();} else {this.className=\'inputplaceholderblur\';}" onkeyup="if(trim(this.value)!=\'search\'){PeepSearch.filter(this.value);}" class="inputplaceholder" value="search"/>
			</td></tr></table>
		</div>
		
		<div align="left" id="peeparea" style="padding-left: 16px; padding-bottom: 12px; height: 200px; width: 644px; overflow-x: none; overflow-y: scroll;"">';
			$peeple = mysql_query ("SELECT eo.u_id, eo.rsvp, u.defaultimg_url FROM event_owners eo INNER JOIN users u ON eo.u_id=u.user_id WHERE eo.e_id='$eid' ORDER BY u.last_name ASC");
			while ($person = mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
				$uid = $person['u_id'];
				echo '<div align="left" id="'.$person['rsvp'].$uid.'" class="peepblk" style="float: left; width: 150px; margin: 4px;'; if($fltr!=$person['rsvp']){echo' display: none;';} echo'">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
						<a href="'.$baseincpat.'meefile.php?id='.$uid.'" target = "_top"><img src="'.$baseincpat.''.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" /></a>
					</td><td align="left" valign="top" style="padding-left: 4px;">
						<div align="left"><a href="'.$baseincpat.'meefile.php?id='.$uid.'" target = "_top">'; loadpersonnamenolink($uid); echo '</a></div>
						<div align="left" class="subtext" style="font-size: 13px; display: none;">';
							//show rsvp
							if ($person['rsvp']=='a') {
								echo 'attending';	
							} elseif ($person['rsvp']=='m') {
								echo 'might attend';	
							} else {
								echo 'not attending';	
							}
						echo '</div>
					</td></tr></table>
				</div>';
			}
		echo '</div>';

} else { //if not able to view
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You can\'t view this event.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>