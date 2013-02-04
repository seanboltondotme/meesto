<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

echo '<div align="left" style="margin-top: 18px; margin-left: 72px;">';
if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='meepic' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='meepic' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_sec_vis piv ON (piv.u_id='$uid' AND piv.sec='meepic' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$uid' AND sec='meepic' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
	echo '<div align="left" id="meepic"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_sec_vis WHERE u_id='$id' AND sec='meepic' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '><div align="left" class="p24">
		<table cellpadding="0" cellspacing="0" width="900px"><tr><td align="left" valign="center">MeePics</td><td align="right" valign="center">';
		if ($uid==$id) {
			echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="button" value="visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editsecvis.php?sec=meepic\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td><td align="left" valign="center" style="padding-left: 12px;"><input type="button" value="edit Meepic" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmeepic.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>';
		}
		echo '</td></tr></table>
	</div>
	<div align="left" style="margin-left: 8px; margin-top: 6px;">';
		//grab meepics
		echo '<table cellpadding="0" cellspacing="0">';
		$photos = @mysql_query ("SELECT ui_id, img_url FROM user_imgs WHERE u_id='$uid' ORDER BY ui_id DESC LIMIT 7");
		$i = 0;
		while ($photo = @mysql_fetch_array ($photos, MYSQL_ASSOC)) {
			if ($i==0) {
				echo '<tr><td align="center" valign="center">';
			} else {
				echo '<td align="center" valign="center" style="padding-left: 18px;">';	
			}
			echo '<div align="center" style="width: 110px;"><a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos&view=meepic&#uiid='.$photo['ui_id'].'"><img src="'.$baseincpat.substr($photo['img_url'], 0, strrpos($photo['img_url'], '.')).'tn'.substr($photo['img_url'], strrpos($photo['img_url'], '.')).'" class="pictn"/></a></div></td>';
			$i++;
		}
		if ($i==0) {
			echo '<tr><td align="center">none</td>';
		}
		echo '</tr></table>
	</div><div align="right" style="margin-top: 10px; padding-right: 32px; padding-top: 28px;"><a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos&view=meepics">view all '.mysql_result(mysql_query ("SELECT COUNT(*) FROM user_imgs WHERE u_id='$id'"), 0).' of '.$fn.'\'s MeePics</a></div>
	</div>';
}
if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$uid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN defvis_apt piv ON (piv.u_id='$uid' AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN defvis_apt piv ON (piv.u_id='$uid' AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$uid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
	echo '<div align="left" id="taggedpics"';
			if (($uid==$id)&&(mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$id' AND type!='user' LIMIT 1"), 0)==0)) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
	echo '><div align="left" class="p24">
	<table cellpadding="0" cellspacing="0" width="900px"><tr><td align="left" valign="center">Tagged Photos</td><td align="right" valign="center">';
	if ($uid==$id) {
		echo '<input type="button" value="visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/settings/editdefaptvis.php?p=meefile\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>';
	}
	echo '</td></tr></table>
</div><div align="left" style="margin-left: 8px; margin-top: 6px;">';
	//grab tagged photos
	echo '<table cellpadding="0" cellspacing="0">';
	if ($uid==$id) {
		$photos = @mysql_query ("SELECT apt.apt_id, ap.url FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id WHERE apt.u_id='$uid' ORDER BY apt.apt_id DESC LIMIT 7");
	} else {
		$photos = @mysql_query ("(SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN album_photos_vis apv ON ap.ap_id=apv.ap_id LEFT JOIN my_peeple mp ON mp.u_id='$uid' AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id='$uid' AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN album_photos_vis apv2 ON ap.ap_id=apv2.ap_id AND apv2.type='user' AND apv2.ref_id='$id' WHERE apt.u_id='$uid' AND ((apv.type='pub' AND apv.sub_type='y') OR (((apv.type='strm' AND apv.sub_type=ps.stream) OR (apv.type='chan' AND apv.ref_id=mpc.mpc_id)) AND (apv2.apvis_id IS NULL)))) UNION DISTINCT (SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN ap_tags apt2 ON ap.ap_id=apt2.ap_id AND apt2.u_id='$id' WHERE apt.u_id='$uid') ORDER BY apt_id DESC LIMIT 7");
	}
	$i = 0;
	while ($photo = @mysql_fetch_array ($photos, MYSQL_ASSOC)) {
		if ($i==0) {
			echo '<tr><td align="center" valign="center">';
		} else {
			echo '<td align="center" valign="center" style="padding-left: 18px;">';	
		}
		echo '<div align="center" style="width: 110px;"><a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos&view=taggedpic&#aptid='.$photo['apt_id'].'"><img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn"/></a></div></td>';
		$i++;
	}
	if ($i==0) {
		echo '<tr><td align="center">none</td>';
		$num_records = 0;
	} else {
		if ($uid==$id) {
			$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM ap_tags WHERE u_id='$uid'"), 0);
		} else {
			$num_records = mysql_num_rows(mysql_query ("(SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN album_photos_vis apv ON ap.ap_id=apv.ap_id LEFT JOIN my_peeple mp ON mp.u_id='$uid' AND mp.p_id='$id' LEFT JOIN peep_streams ps ON ps.u_id='$uid' AND ps.p_id=mp.p_id LEFT JOIN mpc_mems mpc ON mp.p_id=mpc.p_id LEFT JOIN album_photos_vis apv2 ON ap.ap_id=apv2.ap_id AND apv2.type='user' AND apv2.ref_id='$id' WHERE apt.u_id='$uid' AND ((apv.type='pub' AND apv.sub_type='y') OR (((apv.type='strm' AND apv.sub_type=ps.stream) OR (apv.type='chan' AND apv.ref_id=mpc.mpc_id)) AND (apv2.apvis_id IS NULL)))) UNION DISTINCT (SELECT DISTINCT apt.apt_id, ap.ap_id, ap.url, ap.p_num FROM ap_tags apt INNER JOIN album_photos ap ON apt.ap_id=ap.ap_id INNER JOIN ap_tags apt2 ON ap.ap_id=apt2.ap_id AND apt2.u_id='$id' WHERE apt.u_id='$uid') ORDER BY apt_id DESC"));
		}	
	}
	echo '</tr></table>
</div><div align="right" style="margin-top: 10px; padding-right: 32px;"><a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos&view=taggedpics">view all '.$num_records.' of '.$fn.'\'s tagged photos</a></div>
</div>';
}
echo '<div align="left" class="p24" style="padding-top: 28px;">
	<table cellpadding="0" cellspacing="0" width="900px"><tr><td align="left" valign="center">Photo Albums</td><td align="right" valign="center">';
	if ($uid==$id) {
		echo '<input type="button" value="create photo album" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/createalbum.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>';
	}
	echo '</td></tr></table>
</div>
<div align="left" id="maincontent" style="margin-left: 8px; margin-top: 6px;">';
	include('grabphotoalbums.php');
	echo '</div>
</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>