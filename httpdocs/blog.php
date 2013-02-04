<?php
require_once('../externals/sessions/db_sessions.inc.php');
require_once ('../externals/general/includepaths.php');

$title = 'Blog';
include ('../externals/header/header.php');

//main content
echo '<div align="left" style="width: 780px;">
<div align="center" class="p24" style="font-size: 42px;">Meesto Blog</div>
<div align="center" class="subtext" style="margin-top: 4px;">A blog on the happenings of Meesto.</div>
<div align="left" style="margin-top: 24px; line-height: 32px;">';

$blogs = mysql_query("SELECT u_id, title, content, DATE_FORMAT(time_stamp, '%b %D, %Y') AS time FROM meesto_blog ORDER BY mb_id DESC");
while ($blog = mysql_fetch_array ($blogs, MYSQL_ASSOC)) {
	echo '<div align="left" class="p24" style="margin-top: 32px; height: 22px;">'.$blog['title'].'</div>
	<div align="left" style="margin-left: 4px; font-size: 14px;" class="subtext">by '; loadpersonname($blog['u_id']); echo' on '.$blog['time'].'</div><div align="left" style="margin-left: 24px;">
		'.nl2br($blog['content']).'
	</div>';
}
	
echo '</div>
</div>';

include ('../externals/header/footer.php');
?>