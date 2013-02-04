<?php
require_once('../externals/sessions/db_sessions.inc.php');
require_once ('../externals/general/includepaths.php');
require_once ('../externals/general/functions.php');

$uid = escape_data($_GET['id']);
$id = $_SESSION['user_id'];
$title = returnpersonname($uid);
if (isset($_GET['t'])) {
	$t = escape_data($_GET['t']);	
} else {
	$t = '';	
}

if ($t=='') {
	$pdrjs = 'if ($(\'mftabs\').getScrollSize().x>$(\'mftabscont\').getSize().x) {
		$(\'mtabsmorebtn\').set(\'styles\',{\'display\':\'block\'});
	}
	backcontrol.initialize(\''.$baseincpat.'externalfiles/meefile/grabconvo.php?id='.$uid.'&\', \'y\', \'filter\');';	
} elseif (is_numeric($t)) {
	$pdrjs = 'if ($(\'mftabs\').getScrollSize().x>$(\'mftabscont\').getSize().x) {
		$(\'mtabsmorebtn\').set(\'styles\',{\'display\':\'block\'});
	}
	backcontrol.initialize(\''.$baseincpat.'externalfiles/meefile/grabmtsecs.php?id='.$uid.'&t='.$t.'&\');
	if($(\'mtabsname'.$t.'\').getPosition(\'mftabscont\').x>($(\'mftabscont\').getSize().x-120)){
			$(\'mftabs\').set(\'styles\',{\'left\': (($(\'mftabs\').getPosition(\'mftabscont\').x-530)*Math.floor($(\'mtabsname'.$t.'\').getPosition(\'mftabscont\').x/($(\'mftabscont\').getSize().x-120))) });
			if($(\'mftabs\').getSize().x-710<710){$(\'mtabsmorebtn-name\').set(\'html\', \'back\');} 
	}';	
} elseif (($t=='feed')&&($uid==$id)) {
	$pdrjs = 'if ($(\'mftabs\').getScrollSize().x>$(\'mftabscont\').getSize().x) {
		$(\'mtabsmorebtn\').set(\'styles\',{\'display\':\'block\'});
	}	
	backcontrol.initialize(\''.$baseincpat.'externalfiles/meefile/grabfeed.php?id='.$uid.'&\', \'y\', \'topfltr\');';
} elseif ($t=='photos') {
	if (isset($_GET['view'])&&($_GET['view']=='photo')) {
		$pjs = '<script src="'.$baseincpat.'externalfiles/photos/tagger.js" type="text/javascript" charset="utf-8"></script>';
		$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/meefile/grabalbumphoto.php?aid='.escape_data($_GET['aid']).'&\');
		addEvent(\'keydown\', function(event){ 
			if (event.key == \'right\') {
				backcontrol.setState(\'apid=\'+$(\'nextbtn\').get(\'apid\'));
			} else if (event.key == \'left\') {
				backcontrol.setState(\'apid=\'+$(\'prevbtn\').get(\'apid\'));
			}
		});';
	} elseif (isset($_GET['aid'])&&is_numeric($_GET['aid'])) {
		$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/meefile/grabalbumphotos.php?id='.$uid.'&aid='.escape_data($_GET['aid']).'&\');';
	} elseif (isset($_GET['view'])&&($_GET['view']=='meepic')) {
		$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/meefile/grabmeepic.php?\');
		addEvent(\'keydown\', function(event){ 
			if (event.key == \'right\') {
				backcontrol.setState(\'uiid=\'+$(\'nextbtn\').get(\'uiid\'));
			} else if (event.key == \'left\') {
				backcontrol.setState(\'uiid=\'+$(\'prevbtn\').get(\'uiid\'));
			}
		});';
	} elseif (isset($_GET['view'])&&($_GET['view']=='taggedpics')) {
		$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/meefile/grabtaggedpics.php?id='.$uid.'&\');';
	} elseif (isset($_GET['view'])&&($_GET['view']=='taggedpic')) {
		$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/meefile/grabtaggedpic.php?\');
		addEvent(\'keydown\', function(event){ 
			if (event.key == \'right\') {
				backcontrol.setState(\'aptid=\'+$(\'nextbtn\').get(\'aptid\'));
			} else if (event.key == \'left\') {
				backcontrol.setState(\'aptid=\'+$(\'prevbtn\').get(\'aptid\'));
			}
		});';
	} else {
		$pdrjs = 'if ($(\'mftabs\').getScrollSize().x>$(\'mftabscont\').getSize().x) {
			$(\'mtabsmorebtn\').set(\'styles\',{\'display\':\'block\'});
		}
		backcontrol.initialize(\''.$baseincpat.'externalfiles/meefile/grabphotoalbums.php?id='.$uid.'&\');';
	}
} else {
	$pdrjs = 'if ($(\'mftabs\').getScrollSize().x>$(\'mftabscont\').getSize().x) {
		$(\'mtabsmorebtn\').set(\'styles\',{\'display\':\'block\'});
	}';		
}
include ('../externals/header/header.php');

$uinfo = mysql_fetch_array (mysql_query ("SELECT first_name, defaultimg_url FROM users WHERE user_id='$uid' LIMIT 1"), MYSQL_ASSOC);
$fn = $uinfo['first_name'];

if (isset($_GET['aid'])) { //top content switch: load photo view
	$aid = escape_data($_GET['aid']);
	//main content
echo '<div align="left" style="margin-left: 32px; width: 968px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px" height="50px"><a href="'.$baseincpat.'meefile.php?id='.$uid.'"><img src="'.$baseincpat.$uinfo['defaultimg_url'].'" /></a></td><td align="left" valign="top" width="902px" style="padding-left: 8px;">
		<div align="left" class="p24" style="line-height: 20px;"><a href="'.$baseincpat.'meefile.php?id='.$uid.'" style="color: #000;">'; loadpersonnameclean($uid); 
				echo '</a>\'s album ';
				//test vis
				if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$aid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE pa_id='$aid' AND u_id='$id' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN photo_album_vis pav ON (pav.pa_id='$aid'AND pav.type='strm' AND ps.stream=pav.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN photo_album_vis pav ON (pav.pa_id='$aid'AND pav.type='chan' AND pav.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_album_vis WHERE pa_id='$aid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
					$painfo = mysql_fetch_array(mysql_query ("SELECT name, description, u_id FROM photo_albums WHERE pa_id='$aid' LIMIT 1"), MYSQL_ASSOC);
					echo '"<a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos&aid='.$aid.'">'.$painfo['name'].'</a>"';
				} else {
					$painfo = NULL;	
				}
		echo'</div>
		<div align="left" class="subtext" style="padding-top: 4px;">
			<a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos">back to '.$fn.'\'s photos</a>';
			//show back to all photos if viewing single
			if (isset($_GET['view'])&&($_GET['view']=='photo')) { 
				echo ' | <a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos&aid='.$aid.'">back to album photos</a>';
			}
		echo '</div>
	</td></tr></table>
</div>';
	
} elseif (isset($_GET['view'])&&(($_GET['view']=='meepics')||($_GET['view']=='meepic'))) { //top content switch: load userimg photo view
	$aid = escape_data($_GET['aid']);
	//main content
echo '<div align="left" style="margin-left: 32px; width: 968px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px" height="50px"><a href="'.$baseincpat.'meefile.php?id='.$uid.'"><img src="'.$baseincpat.$uinfo['defaultimg_url'].'" /></a></td><td align="left" valign="top" width="902px" style="padding-left: 8px;">
		<div align="left" class="p24" style="line-height: 20px;"><a href="'.$baseincpat.'meefile.php?id='.$uid.'" style="color: #000;">'; loadpersonnameclean($uid); 
				echo '</a>\'s MeePics</div>
		<div align="left" class="subtext" style="padding-top: 4px;">
			<a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos">back to '.$fn.'\'s photos</a>';
			//show back to all photos if viewing single
			if (isset($_GET['view'])&&($_GET['view']=='meepic')) { 
				echo ' | <a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos&view=meepics">back to '.$fn.'\'s MeePics</a>';
			}
		echo '</div>
	</td></tr></table>
</div>';
	
} elseif (isset($_GET['view'])&&(($_GET['view']=='taggedpics')||($_GET['view']=='taggedpic'))) { //top content switch: load userimg photo view
	$aid = escape_data($_GET['aid']);
	//main content
echo '<div align="left" style="margin-left: 32px; width: 968px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px" height="50px"><a href="'.$baseincpat.'meefile.php?id='.$uid.'"><img src="'.$baseincpat.$uinfo['defaultimg_url'].'" /></a></td><td align="left" valign="top" width="902px" style="padding-left: 8px;">
		<div align="left" class="p24" style="line-height: 20px;"><a href="'.$baseincpat.'meefile.php?id='.$uid.'" style="color: #000;">'; loadpersonnameclean($uid); 
				echo '</a>\'s Tagged Photos</div>
		<div align="left" class="subtext" style="padding-top: 4px;">
			<a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos">back to '.$fn.'\'s photos</a>';
			//show back to all photos if viewing single
			if (isset($_GET['view'])&&($_GET['view']=='taggedpic')) { 
				echo ' | <a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos&view=taggedpics">back to '.$fn.'\'s Tagged Photos</a>';
			}
		echo '</div>
	</td></tr></table>
</div>';
	
} elseif (($t=='photos')||is_numeric($t)) { //top content switch: load photo view
	
	echo '<div align="left" style="margin-left: 32px; width: 968px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px" height="50px"><a href="'.$baseincpat.'meefile.php?id='.$uid.'"><img src="'.$baseincpat.$uinfo['defaultimg_url'].'" /></a></td><td align="left" valign="top" width="902px" style="border-bottom: 1px solid #C5C5C5; padding-left: 8px;">
		<div align="left" valign="top">
			<table cellpadding="0" cellspacing="0" width="902px"><tr><td align="left" valign="center">
				<div align="left" class="p24" style="line-height: 20px;">'; loadpersonname($uid); echo'</div>
			</td><td align="right" valign="center">';
				if ($uid==$id) {
					echo '<div align="right" valign="bottom" style="height: 20px;"><input type="button" value="create new tab" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/createmt.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
				}
			echo '</td></tr></table>
		</div>
		
		<div align="left" valign="bottom" class="p24" style="width: 756px;">
			<div align="left" valign="top" style="width: 902px; height: 29px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="146px" class="subtext" style="padding-top: 3px; font-size: 13px;">';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0) {
							echo '<div align="left" class="subtext">(';
							$stts = array ('mb', 'frnd', 'fam', 'prof', 'edu', 'aqu');
							$i = 0;
							foreach ($stts as $stt) {
								if (mysql_result (mysql_query ("SELECT COUNT(*) FROM peep_streams WHERE p_id='$uid' AND u_id='$id' AND stream='$stt' LIMIT 1"), 0)>0) {
									if ($i>0) {
										echo ', ';	
									}
									if ($stt == 'mb') {
							echo 'my bubble';
						} elseif ($stt == 'frnd') {
										echo 'friends';
									} elseif ($stt == 'fam') {
										echo 'family';
									} elseif ($stt == 'prof') {
										echo 'professional';
									} elseif ($stt == 'edu') {
										echo 'education';
									} elseif ($stt == 'aqu') {
										echo 'just met mee';
									}
									$i++;
								}
							}
						echo')</div>';
						}
				echo '</td><td align="left" valign="center">
				<div align="left" id="mftabscont" style="width: 710px; height: 36px; position: relative; overflow: hidden; scroll: none;">
				<div align="left" id="mftabs" style="top: 7px; left: '; if(abs($_GET['tp'])>0){echo $_GET['tp'];}else{echo'0';} echo'px; position: absolute; overflow: hidden; scroll: none; white-space: nowrap;">
					<ul class="mftabul">';
						if (($uid==$id)||(mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0)) {
							echo '<li class="mftabli'; if($t==''){echo'On';} echo'" style="padding-right: 36px;"><div class="mftabliico"></div><a href="'.$baseincpat.'meefile.php?id='.$uid.'">conversations</a></li>';
						}
						echo '<li class="mftabli'; if ($t=='feed'){echo'On';}elseif((mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)<1)&&($t=='')&&($uid!=$id)){echo'On';} echo'" style="padding-right: 36px;"><div class="mftabliico"></div><a href="'.$baseincpat.'meefile.php?id='.$uid; if  (($uid==$id)||(mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0)) {echo '&t=feed';} echo '">feed</a></li>
						<li class="mftabli'; if($t=='about'){echo'On';} echo'" style="padding-right: 36px;"><div class="mftabliico"></div><a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=about">about</a></li>
						<li class="mftabli'; if($t=='photos'){echo'On';} echo'" style="padding-right: 36px;"><div class="mftabliico"></div><a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos">photos</a></li>';
						//get custom tabs
						$cts = mysql_query ("SELECT mt_id, name FROM meefile_tab WHERE u_id='$uid'");
						while ($ct = mysql_fetch_array ($cts, MYSQL_ASSOC)) {
							$plmtid = $ct['mt_id'];
							if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$plmtid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$plmtid'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$plmtid'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$plmtid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
								echo '<li class="mftabli'; if($t==$plmtid){echo'On';} echo'" style="padding-right: 36px;"><div id="mtabsname'.$plmtid.'" class="mftabliico"></div><a href="'.$baseincpat.'meefile.php?id='.$uid.'&t='.$plmtid.'"><nobr>'.$ct['name'].'</nobr></a></li>';
							}
						}
					echo '</ul>
				</div>
				</div>
			</td><td align="left" valign="center" style="padding-left: 4px; padding-top: 4px;">
					<div align="left" id="mtabsmorebtn" style="cursor: pointer; display: none;" onclick="if(-$(\'mftabs\').getPosition(\'mftabscont\').x<$(\'mftabs\').getSize().x-710){$(\'mftabs\').tween(\'left\', ($(\'mftabs\').getPosition(\'mftabscont\').x-530)); if($(\'mftabs\').getSize().x-710<710){$(\'mtabsmorebtn-name\').set(\'html\', \'back\');} }else{$(\'mftabs\').tween(\'left\', (0));$(\'mtabsmorebtn-name\').set(\'html\', \'more\');}"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" id="mtabsmorebtn-name" style="font-size: 14px;">more</td><td align="left" valign="center" style="padding-left: 2px;"><img src="'.$baseincpat.'images/tabs-more.png" /></td></tr></table></div>
			</td></tr></table></div>
		</div>
	</td></tr></table>
</div>';
	
} else { //top content switch: load regular

//main content
echo '<div align="left" style="margin-left: 32px; width: 968px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="center" valign="top" width="186px" height="140px" style="background-color: #C5C5C5;"><img src="'.$baseincpat.substr($uinfo['defaultimg_url'], 0, -4).'p'.substr($uinfo['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="756px" style="border-bottom: 1px solid #C5C5C5; padding-left: 18px;">
		<div align="left" valign="top" style="height: '; if ($uid==$id) {echo'86';}else{echo'110';} echo'px;">
			<div align="left" style="font-size: 42px; color: #36F; margin-bottom: 12px;">'; loadpersonname($uid); echo'</div>';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0) {
				echo '<div align="left" class="subtext">(';
				$stts = array ('mb', 'frnd', 'fam', 'prof', 'edu', 'aqu');
				$i = 0;
				foreach ($stts as $stt) {
					if (mysql_result (mysql_query ("SELECT COUNT(*) FROM peep_streams WHERE p_id='$uid' AND u_id='$id' AND stream='$stt' LIMIT 1"), 0)>0) {
						if ($i>0) {
							echo ', ';	
						}
						if ($stt == 'mb') {
							echo 'my bubble';
						} elseif ($stt == 'frnd') {
							echo 'friends';
						} elseif ($stt == 'fam') {
							echo 'family';
						} elseif ($stt == 'prof') {
							echo 'professional';
						} elseif ($stt == 'edu') {
							echo 'education';
						} elseif ($stt == 'aqu') {
							echo 'just met mee';
						}
						$i++;
					}
				}
			echo ')</div>';
			} elseif (($id!=0)&&($uid!=$id)) {
				echo '<div align="left" id="useraddbtnarea'.$uid.'">';
					if (mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE type='peepcnct' AND ((u_id='$id' AND s_id='$uid') OR (u_id='$uid' AND s_id='$id')) LIMIT 1"), 0)>0) {
						echo '<div align="left">pending connection</div>';
					} else {
						echo '<input type="button" value="connect with '.$fn.'" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/user/add.php?id='.$uid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>';
					}
				echo '</div>';
			}
		echo'</div>
		
		<div align="left" valign="bottom" class="p24" style="width: 756px; height: '; if ($uid==$id) {echo'53';}else{echo'29';} echo'px;">';
			if ($uid==$id) {
				echo '<div align="right" valign="bottom" style="width: 756px; height: 24px;"><input type="button" value="create new tab" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/createmt.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
			}
			echo '<div align="left" valign="bottom" style="width: 756px; height: 29px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
				<div align="left" id="mftabscont" style="width: 710px; height: 36px; position: relative; overflow: hidden; scroll: none;">
				<div align="left" id="mftabs" style="top: 7px; left: '; if(abs($_GET['tp'])>0){echo $_GET['tp'];}else{echo'0';} echo'px; position: absolute; overflow: hidden; scroll: none; white-space: nowrap;">
					<ul class="mftabul">';
				if (($uid==$id)||(mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0)) {
					echo '<li class="mftabli'; if($t==''){echo'On';} echo'" style="padding-right: 36px;"><div class="mftabliico"></div><a href="'.$baseincpat.'meefile.php?id='.$uid.'">conversations</a></li>';
				}
				echo '<li class="mftabli'; if ($t=='feed'){echo'On';}elseif((mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)<1)&&($t=='')&&($uid!=$id)){echo'On';} echo'" style="padding-right: 36px;"><div class="mftabliico"></div><a href="'.$baseincpat.'meefile.php?id='.$uid; if  (($uid==$id)||(mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0)) {echo '&t=feed';} echo '">feed</a></li>
				<li class="mftabli'; if($t=='about'){echo'On';} echo'" style="padding-right: 36px;"><div class="mftabliico"></div><a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=about">about</a></li>
				<li class="mftabli'; if($t=='photos'){echo'On';} echo'" style="padding-right: 36px;"><div class="mftabliico"></div><a href="'.$baseincpat.'meefile.php?id='.$uid.'&t=photos">photos</a></li>';
				//get custom tabs
				$cts = mysql_query ("SELECT mt_id, name FROM meefile_tab WHERE u_id='$uid'");
				while ($ct = mysql_fetch_array ($cts, MYSQL_ASSOC)) {
					$plmtid = $ct['mt_id'];
					if (($uid==$id) || (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$plmtid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$plmtid'AND piv.type='strm' AND ps.stream=piv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN meefile_tab_vis piv ON (piv.mt_id='$plmtid'AND piv.type='chan' AND piv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$plmtid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
						echo '<li class="mftabli'; if($t==$plmtid){echo'On';} echo'" style="padding-right: 36px;"><div id="mtabsname'.$plmtid.'" class="mftabliico"></div><a href="'.$baseincpat.'meefile.php?id='.$uid.'&t='.$plmtid.'"><nobr>'.$ct['name'].'</nobr></a></li>';
					}
				}
			echo '</ul>
			</div>
				</div>
			</td><td align="left" valign="center" style="padding-left: 4px; padding-top: 4px;">
					<div align="left" id="mtabsmorebtn" style="cursor: pointer; display: none;" onclick="if(-$(\'mftabs\').getPosition(\'mftabscont\').x<$(\'mftabs\').getSize().x-710){$(\'mftabs\').tween(\'left\', ($(\'mftabs\').getPosition(\'mftabscont\').x-530)); if($(\'mftabs\').getSize().x-710<710){$(\'mtabsmorebtn-name\').set(\'html\', \'back\');} }else{$(\'mftabs\').tween(\'left\', (0));$(\'mtabsmorebtn-name\').set(\'html\', \'more\');}"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" id="mtabsmorebtn-name" style="font-size: 14px;">more</td><td align="left" valign="center" style="padding-left: 2px;"><img src="'.$baseincpat.'images/tabs-more.png" /></td></tr></table></div>
			</td></tr></table></div>
		</div>
	</td></tr></table>
</div>';

} //end of top content switch

echo '<div align="left">';
	
	//load main content
	if ((($uid==$id)||(mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)>0))&&($t=='')) {
		include ('externalfiles/meefile/conversations.php');
	} elseif (($t=='feed')||($t=='')) {
		include ('externalfiles/meefile/feed.php');
	} elseif ($t=='about') {
		include ('externalfiles/meefile/about.php');
	} elseif ($t=='photos') {
		if (isset($_GET['view'])&&($_GET['view']=='photo')) {
			include ('externalfiles/meefile/photos-viewphoto.php');
		} elseif (isset($_GET['aid'])) {
			include ('externalfiles/meefile/photos-viewalbum.php');
		} elseif (isset($_GET['view'])&&($_GET['view']=='meepics')) {
			include ('externalfiles/meefile/photos-viewmeepics.php');
		} elseif (isset($_GET['view'])&&($_GET['view']=='meepic')) {
			include ('externalfiles/meefile/photos-viewmeepic.php');
		} elseif (isset($_GET['view'])&&($_GET['view']=='taggedpics')) {
			include ('externalfiles/meefile/photos-viewtaggedpics.php');
		} elseif (isset($_GET['view'])&&($_GET['view']=='taggedpic')) {
			include ('externalfiles/meefile/photos-viewtaggedpic.php');
		} else {
			include ('externalfiles/meefile/photos.php');
		}
	} elseif (is_numeric($t)) {
		include ('externalfiles/meefile/mtab.php');
	}
	
echo '</div>';

include ('../externals/header/footer.php');
?>
