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

$title = 'My Peeple';
$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/mypeeple/grab.php?\', \'y\', \'filter\');';
include ('../externals/header/header.php');
	
//main content
echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" id="filterlist" width="180px" style="padding-left: 76px;">
	<div align="left" class="filterOn" id="fltrelm-0" onclick="backcontrol.setState(\'0\');">
		<div align="left">all</div>
		<div align="left" class="underbar" style="background-color: #000;"></div>
	</div><div align="left" class="filter" id="fltrelm-c=mb"  onclick="backcontrol.setState(\'c=mb\');">
		<div align="left">my bubble</div>
		<div align="left" class="underbar" style="background-color: #F36;"></div>
	</div><div align="left" class="filter" id="fltrelm-s=frnd"  onclick="backcontrol.setState(\'s=frnd\');">
		<div align="left">friends</div>
		<div align="left" class="underbar" style="background-color: #FF951C;"></div>
	</div><div align="left" class="filter" id="fltrelm-s=fam"  onclick="backcontrol.setState(\'s=fam\');">
		<div align="left">family</div>
		<div align="left" class="underbar" style="background-color: #E9FF00;"></div>
	</div><div align="left" class="filter" id="fltrelm-s=prof"  onclick="backcontrol.setState(\'s=prof\');">
		<div align="left">professional</div>
		<div align="left" class="underbar" style="background-color: #00D02B;"></div>
	</div><div align="left" class="filter" id="fltrelm-s=edu"  onclick="backcontrol.setState(\'s=edu\');">
		<div align="left">education</div>
		<div align="left" class="underbar" style="background-color: #36F;"></div>
	</div><div align="left" class="filter" id="fltrelm-s=aqu"  onclick="backcontrol.setState(\'s=aqu\');">
		<div align="left">just met mee</div>
		<div align="left" class="underbar" style="background-color: #9D31E3;"></div>
	</div>';
	//get channels
		$channels = mysql_query("SELECT mpc_id, name FROM my_peeple_channels WHERE u_id='$id'");
		while ($channel = mysql_fetch_array ($channels, MYSQL_ASSOC)) {
			echo '<div align="left" class="filter" id="fltrelm-c='.$channel['mpc_id'].'"  onclick="backcontrol.setState(\'c='.$channel['mpc_id'].'\');">
				<div align="left">'.$channel['name'].'</div>
				<div align="left" class="underbar" style="background-color: #60CFDD;"></div>
			</div>';
		}
	echo '<div align="left" id="chancreatebtn" style="margin-top: 12px;">
		<input type="button" value="create new channel" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/mypeeple/createchannel.php\', size: {x: 660, y: 340}, handler:\'iframe\'});" style="padding-left: 12px; padding-right: 12px;"/>
	</div>
	
</td><td align="left" valign="top" id="maincontent" width="488px" style="padding-left: 26px;">';
	include ('externalfiles/mypeeple/grab.php');
echo '</td><td align="left" valign="top" width="227px" style="border-left: 3px solid #C5C5C5; padding-bottom: 36px;">
	<div align="left" style="padding-left: 10px;">
		<div align="left" class="p24">Import/Invite</div>
		<div align="left" class="p18" style="padding-top: 4px; padding-left: 18px;">Use this to import or invite your friends to join Meesto.</div>
	</div>
</td></td></table>';

include ('../externals/header/footer.php');
?>
