<?php
require_once ('../../../externals/general/includepaths.php');

include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Provide Meesto Feedback</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to provide feedback feedback Meesto &mdash; tell us what you think.</div>';

if (isset($_POST['submit'])) {
	
	$errors = NULL;
	
	if (isset($_POST['feedback']) && ($_POST['feedback'] != 'type your feedback here...')) {
		$feedback = escape_form_data($_POST['feedback']);
	} else {
		$errors[] = 'Please fill in your feedback information.';
	}
	
	if (isset($_POST['ispub']) && ($_POST['ispub'] == 'y')) {
		$ispub = true;
	} else {
		$ispub = false;
	}
	
	if (empty($errors)) {
		if ($ispub) {
			$insert = mysql_query("INSERT INTO feedback (u_id, msg, pub, time_stamp) VALUES ('$id', '$feedback', 'y', NOW())");
		} else {
			$insert = mysql_query("INSERT INTO feedback (u_id, msg, time_stamp) VALUES ('$id', '$feedback', NOW())");
		}
						
						//send myself an email
						if ($id!=1) { //if not me
							$fdbkid = mysql_insert_id();
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='1' LIMIT 1"), 0);
										
							//params
							$subject = 'Someone just provided feedback on Meesto';
							$emailercontent = 'Someone just provided <a href="'.$baseincpat.'community.php?#f=fdbk&vid='.$fdbkid.'">feedback on Meesto</a>. ';
							if ($ispub) {
								$emailercontent .= 'It is public.';
							} else {
								$emailercontent .= 'It is not public.';
							}
							$emailercontent .= '<br /><span style="color: #C5C5C5; font: 11px Arial, Helvetica, sans-serif;">In reference to fdbkid='.$fdbkid.'</span>';
										
							include('../../../externals/general/emailer.php');
						}
		
		echo '<div align="center" class="p18">Your feedback has been submitted &mdash; thank you!</div>
		<script type="text/javascript">
			setTimeout("parent.location.reload();", 1600);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

} else {
	$ispub = false;	
}
	
	echo '<form action="'.$baseincpat.'externalfiles/community/providefeedback.php" method="post">
		<div align="left" style="padding-left: 16px;">
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">feedback</td><td align="left" valign="center" style="font-size: 13px; padding-bottom: 2px;">
					<textarea name="feedback" cols="54" rows="5" onfocus="if (trim(this.value) == \'type your feedback here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type your feedback here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'20000\', \'feedbackovertxtalrt\');"';
				if ($feedback){echo'>'.$feedback;}else{echo' class="inputplaceholder">type your feedback here...';}
			echo '</textarea>
				<div id="feedbackovertxtalrt" align="left" class="palert"></div>
				</td></tr></table>
			</div>
			
			<div align="left" style="padding-bottom: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" class="p18" width="110px">visibility</td><td align="left" valign="center" style="padding-bottom: 2px;">
					<div align="left"><table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'ispub\').get(\'checked\') == false){$(\'ispub\').set(\'checked\',true);}else{$(\'ispub\').set(\'checked\',false);}""><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="ispub" name="ispub" value="y" onclick="if($(\'ispub\').get(\'checked\') == false){$(\'ispub\').set(\'checked\',true);}else{$(\'ispub\').set(\'checked\',false);}"'; if($ispub){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">display this in the community &mdash; this is public</td></tr></table></div><div align="left" style="font-size: 13px;">
						We do not automatically make this public in order to respect your privacy. However we do encourage you to make this public because it may help out other people or spark new ideas.
					</div>
				</td></tr></table>
			</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="submit" name="submit" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

include ('../../../externals/header/footer-pb.php');
?>