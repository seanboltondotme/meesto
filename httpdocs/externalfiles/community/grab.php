<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (!isset($fltr)) {
	$f = escape_data($_GET['f']);
}

if ($f=='mp') {
	
	echo '<div align="left">';
	
		$display = 16;
		$num_records = mysql_result(mysql_query ("SELECT COUNT(*) FROM comm_projs cp INNER JOIN commproj_mem cpm ON cpm.cp_id=cp.cp_id AND cpm.u_id='$id'"), 0);
					
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
	
	if ($num_records>0) {
		$projs = mysql_query ("SELECT cp.cp_id, cp.name, cp.about FROM comm_projs cp INNER JOIN commproj_mem cpm ON cpm.cp_id=cp.cp_id AND cpm.u_id='$id' ORDER BY cp.time_stamp DESC LIMIT $start, $display");
		while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
			$cpid = $proj['cp_id'];
			echo '<a href="'.$baseincpat.'proj.php?id='.$cpid.'"><div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 12px;">
				<div align="left" class="p24">'.$proj['name'].'</div>
				<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
			</div></a>';
		}
		
		//paginations
		echo '<div align="center">
		<table cellpadding="0" cellspacing="0"><tr>';
			if ($num_pages > 1) {
							
				if ($page != 1) {
					echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="backcontrol.setState(\'f=mp&pg=' . ($page-1) . '\');">previous</td> ';
				}
							
				for ($i = 1; $i <= $num_pages; $i++) {
					if ($i != $page) {
						echo '<td style="padding-right: 3px;" class="paginationlinks" onclick="backcontrol.setState(\'f=mp&pg=' . $i . '\');">' . $i . '</td>';
					} else {
						echo '<td style="padding-right: 3px;" class="paginationlinkOn">' .$i . '</td> ';
					}
				}
				if ($page != $num_pages) {
					echo '<td class="paginationlinks" onclick="backcontrol.setState(\'f=mp&pg=' . ($page+1) . '\');">next</td>';
				}
			}
		echo '</tr></table>
		</div>';
		
	} else {
		echo '<div align="left" style="padding-top: 4px;">You have not added any projects yet.</div>';
	}
	echo '</div>';
	
} elseif ($f=='bug') {
	
	echo '<div align="left" class="p24">Currntly Being Fixed</div>
		<div align="left" style="padding-left: 14px;">';
			$projs_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM comm_projs WHERE type='bug' AND stat='ip' "), 0);
			if ($projs_ct>0) {
				$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type='bug' AND stat='ip' ORDER BY time_stamp DESC LIMIT 8");
				while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
					$cpid = $proj['cp_id'];
					echo '<a href="'.$baseincpat.'proj.php?id='.$cpid.'"><div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 12px;">
						<div align="left" class="p24">'.$proj['name'].'</div>
						<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
					</div></a>';
				}
				echo '<div align="right" style="padding-right: 16px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/viewallproj.php?type=bug&stat=ip\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$projs_ct.')</div>';
			} else {
				echo '<div align="left" style="padding-top: 4px;">No bugs here at this time &mdash; yay! (assuming none are pending)</div>';
			}
		echo '</div>
		<div align="left" class="p24" style="margin-top: 18px;">Pending</div>
		<div align="left" style="padding-left: 14px;">';
			$projs_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM comm_projs WHERE type='bug' AND stat='pnd' "), 0);
			if ($projs_ct>0) {
				$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type='bug' AND stat='pnd' ORDER BY time_stamp DESC LIMIT 8");
				while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
					$cpid = $proj['cp_id'];
					echo '<a href="'.$baseincpat.'proj.php?id='.$cpid.'"><div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 12px;">
						<div align="left" class="p24">'.$proj['name'].'</div>
						<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
					</div></a>';
				}
				echo '<div align="right" style="padding-right: 16px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/viewallproj.php?type=bug&stat=pnd\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$projs_ct.')</div>';
			} else {
				echo '<div align="left" style="padding-top: 4px;">No bugs here at this time &mdash; yay!</div>';
			}
		echo '</div>
		<div align="left" class="p24" style="margin-top: 18px;">Recently Added</div>
		<div align="left" style="padding-left: 14px;">';
			$projs_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM comm_projs WHERE type='bug'"), 0);
			if ($projs_ct>0) {
				$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type='bug' ORDER BY time_stamp DESC LIMIT 8");
				while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
					$cpid = $proj['cp_id'];
					echo '<a href="'.$baseincpat.'proj.php?id='.$cpid.'"><div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 12px;">
						<div align="left" class="p24">'.$proj['name'].'</div>
						<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
					</div></a>';
				}
			} else {
				echo '<div align="left" style="padding-top: 4px;">No bugs here at this time.</div>';
			}
		echo '</div>
		<div align="left" class="p24" style="margin-top: 18px;">Fixed</div>
		<div align="left" style="padding-left: 14px;">';
			$projs_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM comm_projs WHERE type='bug' AND stat='fixed' "), 0);
			if ($projs_ct>0) {
				$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type='bug' AND stat='fixed' ORDER BY time_stamp DESC LIMIT 8");
				while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
					$cpid = $proj['cp_id'];
					echo '<a href="'.$baseincpat.'proj.php?id='.$cpid.'"><div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 12px;">
						<div align="left" class="p24">'.$proj['name'].'</div>
						<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
					</div></a>';
				}
				echo '<div align="right" style="padding-right: 16px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/viewallproj.php?type=bug&stat=fixed\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$projs_ct.')</div>';
			} else {
				echo '<div align="left" style="padding-top: 4px;">No bugs here at this time :(</div>';
			}
		echo '</div>';
		
} elseif ($f=='fdbk') {
	
		$fdbks_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM feedback WHERE pub='y'"), 0);
		if ($fdbks_ct>0) {
			if (isset($_GET['vid'])&&is_numeric($_GET['vid'])) {
				$vid = escape_data($_GET['vid']);
				$fdbks = mysql_query ("SELECT fdbk_id, u_id, msg, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM feedback WHERE fdbk_id='$vid' LIMIT 1");
				$viewsingle = true;
			} else {
				$viewsingle = false;
				$fdbks = mysql_query ("SELECT fdbk_id, u_id, msg, DATE_FORMAT(time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM feedback WHERE pub='y' ORDER BY fdbk_id DESC LIMIT 14");
			}
			while ($fdbk = mysql_fetch_array ($fdbks, MYSQL_ASSOC)) {
				$fdbkid = $fdbk['fdbk_id'];
				echo '<div align="left" style="margin-bottom: 16px;"';
				if ($fdbk['u_id']==$id) {
					echo ' onmouseover="$(\'btndeletethrd'.$fdbkid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletethrd'.$fdbkid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
				}
				echo '>
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="376px">
					<div align="left" class="p24">"'.nl2br($fdbk['msg']).'"</div>
					<div class="subtext"><table cellpadding="0" cellspacing="0"><tr><td align="left">by '; loadpersonname($fdbk['u_id']); echo' on '.$fdbk['time'].'</td><td align="left">';
					//test for messages in thread
					if (mysql_result (mysql_query("SELECT COUNT(*) FROM feedback_cmts WHERE fdbk_id='$fdbkid' LIMIT 1"), 0)<1) {
						echo '<div align="left" class="postoptlink" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'cmtwrite'.$fdbkid.'\', \'align\': \'left\'});newElem.inject($(\'fdbkcmts'.$fdbkid.'\'), \'bottom\');gotopage(\'cmtwrite'.$fdbkid.'\', \''.$baseincpat.'externalfiles/community/writefdbkcmt.php?id='.$fdbkid.'\');"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" class="subtext" style="padding-left: 6px; padding-right: 6px;">|</td><td align="left" valign="center"><div align="left" class="postoptlinkmrkr"></div></td><td align="left" valign="center" style="padding-left: 4px;">comment</td></tr></table></div>';
					}
					echo '</td></tr></table></div>
				</td><td align="left" valign="top" width="90px" style="padding-left: 16px;">';
					if ($fdbk['u_id']==$id) {
						echo '<div id="btndeletethrd'.$fdbkid.'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/deletefdbk.php?id='.$fdbkid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
					}
				echo '</td></tr></table>	
				<div align="left" style="padding-bottom: 24px;"><div align="left" id="fdbkcmts'.$fdbkid.'" style="padding-left: 12px; border-left: 1px solid #C5C5C5;">';
						//test for messages in thread
						if (mysql_result (mysql_query("SELECT COUNT(*) FROM feedback_cmts WHERE fdbk_id='$fdbkid' LIMIT 1"), 0)>0) {
							include('grabfdbkcmts.php');	
						}
					echo '</div></div>';
			}
			if (!$viewsingle) { //if viewing more than one
				echo '<div align="right" style="padding-right: 16px; cursor: pointer;" onclick="">view all ('.$fdbks_ct.')</div>';
			}
		} else {
			echo '<div align="left" style="padding-top: 4px;">Nothing yet.</div>';
		}
	echo '</div>';
			
} else {
	
	echo '<div align="left" class="p24">In Production</div>
		<div align="left" style="padding-left: 14px;">';
			$projs_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM comm_projs WHERE type IS NULL AND stat='ip' "), 0);
			if ($projs_ct>0) {
				$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type IS NULL AND stat='ip' ORDER BY time_stamp DESC LIMIT 8");
				while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
					$cpid = $proj['cp_id'];
					echo '<a href="'.$baseincpat.'proj.php?id='.$cpid.'"><div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 12px;">
						<div align="left" class="p24">'.$proj['name'].'</div>
						<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
					</div></a>';
				}
				echo '<div align="right" style="padding-right: 16px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/viewallproj.php?type=prj&stat=ip\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$projs_ct.')</div>';
			} else {
				echo '<div align="left" style="padding-top: 4px;">No projects here at this time.</div>';
			}
		echo '</div>
		<div align="left" class="p24" style="margin-top: 18px;">Approved - Awaiting Production</div>
		<div align="left" style="padding-left: 14px;">';
			$projs_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM comm_projs WHERE type IS NULL AND stat='apnd' "), 0);
			if ($projs_ct>0) {
				$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type IS NULL AND stat='apnd' ORDER BY time_stamp DESC LIMIT 8");
				while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
					$cpid = $proj['cp_id'];
					echo '<a href="'.$baseincpat.'proj.php?id='.$cpid.'"><div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 12px;">
						<div align="left" class="p24">'.$proj['name'].'</div>
						<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
					</div></a>';
				}
				echo '<div align="right" style="padding-right: 16px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/viewallproj.php?type=prj&stat=apnd\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$projs_ct.')</div>';
			} else {
				echo '<div align="left" style="padding-top: 4px;">No projects here at this time.</div>';
			}
		echo '</div>
		<div align="left" class="p24" style="margin-top: 18px;">Pending</div>
		<div align="left" style="padding-left: 14px;">';
			$projs_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM comm_projs WHERE type IS NULL AND stat='pnd' "), 0);
			if ($projs_ct>0) {
				$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type IS NULL AND stat='pnd' ORDER BY time_stamp DESC LIMIT 8");
				while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
					$cpid = $proj['cp_id'];
					echo '<a href="'.$baseincpat.'proj.php?id='.$cpid.'"><div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 12px;">
						<div align="left" class="p24">'.$proj['name'].'</div>
						<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
					</div></a>';
				}
				echo '<div align="right" style="padding-right: 16px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/viewallproj.php?type=prj&stat=pnd\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$projs_ct.')</div>';
			} else {
				echo '<div align="left" style="padding-top: 4px;">No projects here at this time.</div>';
			}
		echo '</div>
		<div align="left" class="p24" style="margin-top: 18px;">Recently Added</div>
		<div align="left" style="padding-left: 14px;">';
			$projs_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM comm_projs WHERE type IS NULL"), 0);
			if ($projs_ct>0) {
				$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type IS NULL ORDER BY time_stamp DESC LIMIT 8");
				while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
					$cpid = $proj['cp_id'];
					echo '<a href="'.$baseincpat.'proj.php?id='.$cpid.'"><div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 12px;">
						<div align="left" class="p24">'.$proj['name'].'</div>
						<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
					</div></a>';
				}
			} else {
				echo '<div align="left" style="padding-top: 4px;">No projects here at this time.</div>';
			}
		echo '</div>
		<div align="left" class="p24" style="margin-top: 18px;">Completed</div>
		<div align="left" style="padding-left: 14px;">';
			$projs_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM comm_projs WHERE type IS NULL AND stat='cmpltd' "), 0);
			if ($projs_ct>0) {
				$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type IS NULL AND stat='cmpltd' ORDER BY time_stamp DESC LIMIT 8");
				while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
					$cpid = $proj['cp_id'];
					echo '<a href="'.$baseincpat.'proj.php?id='.$cpid.'"><div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 12px;">
						<div align="left" class="p24">'.$proj['name'].'</div>
						<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
					</div></a>';
				}
				echo '<div align="right" style="padding-right: 16px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/viewallproj.php?type=prj&stat=cmpltd\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$projs_ct.')</div>';
			} else {
				echo '<div align="left" style="padding-top: 4px;">No projects here at this time.</div>';
			}
		echo '</div>
		<div align="left" class="p24" style="margin-top: 18px;">Canceled Projects</div>
		<div align="left" style="padding-left: 14px;">';
			$projs_ct = mysql_result(mysql_query ("SELECT COUNT(*) FROM comm_projs WHERE type IS NULL AND stat='cncl' "), 0);
			if ($projs_ct>0) {
				$projs = mysql_query ("SELECT cp_id, name, about FROM comm_projs WHERE type IS NULL AND stat='cncl' ORDER BY time_stamp DESC LIMIT 8");
				while ($proj = mysql_fetch_array ($projs, MYSQL_ASSOC)) {
					$cpid = $proj['cp_id'];
					echo '<a href="'.$baseincpat.'proj.php?id='.$cpid.'"><div align="left" id="proj'.$cpid.'" style="margin-top: 8px; margin-bottom: 12px;">
						<div align="left" class="p24">'.$proj['name'].'</div>
						<div align="left" class="subtext">'; if(strlen($proj['about'])>63){echo substr($proj['about'], 0, 63).'...'; }else{ echo $proj['about']; } echo'</div>
					</div></a>';
				}
				echo '<div align="right" style="padding-right: 16px; cursor: pointer;" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/viewallproj.php?type=prj&stat=cncl\', size: {x: 660, y: 340}, handler:\'iframe\'});">view all ('.$projs_ct.')</div>';
			} else {
				echo '<div align="left" style="padding-top: 4px;">No projects here at this time.</div>';
			}
		echo '</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>