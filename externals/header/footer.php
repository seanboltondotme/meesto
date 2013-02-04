<?php
//main cont structure
echo '</div>';

//footer
	echo '<div align="center" style="padding-top: 48px;">
		<table cellpadding="0" cellspacing="0" width="952px" class="subtext"><tr><td align="left" style="color: #C5C5C5; font-size: 11px;">a Sean Bolton creation | Meesto: &copy; Sean Bolton 2011. All content: &copy; its respective owner.</td><td align="right">
			<table cellpadding="0" cellspacing="0"><tr><td align="left"><a title="Support Meesto by making a financial contribution" href="'.$baseincpat.'donate.php?"><div align="left" class="footlink">donate</div></a></td><td align="left"><a title="Learn about becoming a part of the Meesto team" href="'.$baseincpat.'howyoucanhelp.php?"><div align="left" class="footlink">how you can help</div></a></td><td align="center" valign="center" style="padding-left: 8px; padding-right: 8px;"><div style="width: 4px; height: 4px; background-color: #C5C5C5;"></div></td><td align="left"><a title="about Meesto" href="'.$baseincpat.'about.php?"><div align="left" class="footlink">about</div></a></td><td align="left"><a title="Meesto blog" href="'.$baseincpat.'blog.php?"><div align="left" class="footlink">blog</div></a></td><td align="left"><a title="Knowledge For Usage" href="'.$baseincpat.'usage.php?"><div align="left" class="footlink">usage</div></a></td><td align="left"><a title="Meesto help" href="'.$baseincpat.'help.php?"><div align="left" class="footlink">help</div></a></td></tr></table>
		</td></tr></table>
	</div>';

echo '</body>
</html>';

session_write_close();
exit();
?>