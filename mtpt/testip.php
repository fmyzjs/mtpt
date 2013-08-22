<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
require_once(get_langfile_path());
if (get_user_class() < UC_MODERATOR) stderr("Error", "Permission denied");

if ($_SERVER["REQUEST_METHOD"] == "POST")
	$ip = $_POST["ip"];
else
	$ip = $_GET["ip"];
if ($ip)
{
	$nip = ip2long($ip);
	if ($nip == -1)
	  stderr("Error", "Bad IP.");
	$res = sql_query("SELECT * FROM bans WHERE '$nip' >= first AND '$nip' <= last") or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) == 0)
	  stderr("Result", $lang_testip['text_resultip']."<b>". htmlspecialchars($ip) ."</b>".$lang_testip['text_notbanned'],false);
	else
	{
	  $banstable = "<div class=main border=0 cellspacing=0 cellpadding=5>\n" .
	    "<div><div class=colhead>First</div><div class=colhead>Last</div><div class=colhead>Comment</div></div>\n";
	  while ($arr = mysql_fetch_assoc($res))
	  {
	    $first = long2ip($arr["first"]);
	    $last = long2ip($arr["last"]);
	    $comment = htmlspecialchars($arr["comment"]);
	    $banstable .= "<div><div>$first</div><div>$last</div><div>$comment</div></div>\n";
	  }
	  $banstable .= "</div>\n";
	
	  stderr("结果", "<div border=0 cellspacing=0 cellpadding=0><div><div class=embedded style='padding-right: 5px'><img src=pic/smilies/excl.gif></div><div class=embedded>The IP address <b>". htmlspecialchars($ip) ."</b> is banned:</div></div></div><p>". $banstable ."</p>",false);
	}
}
stdhead();

?>
<h1><?php echo $lang_testip['head_testip']?></h1>
<form method=post action=testip.php>
<div border=1 cellspacing=0 cellpadding=5>
<div><div class=rowhead><?php echo $lang_testip['text_ip']?></div><div><input type=text name=ip></div></div>
<div><div colspan=2 align=center><input type=submit class=btn></div></div>
</form>
</div>

<?php
stdfoot();
