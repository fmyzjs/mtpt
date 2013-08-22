<?php
require "include/bittorrent.php";
dbconn();
require_once(get_langfile_path());
loggedinorreturn();

if (get_user_class() < $pollmanage_class)
	permissiondenied();

$pollid = 0+$_GET['id'];

if ($pollid)
{
	$res = sql_query("SELECT * FROM polls WHERE id = ".sqlesc($pollid)." LIMIT 1") or sqlerr();
	if (mysql_num_rows($res) == 0)
		stderr($lang_polloverview['std_error'], $lang_polloverview['text_no_poll_id']);
	stdhead($lang_polloverview['head_poll_overview']);
	print("<h1 align=\"center\">".$lang_polloverview['text_polls_overview']."</h1>\n");

	print("<div width=737 border=1 cellspacing=0 cellpadding=5><div>\n" . 
 "<div class=colhead align=center><nobr>".$lang_polloverview['col_id']."</nobr></div><div class=colhead><nobr>".$lang_polloverview['col_added']."</nobr></div><div class=colhead><nobr>".$lang_polloverview['col_question']."</nobr></div></div>\n");
  
	while ($poll = mysql_fetch_assoc($res))
	{
		$o = array($poll["option0"], $poll["option1"], $poll["option2"], $poll["option3"], $poll["option4"], $poll["option5"], $poll["option6"], $poll["option7"], $poll["option8"], $poll["option9"], $poll["option10"], $poll["option11"], $poll["option12"], $poll["option13"], $poll["option14"], $poll["option15"], $poll["option16"], $poll["option17"], $poll["option18"], $poll["option19"]);
   
		$added = gettime($poll['added']);
		print("<div><div align=center><a href=\"polloverview.php?id=".$poll['id']."\">".$poll['id']."</a></div><div>".$added."</div><div><a href=\"polloverview.php?id=".$poll['id']."\">".$poll['question']."</a></div></div>\n");
	}
	print("</div>\n");
 
	print("<h1 align=\"center\">".$lang_polloverview['text_poll_question']."</h1><br />\n");
	print("<div width=737 border=1 cellspacing=0 cellpadding=5><div><div class=colhead>".$lang_polloverview['col_option_no']."</div><div class=colhead>".$lang_polloverview['col_options']."</div></div>\n");
	foreach($o as $key=>$value) {
		if($value != "")
			print("<div><div>".$key."</div><div>".$value."</div></div>\n");
	}
 	print("</div>\n");
	$count = get_row_count("pollanswers", "WHERE pollid = ".sqlesc($pollid)." AND selection < 20");

	print("<h1 align=\"center\">".$lang_polloverview['text_polls_user_overview']."</h1>\n");

	if ($count == 0) {
		print("<p align=\"center\">".$lang_polloverview['text_no_users_voted']."</p>");
	}
	else{
		$perpage = 100;
		list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "?id=".$pollid."&");
		$res2 = sql_query("SELECT pollanswers.*, users.username FROM pollanswers LEFT JOIN users ON pollanswers.userid = users.id WHERE pollid = ".sqlesc($pollid)." AND selection < 20 ORDER BY username ASC ".$limit) or sqlerr();
		print($pagertop);
 		print("<div width=737 border=1 cellspacing=0 cellpadding=5>");
		print("<div><div class=colhead align=center><nobr>".$lang_polloverview['col_username']."</nobr></div><div class=colhead align=center><nobr>".$lang_polloverview['col_selection']."<nobr></div></div>\n");
		while ($useras = mysql_fetch_assoc($res2))
		{
			$username = get_username($useras['userid']);
  			print("<div><div>".$username."</div><div>".$o[$useras['selection']]."</div></div>\n");
 		}
		print("</div>\n");
		print($pagerbottom);
	}
	stdfoot();
}
else
{
	$res = sql_query("SELECT id, added, question FROM polls ORDER BY id DESC") or sqlerr();
 	if (mysql_num_rows($res) == 0)
  		stderr($lang_polloverview['std_error'], $lang_polloverview['text_no_users_voted']);
	stdhead($lang_polloverview['head_poll_overview']);
	print("<h1 align=\"center\">".$lang_polloverview['text_polls_overview']."</h1>\n");

	print("<div width=737 border=1 cellspacing=0 cellpadding=5><div>\n" . 
 "<div class=colhead align=center><nobr>".$lang_polloverview['col_id']."</nobr></div><div class=colhead>".$lang_polloverview['col_added']."</div><div class=colhead><nobr>".$lang_polloverview['col_question']."</nobr></div></div>\n");
	while ($poll = mysql_fetch_assoc($res))
	{
		$added = gettime($poll['added']);
		print("<div><div align=center><a href=\"polloverview.php?id=".$poll['id']."\">".$poll['id']."</a></div><div>".$added."</div><div><a href=\"polloverview.php?id=".$poll['id']."\">".$poll['question']."</a></div></div>\n");  
	}
	print("</div>\n");
	stdfoot();
}
?>
