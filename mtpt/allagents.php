<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
require_once(get_langfile_path());
if (get_user_class() < UC_MODERATOR)
	stderr("Error", "Permission denied.");
$res2 = sql_query("SELECT agent,peer_id FROM peers  GROUP BY agent ") or sqlerr();
stdhead("All Clients");
print("<div align=center border=3 cellspacing=0 cellpadding=5>\n");
print("<div><div class=colhead>".$lang_allagents['text_client']."</div><div class=colhead>".$lang_allagents['text_peerid']."</div></div>\n");
while($arr2 = mysql_fetch_assoc($res2))
{
	print("</a></div><div align=left>$arr2[agent]</div><div align=left>$arr2[peer_id]</div></div>\n");
}
print("</div>\n");
stdfoot();
