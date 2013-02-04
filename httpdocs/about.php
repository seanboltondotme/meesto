<?php
require_once('../externals/sessions/db_sessions.inc.php');

$title = 'About Meesto :)';
include ('../externals/header/header.php');

if (isset($_GET['t'])) {
	$t = escape_data($_GET['t']);	
} else {
	$t = '';	
}

echo '<div align="left" style="margin-left: 54px;">
	<div align="left" style="margin-top: 6px;">
		
		<table cellpadding="0" cellspacing="0"><tr><td align="center" valign="center" class="p24">About</td><td align="center" valign="center" style="padding-right: 12px;"><img src="'.$baseincpat.'images/logo.png" /></td><td align="left" valign="center" class="p24" style="border-left: 3px solid #C5C5C5; padding-left: 12px;">Contact</td><td align="center" valign="center"><img src="'.$baseincpat.'images/logo.png" /></td><td align="left" valign="center" class="p24">: <a href="mailto:sbolton@meesto.com">Sean Bolton</a> <span style="font-size: 18px;">(sbolton [at] meesto [dot] com)</span></td></tr></table>
	</div>
	
	<div align="left" style="font-size: 18px; line-height: 26px; padding-top: 12px;">Meesto is (and always will be) a free tool, supported by <a href="'.$baseincpat.'donate.php">donations</a>.</div>
	
	<div align="left" valign="bottom" class="p24" style="width: 900px; border-bottom: 1px solid #C5C5C5; margin-top: 24px; padding-left: 8px; height: 29px;">
		<ul class="mftabul">
			<li class="mftabli'; if($t==''){echo'On';} echo'" style="padding-right: 32px;"><div class="mftabliico"></div><a href="'.$baseincpat.'about.php?">about</a></li>
			<li class="mftabli'; if($t=='mission'){echo'On';} echo'" style="padding-right: 32px;"><div class="mftabliico"></div><a href="'.$baseincpat.'about.php?t=mission">our mission</a></li>
			<li class="mftabli'; if($t=='future'){echo'On';} echo'" style="padding-right: 32px;"><div class="mftabliico"></div><a href="'.$baseincpat.'about.php?t=future">the future</a></li>
			<li class="mftabli'; if($t=='company'){echo'On';} echo'" style="padding-right: 32px;"><div class="mftabliico"></div><a href="'.$baseincpat.'about.php?t=company">the company</a></li>
			<li class="mftabli'; if($t=='funding'){echo'On';} echo'" style="padding-right: 32px;"><div class="mftabliico"></div><a href="'.$baseincpat.'about.php?t=funding">our funding</a></li>
			<li class="mftabli">
				<div style="position: absolute; top: 0px; left: 18px;"><form method="get" action="'.$baseincpat.'howyoucanhelp.php"><input type="submit" value="Help Us" style="font-size: 24px; height: 42px; padding-left: 8px;  padding-right: 8px;"/></form></div>
			</li>
		</ul>
	</div>
</div>';

echo '<div align="left" style="padding-top: 24px; margin-left: 68px;">';
	
	if ($t=='mission') {
		include ('externalfiles/about/mission.php');
	} elseif ($t=='future') {
		include ('externalfiles/about/future.php');
	} elseif ($t=='company') {
		include ('externalfiles/about/company.php');
	} elseif ($t=='funding') {
		include ('externalfiles/about/funding.php');
	} else {
		include ('externalfiles/about/about.php');
	}

echo '</div>';


include ('../externals/header/footer.php');
?>