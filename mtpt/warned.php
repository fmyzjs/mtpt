<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
require_once(get_langfile_path());
parked();
if (get_user_class() < UC_MODERATOR)
stderr("Sorry", "Access denied.");

stdhead("Warned Users");
$warned = number_format(get_row_count("users", "WHERE warned='yes'"));
begin_frame($lang_warned['head_warned']."($warned)", true);
begin_table();

$res = sql_query("SELECT * FROM users WHERE warned=1 AND enabled='yes' ORDER BY (users.uploaded/users.downloaded)") or sqlerr();
$num = mysql_num_rows($res);
print("<div border=1 width=675 cellspacing=0 cellpadding=2><form action=\"nowarn.php\" method=post>\n");
print("<div align=center><div class=colhead width=90>".$lang_warned['text_username']."</div>
 <div class=colhead width=70>".$lang_warned['text_registered']."</div>
 <div class=colhead width=75>".$lang_warned['text_lastaccess']."</div>  
 <div class=colhead width=75>".$lang_warned['text_userclass']."</div>
 <div class=colhead width=70>".$lang_warned['text_downloaded']."</div>
 <div class=colhead width=70>".$lang_warned['text_uploaded']."</div>
 <div class=colhead width=55>".$lang_warned['text_ratio']."</div>
 <div class=colhead width=125>".$lang_warned['text_end_of_warning']."</div>
 <div class=colhead width=65>".$lang_warned['text_remove_warning']."</div>
 <div class=colhead width=65>".$lang_warned['text_disable_account']."</div></div>\n");
for ($i = 1; $i <= $num; $i++)
{
$arr = mysql_fetch_assoc($res);
if ($arr['added'] == '0000-00-00 00:00:00')
  $arr['added'] = '-';
if ($arr['last_access'] == '0000-00-00 00:00:00')
  $arr['last_access'] = '-';
if($arr["downloaded"] != 0){
$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
} else {
$ratio="---";
}
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
  $uploaded = mksize($arr["uploaded"]);
  $downloaded = mksize($arr["downloaded"]);
// $uploaded = str_replace(" ", "<br>", mksize($arr["uploaded"]));
// $downloaded = str_replace(" ", "<br>", mksize($arr["downloaded"]));

$added = substr($arr['added'],0,10);
$last_access = substr($arr['last_access'],0,10);
$class=get_user_class_name($arr["class"],false,true,true);

print("<div><div align=left>" . get_username($arr['id']) ."</div>
  <div align=center>$added</div>
  <div align=center>$last_access</div>
  <div align=center>$class</div>
  <div align=center>$downloaded</div>
  <div align=center>$uploaded</div>
  <div align=center>$ratio</div>
  <div align=center>$arr[warneduntil]</div>
  <div bgcolor=\"#008000\" align=center><input type=\"checkbox\" name=\"usernw[]\" value=\"$arr[id]\"></div>
  <div bgcolor=\"#FF000\" align=center><input type=\"checkbox\" name=\"desact[]\" value=\"$arr[id]\"></div></div>\n");
}
if (get_user_class() >= UC_ADMINISTRATOR) {
print("<div><div colspan=10 align=right><input type=\"submit\" name=\"submit\"]\"></div></div>\n");
print("<input type=\"hidden\" name=\"nowarned\" value=\"nowarned\"></form></div>\n");
}
print("<p>$pagemenu<br>$browsemenu</p>");

die;

?>
