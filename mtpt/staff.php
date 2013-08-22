<?php
require "include/bittorrent.php";
dbconn();
require_once(get_langfile_path());
loggedinorreturn(true);
stdhead($lang_staff['head_staff']);

$Cache->new_page('staff_page', 900, true);
if (!$Cache->get_page()){
$Cache->add_whole_row();
begin_main_frame();
$secs = 900;
$dt = TIMENOW - $secs;
$onlineimg = "<img class=\"button_online\" src=\"pic/trans.gif\" alt=\"online\" title=\"".$lang_staff['title_online']."\" />";
$offlineimg = "<img class=\"button_offline\" src=\"pic/trans.gif\" alt=\"offline\" title=\"".$lang_staff['title_offline']."\" />";
$sendpmimg = "<img class=\"button_pm\" src=\"pic/trans.gif\" alt=\"pm\" />";
//--------------------- FIRST LINE SUPPORT SECTION ---------------------------//
unset($ppl);
$res = sql_query("SELECT * FROM users WHERE users.support='yes' AND users.status='confirmed' ORDER BY users.username") or sqlerr();
while ($arr = mysql_fetch_assoc($res))
{
	$ppl .= "<div><div class=embedded>". get_username($arr['id']) ."</div>
 <div class=embedded> ".(strtotime($arr['last_access']) > $dt ? $onlineimg : $offlineimg)."</div>".
 "<div class=embedded><a href=sendmessage.php?receiver=".$arr['id']." title=\"".$lang_staff['title_send_pm']."\">".$sendpmimg."</a></div>".
 "<div class=embedded>".$arr['supportfor']."</div></div>\n";
}

begin_frame($lang_staff['text_firstline_support']."<font class=small> - [<a class=altlink href=contactstaff.php><b>".$lang_staff['text_apply_for_it']."</b></a>]</font>");
?>
<?php echo $lang_staff['text_firstline_support_note'] ?>
<br /><br />
<div width=100% cellspacing=0 align=center>
	<div>
		<div class=embedded><b><?php echo $lang_staff['text_username'] ?></b></div>
		<div class=embedded align=center><b><?php echo $lang_staff['text_online_or_offline'] ?></b></div>
		<div class=embedded align=center><b><?php echo $lang_staff['text_contact'] ?></b></div>
		<div class=embedded><b><?php echo $lang_staff['text_support_for'] ?></b></div>
	</div>
	<div>
		<div class=embedded colspan=6>
			<hr color="#4040c0">
		</div>
	</div>
	<?php echo $ppl?>
</div>
<?php
end_frame();

//--------------------- FIRST LINE SUPPORT SECTION ---------------------------//

//--------------------- film critics section ---------------------------//
unset($ppl);
$res = sql_query("SELECT * FROM users WHERE users.picker='yes' AND users.status='confirmed' ORDER BY users.username") or sqlerr();
while ($arr = mysql_fetch_assoc($res))
{
	$ppl .= "<div height=15><div class=embedded>". get_username($arr['id']) ."</div>
 <div class=embedded> ".(strtotime($arr['last_access']) > $dt ? $onlineimg : $offlineimg)."</div>".
 "<div class=embedded><a href=sendmessage.php?receiver=".$arr['id']." title=\"".$lang_staff['title_send_pm']."\">".$sendpmimg."</a></div>".
 "<div class=embedded>".$arr['pickfor']."</div></div>\n";
}

begin_frame($lang_staff['text_movie_critics']."<font class=small> - [<a class=altlink href=contactstaff.php><b>".$lang_staff['text_apply_for_it']."</b></a>]</font>");
?>
<?php echo $lang_staff['text_movie_critics_note'] ?>
<br /><br />
<div width=100% cellspacing=0 align=center>
	<div>
		<div class=embedded><b><?php echo $lang_staff['text_username'] ?></b></div>
		<div class=embedded align=center><b><?php echo $lang_staff['text_online_or_offline'] ?></b></div>
		<div class=embedded align=center><b><?php echo $lang_staff['text_contact'] ?></b></div>
		<div class=embedded><b><?php echo $lang_staff['text_responsible_for'] ?></b></div>
	</div>
	<div>
		<div class=embedded colspan=5>
			<hr color="#4040c0">
		</div>
	</div>
	<?php echo $ppl?>
</div>
<?php
end_frame();

//--------------------- film critics section ---------------------------//

//--------------------- forum moderators section ---------------------------//
unset($ppl);
$res = sql_query("SELECT forummods.userid AS userid, users.last_access, users.country FROM forummods LEFT JOIN users ON forummods.userid = users.id GROUP BY userid ORDER BY forummods.forumid, forummods.userid") or sqlerr();
while ($arr = mysql_fetch_assoc($res))
{
	$forums = "";
	$forumres = sql_query("SELECT forums.id, forums.name FROM forums LEFT JOIN forummods ON forums.id = forummods.forumid WHERE forummods.userid = ".sqlesc($arr[userid]));
	while ($forumrow = mysql_fetch_array($forumres)){
		$forums .= "<a href=forums.php?action=viewforum&forumid=".$forumrow['id'].">".$forumrow['name']."</a>, ";
	}
	$forums = rtrim(trim($forums),",");
	$ppl .= "<div height=15><div class=embedded>". get_username($arr['userid']) ."</div>
 <div class=embedded> ".(strtotime($arr['last_access']) > $dt ? $onlineimg : $offlineimg)."</div>".
 "<div class=embedded><a href=sendmessage.php?receiver=".$arr['userid']." title=\"".$lang_staff['title_send_pm']."\">".$sendpmimg."</a></div>".
 "<div class=embedded>".$forums."</div></div>\n";
}

begin_frame($lang_staff['text_forum_moderators']."<font class=small> - [<a class=altlink href=contactstaff.php><b>".$lang_staff['text_apply_for_it']."</b></a>]</font>");
?>
<?php echo $lang_staff['text_forum_moderators_note'] ?>
<br /><br />
<div width=100% cellspacing=0 align=center>
	<div>
		<div class=embedded><b><?php echo $lang_staff['text_username'] ?></b></div>
		<div class=embedded align=center><b><?php echo $lang_staff['text_online_or_offline'] ?></b></div>
		<div class=embedded align=center><b><?php echo $lang_staff['text_contact'] ?></b></div>
		<div class=embedded><b><?php echo $lang_staff['text_forums'] ?></b></div>
	</div>
	<div>
		<div class=embedded colspan=5>
			<hr color="#4040c0">
		</div>
	</div>
	<?php echo $ppl?>
</div>
<?php
end_frame();

//--------------------- film critics section ---------------------------//

//--------------------- general staff section ---------------------------//
unset($ppl);
$res = sql_query("SELECT * FROM users WHERE class > ".UC_VIP." AND status='confirmed' ORDER BY class DESC, username") or sqlerr();
while ($arr = mysql_fetch_assoc($res))
{
	if($curr_class != $arr['class'])
	{
		$curr_class = $arr['class'];
		if ($ppl != "")
			$ppl .= "<div height=15><div class=embedded colspan=5 align=right>&nbsp;</div></div>";
		$ppl .= "<div height=15><div class=embedded colspan=5 align=right>" . get_user_class_name($arr["class"],false,true,true) . "</div></div>";
		$ppl .= "<div>" . 
		"<div class=embedded><b>" . $lang_staff['text_username'] . "</b></div>".
		"<div class=embedded align=center><b>" . $lang_staff['text_online_or_offline'] . "</b></div>".
		"<div class=embedded align=center><b>" . $lang_staff['text_contact'] . "</b></div>".
		"<div class=embedded><b>" . $lang_staff['text_duties'] . "</b></div>".
		"</div>";
		$ppl .= "<div height=15><div class=embedded colspan=5><hr color=\"#4040c0\"></div></div>";
	}
	$countryrow = get_country_row($arr['country']);
	$ppl .= "<div><div class=embedded>". get_username($arr['id']) ."</div>
 <div class=embedded> ".(strtotime($arr['last_access']) > $dt ? $onlineimg : $offlineimg)."</div>".
 "<div class=embedded><a href=sendmessage.php?receiver=".$arr['id']." title=\"".$lang_staff['title_send_pm']."\">".$sendpmimg."</a></div>".
 "<div class=embedded>".$arr['stafffor']."</div></div>\n";
}

begin_frame($lang_staff['text_general_staff']."<font class=small> - [<a class=altlink href=contactstaff.php><b>".$lang_staff['text_apply_for_it']."</b></a>]</font>");
?>
<?php echo $lang_staff['text_general_staff_note'] ?>
<br /><br />
<div width=100% cellspacing=0 align=center>
	<?php echo $ppl?>
</div>
<?php
end_frame();

//--------------------- general staff section ---------------------------//


//--------------------- VIP section ---------------------------//

/*unset($ppl);
$res = sql_query("SELECT * FROM users WHERE class=".UC_VIP." AND status='confirmed' ORDER BY username") or sqlerr();
while ($arr = mysql_fetch_assoc($res))
{
	$ppl .= "<div><div class=embedded>". get_username($arr['id']) ."</div>
 <div class=embedded> ".(strtotime($arr['last_access']) > $dt ? $onlineimg : $offlineimg)."</div>".
 "<div class=embedded><a href=sendmessage.php?receiver=".$arr['id']." title=\"".$lang_staff['title_send_pm']."\">".$sendpmimg."</a></div>".
 "<div class=embedded>".$arr['stafffor']."</div></div>\n";
}

begin_frame($lang_staff['text_vip']);
?>
<?php echo $lang_staff['text_vip_note'] ?>
<br /><br />
<div width=100% cellspacing=0 align=center>
	<div>
		<div class=embedded><b><?php echo $lang_staff['text_username'] ?></b></div>
		<div class=embedded><b><?php echo $lang_staff['text_online_or_offline'] ?></b></div>
		<div class=embedded><b><?php echo $lang_staff['text_contact'] ?></b></div>
		<div class=embedded><b><?php echo $lang_staff['text_reason'] ?></b></div>
	</div>
	<div>
		<div class=embedded colspan=5>
			<hr color="#4040c0">
		</div>
	</div>
	<?php echo $ppl?>
</div>
<?php
end_frame();*/

//--------------------- VIP section ---------------------------//
end_main_frame();
	$Cache->end_whole_row();
	$Cache->cache_page();
}
echo $Cache->next_row();
stdfoot();
