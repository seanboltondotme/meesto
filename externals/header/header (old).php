<?php
require_once ('../externals/general/includepaths.php');

if (isset($_SESSION['user_id'])) {
	$id = $_SESSION['user_id'];
} else {
	$id = 0;
}

echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Meesto | '.$title.'</title>
<meta name="description" content="Meesto is a social networking tool where you control your data and you control the site (built on Open Source and Nonprofit ideals)." />
<meta name="keywords" content="meesto, social networking, non profit, nonprofit, open source, respect privacy, respect data, respect, privacy, social networking alternatives, social networking tool, social tool" />
<link rel="image_src" href="'.$baseincpat.'images/logoshr.png" / >
<link rel="shortcut icon" href="'.$baseincpat.'images/favico.ico" />
<link rel="stylesheet" href="'.$baseincpat.'externalfiles/m.css" type="text/css" media="screen" charset="utf-8"/>
<script src="'.$baseincpat.'externalfiles/mts.js" type="text/javascript" charset="utf-8"></script>
<script src="'.$baseincpat.'externalfiles/mts-more.js" type="text/javascript" charset="utf-8"></script>
<script src="'.$baseincpat.'externalfiles/m.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="'.$baseincpat.'externalfiles/pb.css" type="text/css" media="screen" charset="utf-8"/>
<script src="'.$baseincpat.'externalfiles/pb.js" type="text/javascript" charset="utf-8"></script>
<script src="'.$baseincpat.'externalfiles/notifications/m.js" type="text/javascript" charset="utf-8"></script>
<script src="'.$baseincpat.'externalfiles/meechat/m.js" type="text/javascript" charset="utf-8"></script>
<script src="'.$baseincpat.'externalfiles/meechat/soundmanager2.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
soundManager.waitForWindowLoad = true;
soundManager.url = \''.$baseincpat.'externalfiles/meechat/\'
soundManager.useFlashBlock = false;
soundManager.debugMode = false;
hs_focus = false;
tgr_focus = false;
window.addEvent(\'keyup\', function(event){ 
	if (hs_focus==true) {
		if (event.key == \'up\') {
			headerSearch.selectPrevious();
			event.stop();
		} else if (event.key == \'down\') {
			headerSearch.selectNext();
			event.stop();
		} else if (event.key == \'enter\') {
			headerSearch.makeChoice();
			event.stop();
		} else {
			if(trim($(\'msrch\').value)!=\'search\'){headerSearch.filter($(\'msrch\').value);}
		}
	} else if (tgr_focus==true) {
		if (event.key == \'up\') {
			tagger.selectPrevious();
			event.stop();
		} else if (event.key == \'down\') {
			tagger.selectNext();
			event.stop();
		} else if (event.key == \'enter\') {
			tagger.makeChoice();
			event.stop();
		} else {
			if(trim($(tagger.getObj()+\'_taggername\').value)!=\'start typing name here...\'){tagger.filter($(tagger.getObj()+\'_taggername\').value);}
		}
	}
});
window.addEvent(\'domready\', function() {';
	if ($id>0) {
		echo 'notifs.initialize();
		meechat.initialize(\''; 
			//test for open chat
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$id' AND mc_sound='y'"), 0)>0) {
				echo 'y';
			} else {
				echo 'n';	
			}
		echo'\','; 
			//test for open chat
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM mc_open WHERE u_id='$id' AND open='y'"), 0)>0) {
				echo mysql_result(mysql_query ("SELECT c_id FROM mc_open WHERE u_id='$id' AND open='y' LIMIT 1"), 0);
			} else {
				echo '0';	
			}
		echo');';
	}
	if(isset($pdrjs)){echo $pdrjs;} echo'
});
</script>
'; if(isset($pjs)){echo $pjs;} echo'
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push([\'_setAccount\', \'UA-11348382-1\']);
  _gaq.push([\'_trackPageview\']);

  (function() {
    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>';
require_once ('../externals/general/functions.php');

if ( (isset($_SESSION['user_id'])) && (!strpos($_SERVER['PHP_SELF'], 'logout.php')) ) {
	echo '<body class="body">
	<div class="container" align="center" valign="top" style="padding-bottom: 58px;">';
	
		// chat | po
			//po count
			$nct = mysql_result(mysql_query("SELECT COUNT(*) FROM notifications WHERE u_id='$id' AND viewed IS NULL"), 0);
			$rct = mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE u_id='$id'"), 0);
			$poct = $nct + $rct;
		echo '<div class="pochatontainer" align="center" valign="bottom">
			<div class="pochattray" align="left" valign="bottom">
				<div align="left" id="potraycont" class="potraycontrea" style="width: 240px; height: 370px; position: absolute; top: -378px; left: 0px; visibility: hidden; zoom: 1; opacity: 0;">
					<div align="left" style="position: absolute; top: 370px; left: -2px; width: 140px; height: 2px; border-bottom: 2px solid #C5C5C5;"></div>
					<div align="left" id="req_openpbalrt" class="palert" style="position: absolute; top: 2px; left: 2px; display: none;">You cannot view this while a popup is open.<p class="subtext">This was done in an effort to help make sure you don\'t accidentally loose what you are currently doing in the popup.</p></div>
					<div align="left" id="po_maincont" style="position: absolute; top: 2px; left: 2px; width: 238px;">
						<div align="left">
							<table cellpadding="0" cellspacing="0" width="100%"><tr><td align="left" valign="top" class="p24">notifications'; if($nct>0){echo' ('.$nct.')';} echo'</td><td align="right" valign="center" style="padding-right: 6px;"><a href="'.$baseincpat.'notifs.php?">view all</a></td></tr></table>
						</div>
						<div align="left" id="ponotifcont" style="padding-left: 8px; height: '; if($rct==0){echo'268';}else{echo'208';} echo'px; width: 230px; overflow-x: none; overflow-y: scroll;">';
							//grab notifications
							if ($nct>6) {
								$notif_snid = mysql_result(mysql_query("SELECT n_id FROM notifications WHERE u_id='$id' AND viewed IS NULL ORDER BY n_id ASC LIMIT 1"), 0);
								$notifs = mysql_query ("SELECT n_id, type, s_id, sub, params, ref_id, xref_id, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM notifications WHERE u_id='$id' AND n_id>='$notif_snid' ORDER BY n_id DESC");
							} else {
								$notifs = mysql_query ("SELECT n_id, type, s_id, sub, params, ref_id, xref_id, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM notifications WHERE u_id='$id' ORDER BY n_id DESC LIMIT 6");
							}
							while ($notif = mysql_fetch_array ($notifs, MYSQL_ASSOC)) {
								$nid = $notif['n_id'];
								$sid = $notif['s_id'];
								$type = $notif['type'];
								$sub = $notif['sub'];
								$params = $notif['params'];
								$refid = $notif['ref_id'];
								$xrefid = $notif['xref_id'];
								if ($type=='feedcmt') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid.'">your feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='feedcmtx') {
									$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									$n_feeduid = $n_feedinfo['u_id'];
									if ($sub=='emo') {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a> you '; if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$refid' AND u_id='$id' AND type='d' LIMIT 1"), 0)>0) {echo'dislike';}else{echo'like';} echo'd. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									} else {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									}
								} elseif ($type=='feedeml') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' liked <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid.'">your feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='feedemlx') {
									$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									$n_feeduid = $n_feedinfo['u_id'];
									if ($sub=='cmt') {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' liked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a> you commented on. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									} else {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$refid' AND u_id='$id' AND type='l' LIMIT 1"), 0)>0) {echo' also';} echo' liked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									}
								} elseif ($type=='feedemd') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' disliked <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=feed&vid='.$refid.'">your feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='feedemdx') {
									$n_feedinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM feed WHERE f_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									$n_feeduid = $n_feedinfo['u_id'];
									if ($sub=='cmt') {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' disliked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a> you commented on. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									} else {
										echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); if(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_emo WHERE f_id='$refid' AND u_id='$id' AND type='d' LIMIT 1"), 0)>0) {echo' also';} echo' disliked <a href="'.$baseincpat.'meefile.php?id='.$n_feeduid.'&t=feed&vid='.$refid.'">'; if($sid==$n_feeduid){ if(mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$n_feeduid' AND gender='male'"), 0)>0){echo'his';}else{echo'her';} }else{ loadpersonnamenolink($n_feeduid);echo '\'s'; } echo' feed post</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
									}
								} elseif ($type=='msg') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' <a href="'.$baseincpat.'meefile.php?id='.$id.'&#&vid='.$refid.'">sent you a message</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='msgcmt') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' replied to <a href="'.$baseincpat.'meefile.php?id='.$id.'&#&vid='.$refid.'">one of your messages</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='apt') {
									$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id, u_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' tagged you in <a href="'.$baseincpat.'meefile.php?id='.$n_apinfo['u_id'].'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid.'">a photo</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='apcmt') {
									$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid.'">your photo</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='apcmtx') {
									$n_apinfo = mysql_fetch_array (mysql_query ("SELECT pa_id, u_id FROM album_photos WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$n_apinfo['u_id'].'&t=photos&aid='.$n_apinfo['pa_id'].'&view=photo&#apid='.$refid.'">a photo you commented on</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='uicmt') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$id.'&t=photos&view=meepic&#uiid='.$refid.'">your MeePic</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='uicmtx') {
									$n_uiinfo = mysql_fetch_array (mysql_query ("SELECT u_id FROM user_imgs WHERE ap_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'meefile.php?id='.$n_uiinfo['u_id'].'&t=photos&view=meepic&#uiid='.$refid.'">a MeePic you commented on</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='eiresp') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT e.name, eo.rsvp FROM events e INNER JOIN event_owners eo ON e.e_id=eo.e_id AND eo.u_id='$sid' WHERE e.e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn"><a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> accepted your event invite to "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>" and '; if($n_einfo['rsvp']=='a'){echo'is attending';}elseif($n_einfo['rsvp']=='m'){echo'might attend';}else{echo'isn\'t attending';} echo'. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='eirespn') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn"><a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> denied your event invite to "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='evntadm') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">You are now an admin of the event "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='evntadmr') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">You are no longer an admin of the event "<a href="'.$baseincpat.'event.php?id='.$refid.'">'.$n_einfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='projadm') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name, type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									if ($n_cpinfo['type']=='bug') {
										$n_cpinfo_name = 'Meesto Bug';
									} else {
										$n_cpinfo_name = 'Meesto Community Project';
									}
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">You are now a team member of the '.$n_cpinfo_name.' "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='projadmr') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name, type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									if ($n_cpinfo['type']=='bug') {
										$n_cpinfo_name = 'Meesto Bug';
									} else {
										$n_cpinfo_name = 'Meesto Community Project';
									}
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">You are no longer a team member of the '.$n_cpinfo_name.' "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='mtscmt') {
									$n_mtsinfo = mysql_fetch_array (mysql_query ("SELECT title, mt_id FROM meefile_tab_sec WHERE mts_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on "<a href="'.$baseincpat.'meefile.php?id='.$id.'&t='.$n_mtsinfo['mt_id'].'&vid='.$refid.'">'.$n_mtsinfo['title'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='mtscmtx') {
									$n_mtsinfo = mysql_fetch_array (mysql_query ("SELECT mts.title, mts.mt_id, mt.u_id FROM meefile_tab_sec mts INNER JOIN meefile_tab mt ON mt.mt_id=mts.mt_id WHERE mts.mts_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' also commented on "<a href="'.$baseincpat.'meefile.php?id='.$n_mtsinfo['u_id'].'&t='.$n_mtsinfo['mt_id'].'&vid='.$refid.'">'.$n_mtsinfo['title'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='cpiresp') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn"><a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> accepted your invite to support "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='cpirespn') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn"><a href="'.$baseincpat.'meefile.php?id='.$sid.'">'; loadpersonnamenolink($sid); echo '</a> denied your invite to support "<a href="'.$baseincpat.'proj.php?id='.$refid.'">'.$n_cpinfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='fdbkcmt') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'community.php?#f=fdbk&vid='.$refid.'">your feedback</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='fdbkcmtx') {
									$n_fdbkuid = mysql_result(mysql_query ("SELECT u_id FROM feedback WHERE fdbk_id='$refid' LIMIT 1"), 0);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'community.php?#f=fdbk&vid='.$refid.'">'; loadpersonnamenolink($n_fdbkuid); echo '\'s feedback</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='evntmcmt') {
									$n_einfo = mysql_fetch_array (mysql_query ("SELECT name FROM events WHERE e_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on "<a href="'.$baseincpat.'event.php?id='.$refid.'&vid='.$xrefid.'">'.$n_einfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='evntcmt') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'event.php?id='.$refid.'&vid='.$xrefid.'&vt=x">your event comment</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='evntcmtx') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'event.php?id='.$refid.'&vid='.$xrefid.'&vt=x">an event comment</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='cprjmcmt') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on "<a href="'.$baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid.'">'.$n_cpinfo['name'].'</a>" <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='cprjcmt') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' commented on <a href="'.$baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid.'&vt=x">your community '; if($n_cpinfo['type']=='bug'){echo'bug';}else{echo'project';} echo' comment</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='cprjcmtx') {
									$n_cpinfo = mysql_fetch_array (mysql_query ("SELECT type FROM comm_projs WHERE cp_id='$refid' LIMIT 1"), MYSQL_ASSOC);
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' also commented on <a href="'.$baseincpat.'proj.php?id='.$refid.'&vid='.$xrefid.'&vt=x">a community '; if($n_cpinfo['type']=='bug'){echo'bug';}else{echo'project';} echo' comment</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif (($type=='pcntresp')&&($sub=='deny')) {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' denied your request to connect on your ';
										$params = explode(";", $params);
										$params_ct = count($params)-1;
										$i = 0;
										foreach ($params as $param) {
											if (($i==$params_ct)&&($i==1)) {
												echo ' and ';	
											} elseif (($i==$params_ct)&&($i>0)) {
												echo ', and ';	
											} elseif ($i>0) {
												echo ', ';	
											}
											if ($param == 'mb') {
												echo 'my bubble';
											} elseif ($param == 'frnd') {
												echo 'friends';
											} elseif ($param == 'fam') {
												echo 'family';
											} elseif ($param == 'prof') {
												echo 'professional';
											} elseif ($param == 'edu') {
												echo 'education';
											} elseif ($param == 'aqu') {
												echo 'acquaintances';
											}
											$i++;
										}
									echo ' stream'; if($params_ct>0){echo's';} echo'. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='pcntresp') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' accepted your request to connect on your ';
										$params = explode(";", $params);
										$params_ct = count($params)-1;
										$i = 0;
										foreach ($params as $param) {
											if (($i==$params_ct)&&($i==1)) {
												echo ' and ';	
											} elseif (($i==$params_ct)&&($i>0)) {
												echo ', and ';	
											} elseif ($i>0) {
												echo ', ';	
											}
											if ($param == 'mb') {
												echo 'my bubble';
											} elseif ($param == 'frnd') {
												echo 'friends';
											} elseif ($param == 'fam') {
												echo 'family';
											} elseif ($param == 'prof') {
												echo 'professional';
											} elseif ($param == 'edu') {
												echo 'education';
											} elseif ($param == 'aqu') {
												echo 'acquaintances';
											}
											$i++;
										}
									echo ' stream'; if($params_ct>0){echo's';} echo'. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='rsresp') {
									echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' has '; if($sub=='a'){echo'accpeted';}else{echo'denied';} echo' your relationship status request. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								} elseif ($type=='helprep') {
							echo '<div align="left" id="notif'.$nid.'" class="blockbtn">'; loadpersonname($sid); echo ' answered <a href="'.$baseincpat.'help.php?htid='.$refid.'">your help question</a>. <span class="subtext" style="font-size: 13px;">'.$notif['time'].'</span></div>';
								}
							}
						echo '</div>
						<div align="left" class="p24" style="padding-top: 12px;">requests/invites'; if($rct>0){echo' ('.$rct.')';} echo'</div>';
						if ($rct>0) {
							echo '<div align="left" id="poreqicont" style="padding-left: 8px; height: 90px; width: 230px; overflow-x: none; overflow-y: scroll;">';
								$rrscnct = mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE u_id='$id' AND type='rs'"), 0);
								if ($rrscnct>0) {
									echo '<div align="left" class="blockbtn" style="font-size: 18px;" onclick="$(\'potraycont\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/po/reqview.php?t=rs\', handler:\'iframe\'});">1 relationship request!</div>';
								}
								$rinvtpeepcnct = mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE u_id='$id' AND type='peepcnct'"), 0);
								if ($rinvtpeepcnct>0) {
									echo '<div align="left" class="blockbtn" style="font-size: 18px;" onclick="$(\'potraycont\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/po/reqview.php?t=peepcnct\', handler:\'iframe\'});">'.$rinvtpeepcnct.' peeple connection request'; if($rinvtpeepcnct>1){echo's';} echo'!</div>';
								}
								$rinvtevntct = mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE u_id='$id' AND type='invtevnt'"), 0);
								if ($rinvtevntct>0) {
									echo '<div align="left" class="blockbtn" style="font-size: 18px;" onclick="$(\'potraycont\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/po/reqview.php?t=invtevnt\', handler:\'iframe\'});">'.$rinvtevntct.' event invite'; if($rinvtevntct>1){echo's';} echo'!</div>';
								}
								$rinvtcprojct = mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE u_id='$id' AND type='invtcproj'"), 0);
								if ($rinvtcprojct>0) {
									echo '<div align="left" class="blockbtn" style="font-size: 18px;" onclick="$(\'potraycont\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/po/reqview.php?t=invtcproj\', handler:\'iframe\'});">'.$rinvtcprojct.' Community Project invite'; if($rinvtcprojct>1){echo's';} echo'!</div>';
								}
						echo '</div>';
						} else { //if there are no requests/invites
							echo '<div align="left" id="poreqicont" style="padding-left: 8px;">none</div>';
						}
					echo '</div>
				</div>
				<div align="center" style="position: absolute; top: 0px; left: 0px; width: 140px; height: 30px; border-right: 2px solid #fff; cursor: pointer;" onclick="notifs.setRead(); if($(\'potraycont\').getStyles(\'visibility\').visibility==\'visible\'){ $(\'potraycont\').set(\'tween\', {duration: \'short\'}).fade(\'hide\'); }else{ $(\'potraycont\').set(\'tween\', {duration: \'short\'}).fade(\'show\'); }">
					<div align="right" id="po_badge" class="chat_badge" style="position: absolute; top: -16px; right: 0px; z-index: 110;'; if($poct==0){echo' display: none;';} echo'">'.$poct.'</div>
					<div id="poname" class="p24" style="position: absolute; top: 2px; width: 140px;">post office</div>
				</div>
				
				<div align="left" id="chat_tray" style="position: absolute; top: 0px; left: 142px; width: 643px; height: 30px;">';
					//load open chats
					$openchats = mysql_query ("SELECT DISTINCT c_id, open FROM mc_open WHERE u_id='$id' ORDER BY time_stamp ASC");
					$openchats_ct = mysql_result(mysql_query("SELECT COUNT(DISTINCT c_id) FROM mc_open WHERE u_id='$id' ORDER BY time_stamp ASC"), 0);
					while ($openchat = mysql_fetch_array ($openchats, MYSQL_ASSOC)) {
						$cid = $openchat['c_id'];
						echo '<div id="chat_pers'.$cid.'" align="center" style="float: right; width: 134px; height: 30px; border-left: 2px solid #fff;">
						<div style="position: relative; top: 0px; left: 0px;">
							<div class="p18" align="center" style="position: absolute; top: 6px; left: 0px; z-index: 100; width: 134px; height: 36px; cursor: pointer;" onclick="if($(\'chat_convocont'.$cid.'\').getStyles(\'visibility\').visibility==\'visible\'){ meechat.hideChat('.$cid.'); }else{ meechat.openChat('.$cid.'); }">'; if (strlen(returnpersonname($cid))>13) {echo substr(returnpersonname($cid), 0, 10).'...';}else{loadpersonnameclean($cid);} echo'</div>
							
							<div align="right" id="chat_badge'.$cid.'" class="chat_badge" style="position: absolute; top: -16px; right: 0px; z-index: 110; visibility: hidden; zoom: 1; opacity: 0;">0</div>
							
							<div align="left" id="chat_convocont'.$cid.'" class="chat_convocont" style="position: absolute; top: -288px; left: -30px; width: 258px; height: 280px;'; if($openchat['open']!='y'){echo' visibility: hidden; zoom: 1; opacity: 0;';} echo'">
								<div align="right" style="position: absolute; top: -16px; left: -3px; width: 264px; height: 16px; background-color: #C5C5C5;">
									<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="bottom">
									
									</td><td align="left" valign="bottom">
										<div align="center" onclick="meechat.closeChat('.$cid.');">x</div>
									</td></tr></table>
								</div>
								<div align="left" id="chat_convomain" style="position: absolute; top: 2px; left: 2px; width: 190px;">
							
										<div id="chat_thread'.$cid.'" style="height: 242px; width: 254px; overflow-x: none; overflow-y: scroll; border-bottom: 1px solid #C5C5C5;">';
											include ('externalfiles/meechat/grabmsgs.php');
										echo '</div>
										<div style="padding-top: 4px;">
											<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
												<input type="text" id="chat_chatter'.$cid.'" name="chat_chatter'.$cid.'" size="20" maxlength="900" autocomplete="off" onfocus="if (trim(this.value) == \'type chatter here\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type chatter here\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="type chatter here" />
											</td><td align="left" valign="center" style="padding-left: 4px;">
												<input type="button" id="chat_sendbtn'.$cid.'" value="send" onclick="if (trim($(\'chat_chatter'.$cid.'\').get(\'value\'))!=\'type chatter here\') { meechat.newMsg(\''.$cid.'\', encodeURIComponent($(\'chat_chatter'.$cid.'\').value) ); $(\'chat_chatter'.$cid.'\').set(\'value\', \'\');}" style="padding-left: 6px; padding-right: 6px;"/>
											</td></tr></table>
										</div>
								</div>
								<div align="left" style="position: absolute; top: 281px; left: 27px; width: 134px; height: 2px; border-bottom: 2px solid #C5C5C5;"></div>
							</div>
							<div style="position: absolute; top: 0px; left: 0px; z-index: 111; width: 134px; height: 30px; cursor: pointer;" onclick="if($(\'chat_convocont'.$cid.'\').getStyles(\'visibility\').visibility==\'visible\'){ meechat.hideChat('.$cid.'); }else{ meechat.openChat('.$cid.'); }"></div>
						</div>
						</div>';
					}
				echo '</div>
				
				<div align="left" id="chat_content" class="chatcontentarea" style="width: 209px; height: 450px; position: absolute; top: -454px; left: 787px; visibility: hidden; zoom: 1; opacity: 0;">
					<div align="left" style="padding-left: 2px; padding-top: 2px; padding-bottom: 2px; border-bottom: 2px solid #000; margin-bottom: 4px;">
						<table cellpadding="0" cellspacing="0" width="202px"><tr><td align="left" valign="center">
							<div id="mc_mainstat" class="chatgrp';
							if (mysql_result (mysql_query("SELECT COUNT(*) FROM mc_vis WHERE u_id='$id' LIMIT 1"), 0)>0) {
								$ison = true;
								echo 'On';
							}
							echo '" align="left" onclick="if(this.hasClass(\'chatgrp\')){this.set(\'class\', \'chatgrpOn\');$(\'mc_mainstat_text\').set(\'html\', \'Online\');}else{this.set(\'class\', \'chatgrp\');$(\'mc_mainstat_text\').set(\'html\', \'Offline\');} meechat.injectLoader(\'chat_main\'); loadcont(\'chat_main\', \''.$baseincpat.'externalfiles/meechat/togglestat.php?type=all\');"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" style="padding-left: 1px;"><div class="chat_statmain" style="height: 8px; width: 8px;"></div></td><td align="left" valign="center" id="mc_mainstat_text" style="padding-left: 2px;">Online</td></tr></table></div>
						</td><td align="right" valign="center" class="subtext" style="font-size: 13px;">
							sound: <span id="mc_sndOn" style="'; if (mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$id' AND mc_sound='y'"), 0)>0) {echo' font-size: 16px; color: #000; ';} echo'cursor: pointer;" onclick="meechat.toggleSound(\'on\');">on</span> | <span id="mc_sndOff" style="'; if (mysql_result(mysql_query("SELECT COUNT(*) FROM users WHERE user_id='$id' AND mc_sound IS NULL"), 0)>0) {echo' font-size: 16px; color: #000; ';} echo'cursor: pointer;" onclick="meechat.toggleSound(\'off\');">off</span>
						</td></tr></table>
					</div>';
					//to be added shortly...
					/*echo '<div align="left" style="padding-left: 6px; padding-top: 3px; padding-bottom: 3px; border-bottom: 1px solid #000;">
						<input type="text" id="chat_search" name="chat_search" size="21px" maxlength="60" onfocus="if (trim(this.value) == \'search by name here\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'search by name here\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="search by name here"/>
					</div>';*/
					echo '<div align="left" id="chat_main" style="height: 420px; width: 207px; overflow-x: none; overflow-y: scroll;">
						<div style="padding-top: 2px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
					</div>
				</div>
				
				<div align="center" style="position: absolute; top: 0px; left: 785px; width: 215px; height: 30px; border-left: 2px solid #fff; cursor: pointer;" onclick="if($(\'chat_content\').getStyles(\'visibility\').visibility==\'visible\'){ $(\'chat_content\').set(\'tween\', {duration: \'short\'}).fade(\'hide\'); }else{ meechat.loadlist(); $(\'chat_content\').set(\'tween\', {duration: \'short\'}).fade(\'show\'); }">
					<div align="center" style="position: absolute; top: 0px; left: 0px; width: 215px; height: 30px; border-right: 2px solid #fff; cursor: pointer;">
						<div class="p24" style="position: absolute; top: 2px; width: 215px;">meechat<span class="p18"> (<span id="mc_onlinect"></span> online)</span></div>
					</div>
				</div>
			</div>
		</div>';
				
		//header | navtray
		$this_page = basename($_SERVER['REQUEST_URI']);
		echo '<div class="headbarcontainter">
				<div class="navtray" align="left" valign="bottom">
					<div style="position: absolute; top: 4px; left: 109px; width: 50px; height: 38px; z-index: 100;" onmouseover="$(\'iconhome\').set(\'tween\', { transition: Fx.Transitions.Elastic.easeOut }).tween(\'top\', \'10\');$(\'namehome\').fade(\'show\');" onmouseout="$(\'iconhome\').tween(\'top\', \'4\');$(\'namehome\').fade(\'hide\');"><a href="'.$baseincpat.'home.php?" style="position: absolute; top: 0px; left: 0px; width: 50px; height: 38px; z-index: 1000;"></a></div>
					<div align="center" id="iconhome" class="navtrayicn'; if(substr($this_page, 0, 8)=='home.php'){echo'On';} echo'" style="position: absolute; top: 4px; left: 114px; height: 40px; background-image:url(\''.$baseincpat.'images/trayiconhome.png\'); background-repeat: no-repeat; background-position: 7px 4px"></div>
					<div style="position: absolute; top: 43px; left: 94px; width: 80px; text-align: center; visibility: hidden; zoom: 1; opacity: 0; filter:alpha(opacity=0);" id="namehome">Home</div>
					<div style="position: absolute; top: 4px; left: 159px; width: 50px; height: 38px; z-index: 100;" onmouseover="$(\'iconpeep\').set(\'tween\', { transition: Fx.Transitions.Elastic.easeOut }).tween(\'top\', \'10\');$(\'namepeep\').fade(\'show\');" onmouseout="$(\'iconpeep\').tween(\'top\', \'4\');$(\'namepeep\').fade(\'hide\');"><a href="'.$baseincpat.'mypeeple.php" style="position: absolute; top: 0px; left: 0px; width: 50px; height: 38px; z-index: 100;"></a></div>
					<div align="center" id="iconpeep" class="navtrayicn'; if(substr($this_page, 0, 12)=='mypeeple.php'){echo'On';} echo'" style="position: absolute; top: 4px; left: 164px; height: 40px; background-image:url(\''.$baseincpat.'images/trayiconmypeeple.png\'); background-repeat: no-repeat; background-position: 7px 4px"></div>
					<div style="position: absolute; top: 43px; left: 144px; width: 80px; text-align: center; visibility: hidden; zoom: 1; opacity: 0; filter:alpha(opacity=0);" id="namepeep">My Peeple</div>
					<div style="position: absolute; top: 4px; left: 209px; width: 50px; height: 38px; z-index: 100;" onmouseover="$(\'iconmfl\').set(\'tween\', { transition: Fx.Transitions.Elastic.easeOut }).tween(\'top\', \'10\');$(\'namemfl\').fade(\'show\');" onmouseout="$(\'iconmfl\').tween(\'top\', \'4\');$(\'namemfl\').fade(\'hide\');"><a href="'.$baseincpat.'meefile.php?id='.$id.'" style="position: absolute; top: 0px; left: 0px; width: 50px; height: 38px; z-index: 100;"></a></div>
					<div align="center" id="iconmfl" class="navtrayicn'; if(substr($this_page, 0, 15+strlen($id))=='meefile.php?id='.$id){echo'On';} echo'" style="position: absolute; top: 4px; left: 214px; height: 40px; background-image:url(\''.$baseincpat.'images/trayiconmeefile.png\'); background-repeat: no-repeat; background-position: 7px 4px"></div>
					<div style="position: absolute; top: 43px; left: 194px; width: 80px; text-align: center; visibility: hidden; zoom: 1; opacity: 0; filter:alpha(opacity=0);" id="namemfl">Meefile</div>
					<div style="position: absolute; top: 4px; left: 259px; width: 50px; height: 38px; z-index: 100;" onmouseover="$(\'iconcal\').set(\'tween\', { transition: Fx.Transitions.Elastic.easeOut }).tween(\'top\', \'10\');$(\'namecal\').fade(\'show\');" onmouseout="$(\'iconcal\').tween(\'top\', \'4\');$(\'namecal\').fade(\'hide\');"><a href="'.$baseincpat.'cal.php?" style="position: absolute; top: 0px; left: 0px; width: 50px; height: 38px; z-index: 100;"></a></div>
					<div align="center" id="iconcal" class="navtrayicn'; if((substr($this_page, 0, 7)=='cal.php')||(substr($this_page, 0, 9)=='event.php')){echo'On';} echo'" style="position: absolute; top: 4px; left: 264px; height: 40px; background-image:url(\''.$baseincpat.'images/trayiconcal.png\'); background-repeat: no-repeat; background-position: 7px 4px"></div>
					<div style="position: absolute; top: 43px; left: 244px; width: 80px; text-align: center; visibility: hidden; zoom: 1; opacity: 0; filter:alpha(opacity=0);" id="namecal">Calendar</div>
					<div style="position: absolute; top: 4px; left: 309px; width: 50px; height: 38px; z-index: 100;" onmouseover="$(\'iconcomm\').set(\'tween\', { transition: Fx.Transitions.Elastic.easeOut }).tween(\'top\', \'10\');$(\'namecomm\').fade(\'show\');" onmouseout="$(\'iconcomm\').tween(\'top\', \'4\');$(\'namecomm\').fade(\'hide\');"><a href="'.$baseincpat.'community.php?" style="position: absolute; top: 0px; left: 0px; width: 50px; height: 38px; z-index: 100;"></a></div>
					<div align="center" id="iconcomm" class="navtrayicn'; if((substr($this_page, 0, 13)=='community.php')||(substr($this_page, 0, 8)=='proj.php')){echo'On';} echo'" style="position: absolute; top: 4px; left: 314px; height: 40px; background-image:url(\''.$baseincpat.'images/trayiconcommunity.png\'); background-repeat: no-repeat; background-position: 7px 4px"></div>
					<div style="position: absolute; top: 43px; left: 294px; width: 80px; text-align: center; visibility: hidden; zoom: 1; opacity: 0; filter:alpha(opacity=0);" id="namecomm">Community</div>
				<div width="61px" style="position: absolute; top: 0px; left: 16px;">
					<a href="home.php?"><img src="'.$baseincpat.'images/headerlogo.png" /></a>
				</div><div width="61px" style="position: absolute; top: 0px; right: 6px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-top: 1px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left"><a href="'.$baseincpat.'help.php?"><div align="left" class="headlink'; if(substr($this_page, 0, 8)=='help.php'){echo'On';} echo'">help</div></a></td><td align="left"><a href="'.$baseincpat.'settings.php?"><div align="left" class="headlink'; if(substr($this_page, 0, 12)=='settings.php'){echo'On';} echo'">settings</div></a></td><td align="left"><a href="'.$baseincpat.'logout.php?id='.$id.'"><div align="left" class="headlink">logout</div></a></td></tr></table>
					</td><td align="right" valign="top" style="padding-left: 12px; padding-top: 6px;">
						<div align="left" id="msrch_inputcont"><input type="text" id="msrch" name="msrch" size="30px" maxlength="60" onfocus="if (trim(this.value) == \'search\') {this.value=\'\';};this.className=\'inputfocus\'; headerSearch.loadValues(); hs_focus=true;" onblur="if (trim(this.value) == \'\') {this.value=\'search\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';} hs_focus=false;" class="inputplaceholder" value="search"/></div>
					</td></tr></table>
				</div>
				</div>
		</div>';
	//main cont structure
	echo '<div class="contentarea" align="center">';

} else {
	echo '<body class="body">
	<div class="container" align="center" valign="top" style="padding-bottom: 52px;">';
	
	//show default header
	echo '<div class="headbarcontainter">
		<table cellpadding="0" cellspacing="0" width="1000px"><tr><td align="left" style="padding-left: 24px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left"><a href="index.php?"><img src="'.$baseincpat.'images/headerlogo.png" /></a><td align="left" style="padding-left: 18px;">
				<form method="get" action="'.$baseincpat.'signup.php"><input type="submit" value="Join Meesto!"/></form>
			</td></tr></table>
		</td><td align="right" valign="center" style="padding-right: 4px; padding-top: 2px;">
				<form action="'.$baseincpat.'login.php" method="post">
						<table cellpadding="0" cellspacing="0"><tr><td align="left">
								<table cellpadding="0" cellspacing="0"><tr><td align="left"><input type="text" id="email" name="email" size="20px" maxlength="40" value=""/></td><td align="left" style="padding-left: 16px;"><input type="password" id="password" name="password" size="20px" maxlength="20" /></td></tr></table>
						</td><td align="left" style="padding-left: 12px;">
							<input type="submit" class="meebtn" align="center" valign="center" name="login" value="login"/>
						</td></tr></table>
				</form>
		</td></tr></table>
	</div>';
	//main cont structure
	echo '<div class="contentarea" align="center">';
}
?>