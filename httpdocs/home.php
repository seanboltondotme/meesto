<?php
require_once('../externals/sessions/db_sessions.inc.php');
require_once ('../externals/general/includepaths.php');

if ($_SESSION['user_id'] == NULL) {
	echo '<script type="text/javascript">
		window.location.href = \''.$baseincpat.'login.php?rel=\'+encodeURIComponent(window.location.pathname+window.location.search+window.location.hash);
	</script>
	<div align="left" valign="top" style="padding: 24px;">
		We were unable to redirect you. <form action="'.$baseincpat.'login.php?"><input type="submit" value="click here to login"/></form>
	</div>';
	exit();
}		

$title = 'Home';
$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/home/grabfeed.php?\', \'y\', \'topfltr\');
	if ((Browser.Platform.mac)&&(Browser.Engine.gecko)&&((Browser.Plugins.Flash.version<10)||((Browser.Plugins.Flash.version==10)&&(Browser.Plugins.Flash.build<85)))) {
		var newElem = new Element(\'div\', {\'align\': \'left\', \'class\': \'p18\', \'styles\': {\'padding-bottom\': \'4px\', \'border-bottom\': \'2px solid #C5C5C5\', \'margin-bottom\': \'18px\'}, \'html\': \'Please <a href="http://www.mozilla.com/plugincheck" target="_blank">check and update your Flash plugin version</a>.<br />The Meesto photo uploader will only work with the newest version.\'});newElem.inject($(\'leftcontent\'), \'top\');
	} else if (Browser.Engine.trident) {
		var newElem = new Element(\'div\', {\'align\': \'left\', \'class\': \'p18\', \'styles\': {\'padding-bottom\': \'4px\', \'border-bottom\': \'2px solid #C5C5C5\', \'margin-bottom\': \'18px\'}, \'html\': \'Some Internet Explorer users are experiencing problems, <a href="http://www.meesto.com/proj.php?id=5">we are working on those</a>.<br />We recommend using <a href="http://www.mozilla.com/firefox" target="_blank">Mozilla FireFox</a> or <a href="http://www.google.com/chrome" target="_blank">Google Chrome</a>.\'});newElem.inject($(\'leftcontent\'), \'top\');
	}';
include ('../externals/header/header.php');
$sid = session_id();

//main content
echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" id="leftcontent" width="686px" style="padding-left: 76px; padding-right: 8px;">
	<div align="left" style="border-bottom: 1px solid #C5C5C5;">
		<iframe width="100%" height="160px" align="center" id="postfeed'.$id.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/home/postfeed.php"></iframe>
	</div><div align="left" id="filterlist" style="padding-top: 14px; padding-bottom: 4px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
				<div align="center" id="fltrelm-0" class="topfltrOn" onclick="backcontrol.setState(\'0\');">
					<div align="center" class="title">all</div>
					<div align="center" class="bar"><div align="center" class="barclrfx"></div></div>
					<div align="center" class="arrow"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 6px;">
				<div align="center" id="fltrelm-f=mb" class="topfltr" onclick="backcontrol.setState(\'f=mb\');">
					<div align="center" class="title">my bubble</div>
					<div align="center" class="bar" style="background-color: #F36;"><div align="center" class="barclrfx"></div></div>
					<div align="center" class="arrow" style="background-color: #F36;"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 6px;">
				<div align="center" id="fltrelm-f=frnd" class="topfltr" onclick="backcontrol.setState(\'f=frnd\');">
					<div align="center" class="title">friends</div>
					<div align="center" class="bar" style="background-color: #FF951C;"><div align="center" class="barclrfx"></div></div>
					<div align="center" class="arrow" style="background-color: #FF951C;"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 6px;">
				<div align="center" id="fltrelm-f=fam" class="topfltr" onclick="backcontrol.setState(\'f=fam\');">
					<div align="center" class="title">family</div>
					<div align="center" class="bar" style="background-color: #E9FF00;"><div align="center" class="barclrfx"></div></div>
					<div align="center" class="arrow" style="background-color: #E9FF00;"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 6px;">
				<div align="center" id="fltrelm-f=prof" class="topfltr" onclick="backcontrol.setState(\'f=prof\');">
					<div align="center" class="title">professional</div>
					<div align="center" class="bar" style="background-color: #00D02B;"><div align="center" class="barclrfx"></div></div>
					<div align="center" class="arrow" style="background-color: #00D02B;"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 6px;">
				<div align="center" id="fltrelm-f=edu" class="topfltr" onclick="backcontrol.setState(\'f=edu\');">
					<div align="center" class="title">education</div>
					<div align="center" class="bar" style="background-color: #36F;"><div align="center" class="barclrfx"></div></div>
					<div align="center" class="arrow" style="background-color: #36F;"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td><td align="left" valign="top" style="padding-left: 6px;">
				<div align="center" id="fltrelm-f=aqu" class="topfltr" style="width: 100px;"onclick="backcontrol.setState(\'f=aqu\');">
					<div align="center" class="title" style="width: 100px;">just met mee</div>
					<div align="center" class="bar" style="width: 100px; background-color: #9D31E3;"><div align="center" class="barclrfx" style="width: 100px;"></div></div>
					<div align="center" class="arrow" style="background-color: #9D31E3;"><img src="'.$baseincpat.'images/topfltrarwcap.png"/></div>
				</div>
			</td></tr></table>
		</div><div align="left" id="maincontent" style="padding-top: 14px;">';
	include ('externalfiles/home/grabfeed.php');
echo '</div>
</td><td align="left" valign="top" width="227px" style="border-left: 3px solid #C5C5C5; padding-bottom: 36px;">
	<div style="padding-left: 10px;">';
	//alerts
		$alrt_ct = 0;
		//test for account alert count
		$alrts = NULL;
		//welcome and activation accouncement
		if (mysql_num_rows($actveq = @mysql_query ("SELECT user_id, UNIX_TIMESTAMP(ADDDATE(emailset_date, INTERVAL 30 DAY)) AS lastday FROM users WHERE user_id='$id' AND active!='yes' LIMIT 1"))>0) {
				$actve = @mysql_fetch_array ($actveq, MYSQL_ASSOC);
				$unixtimeleft = $actve['lastday'] - time();
				$dysleft = ceil($unixtimeleft/86400);
			$alrts[] = 'Welcome to Meesto!<br />You have '.$dysleft.' days left to activate your account.<br />Please check your email and click the activation link.</div><div align="right" style="width: 200px; padding-bottom: 1px;">
				<input type="button" value="resend activation" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/login/resndactv.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>';
			$alrt_ct++;
		}
		//accouncemnets
		$ancmts = mysql_query ("SELECT a.ancmt_id, a.body, DATE_FORMAT(a.time_stamp, '%Y-%m-%d') FROM announcements a LEFT JOIN hide_announcements ha ON ha.u_id='$id' AND ha.ancmt_id=a.ancmt_id WHERE ha.ha_id IS NULL ORDER BY ancmt_id ASC");
		while ($ancmt = mysql_fetch_array ($ancmts, MYSQL_ASSOC)) {
			$ancmtid = $ancmt['ancmt_id'];
			$alrts[] = '<div align="left" id="ancmtid'.$ancmtid.'" onmouseover="$(\'ancmtidbtn'.$ancmtid.'\').set(\'styles\',{\'display\':\'block\'});" onmouseout="$(\'ancmtidbtn'.$ancmtid.'\').set(\'styles\',{\'display\':\'none\'});">
				<div align="left" style="width: 200px; padding-bottom: 1px;">'.$ancmt['body'].'</div>
				<div align="right" id="ancmtidbtn'.$ancmtid.'" style="display: none; width: 200px;"><input type="button" value="hide this" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/home/hideancmt.php?id='.$ancmtid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
			</div>';
			$alrt_ct++;
		}
	if (($alrt_ct>0)) {
		echo '<div class="p24">Announcements</div>
		<div class="p18" style="padding-left: 12px; padding-bottom: 24px;">';
			foreach ($alrts as $alrt) {
				echo '<div align="left" style="width: 200px; padding-top: 8px; padding-bottom: 1px;">'.$alrt.'</div>';
			}
		echo '</div>';
	}
	
	
	//test for birthday
		$my_bdayq = @mysql_query ("SELECT DATE_FORMAT(birthday, '%Y') AS binfo FROM users WHERE user_id='$id' AND DATE_FORMAT(NOW(), '%m-%d')=DATE_FORMAT(birthday, '%m-%d') LIMIT 1");
		if (mysql_num_rows($my_bdayq)>0) {
				$my_bday = @mysql_fetch_array ($my_bdayq, MYSQL_ASSOC);
			$cur_year=date("Y");
			$dob_year=$my_bday['binfo'];
			$newage = $cur_year-$dob_year;
						//nice age
							if (substr($newage, -1)=='1') {
								if (substr($newage, 0, 1)=='1') {
									$suffix = 'th';
								} else {
									$suffix = 'st';
								}
							} elseif (substr($newage, -1)=='2') {
								if (substr($newage, 0, 1)=='1') {
									$suffix = 'th';
								} else {
									$suffix = 'nd';
								}
							} elseif (substr($newage, -1)=='3') {
								if (substr($newage, 0, 1)=='1') {
									$suffix = 'th';
								} else {
									$suffix = 'rd';
								}
							} else {
								$suffix = 'th';
							}
			$alrts[] = '<div class="p24">It\'s Your Birthday!</div>
			<div class="p18" style="padding-top: 6px; padding-left: 12px; padding-bottom: 24px;">Happy '.$newage.$suffix.' Birthday Today!</div>';
		}
	
	//calendar
		echo '<div class="p24">Calendar</div>
		<div class="p18" style="padding-top: 6px; padding-left: 12px;">';
			$mycals = mysql_query("(SELECT DISTINCT DATE_ADD(u.birthday, INTERVAL YEAR(CURRENT_DATE)-YEAR(u.birthday) YEAR) date, 'bday' type, u.user_id id, DATE_FORMAT(DATE_ADD(u.birthday, INTERVAL YEAR(CURRENT_DATE)-YEAR(u.birthday) YEAR), '%a, %b %D') d1, DATE_FORMAT(u.birthday, '%Y-%m-%d') d2, '' name FROM users u INNER JOIN my_peeple mp ON u.user_id=mp.p_id AND mp.u_id='$id' INNER JOIN meefile_infosec_vis miv ON u.user_id=miv.u_id AND miv.sec='genbday' INNER JOIN peep_streams ps ON ps.u_id=u.user_id AND ps.p_id='$id' LEFT JOIN mpc_mems mpc ON u.user_id=mpc.p_id LEFT JOIN meefile_infosec_vis miv2 ON u.user_id=miv2.u_id AND miv2.sec='genbday' AND miv2.type='user' AND miv2.ref_id='$id' WHERE ((miv.type='pub' AND miv.sub_type='y') OR (((miv.type='strm' AND miv.sub_type=ps.stream) OR (miv.type='chan' AND miv.ref_id=mpc.mpc_id)) AND (miv2.misvis_id IS NULL))) AND (IF (TO_DAYS(DATE_ADD(u.birthday, INTERVAL YEAR(CURRENT_DATE)-YEAR(u.birthday) YEAR)) - TO_DAYS(CURRENT_DATE) > 0, TO_DAYS(DATE_ADD(u.birthday, INTERVAL YEAR(CURRENT_DATE)-YEAR(u.birthday) YEAR)) - TO_DAYS(CURRENT_DATE) BETWEEN 0 AND 20, TO_DAYS(DATE_ADD(u.birthday, INTERVAL YEAR(CURRENT_DATE)-YEAR(u.birthday)+1 YEAR)) - TO_DAYS(CURRENT_DATE) BETWEEN 0 AND 20))) UNION (SELECT e.start_date date, 'evnt' type, e.e_id id, DATE_FORMAT(e.start_date, '%a, %b %D, %Y at %l:%i%p') d1, DATE_FORMAT(e.end_date, '%b %D, %Y at %l:%i%p') d2, e.name name FROM events e INNER JOIN event_owners eo ON e.e_id=eo.e_id AND eo.u_id='$id' AND eo.rsvp IS NOT NULL AND NOW()<=e.end_date) ORDER BY date ASC");
			$lastdate = NULL;
				//set date vars
				date_default_timezone_set('America/Los_Angeles');
				$cur_year=date("Y");
				$cur_month=date("m");
				$cur_day=date("d");
			while ($mycal = mysql_fetch_array ($mycals, MYSQL_ASSOC)) {
				if ($mycal['type']=='evnt') {
					
					$thisdate = substr($mycal['d1'], 0, strpos($mycal['d1'], ',', 5));
					if ($thisdate!=$lastdate) {
						echo '<div align="left" class="subtext" style="'; if($lastdate!=''){echo'padding-top: 8px;';} echo'padding-bottom: 4px;">'.$thisdate.'</div>';
						$lastdate = $thisdate;
					}
					echo '<div align="left" style="padding-left: 8px; padding-bottom: 1px;"><a href="'.$baseincpat.'event.php?id='.$mycal['id'].'">'.$mycal['name'].'</a></div><div align="right" class="subtext" style="font-size: 13px; padding-bottom: 4px;">from '; 
					$estart = substr($mycal['d1'], 5);
					if (substr($estart, -7)=='12:00AM') {
						$systerdy = strtotime("-1 day", strtotime(trim(substr($estart, 0, 14))));
						$sdate = date("M jS, Y", $systerdy);
						$estart = $sdate.' at Midnight';
					} elseif (substr($estart, -7)=='12:00PM') {
						$estart = trim(substr($estart, 0, 14)).' at Noon';
					}
					if (substr($mycal['d2'], -7)=='12:00AM') {
						$eysterdy = strtotime("-1 day", strtotime(trim(substr($mycal['d2'], 0, 14))));
						$edate = date("M jS, Y", $eysterdy);
						$mycal['d2'] = $edate.' at Midnight';
					} elseif (substr($mycal['d2'], -7)=='12:00PM') {
						$mycal['d2'] = trim(substr($mycal['d2'], 0, 14)).' at Noon';
					}
					$estartcln = substr($estart, strpos($estart, 'at')+2);
					if(substr($estart, 0, 14)==substr($mycal['d2'], 0, 14)){echo $estartcln.' until '.trim(substr($mycal['d2'], 16));}else{echo $estartcln.' to '.$mycal['d2'];} 
				echo'</div>';
					
				} else {
						//get age
							$dob_year=substr($mycal['d2'], 0, 4);
							$dob_month=substr($mycal['d2'], 5, 2);
							$dob_day=substr($mycal['d2'], 8, 2);
							
							if($cur_month>$dob_month || ($dob_month==$cur_month &&$cur_day>=$dob_day)) {
								$age = $cur_year-$dob_year;
							} else {
								$age = $cur_year-$dob_year;
							}
						if($cur_day==$dob_day){$thisdate='Today!';}else{$thisdate=$mycal['d1'];}
						if ($thisdate!=$lastdate) {
							echo '<div align="left" class="subtext" style="'; if($lastdate!=''){echo'padding-top: 8px;';} echo'padding-bottom: 4px;">'.$thisdate.'</div>';
							$lastdate = $thisdate;
						}
						echo '<div align="left" style="padding-left: 8px; padding-bottom: 1px;">'; loadpersonname($mycal['id']); echo '\'s ';
						echo $mycal['birthday'];
							//get suffix
							if (substr($age, -1)=='1') {
								if (substr($age, 0, 1)=='1') {
									$bdaysfx = 'th';
								} else {
									$bdaysfx = 'st';
								}
							} elseif (substr($age, -1)=='2') {
								if (substr($age, 0, 1)=='1') {
									$bdaysfx = 'th';
								} else {
									$bdaysfx = 'nd';
								}
							} elseif (substr($age, -1)=='3') {
								if (substr($age, 0, 1)=='1') {
									$bdaysfx = 'th';
								} else {
									$bdaysfx = 'rd';
								}
							} else {
								$bdaysfx = 'th';
							}
							
						echo $age.$bdaysfx.' bday</div>';
				}
			}
			if (mysql_num_rows($mycals)==0) {
				echo '<div align="left" style="padding-top: 8px;">nothing in the near future, relax :)</div>';
			}
		echo '</div>
	</div>
</td></tr></table>';

include ('../externals/header/footer.php');
?>