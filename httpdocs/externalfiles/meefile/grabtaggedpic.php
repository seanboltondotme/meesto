<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$m_aptid = escape_data($_GET['aptid']);

$m_uid = mysql_result(mysql_query("SELECT u_id FROM ap_tags WHERE apt_id='$m_aptid' LIMIT 1"), 0);

if (($m_uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$m_uid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN defvis_apt piv ON (piv.u_id='$m_uid' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$m_uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN defvis_apt piv ON (piv.u_id='$m_uid' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$m_uid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
	
	if ($m_uid==$id) {
		$pnphotofinds = mysql_query ("SELECT apt_id FROM ap_tags WHERE u_id='$m_uid' ORDER BY apt_id DESC");
	} else {
		$pnphotofinds = mysql_query ("(SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN album_photos_vis apv ON ap.ap_id=apv.ap_id LEFT JOIN my_peeple mp ON mp.u_id='$m_uid' AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id='$m_uid' AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN album_photos_vis apv2 ON ap.ap_id=apv2.ap_id AND apv2.type='user' AND apv2.ref_id='$id' WHERE apt.u_id='$m_uid' AND ((apv.type='pub' AND apv.sub_type='y') OR (((apv.type='strm' AND apv.sub_type=ps.stream) OR (apv.type='chan' AND apv.ref_id=mpc.mpc_id)) AND (apv2.apvis_id IS NULL)))) UNION DISTINCT (SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN ap_tags apt2 ON ap.ap_id=apt2.ap_id AND apt2.u_id='$id' WHERE apt.u_id='$m_uid') ORDER BY apt_id DESC");
	}
	$prevap = 0;
	$metthisphoto = false;
	$nextap = 0;
	$i = 1;
	while (($nextap==0)&&($pnphotofind = mysql_fetch_array ($pnphotofinds, MYSQL_ASSOC))) {
		$thisap = $pnphotofind['apt_id'];
		if ($thisap==$m_aptid) {
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
		if ($uid==$id) {
			$prevap = mysql_result(mysql_query("SELECT apt_id FROM ap_tags WHERE u_id='$m_uid' ORDER BY apt_id ASC LIMIT 1"), 0);
		} else {
			$prevap = mysql_result(mysql_query("(SELECT DISTINCT apt.apt_id FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN album_photos_vis apv ON ap.ap_id=apv.ap_id LEFT JOIN my_peeple mp ON mp.u_id='$m_uid' AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id='$m_uid' AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN album_photos_vis apv2 ON ap.ap_id=apv2.ap_id AND apv2.type='user' AND apv2.ref_id='$id' WHERE apt.u_id='$m_uid' AND ((apv.type='pub' AND apv.sub_type='y') OR (((apv.type='strm' AND apv.sub_type=ps.stream) OR (apv.type='chan' AND apv.ref_id=mpc.mpc_id)) AND (apv2.apvis_id IS NULL)))) UNION DISTINCT (SELECT DISTINCT apt.apt_id FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN ap_tags apt2 ON ap.ap_id=apt2.ap_id AND apt2.u_id='$id' WHERE apt.u_id='$m_uid') ORDER BY apt_id ASC LIMIT 1"), 0);
		}
	}
	if ($nextap==0) {
		if ($uid==$id) {
			$nextap = mysql_result(mysql_query("SELECT apt_id FROM ap_tags WHERE u_id='$m_uid' ORDER BY apt_id DESC LIMIT 1"), 0);
		} else {
			$nextap = mysql_result(mysql_query("(SELECT DISTINCT apt.apt_id FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN album_photos_vis apv ON ap.ap_id=apv.ap_id LEFT JOIN my_peeple mp ON mp.u_id='$m_uid' AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id='$m_uid' AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN album_photos_vis apv2 ON ap.ap_id=apv2.ap_id AND apv2.type='user' AND apv2.ref_id='$id' WHERE apt.u_id='$m_uid' AND ((apv.type='pub' AND apv.sub_type='y') OR (((apv.type='strm' AND apv.sub_type=ps.stream) OR (apv.type='chan' AND apv.ref_id=mpc.mpc_id)) AND (apv2.apvis_id IS NULL)))) UNION DISTINCT (SELECT DISTINCT apt.apt_id FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN ap_tags apt2 ON ap.ap_id=apt2.ap_id AND apt2.u_id='$id' WHERE apt.u_id='$m_uid') ORDER BY apt_id DESC LIMIT 1"), 0);
		}
	}
	
	//grab albums
	echo '<div align="right" style="width: 666px; margin-bottom: 2px;">
		<div align="left" id="taggerinstructions" style="margin-top: 12px; margin-bottom: 8px; display: none;">You are now in tagging mode.<br /><span class="subtext" style="font-size: 14px;">Click on the photo to move the tagger, start typing a name, select the name from the suggestion list, and the tag will be added.</span></div>
		<div align="left" id="painfoarea"><table cellpadding="0" cellspacing="0" width="666px"><tr><td align="left" valign="center" class="subtext" >
			'.$thispnum.' of '.mysql_num_rows($pnphotofinds).'
		</td><td align="right" valign="center">
			<table cellpadding="0" cellspacing="0"><tr><td align="center" valign="center"><div id="prevbtn" aptid="'.$prevap.'" class="photoview_pnbtns" onclick="backcontrol.setState(\'aptid='.$prevap.'\');">PREVIOUS</div></td><td align="center" valign="center">|</td><td align="center" valign="center"><div id="nextbtn" aptid="'.$nextap.'" class="photoview_pnbtns" onclick="backcontrol.setState(\'aptid='.$nextap.'\');">NEXT</div></td></tr></table>
		</td></tr></table></div>
	</div>';
	$photo = mysql_fetch_array (mysql_query ("SELECT apt.apt_id, ap.ap_id, ap.pa_id, ap.url, ap.caption FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id WHERE apt.apt_id='$m_aptid' LIMIT 1"), MYSQL_ASSOC);
	$aid = $photo['pa_id'];
	$apid = $photo['ap_id'];
	$uid = mysql_result(mysql_query("SELECT pa.u_id FROM photo_albums pa INNER JOIN album_photos ap ON ap.pa_id=pa.pa_id AND ap.ap_id='$apid' LIMIT 1"), 0);
	if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE ap_id='$apid' AND u_id='$id' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='strm' AND ps.stream=apv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='chan' AND apv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		
	echo '<div align="left">
		<table cellpadding="0" cellspacing="0"><tr><td align="center" valign="top" width="664px" style="padding-right: 8px;">';
			list($ap_width, $ap_height) = getimagesize('../../'.$photo['url']);
			$borderoffset = 2;
			echo '<div align="center" id="ap'.$apid.'_cont" style="width: '.($ap_width+($borderoffset*2)).'px; height: '.($ap_height+($borderoffset*2)).'px; min-height: 400px; position: relative;">
				<div align="left" style="position: absolute; top: 0px; left: 0px;"><img src="'.$baseincpat.$photo['url'].'" class="pictn"/></div>
				<div align="left" id="ap'.$apid.'_tagcont" style="position: absolute; top: 0px; left: 0px; width: '.$ap_width.'px; height: '.$ap_height.'px;">';
					if ($uid==$id) {
						$tags = mysql_query("SELECT apt_id, u_id, type, x, y FROM ap_tags WHERE ap_id='$apid' ORDER BY apt_id ASC");
					} else {
						$tags = mysql_query("(SELECT DISTINCT apt.apt_id, apt.u_id, apt.type, apt.x, apt.y FROM ap_tags apt INNER JOIN defvis_apt dvapt ON apt.u_id=dvapt.u_id INNER JOIN my_peeple mp ON mp.u_id=apt.u_id AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id=apt.u_id AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN defvis_apt dvapt2 ON apt.u_id=dvapt2.u_id AND dvapt2.type='user' AND dvapt2.ref_id='$id' WHERE (apt.ap_id='$apid') AND ((dvapt.type='pub' AND dvapt.sub_type='y') OR (((dvapt.type='strm' AND dvapt.sub_type=ps.stream) OR (dvapt.type='chan' AND dvapt.ref_id=mpc.mpc_id)) AND (dvapt2.aptvis_id IS NULL)))) UNION (SELECT DISTINCT apt_id, u_id, type, x, y FROM ap_tags WHERE ap_id='$apid' AND u_id='$id') ORDER BY apt_id ASC");
					}
					while ($tag = mysql_fetch_array ($tags, MYSQL_ASSOC)) {
						echo '<div style="top: '.$tag['y'].'px; left: '.$tag['x'].'px; width: 104px; height: 104px; position: absolute; z-index: 10; display: block;" onmouseover="$(\''.$apid.'apt'.$tag['u_id'].'_name\').set(\'styles\',{\'display\':\'block\'});" onmouseout="$(\''.$apid.'apt'.$tag['u_id'].'_name\').set(\'styles\',{\'display\':\'none\'});">
							<div align="left" id="'.$apid.'apt'.$tag['u_id'].'" style="border: 1px solid #36F; display: none;"><div align="left" style="width: 100px; height: 100px; border: 1px solid #fff;"></div></div>
							<div align="center" id="'.$apid.'apt'.$tag['u_id'].'_name" style="top: 104px; width: 104px; position: absolute; display: none; background-color: #fff; border: 1px solid #C5C5C5; padding: 2px;">'; loadpersonname($tag['u_id']); echo'</div>
						</div>';
					}
				echo '</div>
				<div align="left" id="ap'.$apid.'_nexthit" style="position: absolute; top: 0px; left: 0px; width: '.$ap_width.'px; height: '.$ap_height.'px;" onclick="backcontrol.setState(\'apid='.$nextap.'\');" class="pictn"></div>
				<div align="left" id="ap'.$apid.'_taggerhit" style="position: absolute; top: 0px; left: 0px; width: '.$ap_width.'px; height: '.$ap_height.'px; cursor: crosshair; display: none; z-index: 100;" onclick="tagger.movetag(event);"></div>
			</div>
			<div align="left" style="padding-left: 6px; padding-top: 12px;">
				<div align="left" class="p24">Comments</div>
				<div align="left" id="apcmts'.$apid.'" style="padding-top: 4px; padding-left: 12px;">';
					include('../photos/grabpacmts.php');	
				echo '</div>
			</div>
		</td><td align="left" valign="top" width="256px" style="border-top: 1px solid #C5C5C5;">
			<div align="left" class="p24" style="padding-left: 12px; padding-top: 8px;">
				<table cellpadding="0" cellspacing="0" width="236px"><tr><td align="left" valign="center">Tags</td><td align="right" valign="center">';
				if ($uid==$id) { //or if tagged in photo
					echo '<input type="button" id="ap'.$apid.'_tagbtn" value="tag photo" onclick="tagger.starttagger(\'ap'.$apid.'\');$(\'painfoarea\').set(\'styles\',{\'display\':\'none\'});$(\'taggerinstructions\').set(\'styles\',{\'display\':\'block\'});"/><input type="button" id="ap'.$apid.'_tagdonebtn" value="done tagging" onclick="tagger.endtagger();$(\'taggerinstructions\').set(\'styles\',{\'display\':\'none\'});$(\'painfoarea\').set(\'styles\',{\'display\':\'block\'});" style="display: none;"/>';
					}
				echo '</td></tr></table>
			</div>
			<div align="left" id="ap'.$apid.'_taglist" style="padding-top: 4px; padding-left: 28px;">';
				include('../photos/grabtags.php');
			echo '</div>';
			if (($uid==$id)||($photo['caption']!='')) {
				echo '<div align="left" class="p24" style="padding-left: 12px; padding-top: 24px;">
					<table cellpadding="0" cellspacing="0" width="236px"><tr><td align="left" valign="center">Caption</td><td align="right" valign="center">';
					if ($uid==$id) {
						echo '<input type="button" value="edit caption" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/editcaption.php?id='.$apid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>';
						}
					echo '</td></tr></table>
				</div>
				<div align="left" id="caption_cont" style="padding-top: 4px; padding-left: 28px;">'.$photo['caption'].'</div>';
			}
			if (($m_uid==$id)||($uid==$id)||(mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE ap_id='$apid' AND u_id='$id' LIMIT 1"), 0)>0)) {
				echo '<div align="left" class="p24" style="padding-left: 12px; padding-top: 24px;">Options</div>
				<div align="left" style="padding-top: 4px; padding-left: 54px;">
					<div align="left" style="width: 120px; padding-top: 6px;"><input type="button" value="set as MeePic" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmeepic.php?action=setapmeepic&apid='.$apid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
					if ($m_uid==$id) {
						echo '<div align="left" style="width: 120px; padding-top: 12px;"><input type="button" value="visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editdefaptvis.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
						<div align="left" style="width: 120px; padding-top: 12px;"><input type="button" value="delete" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/deleteaptag.php?id='.$m_aptid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
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