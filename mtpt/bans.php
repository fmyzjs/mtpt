<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
require_once(get_langfile_path());
if (get_user_class() < UC_ADMINISTRATOR)
stderr("Sorry", "Access denied.");

$remove = (int)$_GET['remove'];
if (is_valid_id($remove))
{
  sql_query("DELETE FROM bans WHERE id=".mysql_real_escape_string($remove)) or sqlerr();
  write_log("Ban ".htmlspecialchars($remove)." was removed by $CURUSER[id] ($CURUSER[username])",'mod');
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && get_user_class() >= UC_ADMINISTRATOR)
{
	$first = trim($_POST["first"]);
	$last = trim($_POST["last"]);
	$comment = trim($_POST["comment"]);
	if (!$first || !$last || !$comment)
		stderr("Error", "Missing form data.");
	$firstlong = ip2long($first);
	$lastlong = ip2long($last);
	if ($firstlong == -1 || $lastlong == -1)
		stderr("Error", "Bad IP address.");
	$comment = sqlesc($comment);
	$added = sqlesc(date("Y-m-d H:i:s"));
	sql_query("INSERT INTO bans (added, addedby, first, last, comment) VALUES($added, ".mysql_real_escape_string($CURUSER[id]).", $firstlong, $lastlong, $comment)") or sqlerr(__FILE__, __LINE__);
	header("Location: $_SERVER[REQUEST_URI]");
	die;
}

//ob_start("ob_gzhandler");

$res = sql_query("SELECT * FROM bans ORDER BY added DESC") or sqlerr();

stdhead("Bans");

print("<h1>".$lang_bans['head_current']."</h1>\n");

if (mysql_num_rows($res) == 0)
  print("<p align=center><b>".$lang_bans['text_nothing']."</b></p>\n");
else
{
  print("<div border=1 cellspacing=0 cellpadding=5>\n");
  print("<div><div class=colhead>".$lang_bans['text_addtime']."</div><div class=colhead align=left>".$lang_bans['text_firstip']."</div><div class=colhead align=left>".$lang_bans['text_lastip']."</div>".
    "<div class=colhead align=left>".$lang_bans['text_by']."</div><div class=colhead align=left>".$lang_bans['text_comment']."</div><div class=colhead>".$lang_bans['text_act']."</div></div>\n");

  while ($arr = mysql_fetch_assoc($res))
  {
 	  print("<div><div>".gettime($arr[added])."</div><div align=left>".long2ip($arr[first])."</div><div align=left>".long2ip($arr[last])."</div><div align=left>". get_username($arr['addedby']) .
 	    "</div><div align=left>$arr[comment]</div><div><a href=bans.php?remove=$arr[id]>".$lang_bans['text_remove']."</a></div></div>\n");
  }
  print("</div>\n");
}

if (get_user_class() >= UC_ADMINISTRATOR)
{
	print("<h1>".$lang_bans['head_addban']."</h1>\n");
	print("<div border=1 cellspacing=0 cellpadding=5>\n");
	print("<form method=post action=bans.php>\n");
	print("<div><div class=rowhead>".$lang_bans['text_firstip']."</div><div><input type=text name=first size=40></div></div>\n");
	print("<div><div class=rowhead>".$lang_bans['text_lastip']."</div><div><input type=text name=last size=40></div></div>\n");
	print("<div><div class=rowhead>".$lang_bans['text_comment']."</div><div><input type=text name=comment size=40></div></div>\n");
	print("<div><div colspan=2 align=center><input type=submit value='".$lang_bans['submit_add']."' class=btn></div></div>\n");
	print("</form>\n</div>\n");
}

stdfoot();

?>
