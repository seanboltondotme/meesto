<?php
require_once('../externals/sessions/db_sessions.inc.php');
require_once ('../externals/general/includepaths.php');

if ($_SESSION['user_id'] == NULL) {
	$url = $baseincpat.'login.php';
	header("Location: $url");
	exit();
}

$title = 'Settings';
include ('../externals/header/header.php');

$uinfo = mysql_fetch_array (mysql_query ("SELECT CONCAT_WS(' ', first_name, middle_name, last_name) AS name, full_name, email FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);

//main content
echo '<div align="left" style="width: 900px;">
<div align="left" class="p24" style="margin-bottom: 4px; border-bottom: 1px solid #C5C5C5;">Meesto Settings</div>
<div align="left" style="margin-left: 24px;">
	<div align="left" style="margin-top: 12px; padding-bottom: 2px; border-bottom: 1px solid #C5C5C5;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="110px">Name</td><td align="left" valign="center" width="790px">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="680px" class="subtext" style="font-size: 14px;">'.$uinfo['name']; if($uinfo['full_name']!=''){echo' ('.$uinfo['full_name'].')';} echo'</td><td align="right" valign="center" width="110px"><input type="button" value="edit" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editname.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
		</td></tr></table>
	</div>
	<div align="left" style="margin-top: 12px; padding-bottom: 2px; border-bottom: 1px solid #C5C5C5;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="110px">Email</td><td align="left" valign="center" width="790px">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="680px" class="subtext" style="font-size: 14px;">'.$uinfo['email'].'</td><td align="right" valign="center" width="110px"><input type="button" value="edit" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editemail.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
		</td></tr></table>
	</div>
	<div align="left" style="margin-top: 12px; padding-bottom: 2px; border-bottom: 1px solid #C5C5C5;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="110px">Password</td><td align="left" valign="center" width="790px">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="680px" class="subtext" style="font-size: 14px;">&bull;&bull;&bull;&bull;&bull;&bull;&bull;</td><td align="right" valign="center" width="110px"><input type="button" value="edit" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editpword.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
		</td></tr></table>
	</div>
	<div align="left" style="margin-top: 12px; padding-bottom: 2px; border-bottom: 1px solid #C5C5C5;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="110px" style="padding-top: 2px;">Visibility</td><td align="left" valign="center" width="790px">
			<div align="left" style="padding-bottom: 2px; border-bottom: 1px solid #C5C5C5;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="110px">Feed Post</td><td align="left" valign="center" width="570px" class="subtext" style="font-size: 14px;">This is the default visibility setting applied to new feed posts.</td><td align="right" valign="center" width="110px"><input type="button" value="edit" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editdeffeedpostvis.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
			</div>
			<div align="left" style="margin-top: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="110px">Photo Tags</td><td align="left" valign="center" width="570px" class="subtext" style="font-size: 14px;">This is the visibility setting for all of your photo tags.</td><td align="right" valign="center" width="110px"><input type="button" value="edit" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editdefaptvis.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
			</div>
		</td></tr></table>
	</div>
	<div align="left" style="margin-top: 12px; padding-bottom: 2px; border-bottom: 1px solid #C5C5C5;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="110px" style="padding-top: 2px;">Activity Posts</td><td align="left" valign="center" width="790px">
			<div align="left" style="padding-bottom: 2px; border-bottom: 1px solid #C5C5C5;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="110px" style="padding-bottom: 14px;">Photo Albums</td><td align="left" valign="center" width="570px" class="subtext" style="font-size: 14px;">This will automatically make a feed post when you create an album.<br/><span style="font-size: 13px;">(Visibility is inherited from the album.)</span></td><td align="right" valign="center" width="110px"><input type="button" value="edit" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editfeedpost.php?sec=pa\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
			</div>
			<div align="left" style="margin-top: 12px; padding-bottom: 2px; border-bottom: 1px solid #C5C5C5;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="110px" style="padding-bottom: 14px;">Photo Tags</td><td align="left" valign="center" width="570px" class="subtext" style="font-size: 14px;">This will automatically make a feed post when you are tagged in a photo.<br/><span style="font-size: 13px;">(Visibility is inherited from your photo tag visibility settings.)</span></td><td align="right" valign="center" width="110px"><input type="button" value="edit" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editfeedpost.php?sec=ptags\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
			</div>
			<div align="left" style="margin-top: 12px; padding-bottom: 2px; border-bottom: 1px solid #C5C5C5;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="110px" style="padding-bottom: 14px;">Meefile Tabs</td><td align="left" valign="center" width="570px" class="subtext" style="font-size: 14px;">This will automatically make a feed post when you create a Meefile tab or tab post.<br/><span style="font-size: 13px;">(Visibility is inherited from each unique Meefile tab visibility setting.)</span></td><td align="right" valign="center" width="110px"><input type="button" value="edit" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editfeedpost.php?sec=mtabs\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
			</div>
			<div align="left" style="margin-top: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="110px" style="padding-bottom: 14px;">Events</td><td align="left" valign="center" width="570px" class="subtext" style="font-size: 14px;">This will automatically make a feed post when you create an event.<br/><span style="font-size: 13px;">(Visibility is inherited from the event.)</span></td><td align="right" valign="center" width="110px"><input type="button" value="edit" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editfeedpost.php?sec=events\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
			</div>
		</td></tr></table>
	</div>
	<div align="left" style="margin-top: 12px; padding-bottom: 2px; border-bottom: 1px solid #C5C5C5;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="110px">Notifications</td><td align="left" valign="center" width="790px">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="680px" class="subtext" style="font-size: 14px;">These are your email notification settings.</td><td align="right" valign="center" width="110px"><input type="button" value="edit" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editenotif.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
		</td></tr></table>
	</div>
	<div align="left" style="margin-top: 12px; padding-bottom: 2px; border-bottom: 1px solid #C5C5C5;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="110px">Delete</td><td align="left" valign="center" width="790px">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" width="680px" class="subtext" style="font-size: 14px;">Use this to delete your Meesto account :(</td><td align="right" valign="center" width="110px"><input type="button" value="delete account" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editpword.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
		</td></tr></table>
	</div>
</div>
</div>';

include ('../externals/header/footer.php');
?>