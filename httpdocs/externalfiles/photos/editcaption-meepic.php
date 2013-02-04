<?php
require_once ('../../../externals/general/includepaths.php');

include ('../../../externals/header/header-pb.php');

$uiid = escape_data($_GET['id']);

$apinfo = mysql_fetch_array(mysql_query ("SELECT u_id, caption FROM user_imgs WHERE ui_id='$uiid' LIMIT 1"), MYSQL_ASSOC);

if ($apinfo['u_id']==$id) {

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit Photo Caption</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 28px;">Use this to edit your photo caption.</div>';

if (isset($_POST['save'])) {
	
	$errors = NULL;
	
	if (isset($_POST['description']) && ($_POST['description'] != 'type photo caption here')) {
		$description = escape_form_data($_POST['description']);
	} else {
		$description = '';
	}
	
	if (empty($errors)) {
		$update = mysql_query("UPDATE user_imgs SET caption='$description' WHERE ui_id='$uiid'");
		
		echo '<div align="center" class="p18">Your caption has been saved.</div>
		<script type="text/javascript">
			setTimeout("parent.gotopage(\'caption_cont\', \''.$baseincpat.'externalfiles/photos/grabcaption-meepic.php?id='.$uiid.'\');", 0);
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
		include ('../../../externals/header/footer-pb.php');
	} else {
		foreach ($errors as $error) {
			echo '<div align="left" class="palert" style="padding-bottom: 8px;">'.$error.'</div>';	
		}
	}

} else {
	//load values
	$description = $apinfo['caption'];
}
	
	echo '<form action="'.$baseincpat.'externalfiles/photos/editcaption-meepic.php?id='.$uiid.'" method="post">
		<div align="left" style="padding-left: 16px;">
			
			<div align="center" style="padding-bottom: 12px;"><textarea name="description" cols="52" rows="2" onfocus="if (trim(this.value) == \'type photo caption here\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type photo caption here\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'5000\', \'infovertxtalrt\');"';
				if ($description!=''){echo'>'.$description;}else{echo' class="inputplaceholder">type photo caption here';}
			echo '</textarea>
				<div id="infovertxtalrt" align="left" class="palert"></div>
			</div>
		
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="save" name="save" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		
	</div>
	</form>';

} else { //if not tab owner
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must be the owner of this tab to use this feature.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>