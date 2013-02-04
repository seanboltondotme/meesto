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

$title = 'Welcome';
$pdrjs = '$(\'meelogo\').fade(\'hide\');
	$(\'choices\').fade(\'hide\');
	$(\'meelogo\').fade(\'show\');
	$(\'meelogo\').set(\'tween\', { transition: Fx.Transitions.Elastic.easeOut }).tween(\'top\', \'94\');
	$(\'choices\').set(\'tween\', { duration: 10000 }).tween(\'opacity\', \'100\');';
include ('../externals/header/header.php');

$state = strip_tags(escape_data($_GET['state']));

//main structure
echo '<div id="contentcontain" style="width: 900px; margin-top: 150px;">';

//content area
echo '<div id="wlcm" class="crumb14" style="position: absolute; top: 80px; left: 300px;">'.$_SESSION['name'].', we\'d like to welcome you to...</div>
<div id="meelogo" style="position: absolute; top: -300px; left: 370px;"><img src="'.$baseincpat.'images/welcome/logolarge.png"/></div>
<div align="center" style="padding-bottom: 18px;">
		<iframe src="http://player.vimeo.com/video/15577223?byline=0&amp;portrait=0&amp;autoplay=1" width="500" height="281" frameborder="0"></iframe>
	</div>
<div align="center" id="choices" style="padding-top: 18px; border-top: 1px solid #C5C5C5;">
	<div align="center" class="p18" style="width: 680px; padding-bottom: 18px;">Your default visibility settings have been set to "my bubble" and "friends" you can change this at any time by clicking the visibility button next to your content.</div>
	<div align="center" class="p24" style="padding-top: 18px; border-top: 1px solid #C5C5C5; font-size: 42px;">Knowledge For Usage</div>';
		include('externalfiles/usage/body.php');
	echo '</div>';

//main structure
echo '</div>';

include ('../externals/header/footer.php');
?>
