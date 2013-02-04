<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$apid = escape_data($_GET['id']);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_albums pa INNER JOIN album_photos ap ON pa.pa_id=ap.pa_id AND ap.ap_id='$apid' WHERE pa.u_id='$id' LIMIT 1"), 0)>0) {//test for admin
	
if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {

$ifname = 'editcmt'.$apid;
if (!isset($_POST['save'])) {
	$pdrjs = '$(\'caption'.$apid.'\').focus();';
}
include ('../../../externals/header/header-iframe.php');

if (isset($_POST['save'])) {
//save
	
	$errors = NULL;

	if (isset($_POST['caption'.$apid]) && ($_POST['caption'.$apid] != 'type a caption for this photo here')) {
		$caption = escape_form_data($_POST['caption'.$apid]);
	} else {
		$caption = '';
	}
		$update = mysql_query("UPDATE album_photos SET caption='$caption' WHERE ap_id='$apid'");
	
	echo '<div align="center" class="p18" id="savemsg" style="margin-bottom: 2px;">Your photo caption has been saved!</div>
	<script type="text/javascript">
		setTimeout("$(\'savemsg\').destroy();", \'1400\');
	</script>';
	
}

	$photo = mysql_fetch_array(mysql_query ("SELECT ap_id, url, caption FROM album_photos WHERE ap_id='$apid' LIMIT 1"), MYSQL_ASSOC);
		$apid = $photo['ap_id'];
		echo '<div align="left" style="padding-left: 2px;">
		<form action="'.$baseincpat.'externalfiles/photos/editalbum-editp-caption.php?action=iframe&id='.$apid.'" method="post">
						<textarea id="caption'.$apid.'" name="caption'.$apid.'" cols="39" rows="2" onfocus="if (trim(this.value) == \'type a caption for this photo here\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type a caption for this photo here\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'740\', \'overtxtalrt'.$apid.'\'); if(trim(this.value)==\''.$photo['caption'].'\'){$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});}else{$(\'submitbtns\').set(\'styles\',{\'display\':\'block\'});}"';
							if ($photo['caption']!=''){echo'>'.$photo['caption'];}else{echo' class="inputplaceholder">type a caption for this photo here';}
						echo '</textarea>
						<div id="overtxtalrt'.$apid.'" align="left" class="palert"></div>
						<div align="right">
							<div id="loader" style="display: none; margin-top: 8px;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
							<div id="submitbtns" align="right" style="display: none; margin-top: 8px;">
								<input type="submit" id="submit" value="save" name="save" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/>
							</div>
						</div>
		</form>
		</div>';

include ('../../../externals/header/footer-iframe.php');

	} else {
		echo '<iframe width="366px" height="100px" align="center" id="editcmt'.$apid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/photos/editalbum-editp-caption.php?action=iframe&id='.$apid.'"></iframe>';
	}

} else { //if not tab owner
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}