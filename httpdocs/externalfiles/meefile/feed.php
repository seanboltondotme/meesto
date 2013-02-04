<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (isset($_GET['vid'])&&is_numeric($_GET['vid'])) {
	echo '<div align="left" style="width: 708px; margin-left: 162px; margin-top: 28px;">
			<input type="button" value="view all" onclick="window.location.href=\''.$baseincpat.'meefile.php?id='.$uid.'&t=feed\';"/>
		</div>';
} elseif ($uid==$id) {
	echo '<div align="left" style="width: 708px; margin-left: 162px; margin-top: 28px; border-bottom: 1px solid #C5C5C5;">
		<iframe width="100%" height="160px" align="center" id="postfeed'.$id.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/home/postfeed.php?mf=y"></iframe>
	</div><div align="left" id="filterlist" style="margin-top: 28px; margin-left: 162px; padding-bottom: 4px;">
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
		</div>';
}
		
echo '<div align="left" id="maincontent" style="margin-left: 162px; margin-top: 28px;">';
	include ('externalfiles/meefile/grabfeed.php');
echo '</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>