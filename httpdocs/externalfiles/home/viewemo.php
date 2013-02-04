<?php
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');
$fid = escape_data($_GET['id']);
$pjs = '<script src="'.$baseincpat.'externalfiles/home/viewemo.js" type="text/javascript" charset="utf-8"></script>';
$pdrjs = 'new Request.JSON({url: \''.$baseincpat.'externalfiles/home/viewemo-search.php?id='.$fid.'\', onSuccess: function(r){
				PeepSearch.setValues(r);
			}}).send();';
$fullmts = true;
include ('../../../externals/header/header-pb.php');

$isvis = false;

if (isset($_GET['fltr'])) {
	$fltr = escape_data($_GET['fltr']);
}

$feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id, type, ref_id, ref_type FROM feed WHERE f_id='$fid' LIMIT 1"), MYSQL_ASSOC);
$fuid = $feedinfo['u_id'];
$type = $feedinfo['type'];
$ref_id = $feedinfo['ref_id'];
$ref_type = $feedinfo['ref_type'];

if ($type=='actvapt') {
	if (($fuid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$fuid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN defvis_apt dvapt ON (dvapt.u_id='$fuid' AND dvapt.type='strm' AND ps.stream=dvapt.sub_type) WHERE ps.u_id='$fuid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN defvis_apt dvapt ON (dvapt.u_id='$fuid' AND dvapt.type='chan' AND dvapt.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM defvis_apt WHERE u_id='$fuid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif ($type=='actvap') {
	if (($fuid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE pa_id='$ref_id' AND u_id='$id' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN photo_album_vis pav ON (pav.pa_id='$ref_id'AND pav.type='strm' AND ps.stream=pav.sub_type) WHERE ps.u_id='$fuid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN photo_album_vis pav ON (pav.pa_id='$ref_id'AND pav.type='chan' AND pav.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif (($type=='actvmt')&&($ref_type=='mt')) {
	if (($fuid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$ref_id'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$fuid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$ref_id'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif (($type=='actvmt')&&($ref_type=='mts')) {
	if (($fuid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$ref_id' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$ref_id'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$fuid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_sec_vis piv ON (piv.mts_id='$ref_id'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_sec_vis WHERE mts_id='$ref_id' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
} elseif ($type=='actvcev') {
	if (($fuid==$id) || (mysql_result (mysql_query("SELECT COUNT(*) FROM events WHERE e_id='$ref_id' AND vis='pub' LIMIT 1"), 0)>0) || (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$ref_id' AND u_id='$id' LIMIT 1"), 0)>0)) {
		$isvis = true;
	}
} else {
	if (($fuid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN feed_vis fv ON (fv.f_id='$fid' AND fv.type='strm' AND ps.stream=fv.sub_type) WHERE ps.u_id='$fuid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN feed_vis fv ON (fv.f_id='$fid' AND fv.type='chan' AND fv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
		$isvis = true;
	}
}

if ($isvis==true) {

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">View Post MeeLikes And MeeDislikes</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to view feed post meelikes and meedislikes.</div>';

	echo '<div align="left" style="padding-left: 16px; padding-bottom: 12px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
				<div align="center" id="fltra" class="topfltr'; if($fltr=='l'){echo 'On';} echo'" style="width: 120px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltrm\').set(\'class\', \'topfltr\');$$(\'#peeparea div.subtext\').set(\'styles\',{\'display\':\'none\'});showA();">
					<div align="center" class="title" style="width: 120px;">meelikes</div>
					<div align="center" class="bar" style="width: 120px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 12px;">
				<div align="center" id="fltrm" class="topfltr'; if($fltr=='d'){echo 'On';} echo'" style="width: 120px;" onclick="this.set(\'class\', \'topfltrOn\');$(\'fltra\').set(\'class\', \'topfltr\');$$(\'#peeparea div.subtext\').set(\'styles\',{\'display\':\'none\'});showM();">
					<div align="center" class="title" style="width: 120px;">meedislikes</div>
					<div align="center" class="bar" style="width: 120px;"></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="right" valign="top" style="padding-top: 2px; padding-left: 146px;">
				<input type="text" id="msrch" name="msrch" size="26px" maxlength="60" onfocus="if (trim(this.value) == \'search\') {this.value=\'\';};this.className=\'inputfocus\'; $(\'fltra\').set(\'class\', \'topfltr\');$(\'fltrm\').set(\'class\', \'topfltr\'); $$(\'#peeparea div.subtext\').set(\'styles\',{\'display\':\'block\'});" onblur="if (trim(this.value) == \'\') {this.value=\'search\';this.className=\'inputplaceholder\'; $(\'fltra\').set(\'class\', \'topfltrOn\'); $$(\'#peeparea div.subtext\').set(\'styles\',{\'display\':\'none\'}); showA();} else {this.className=\'inputplaceholderblur\';}" onkeyup="if(trim(this.value)!=\'search\'){PeepSearch.filter(this.value);}" class="inputplaceholder" value="search"/>
			</td></tr></table>
		</div>
		
		<div align="left" id="peeparea" style="padding-left: 16px; padding-bottom: 12px; height: 200px; width: 644px; overflow-x: none; overflow-y: scroll;"">';
			$peeple = mysql_query ("SELECT DISTINCT fe.u_id, fe.type, u.defaultimg_url FROM feed_emo fe INNER JOIN users u ON fe.u_id=u.user_id WHERE fe.f_id='$fid' ORDER BY u.last_name ASC");
			while ($person = mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
				$uid = $person['u_id'];
				echo '<div align="left" id="'; if($person['type']=='d'){echo'd';}else{echo'l';} echo $uid.'" class="peepblk" style="float: left; width: 150px; margin: 4px;'; if($person['type']!=$fltr){echo ' display: none;';} echo'">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
						<a href="'.$baseincpat.'meefile.php?id='.$uid.'" target = "_top"><img src="'.$baseincpat.''.substr($person['defaultimg_url'], 0, -4).'m'.substr($person['defaultimg_url'], -4).'" /></a>
					</td><td align="left" valign="top" style="padding-left: 4px;">
						<div align="left"><a href="'.$baseincpat.'meefile.php?id='.$uid.'" target = "_top">'; loadpersonnamenolink($uid); echo '</a></div>
						<div align="left" class="subtext" style="font-size: 13px; display: none;">';
							//show rsvp
							if ($person['type']=='d') {
								echo 'meelike';	
							} else {
								echo 'meedislike';	
							}
						echo '</div>
					</td></tr></table>
				</div>';
			}
		echo '</div>';

} else { //if not able to view
	echo '<div align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>