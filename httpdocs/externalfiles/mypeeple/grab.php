<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!isset($s)) {
	if (isset($_GET['s'])) {
		$s = escape_data($_GET['s']);
	} else {
		$s = NULL;	
	}
}

if (!isset($chan)) {
	if (isset($_GET['c'])) {
		$chan = escape_data($_GET['c']);
	} else {
		$chan = NULL;	
	}
}
	
	//test if channel to show edit panel
	if ($chan) {
		if ($chan=='mb') {
			$s = $chan; //fix to be stream
			echo '<div align="right" style="border-bottom: 1px solid #C5C5C5; margin-right: 8px; padding-bottom: 6px; margin-bottom: 18px;">
				<table cellpadding="0" cellspacing="0" width="100%"><tr><td align="left" valign="center">"People you are close with." <span class="subtext" style="font-size: 13px;">(Only you can see this.)</span></td><td align="right" valign="center">
					<input type="button" value="edit peeple list" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/mypeeple/chaneditlist.php?id='.$chan.'\', size: {x: 660, y: 340}, handler:\'iframe\'});" style="padding-left: 12px; padding-right: 12px;"/>
				</td></tr></table>
			</div>';
		} else {
			$chandesc = mysql_result(mysql_query("SELECT description FROM my_peeple_channels WHERE mpc_id='$chan' LIMIT 1"), 0);
			if ($chandesc!='') {
				echo '<div align="left" style="margin-right: 8px; padding-bottom: 6px;">"'.nl2br($chandesc).'"</div>';
			}
			echo '<div align="right" style="border-bottom: 1px solid #C5C5C5; margin-right: 8px; padding-bottom: 6px; margin-bottom: 18px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
					<input type="button" value="edit peeple list" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/mypeeple/chaneditlist.php?id='.$chan.'\', size: {x: 660, y: 340}, handler:\'iframe\'});" style="padding-left: 12px; padding-right: 12px;"/>
				</td><td align="left" valign="top" style="padding-left: 8px;">
					<input type="button" value="edit channel" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/mypeeple/editchannel.php?id='.$chan.'\', size: {x: 660, y: 340}, handler:\'iframe\'});" style="padding-left: 12px; padding-right: 12px;"/>
				</td><td align="left" valign="top" style="padding-left: 8px;">
					<input type="button" value="delete channel" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/mypeeple/deletechannel.php?id='.$chan.'\', size: {x: 660, y: 340}, handler:\'iframe\'});" style="padding-left: 12px; padding-right: 12px;"/>
				</td></tr></table>
			</div>';
		}
	}
	
		$display = 18;
		if (($chan)&&($chan!='mb')) {
			if ($s) {
				$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM peep_streams ps INNER JOIN mpc_mems mpcm ON mpc_id='$chan' AND mp.p_id=mpcm.p_id INNER JOIN users u ON ps.p_id = u.user_id WHERE ps.u_id='$id' AND ps.stream='$s'"), 0);	
			} else {
				$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM my_peeple mp INNER JOIN mpc_mems mpcm ON mpc_id='$chan' AND mp.p_id=mpcm.p_id INNER JOIN users u ON mp.p_id = u.user_id WHERE mp.u_id='$id'"), 0);
			}
		} else {
			if ($s) {
				$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM peep_streams ps INNER JOIN users u ON ps.p_id = u.user_id WHERE ps.u_id='$id' AND ps.stream='$s'"), 0);	
			} else {
				$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM my_peeple mp INNER JOIN users u ON mp.p_id = u.user_id WHERE mp.u_id='$id'"), 0);
			}
		}
					
		if ($num_records > $display) {
			$num_pages = ceil ($num_records/$display);
		} else {
			$num_pages = 1;
		}
					
		if (isset($_GET['pg'])&&is_numeric($_GET['pg'])) {
			$page = escape_data($_GET['pg']);
			$start = ($display*($page-1));
		} else {
			$page = 1;
			$start = 0;
		}

	//display my peeple
	if (($chan)&&($chan!='mb')) {
		if ($s) {
			$peeple = mysql_query ("SELECT ps.p_id, u.defaultimg_url FROM peep_streams ps INNER JOIN mpc_mems mpcm ON mpc_id='$chan' AND mp.p_id=mpcm.p_id INNER JOIN users u ON ps.p_id = u.user_id WHERE ps.u_id='$id' AND ps.stream='$s' ORDER BY u.last_name ASC LIMIT $start, $display");
		} else {
			$peeple = mysql_query ("SELECT mp.p_id, u.defaultimg_url FROM my_peeple mp INNER JOIN mpc_mems mpcm ON mpc_id='$chan' AND mp.p_id=mpcm.p_id INNER JOIN users u ON mp.p_id = u.user_id WHERE mp.u_id='$id' ORDER BY u.last_name ASC LIMIT $start, $display");
		}
	} else {
		if ($s) {
			$peeple = mysql_query ("SELECT ps.p_id, u.defaultimg_url FROM peep_streams ps INNER JOIN users u ON ps.p_id = u.user_id WHERE ps.u_id='$id' AND ps.stream='$s' ORDER BY u.last_name ASC LIMIT $start, $display");
		} else {
			$peeple = mysql_query ("SELECT mp.p_id, u.defaultimg_url FROM my_peeple mp INNER JOIN users u ON mp.p_id = u.user_id WHERE mp.u_id='$id' ORDER BY u.last_name ASC LIMIT $start, $display");
		}
	}
	while ($person = mysql_fetch_array ($peeple, MYSQL_ASSOC)) {
		$pid = $person['p_id'];
			
		echo '<div id="parentmainperson'.$pid.'" style="padding-bottom: 24px;" onmouseover="$(\'btns'.$pid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btns'.$pid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px"><a href="'.$baseincpat.'meefile.php?id='.$pid.'"><img src="'.$baseincpat.''.$person['defaultimg_url'].'" /></a></td><td align="left" valign="top" id="mainperson'.$pid.'" width="314px" style="padding-left: 12px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24">';
				loadpersonname($pid);
			echo '</td></tr><tr><td align="left" class="subtext" style="padding-top: 2px;">(';
				$stts = array ('mb', 'frnd', 'fam', 'prof', 'edu', 'aqu');
				$i = 0;
				foreach ($stts as $stt) {
					if (mysql_result (mysql_query ("SELECT COUNT(*) FROM peep_streams WHERE p_id='$pid' AND u_id='$id' AND stream='$stt' LIMIT 1"), 0)>0) {
						if ($i>0) {
							echo ', ';	
						}
						if ($stt == 'mb') {
							echo 'my bubble';
						} elseif ($stt == 'frnd') {
							echo 'friends';
						} elseif ($stt == 'fam') {
							echo 'family';
						} elseif ($stt == 'prof') {
							echo 'professional';
						} elseif ($stt == 'edu') {
							echo 'education';
						} elseif ($stt == 'aqu') {
							echo 'just met mee';
						}
						$i++;
					}
				}
			echo ')</td></tr></table>
			</td><td align="left" valign="top" width="100px" style="padding-left: 12px;">
				<div id="btns'.$pid.'" align="left" style="visibility: hidden; zoom: 1; opacity: 0;">
					<div style="padding-bottom: 6px;"><input type="button" align="center" valign="center" value="edit" onclick="gotopage(\'parentmainperson'.$pid.'\', \''.$baseincpat.'externalfiles/mypeeple/editpers.php?pid='.$pid.'\');"/></div>
				</div>
			</td></tr></table>
		</div>';
	}
	//if no records
	if ($num_records==0) {
		echo '<div align="left">no peeple here yet</div>';
	}
	//paginations
	echo '<div align="center">
		<table cellpadding="0" cellspacing="0"><tr>';
			if ($num_pages > 1) {
							
				if ($page != 1) {
					echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="backcontrol.setState(\'';
							//url vars switch
							if (isset($chan)) {
								//in channel
								echo 'c='.$chan;
							} elseif (isset($s)) {
								//in stream
								echo 's='.$s;
							}
						echo'&pg=' . ($page-1) . '\');">previous</td> ';
				}
							
				for ($i = 1; $i <= $num_pages; $i++) {
					if ($i != $page) {
						echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="backcontrol.setState(\'';
							//url vars switch
							if (isset($chan)) {
								//in channel
								echo 'c='.$chan;
							} elseif (isset($s)) {
								//in stream
								echo 's='.$s;
							}
						echo'&pg=' . $i . '\');">' . $i . '</td>';
					} else {
						echo '<td style="padding-right: 3px;" class="paginationlinkOn">' .$i . '</td> ';
					}
				}
				if ($page != $num_pages) {
					echo '<td class="paginationlinks" onclick="backcontrol.setState(\'';
							//url vars switch
							if (isset($chan)) {
								//in channel
								echo 'c='.$chan;
							} elseif (isset($s)) {
								//in stream
								echo 's='.$s;
							}
						echo'&pg=' . ($page+1) . '\');">next</td>';
				}
			}
		echo '</tr></table>
		</div>';

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>