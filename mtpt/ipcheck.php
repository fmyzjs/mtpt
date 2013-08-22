<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
require_once(get_langfile_path());

if (get_user_class() < UC_MODERATOR)
stderr("Sorry", "Access denied.");
stdhead("Duplicate IP users");
begin_frame($lang_ipcheck['head_ipcheck'], true);
begin_table();

if (get_user_class() >= UC_MODERATOR || $CURUSER["guard"] == "yes")
{
 $res = sql_query("SELECT count(*) AS dupl, ip FROM users WHERE enabled = 'yes' AND ip <> '' AND ip <> '127.0.0.0' GROUP BY ip ORDER BY dupl DESC, ip") or sqlerr();
  print("<div align=center><div class=colhead width=90>".$lang_ipcheck['text_username']."</div>
 <div class=colhead width=70>".$lang_ipcheck['text_email']."</div>
 <div class=colhead width=70>".$lang_ipcheck['text_registered']."</div>
 <div class=colhead width=75>".$lang_ipcheck['text_lastaccess']."</div>
 <div class=colhead width=70>".$lang_ipcheck['text_downloaded']."</div>
 <div class=colhead width=70>".$lang_ipcheck['text_uploaded']."</div>
 <div class=colhead width=45>".$lang_ipcheck['text_ratio']."</div>
 <div class=colhead width=125>".$lang_ipcheck['text_ip']."</div>
 <div class=colhead width=40>".$lang_ipcheck['text_peer']."</div></div>\n");
 $uc = 0;
  while($ras = mysql_fetch_assoc($res))
  {
	if ($ras["dupl"] <= 1)
	  break;
	if ($ip <> $ras['ip'])
    {
	  $ros = sql_query("SELECT  id, username, email, added, last_access, downloaded, uploaded, ip, warned, donor, enabled FROM users WHERE ip='".$ras['ip']."' ORDER BY id") or sqlerr();
	  $num2 = mysql_num_rows($ros);
	  if ($num2 > 1)
	  {
		$uc++;
	    while($arr = mysql_fetch_assoc($ros))
		{
		  if ($arr['added'] == '0000-00-00 00:00:00')
			$arr['added'] = '-';
		  if ($arr['last_access'] == '0000-00-00 00:00:00')
			$arr['last_access'] = '-';
		  if($arr["downloaded"] != 0)
			$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
		  else
			$ratio="---";
 
		  $ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
		  $uploaded = mksize($arr["uploaded"]);
		  $downloaded = mksize($arr["downloaded"]);
		  $added = substr($arr['added'],0,10);
		  $last_access = substr($arr['last_access'],0,10);
		  if($uc%2 == 0)
			$utc = "";
		  else
			$utc = " bgcolor=\"ECE9D8\"";
			
			$peer_res = sql_query("SELECT count(*) FROM peers WHERE ip = " . sqlesc($ras['ip']) . " AND userid = " . $arr['id']);
			$peer_row = mysql_fetch_row($peer_res);
		  print("<div$utc><div align=left>" . get_username($arr["id"])."</div>
				  <div align=center>$arr[email]</div>
				  <div align=center>$added</div>
				  <div align=center>$last_access</div>
				  <div align=center>$downloaded</div>
				  <div align=center>$uploaded</div>
				  <div align=center>$ratio</div>
				  <div align=center><a href=\"http://www.whois.sc/$arr[ip]\" target=\"_blank\">$arr[ip]</a></div>\n<div align=center>" . 
				  ($peer_row[0] ? "ja" : "nein") . "</div></div>\n");
		  $ip = $arr["ip"];
		}
	  }
	}
  }
}
else
{
 print("<br /><div width=60% border=1 cellspacing=0 cellpadding=9><div><div align=center>");
 print("<h2>Sorry, only for Team</h2></div></div></div>");
}
end_frame();
end_table();

stdfoot();
?>
