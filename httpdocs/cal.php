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

$title = 'Calendar';
$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/cal/grab.php?\');';
include ('../externals/header/header.php');
	
//main content
echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="694px" style="padding-left: 76px;">
	<div align="right" style="padding-right: 22px; padding-bottom: 14px;">
		<input type="button" id="addnewcustsec" value="create event" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/cal/createevent.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
	</div><div align="left" id="maincontent">';
	include ('externalfiles/cal/grab.php');
echo '</div>
</td><td align="left" valign="top" width="227px" style="border-left: 3px solid #C5C5C5; padding-bottom: 36px;">
	<div style="padding-left: 10px;">
		<div class="p24">Birthdays</div>
		<div class="p18" style="padding-top: 6px; padding-left: 12px;">';
			$ucbdays = mysql_query("SELECT u.user_id, DATE_FORMAT(DATE_ADD(u.birthday, INTERVAL YEAR(CURRENT_DATE)-YEAR(u.birthday) YEAR), '%a, %b %D') AS bday, DATE_FORMAT(u.birthday, '%Y-%m-%d') AS binfo FROM users u INNER JOIN my_peeple mp ON u.user_id=mp.p_id AND mp.u_id='$id' INNER JOIN meefile_infosec_vis miv ON u.user_id=miv.u_id AND miv.sec='genbday' INNER JOIN peep_streams ps ON ps.u_id=u.user_id AND ps.p_id='$id' LEFT JOIN mpc_mems mpc ON u.user_id=mpc.p_id LEFT JOIN meefile_infosec_vis miv2 ON u.user_id=miv2.u_id AND miv2.sec='genbday' AND miv2.type='user' AND miv2.ref_id='$id' WHERE ((miv.type='pub' AND miv.sub_type='y') OR (((miv.type='strm' AND miv.sub_type=ps.stream) OR (miv.type='chan' AND miv.ref_id=mpc.mpc_id)) AND (miv2.misvis_id IS NULL))) AND (IF (TO_DAYS(DATE_ADD(u.birthday, INTERVAL YEAR(CURRENT_DATE)-YEAR(u.birthday) YEAR)) - TO_DAYS(CURRENT_DATE) > 0, TO_DAYS(DATE_ADD(u.birthday, INTERVAL YEAR(CURRENT_DATE)-YEAR(u.birthday) YEAR)) - TO_DAYS(CURRENT_DATE) BETWEEN 0 AND 20, TO_DAYS(DATE_ADD(u.birthday, INTERVAL YEAR(CURRENT_DATE)-YEAR(u.birthday)+1 YEAR)) - TO_DAYS(CURRENT_DATE) BETWEEN 0 AND 20)) ORDER BY u.birthday ASC");
			$lastdate = NULL;
			while ($ucbday = mysql_fetch_array ($ucbdays, MYSQL_ASSOC)) {
				//get age
					date_default_timezone_set('America/Los_Angeles');
					
					$cur_year=date("Y");
					$cur_month=date("m");
					$cur_day=date("d");
					
					$dob_year=substr($ucbday['binfo'], 0, 4);
					$dob_month=substr($ucbday['binfo'], 5, 2);
					$dob_day=substr($ucbday['binfo'], 8, 2);
					
					if($cur_month>$dob_month || ($dob_month==$cur_month &&$cur_day>=$dob_day)) {
						$age = $cur_year-$dob_year;
					} else {
						$age = $cur_year-$dob_year;
					}
				if($cur_day==$dob_day){$thisdate='Today!';}else{$thisdate=$ucbday['bday'];}
				if ($thisdate!=$lastdate) {
					echo '<div align="left" class="subtext" style="'; if($lastdate!=''){echo'padding-top: 6px;';} echo'padding-bottom: 4px;">'.$thisdate.'</div>';
					$lastdate = $thisdate;
				}
				echo '<div align="left" style="padding-left: 8px; padding-bottom: 8px;">'; loadpersonname($ucbday['user_id']); echo '\'s ';
				echo $ucbday['birthday'];
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
					
				echo $age.$bdaysfx.'</div>';
			}
		echo '</div>
		<div class="p24" style="padding-top: 32px;">Past Events</div>
		<div id="pastevntsmain" style="padding-top: 6px; padding-left: 12px;">';
			include ('externalfiles/cal/grabpast.php');
		echo '</div>
	</div>
</td></tr></table>';

include ('../externals/header/footer.php');
?>
