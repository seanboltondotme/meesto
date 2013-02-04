<?php
require_once ('../../../externals/general/includepaths.php');

include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Meesto Help: Ask A New Question</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to ask a new Meesto help question. <span style="font-size: 13px;">(This is public.)</span></div>';

if (isset($_POST['ask'])) {
	
	$errors = NULL;
	
	if (isset($_POST['question']) && ($_POST['question'] != 'type your question here... (To ensure a repaid response, try to be as specific as possible while still being simple and concise.)')) {
		$question = escape_form_data($_POST['question']);
	} else {
		$errors[] = 'We can\'t answer a qustion that has not been asked :)';
	}
	
	if (empty($errors)) {
		$insert = mysql_query("INSERT INTO help_threads (u_id, msg, time_stamp) VALUES ('$id', '$question', NOW())");
		$htid = mysql_insert_id();
			
						//send myself an email
						if ($id!=1) { //if not me
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='1' LIMIT 1"), 0);
										
							//params
							$subject = 'Someone just asked a Meesto help question';
							$emailercontent = 'Someone just reported a Meesto help question <a href="'.$baseincpat.'help.php?htid='.$htid.'">click to view</a>.<br /><span style="color: #C5C5C5; font: 11px Arial, Helvetica, sans-serif;">In reference to htid='.$htid.'</span><br /><br />"'.escape_emailcont_data($_POST['question']).'"';
										
							include('../../../externals/general/emailer.php');
						}
			
		echo '<div align="center" class="p18">Your question has been submitted! You will be notified when someone answers it. (Hopefully it wont be too long.)</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 3800);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

}
	
	echo '<form action="'.$baseincpat.'externalfiles/help/ask.php" method="post">
		<div align="left" style="padding-left: 16px;">
			
			<div align="center" style="padding-bottom: 12px;">
					<textarea name="question" cols="58" rows="5" onfocus="if (trim(this.value) == \'type your question here... (To ensure a repaid response, try to be as specific as possible while still being simple and concise.)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type your question here... (To ensure a repaid response, try to be as specific as possible while still being simple and concise.)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'20000\', \'questionovertxtalrt\');"';
				if ($question){echo'>'.$question;}else{echo' class="inputplaceholder">type your question here... (To ensure a repaid response, try to be as specific as possible while still being simple and concise.)';}
			echo '</textarea>
				<div id="questionovertxtalrt" align="left" class="palert"></div>
			</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="ask question" name="ask" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

include ('../../../externals/header/footer-pb.php');
?>