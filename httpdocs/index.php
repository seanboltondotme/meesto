<?php
require_once('../externals/sessions/db_sessions.inc.php');

$title = 'Welcome :)';
include ('../externals/header/header.php');

echo '<div align="left" style="margin-left: 24px;">
	<div align="center" style="border-bottom: 1px solid #C5C5C5; padding-bottom: 12px; height: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
			<div style="width: 100px;"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="meestosocial">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
		</td><td align="left" valign="center" style="padding-right: 8px;">
			<a href="http://www.twitter.com/meestosocial"><img src="http://twitter-badges.s3.amazonaws.com/twitter-b.png" alt="Follow meestosocial on Twitter"/></a>
		</td><td align="left" valign="center" style="padding-left: 8px; border-left: 1px solid #C5C5C5;">
			<span class="st_facebook_hcount" displayText="Share"></span>
		</td><td align="left" valign="center" style="padding-left: 8px; padding-right: 8px;">
			<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.meesto.com%2F&amp;layout=button_count&amp;show_faces=true&amp;width=40&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:21px;" allowTransparency="true"></iframe>
		</td><td align="left" valign="center" style="padding-left: 8px; border-left: 1px solid #C5C5C5;">
			<span class="st_email_hcount" displayText="Email"></span>
		</td><td align="left" valign="center" style="padding-left: 6px;">
			<span class="st_sharethis_button" displayText="Share"></span>
		</td></tr></table>
	</div>
	
	<div align="left" style="margin-top: 14px; border-bottom: 2px solid #C5C5C5; padding-bottom: 16px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-right: 12px;"><img src="'.$baseincpat.'images/logo.png" /></td><td align="left" valign="top">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
				<div align="left" class="p24"><span style="font-size: 36px;">plans to be</span> an Nonprofit Open Source cloud-based <br />framework where you can safely and comfortably store, <br />manipulate, and share your personal data.</div>
				<div align="left" style="margin-top: 12px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="200px">Nonprofit so we never lose our focus on respecting you and respecting your data.</td><td align="left" valign="top" width="190px" style="padding-left: 18px;">Open Source so everyone can build and create a better Meesto together.</td><td align="left" valign="top" width="200px" style="padding-left: 24px;">Cloud-based so you can access your data anytime, anywhere.</td></tr></table>
				</div>
			</td><td align="left" valign="bottom" style="padding-left: 8px;">
					<div align="left">
						<form method="get" action="'.$baseincpat.'joinourteam.php"><input type="submit" value="help us!" style="height: 34px; width: 100%;"/></form>
					</div><div align="left" style="margin-top: 12px; width: 112px;">
						<form method="get" action="'.$baseincpat.'howyoucanhelp.php"><input type="hidden" name="t" value="development"/><input type="submit" value="learn more!" style="height: 34px; width: 100%;"/></form>
					</div>
			</td></tr></table>
		</td></tr></table>
	</div>
	
	<div align="left" style="margin-top: 18px; border-bottom: 2px solid #C5C5C5; padding-bottom: 16px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-right: 12px;"><img src="'.$baseincpat.'images/logo.png" /></td><td align="left" valign="top">
			<div align="left" style="font-size: 36px;">needs your help...</div>
			<div align="left" style="margin-top: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
					<form method="get" action="'.$baseincpat.'donate.php"><input type="submit" value="Donate!" style="height: 34px;"/></form>
				</td><td align="left" valign="center" style="padding-left: 41px;">
					<form method="get" action="'.$baseincpat.'donate.php"><input type="submit" value="Buy a Shirt!" style="height: 34px;"/></form>
				</td><td align="left" valign="center" style="padding-left: 41px;">
					<form method="get" action="'.$baseincpat.'joinourteam.php"><input type="submit" value="Join Our Open Source Dev. Team!" style="height: 34px;"/></form>
				</td><td align="left" valign="center" style="padding-left: 42px;">
					<form method="get" action="'.$baseincpat.'howyoucanhelp.php"><input type="submit" value="learn more" style="height: 34px;"/></form>
				</td></tr></table>
			</div>
		</td></tr></table>
	</div>
	
	<div align="left" style="margin-top: 18px; border-bottom: 2px solid #C5C5C5; padding-bottom: 16px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-right: 12px;"><img src="'.$baseincpat.'images/logo.png" /></td><td align="left" valign="top">
			<div align="left" class="p24"><span style="font-size: 36px;">is</span> a social networking tool where you control your data and you control the site (built on Open Source and Nonprofit ideals).</div>
			<div align="left" style="margin-top: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="200px">
					<img src="'.$baseincpat.'images/index/welcomevid.png" style="cursor: pointer;" onclick="PopBox.fromElement(this , {url: \'http://player.vimeo.com/video/15577223?byline=0&amp;portrait=0&amp;autoplay=1\', size: {x: 500, y: 281}, handler:\'iframe\'});$(\'pbox-loader\').set(\'styles\',{\'display\':\'none\'});"/>
				</td><td align="left" valign="top" width="200px" style="padding-left: 18px;">Meesto utilizes a tiered social graph which allows you to separate relationships such as friends and family &mdash; so you can easily connect with everyone in your life.</td><td align="left" valign="top" width="190px" style="padding-left: 24px;">Meesto is your social tool. We respect you, respect your data, and respect your privacy. Plus, you control what Meesto is and what its policies are.</td><td align="left" valign="center" style="padding-left: 8px;">
				<form method="get" action="'.$baseincpat.'about.php"><input type="submit" value="learn more" style="height: 34px; width: 100%;"/></form>
			</td></tr></table>
			</div>
		</td></tr></table>
	</div>
	
	<div align="left" style="margin-top: 18px; border-bottom: 2px solid #C5C5C5; padding-bottom: 16px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-right: 12px;"><img src="'.$baseincpat.'images/logo.png" /></td><td align="right" valign="center">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" style="font-size: 36px;">does not believe in fine print...</td><td align="right" valign="center" style="padding-left: 18px;">
					<form method="get" action="'.$baseincpat.'usage.php"><input type="submit" value=\'read our "Knowledge for Usage"\' style="height: 34px;"/></form>
				</td></tr></table>
		</td></tr></table>
	</div>
		
	
</div>';


include ('../externals/header/footer.php');
?>