<?php
require_once ('../../../externals/general/includepaths.php');

if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {

$ifname = 'editip';
include ('../../../externals/header/header-iframe.php');

$pinfo = @mysql_fetch_array (@mysql_query ("SELECT activities, interests, fav_quotes, vacation_spot, dream_life, about_me FROM meefile_pers WHERE u_id='$id' LIMIT 1"), MYSQL_ASSOC);

if (isset($_POST['save'])) {
//save
	
	$errors = NULL;
	
	if (isset($_POST['activities']) && ($_POST['activities'] != 'what sorts of activities are you involved with? what you actively do?')) {
		$activities = escape_form_data($_POST['activities']);
	} else {
		$activities = '';
	}
	
	if (isset($_POST['interests']) && ($_POST['interests'] != 'what are you interested in? what are you passionate about?')) {
		$interests = escape_form_data($_POST['interests']);
	} else {
		$interests = '';
	}
	
	if (isset($_POST['fav_quotes']) && ($_POST['fav_quotes'] != 'your favorite quotes? (you know you have some)')) {
		$fav_quotes = escape_form_data($_POST['fav_quotes']);
	} else {
		$fav_quotes = '';
	}
	
	if (isset($_POST['vacation_spot']) && ($_POST['vacation_spot'] != 'if you could pick any place in the world to go on a vacation, where you would it be? (feel free to use details and explain why; no this is not a test)')) {
		$vacation_spot = escape_form_data($_POST['vacation_spot']);
	} else {
		$vacation_spot = '';
	}
	
	if (isset($_POST['dream_life']) && ($_POST['dream_life'] != 'what would your dream life look like? (if it is not extravagant or if you do not have one that is ok too)')) {
		$dream_life = escape_form_data($_POST['dream_life']);
	} else {
		$dream_life = '';
	}
	
	if (isset($_POST['about_me']) && ($_POST['about_me'] != 'tell us about you. (try to make it more exciting than that boring essay for English class we all have had to write, or if you wrote an exciting one, use that)')) {
		$about_me = escape_form_data($_POST['about_me']);
	} else {
		$about_me = '';
	}
			
	if (empty($errors)) {
		$update = mysql_query("UPDATE meefile_pers SET activities='$activities', interests='$interests', fav_quotes='$fav_quotes', vacation_spot='$vacation_spot', dream_life='$dream_life', about_me='$about_me' WHERE u_id='$id'");
		//custom sections
			$customsecs = @mysql_query("SELECT mpe_id FROM meefile_pers_ext WHERE u_id='$id'");
			while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
				$mpeid = $customsec['mpe_id'];
				if (isset($_POST['cst'.$mpeid]) && ($_POST['cst'.$mpeid] != 'enter name')) {
					$type = escape_form_data($_POST['cst'.$mpeid]);
				} else {
					$type  = '';
				}
				if (isset($_POST['cs'.$mpeid]) && ($_POST['cs'.$mpeid] != 'type whatever you would like')) {
					$content = escape_form_data($_POST['cs'.$mpeid]);
				} else {
					$content  = '';
				}
				$update = @mysql_query("UPDATE meefile_pers_ext SET type='$type', content='$content' WHERE mpe_id='$mpeid'");
			}
		echo '<script type="text/javascript">
				setTimeout("parent.$(\'pieditbtn\').set(\'styles\',{\'display\':\'block\'});parent.$(\'pivisbtn\').set(\'styles\',{\'display\':\'none\'});parent.gotopage(\'pinfomain\', \''.$baseincpat.'externalfiles/meefile/grabpers.php?id='.$id.'\');", \'0\');
			</script>';
	} else {
		echo '<script type="text/javascript">
				setTimeout("parent.$(\'pieditbtn\').set(\'styles\',{\'display\':\'block\'});parent.$(\'pivisbtn\').set(\'styles\',{\'display\':\'none\'});", \'3200\');
			</script>';
		echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('meefile/editpers.php', 'editing basic info', $errors);
	}
	
} else {

echo '<form action="'.$baseincpat.'externalfiles/meefile/editpers.php?action=iframe" method="post">
	
	<div align="left" id="prsact" style="padding-bottom: 20px;" onmouseover="$(\'pivisbtnactiv\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'pivisbtnactiv\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsact' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">activities</td><td align="left" width="406px">
			<textarea name="activities" cols="44" rows="3" onfocus="if (trim(this.value) == \'what sorts of activities are you involved with? what you actively do?\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'what sorts of activities are you involved with? what you actively do?\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'activovertxtalrt\');"';
				if ($pinfo['activities']!=''){echo'>'.$pinfo ['activities'];}else{echo' class="inputplaceholder">what sorts of activities are you involved with? what you actively do?';}
			echo '</textarea>
			<div id="activovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="pivisbtnactiv" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=prsact\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="prsint" style="padding-bottom: 20px;" onmouseover="$(\'pivisbtnint\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'pivisbtnint\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsint' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">interest/passions</td><td align="left" width="406px">
			<textarea name="interests" cols="44" rows="3" onfocus="if (trim(this.value) == \'what are you interested in? what are you passionate about?\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'what are you interested in? what are you passionate about?\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'intovertxtalrt\');"';
				if ($pinfo['interests']!=''){echo'>'.$pinfo ['interests'];}else{echo' class="inputplaceholder">what are you interested in? what are you passionate about?';}
			echo '</textarea>
			<div id="intovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="pivisbtnint" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=prsint\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="prsfq" style="padding-bottom: 20px;" onmouseover="$(\'pivisbtnfq\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'pivisbtnfq\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsfq' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">favorite quotes</td><td align="left" width="406px">
			<textarea name="fav_quotes" cols="44" rows="3" onfocus="if (trim(this.value) == \'your favorite quotes? (you know you have some)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'your favorite quotes? (you know you have some)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'fqovertxtalrt\');"';
				if ($pinfo['fav_quotes']!=''){echo'>'.$pinfo ['fav_quotes'];}else{echo' class="inputplaceholder">your favorite quotes? (you know you have some)';}
			echo '</textarea>
			<div id="fqovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="pivisbtnfq" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=prsfq\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="prsvs" style="padding-bottom: 20px;" onmouseover="$(\'pivisbtnvs\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'pivisbtnvs\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsvs' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">vacation spot</td><td align="left" width="406px">
			<textarea name="vacation_spot" cols="44" rows="3" onfocus="if (trim(this.value) == \'if you could pick any place in the world to go on a vacation, where you would it be? (feel free to use details and explain why; no this is not a test)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'if you could pick any place in the world to go on a vacation, where you would it be? (feel free to use details and explain why; no this is not a test)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'vsovertxtalrt\');"';
				if ($pinfo['vacation_spot']!=''){echo'>'.$pinfo ['vacation_spot'];}else{echo' class="inputplaceholder">if you could pick any place in the world to go on a vacation, where you would it be? (feel free to use details and explain why; no this is not a test)';}
			echo '</textarea>
			<div id="vsovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="pivisbtnvs" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=prsvs\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="prsdl" style="padding-bottom: 20px;" onmouseover="$(\'pivisbtndl\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'pivisbtndl\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsdl' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">dream life</td><td align="left" width="406px">
			<textarea name="dream_life" cols="44" rows="3" onfocus="if (trim(this.value) == \'what would your dream life look like? (if it is not extravagant or if you do not have one that is ok too)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'what would your dream life look like? (if it is not extravagant or if you do not have one that is ok too)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'dlovertxtalrt\');"';
				if ($pinfo['dream_life']!=''){echo'>'.$pinfo ['dream_life'];}else{echo' class="inputplaceholder">what would your dream life look like? (if it is not extravagant or if you do not have one that is ok too)';}
			echo '</textarea>
			<div id="dlovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="pivisbtndl" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=prsdl\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="prsam" style="padding-bottom: 20px;" onmouseover="$(\'pivisbtnam\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'pivisbtnam\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_infosec_vis WHERE u_id='$id' AND sec='prsam' AND type!='user' LIMIT 1"), 0)==0) {
				echo ' class="notvissec">
				<div align="center" class="palert">this is not visible to anyone!</div';
			}
		echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">about me</td><td align="left" width="406px">
			<textarea name="about_me" cols="44" rows="3" onfocus="if (trim(this.value) == \'tell us about you. (try to make it more exciting than that boring essay for English class we all have had to write, or if you wrote an exciting one, use that)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'tell us about you. (try to make it more exciting than that boring essay for English class we all have had to write, or if you wrote an exciting one, use that)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'amovertxtalrt\');"';
				if ($pinfo['about_me']!=''){echo'>'.$pinfo ['about_me'];}else{echo' class="inputplaceholder">tell us about you. (try to make it more exciting than that boring essay for English class we all have had to write, or if you wrote an exciting one, use that)';}
			echo '</textarea>
			<div id="amovertxtalrt" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div id="pivisbtnam" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editinfosecvis.php?ifn='.$ifname.'&sec=prsam\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="pinfocustsecs">';
	
		//get custom secs
		$customsecs = @mysql_query("SELECT mpe_id, type, content FROM meefile_pers_ext WHERE u_id='$id' ORDER BY mpe_id ASC");
		while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
			$mpeid = $customsec['mpe_id'];
			echo '<div align="left" id="mpe'.$mpeid.'" style="padding-bottom: 20px;" onmouseover="$(\'pibtns'.$mpeid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'pibtns'.$mpeid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM meefile_pers_ext_vis WHERE mpe_id='$mpeid' AND type!='user' LIMIT 1"), 0)==0) {
							echo ' class="notvissec">
							<div align="center" class="palert">this is not visible to anyone!</div';
						}
					echo '>
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" style="padding-left: 2px;">
				<input type="text" name="cst'.$mpeid.'" size="14" maxlength="40" autocomplete="off" onfocus="if (trim(this.value) == \'enter name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($customsec['type']!=''){echo'value="'.$customsec['type'].'"';}else{echo' class="inputplaceholder" value="enter name"';}
					 echo'>
			</td><td align="left" width="406px">
				<textarea name="cs'.$mpeid.'" cols="44" rows="3" onfocus="if (trim(this.value) == \'type whatever you would like\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type whatever you would like\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'csovertxtalrt'.$mpeid.'\');"';
					if ($customsec['content']!=''){echo'>'.$customsec ['content'];}else{echo' class="inputplaceholder">type whatever you would like';}
				echo '</textarea>
				<div id="csovertxtalrt'.$mpeid.'" align="left" class="palert"></div>
			</td><td align="left" valign="top" width="110px">
				<div align="left" id="pibtns'.$mpeid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
					<div><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editpinfovis.php?ifn='.$ifname.'&id='.$mpeid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
							<div style="padding-top: 10px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletepinfo.php?id='.$mpeid.'\', size: {x: 480, y: 380}, handler:\'iframe\'});"/></div>
				</div>
			</td></tr></table>
		</div>';
		}
	
	echo '</div>
	
	<div align="left" style="padding-bottom: 20px;">
		<input type="button" id="addnewcustsec" value="add custom info section" onclick="var newElem = new Element(\'div\', {\'align\': \'left\'});newElem.inject($(\'pinfocustsecs\'), \'bottom\');gotopage(newElem, \''.$baseincpat.'externalfiles/meefile/addpinfosec.php\');"/>
	</div>
	
	<div align="center" style="padding-top: 8px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left">
			<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		</td><td align="left">
			<div id="submitbtns" align="left">
			<table cellpadding="0" cellspacing="0"><tr><td align="left">
				<input type="submit" id="submit" value="save" name="save" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/>
			</td><td align="left" style="padding-left: 12px;">
				<input type="button" id="cancel" value="cancel" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});parent.$(\'pieditbtn\').set(\'styles\',{\'display\':\'block\'});parent.$(\'pivisbtn\').set(\'styles\',{\'display\':\'none\'});parent.gotopage(\'pinfomain\', \''.$baseincpat.'externalfiles/meefile/grabpers.php?id='.$id.'\');"/>
			</td></tr></table>
			</div>
		</td></tr></table>
	</div>

</form>';
}

include ('../../../externals/header/footer-iframe.php');

} else {
	echo '<iframe width="100%" height="200px" align="center" id="editip" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/meefile/editpers.php?action=iframe"></iframe>';
}
?>