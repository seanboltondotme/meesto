<?php
require_once('../externals/sessions/db_sessions.inc.php');

$title = 'How You Can Help!';
include ('../externals/header/header.php');

if (isset($_GET['t'])) {
	$t = escape_data($_GET['t']);	
} else {
	$t = '';	
}

echo '<div align="left" style="margin-left: 54px;">
	<div align="left" style="margin-top: 6px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="center" valign="center" class="p24">How You Can Help Make</td><td align="center" valign="center" style="padding-right: 12px;"><img src="'.$baseincpat.'images/logo.png" /></td></tr></table>
	</div>
		
	<div align="left" valign="bottom" class="p24" style="width: 900px; border-bottom: 1px solid #C5C5C5; margin-top: 24px; padding-left: 8px; height: 29px;">
		<ul class="mftabul">
			<li class="mftabli" style="padding-right: 24px;"><div class="mftabliico"></div><a href="'.$baseincpat.'howyoucanhelp.php?">general</a></li>
			<li class="mftabli" style="padding-right: 24px;"><div class="mftabliico"></div><a href="'.$baseincpat.'donate.php?">donate/support</a></li>
			<li class="mftabli" style="padding-right: 24px;"><div class="mftabliico"></div><a href="'.$baseincpat.'howyoucanhelp.php?t=development">open source development</a></li>
			<li class="mftabliOn"><div class="mftabliico"></div><a href="'.$baseincpat.'jobs.php?">corporate positions</a></li>
		</ul>
	</div>
</div>';

echo '<div align="left" style="padding-top: 24px; margin-left: 68px;">';
	
	include ('externalfiles/hych/corporate.php');

echo '</div>';


include ('../externals/header/footer.php');
?>