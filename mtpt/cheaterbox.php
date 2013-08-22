<?php
require "include/bittorrent.php";
dbconn();
require_once(get_langfile_path());
loggedinorreturn();
parked();

if (get_user_class() < $staffmem_class)
	permissiondenied();


if ($_POST['setdealt']){
	$res = sql_query ("SELECT id FROM cheaters WHERE dealtwith=0 AND id IN (" . implode(", ", $_POST[delcheater]) . ")");
	while ($arr = mysql_fetch_assoc($res))
		sql_query ("UPDATE cheaters SET dealtwith=1, dealtby = $CURUSER[id] WHERE id = $arr[id]") or sqlerr();
	$Cache->delete_value('staff_new_cheater_count');
}
elseif ($_POST['delete']){
	$res = sql_query ("SELECT id FROM cheaters WHERE id IN (" . implode(", ", $_POST[delcheater]) . ")");
	while ($arr = mysql_fetch_assoc($res))
		sql_query ("DELETE from cheaters WHERE id = $arr[id]") or sqlerr();
	$Cache->delete_value('staff_new_cheater_count');
}

$count = get_row_count("cheaters");
if (!$count){
	stderr($lang_cheaterbox['std_oho'], $lang_cheaterbox['std_no_suspect_detected']);
}
$perpage = 10;
list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "cheaterbox.php?");
stdhead($lang_cheaterbox['head_cheaterbox']);
?>
<style type="text/css">
table.cheaterbox td
{
	text-align: center;
}
</style>
<?php
begin_main_frame();
print("<h1 align=center>".$lang_cheaterbox['text_cheaterbox']."</h1>");
print("<div class=cheaterbox border=1 cellspacing=0 cellpadding=5 align=center>\n");
print("<div><div class=colhead><nobr>".$lang_cheaterbox['col_added']."</nobr></div><div class=colhead>".$lang_cheaterbox['col_suspect']."</div><div class=colhead><nobr>".$lang_cheaterbox['col_hit']."</nobr></div><div class=colhead>".$lang_cheaterbox['col_torrent']."</div><div class=colhead>".$lang_cheaterbox['col_ul']."</div><div class=colhead>".$lang_cheaterbox['col_dl']."</div><div class=colhead><nobr>".$lang_cheaterbox['col_ann_time']."</nobr></div><div class=colhead><nobr>".$lang_cheaterbox['col_seeders']."</nobr></div><div class=colhead><nobr>".$lang_cheaterbox['col_leechers']."</nobr></div><div class=colhead>".$lang_cheaterbox['col_comment']."</div><div class=colhead><nobr>".$lang_cheaterbox['col_dealt_with']."</nobr></div><div class=colhead><nobr>".$lang_cheaterbox['col_action']."</nobr></div></div>");

print("<form method=post action=cheaterbox.php>");
$cheatersres = sql_query("SELECT * FROM cheaters ORDER BY dealtwith ASC, id DESC $limit");

while ($row = mysql_fetch_array($cheatersres))
{
	$upspeed = ($row['uploaded'] > 0 ? $row['uploaded'] / $row['anctime'] : 0);
	$lespeed = ($row['downloaded'] > 0 ? $row['downloaded'] / $row['anctime'] : 0);
	$torrentres = sql_query("SELECT name FROM torrents WHERE id=".sqlesc($row['torrentid']));
	$torrentrow = mysql_fetch_array($torrentres);
	if ($torrentrow)
		$torrent = "<a href=details.php?id=".$row['torrentid'].">".htmlspecialchars($torrentrow['name'])."</a>";
	else $torrent = $lang_cheaterbox['text_torrent_does_not_exist'];
	if ($row['dealtwith'])
		$dealtwith = "<font color=green>".$lang_cheaterbox['text_yes']."</font> - " . get_username($row['dealtby']);
	else
		$dealtwith = "<font color=red>".$lang_cheaterbox['text_no']."</font>";

	print("<div><div class=rowfollow>".gettime($row['added'])."</div><div class=rowfollow>" . get_username($row['userid']) . "</div><div class=rowfollow>" . $row['hit'] . "</div><div class=rowfollow>" . $torrent . "</div><div class=rowfollow>".mksize($row['uploaded']).($upspeed ? " @ ".mksize($upspeed)."/s" : "")."</div><div class=rowfollow>".mksize($row['downloaded']).($lespeed ? " @ ".mksize($lespeed)."/s" : "")."</div><div class=rowfollow>".$row['anctime']." sec"."</div><div class=rowfollow>".$row['seeders']."</div><div class=rowfollow>".$row['leechers']."</div><div class=rowfollow>".htmlspecialchars($row['comment'])."</div><div class=rowfollow>".$dealtwith."</div><div class=rowfollow><input type=\"checkbox\" name=\"delcheater[]\" value=\"" . $row[id] . "\" /></div></div>\n");
}
?>
<div><div class="colhead" colspan="12" style="text-align: right"><input type="submit" name="setdealt" value="<?php echo $lang_cheaterbox['submit_set_dealt']?>" /><input type="submit" name="delete" value="<?php echo $lang_cheaterbox['submit_delete']?>" /></div></div> 
</form>
<?php
print("</div>");
print($pagerbottom);
end_main_frame();
stdfoot();
?>
