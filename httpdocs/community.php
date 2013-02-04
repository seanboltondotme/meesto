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

$title = 'Community';
$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/community/grab.php?\', \'y\', \'filter\');';
include ('../externals/header/header.php');
	
//main content
echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="694px" style="padding-left: 76px;">
	<div align="left" style="border-bottom: 1px solid #C5C5C5; padding-bottom: 12px; margin-right: 14px; margin-bottom: 14px;">
		<div align="left" style="font-size: 36px;">Welcome to the Meesto Community!</div>
		<div align="left">This is where you decide what happens with Meesto &mdash; make your voice heard! <a href="'.$baseincpat.'community.php?action=learn">learn more</a></div>';
		//new community user msg !important
		echo '<div align="left" style="padding-top: 12px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
				<input type="button" value="create a new project" style="padding-left: 6px; padding-right: 6px;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/createproj.php?\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
			</td><td align="left" valign="center" style="padding-left: 22px;">
				<input type="button" value="provide feedback" style="padding-left: 6px; padding-right: 6px;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/providefeedback.php?\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
			</td><td align="left" valign="center" style="padding-left: 22px;">
				<input type="button" value="report a bug" style="padding-left: 10px; padding-right: 10px;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/reportbug.php?\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
			</td><td align="left" valign="center" style="padding-left: 22px;">
				<input type="text" id="comsrch" name="comsrch" size="20" maxlength="200" onfocus="if (trim(this.value) == \'search community\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'search community\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="" class="inputplaceholder" value="search community"/>
			</td></tr></table>
		</div>
	</div>
	<div align="left" style="padding-top: 12px;">';
			if (isset($_GET['action'])&&($_GET['action']=='learn')) {
				include ('externalfiles/community/learn.php');
			} else {
				echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" id="filterlist" width="180px">
					<div align="left" class="filterOn" id="fltrelm-0" onclick="backcontrol.setState(\'0\');">
						<div align="left">all projects</div>
						<div align="left" class="underbar" style="background-color: #000;"></div>
					</div><div align="left" class="filter" id="fltrelm-f=mp"  onclick="backcontrol.setState(\'f=mp\');">
						<div align="left">my projects</div>
						<div align="left" class="underbar" style="background-color: #000;"></div>
					</div><div align="left" class="filter" id="fltrelm-f=fdbk"  onclick="backcontrol.setState(\'f=fdbk\');">
						<div align="left">feedback</div>
						<div align="left" class="underbar" style="background-color: #000;"></div>
					</div><div align="left" class="filter" id="fltrelm-f=bug"  onclick="backcontrol.setState(\'f=bug\');">
						<div align="left">bugs</div>
						<div align="left" class="underbar" style="background-color: #000;"></div>
					</div>
				</td><td align="left" valign="top" id="maincontent" width="488px" style="padding-left: 26px;">';
					include ('externalfiles/community/grab.php');
				echo '</td></tr></table>';
			}
	echo '</div>
</td><td align="left" valign="top" width="227px" style="border-left: 3px solid #C5C5C5; padding-bottom: 36px;">
	<div align="left" style="padding-left: 10px;">
		<div align="left" class="p24">Meesto Blog</div>
		<div align="left" class="p18" style="padding-top: 4px; padding-left: 18px;">Stay in-the-know.</div>
		<div align="left" class="p18" style="padding-top: 4px; padding-left: 18px;"><a href="'.$baseincpat.'blog.php?"><input type="button" value="view Meesto Blog" onclick=""/></a></div>
	</div>
	<div align="left" style="padding-left: 10px; padding-top: 24px;">
		<div align="left" class="p24">How You Can Help</div>
		<div align="left" class="p18" style="padding-top: 4px; padding-left: 18px;">If you\'re interested in helping create Meesto, this is how you can.</div>
		<a href="'.$baseincpat.'howyoucanhelp.php?"><div align="right" style="padding-right: 22px;">learn more</div></a>
		<div align="left" class="p18" style="padding-top: 4px; padding-left: 18px;"><a href="'.$baseincpat.'howyoucanhelp.php?"><input type="button" value="how you can help"/></a></div>
	</div>
	<div align="left" style="padding-left: 10px; padding-top: 24px;">
		<div align="left" class="p24">Support/Donate</div>
		<div align="left" class="p18" style="padding-top: 4px; padding-left: 18px;">Buy a shirt and/or make a financial contribution</div>
		<a href="'.$baseincpat.'donate.php?"><div align="right" style="padding-right: 22px;">learn more</div></a>
		<div align="center" class="p18" style="padding-top: 4px; padding-right: 18px;"><form action="http://meesto.spreadshirt.com/" target="_blank"><input type="submit" value="get a shirt!"/></form></div>
		<div align="center" class="p18" style="padding-top: 8px; padding-right: 18px;"><a href="'.$baseincpat.'donate.php?"><input type="button" value="donate" style="padding-left: 8px; padding-right: 8px;"/></a></div>
	</div>
	<div align="left" style="padding-left: 10px; padding-top: 24px;">
		<div align="left" class="p24">Meesto (2.0)</div>
		<div align="left" class="p18" style="padding-top: 4px; padding-left: 18px;">Meesto (2.0) will have its own section of the community. This will be coming soon.</div>
	</div>
</td></tr></table>';

include ('../externals/header/footer.php');
?>
