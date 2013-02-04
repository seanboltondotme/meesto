<?php
	$emailercontainer = '<html>
	<head>
	<title>HTML email</title>
	</head>
	<body width="100%" style="background-color: #f6f6f6; font: 16px Arial, Helvetica, sans-serif; color: #000; line-height: 16px; padding: 6px;">
		<div style="margin: 12px;">
			<div align="left" style="background-color: #000000; padding-left: 12px;">
				<a title="Meesto" href="'.$baseincpat.'"><img src="'.$baseincpat.'images/emails/00/logo.png" alt="meesto" style="border: none;"/></a>
			</div><div align="left" style="padding: 12px; background-color: #ffffff; border-left: 1px solid #C5C5C5; border-bottom: 1px solid #C5C5C5; border-right: 1px solid #C5C5C5; font: 16px Arial, Helvetica, sans-serif;">
					'.$emailercontent.'
			</div><div align="left" style="padding-top: 12px;">
				<table cellpadding="0" cellspacing="0" width="100%"><tr><td align="left" valign="top" style="color: #C5C5C5; font: 11px Arial, Helvetica, sans-serif;">Meesto: &copy; Sean Bolton 2010.<br />All content: &copy; its respective owner.</td><td align="right" valign="top">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" style="color: #C5C5C5; font: 11px Arial, Helvetica, sans-serif;"><a title="Support Meesto by making a financial contribution" href="'.$baseincpat.'donate.php?">donate</a> | <a title="Learn about becoming a part of the Meesto team" href="'.$baseincpat.'howyoucanhelp.php?">how you can help</a></td><td align="center" valign="center" style="padding-left: 8px; padding-right: 8px;"><div style="width: 4px; height: 4px; background-color: #C5C5C5;"></div></td><td align="left" style="color: #C5C5C5; font: 11px Arial, Helvetica, sans-serif;"><a title="about Meesto" href="'.$baseincpat.'about.php?">about</a> | <a title="Meesto blog" href="'.$baseincpat.'blog.php?">blog</a> | <a title="Knowledge For Usage" href="'.$baseincpat.'usage.php?">usage</a> | <a title="Meesto help" href="'.$baseincpat.'help.php?">help</a></td></tr></table>
				</td></tr></table>
			</div><div align="center" style="padding-top: 12px;">
				<a title="Edit your Meesto email notification settings" href="'.$baseincpat.'settings.php?action=editenotif" style="font: 11px Arial, Helvetica, sans-serif; text-decoration: none;">click here to edit your email notification settings</a>
			</div>
		</div>
	</body>
	</html>
	';
	// Always set content-type when sending HTML email
	$headers = 'From: Meesto <no-reply@meesto.com>' . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1; MIME-Version: 1.0" . "\r\n";
	// More headers
	
	mail($to,$subject,$emailercontainer,$headers);
?>