<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$uiid = escape_data($_GET['uiid']);

$uid = mysql_result(mysql_query("SELECT u_id FROM user_imgs WHERE ui_id='$uiid' LIMIT 1"), 0);

if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='meepic' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='meepic' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='meepic' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='meepic' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
	
	$pnphotofinds = mysql_query ("SELECT ui_id FROM user_imgs WHERE u_id='$uid' ORDER BY ui_id DESC");
	$prevap = 0;
	$metthisphoto = false;
	$nextap = 0;
	$i = 1;
	while (($nextap==0)&&($pnphotofind = mysql_fetch_array ($pnphotofinds, MYSQL_ASSOC))) {
		$thisap = $pnphotofind['ui_id'];
		if ($thisap==$uiid) {
			$metthisphoto = true;
			$thispnum = $i;
		} elseif ($metthisphoto==true) {
			$nextap = $thisap;
		} else {
			$prevap = $thisap;
		}
		$i++;
	}
	//fix for first and last photo
	if ($prevap==0) {
		$prevap = mysql_result(mysql_query("SELECT ui_id FROM user_imgs WHERE u_id='$uid' ORDER BY ui_id ASC LIMIT 1"), 0);
	}
	if ($nextap==0) {
		$nextap = mysql_result(mysql_query("SELECT ui_id FROM user_imgs WHERE u_id='$uid' ORDER BY ui_id DESC LIMIT 1"), 0);
	}
	
	//grab albums
	echo '<div align="right" style="width: 666px; margin-bottom: 2px;">
		<div align="left" id="taggerinstructions" style="margin-top: 12px; margin-bottom: 8px; display: none;">You are now in tagging mode.<br /><span class="subtext" style="font-size: 14px;">Click on the photo to move the tagger, start typing a name, select the name from the suggestion list, and the tag will be added.</span></div>
		<div align="left" id="painfoarea"><table cellpadding="0" cellspacing="0" width="666px"><tr><td align="left" valign="center" class="subtext" >
			'.$thispnum.' of '.mysql_num_rows($pnphotofinds).'
		</td><td align="right" valign="center">
			<table cellpadding="0" cellspacing="0"><tr><td align="center" valign="center"><div id="prevbtn" uiid="'.$prevap.'" class="photoview_pnbtns" onclick="backcontrol.setState(\'uiid='.$prevap.'\');">PREVIOUS</div></td><td align="center" valign="center">|</td><td align="center" valign="center"><div id="nextbtn" uiid="'.$nextap.'" class="photoview_pnbtns" onclick="backcontrol.setState(\'uiid='.$nextap.'\');">NEXT</div></td></tr></table>
		</td></tr></table></div>
	</div>';
	$photo = mysql_fetch_array (mysql_query ("SELECT ui_id, img_url, caption FROM user_imgs WHERE ui_id='$uiid' LIMIT 1"), MYSQL_ASSOC);
	echo '<div align="left">
		<table cellpadding="0" cellspacing="0"><tr><td align="center" valign="top" width="664px" style="padding-right: 8px;">';
			list($ap_width, $ap_height) = getimagesize('../../'.substr($photo['img_url'], 0, strrpos($photo['img_url'], '.')).'l'.substr($photo['img_url'], strrpos($photo['img_url'], '.')));
			$borderoffset = 2;
			echo '<div align="center" id="ap'.$uiid.'_cont" style="width: '.($ap_width+($borderoffset*2)).'px; height: '.($ap_height+($borderoffset*2)).'px; min-height: 400px; position: relative;">
				<div align="left" style="position: absolute; top: 0px; left: 0px;"><img src="'.$baseincpat.substr($photo['img_url'], 0, strrpos($photo['img_url'], '.')).'l'.substr($photo['img_url'], strrpos($photo['img_url'], '.')).'" class="pictn"/></div>
				<div align="left" id="ap'.$uiid.'_nexthit" style="position: absolute; top: 0px; left: 0px; width: '.$ap_width.'px; height: '.$ap_height.'px;" onclick="backcontrol.setState(\'uiid='.$nextap.'\');" class="pictn"></div>
				</div>
			</div>
			<div align="left" style="padding-left: 6px; padding-top: 12px;">
				<div align="left" class="p24">Comments</div>
				<div align="left" id="uicmts'.$uiid.'" style="padding-top: 4px; padding-left: 12px;">';
					include('../photos/grabmeepiccmts.php');	
				echo '</div>
			</div>
		</td><td align="left" valign="top" width="256px" style="border-top: 1px solid #C5C5C5;">';
			if (($uid==$id)||($photo['caption']!='')) {
				echo '<div align="left" class="p24" style="padding-left: 12px; padding-top: 8px;">
					<table cellpadding="0" cellspacing="0" width="236px"><tr><td align="left" valign="center">Caption</td><td align="right" valign="center">';
					if ($uid==$id) {
						echo '<input type="button" value="edit caption" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/editcaption-meepic.php?id='.$uiid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>';
						}
					echo '</td></tr></table>
				</div>
				<div align="left" id="caption_cont" style="padding-top: 4px; padding-left: 28px;">'.$photo['caption'].'</div>';
			}
			if ($uid==$id) { //or if tagged in photo
				echo '<div align="left" class="p24" style="padding-left: 12px; padding-top: 24px;">Options</div>
				<div align="left" style="padding-top: 4px; padding-left: 54px;">
					<div align="left" style="width: 120px; padding-top: 6px;"><input type="button" value="set as MeePic" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmeepic.php?action=setuimeepic&uiid='.$uiid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
					if ($uid==$id) {
						echo '<div align="left" style="width: 120px; padding-top: 12px;"><input type="button" value="visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editsecvis.php?sec=meepic\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
						<div align="left" style="width: 120px; padding-top: 12px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/deletemeepic.php?id='.$uiid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
					}
				echo '</div>';
			}
		echo '</td></tr></table>
	</div>';
	
} else { //if not vis
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>