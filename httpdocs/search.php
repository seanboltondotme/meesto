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

$title = 'Search';
$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/home/grabfeed.php?\');';
include ('../externals/header/header.php');

$q = escape_data(urldecode($_GET['q']));

//main content
echo '<div align="left" style="width: 760px;">
<div align="left" class="p24" style="margin-bottom: 4px;">Search Meesto</div><div align="left" style="padding-bottom: 6px; border-bottom: 1px solid #C5C5C5;">
	<form action="'.$baseincpat.'search.php" method="get">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
			<input type="text" name="q" size="68" maxlength="1000" autocomplete="off" onfocus="if (trim(this.value) == \'type name here to search...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type name here to search...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" style="font-size: 18px;"';
				if ($q!=''){echo'value="'.$q.'"';}else{echo'class="inputplaceholder" value="type name here to search..."';}
			echo '/>
		</td><td align="left" valign="center" style="padding-left: 12px;">
			<input type="submit" value="search"/>
		</td></tr></table>
	</form>
</div>
<div align="center" style="margin-top: 18px;">';
	include('externalfiles/search/grab.php');
echo '</div>
</div>';

include ('../externals/header/footer.php');
?>