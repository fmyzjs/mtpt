<?php
require "include/bittorrent.php";
dbconn();
require_once(get_langfile_path());
//Send some headers to keep the user's browser from caching the response.
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
header("Cache-Control: no-cache, must-revalidate" ); 
header("Pragma: no-cache" );
header("Content-Type: text/xml; charset=utf-8");

$id = 0 + $_GET['id'];
if(isset($CURUSER))
{
function dltable($name, $arr, $torrent)
{
	global $lang_viewpeerlist,$viewanonymous_class,$userprofile_class,$enablelocation_tweak;
	global $CURUSER;
	$s = "<b>" . count($arr) . " $name</b>\n";
	if (!count($arr))
		return $s;
	$s .= "\n";
	$s .= "<div width=825 class=main border=1 cellspacing=0 cellpadding=3>\n";
	$s .= "<div><div class=colhead align=center width=1%>".$lang_viewpeerlist['col_user_ip']."</div>" .
	($enablelocation_tweak == 'yes' || get_user_class() >= $userprofile_class ? "<div class=colhead align=center width=1%>".$lang_viewpeerlist['col_location']."</div>" : "").
	"<div class=colhead align=center width=1%>".$lang_viewpeerlist['col_connectable']."</div>".
	"<div class=colhead align=center width=1%>".$lang_viewpeerlist['col_uploaded']."</div>".
	"<div class=colhead align=center width=1%>".$lang_viewpeerlist['col_rate']."</div>" .
	"<div class=colhead align=center width=1%>".$lang_viewpeerlist['col_downloaded']."</div>" .
	"<div class=colhead align=center width=1%>".$lang_viewpeerlist['col_rate']."</div>" .
	"<div class=colhead align=center width=1%>".$lang_viewpeerlist['col_ratio']."</div>" .
	"<div class=colhead align=center width=1%>".$lang_viewpeerlist['col_complete']."</div>" .
	"<div class=colhead align=center width=1%>".$lang_viewpeerlist['col_connected']."</div>" .
	"<div class=colhead align=center width=1%>".$lang_viewpeerlist['col_idle']."</div>" .
	"<div class=colhead align=center width=1%>".$lang_viewpeerlist['col_client']."</div></div>\n";
	$now = time();
	foreach ($arr as $e) {
		$privacy = get_single_value("users", "privacy","WHERE id=".sqlesc($e['userid']));
		++$num;

		$highlight = $CURUSER["id"] == $e['userid'] ? " bgcolor=#BBAF9B" : "";
		$s .= "<div$highlight>\n";
		if($privacy == "strong" || ($torrent['anonymous'] == 'yes' && $e['userid'] == $torrent['owner']))
		{
			if(get_user_class() >= $viewanonymous_class || $e['userid'] == $CURUSER['id'])
				$s .= "<div class=rowfollow align=left width=1%><i>".$lang_viewpeerlist['text_anonymous']."</i><br />(" . get_username($e['userid']) . ")";
			else
				$s .= "<div class=rowfollow align=left width=1%><i>".$lang_viewpeerlist['text_anonymous']."</i></a></div>\n";
		}
		else
			$s .= "<div class=rowfollow align=left width=1%>" . get_username($e['userid']);

		$secs = max(1, ($e["la"] - $e["st"]));
		if ($enablelocation_tweak == 'yes'){
			list($loc_pub, $loc_mod) = get_ip_location($e["ip"]);
			$location = get_user_class() >= $userprofile_class ? "<div title='" . $loc_mod . "'>" . $loc_pub . "</div>" : $loc_pub;
			$s .= "<div class=rowfollow align=center width=1%><nobr>" . $location . "</nobr></div>\n";
		}
		elseif (get_user_class() >= $userprofile_class){
			$location = $e["ip"];
			$s .= "<div class=rowfollow align=center width=1%><nobr>" . $location . "</nobr></div>\n";
		}
		else $location = "";

		$s .= "<div class=rowfollow align=center width=1%><nobr>" . ($e[connectable] == "yes" ? $lang_viewpeerlist['text_yes'] : "<font color=red>".$lang_viewpeerlist['text_no']."</font>") . "</nobr></div>\n";
		$s .= "<div class=rowfollow align=center width=1%><nobr>" . mksize($e["uploaded"]) . "</nobr></div>\n";

		$s .= "<div class=rowfollow align=center width=1%><nobr>" . mksize(($e["uploaded"] - $e["uploadoffset"]) / $secs) . "/s</nobr></div>\n";
		$s .= "<div class=rowfollow align=center width=1%><nobr>" . mksize($e["downloaded"]) . "</nobr></div>\n";

		if ($e["seeder"] == "no")
		$s .= "<div class=rowfollow align=center width=1%><nobr>" . mksize(($e["downloaded"] - $e["downloadoffset"]) / $secs) . "/s</nobr></div>\n";
		else
		$s .= "<div class=rowfollow align=center width=1%><nobr>" . mksize(($e["downloaded"] - $e["downloadoffset"]) / max(1, $e["finishedat"] - $e[st])) .	"/s</nobr></div>\n";
		if ($e["downloaded"])
		{
			$ratio = floor(($e["uploaded"] / $e["downloaded"]) * 1000) / 1000;
			$s .= "<div class=rowfollow align=\"center\" width=1%><font color=" . get_ratio_color($ratio) . "><nobr>" . number_format($ratio, 3) . "</nobr></font></div>\n";
		}
		elseif ($e["uploaded"])
		$s .= "<div class=rowfollow align=center width=1%>".$lang_viewpeerlist['text_inf']."</div>\n";
		else
		$s .= "<div class=rowfollow align=center width=1%>---</div>\n";
		$s .= "<div class=rowfollow align=center width=1%><nobr>" . sprintf("%.2f%%", 100 * (1 - ($e["to_go"] / $torrent["size"]))) . "</nobr></div>\n";
		$s .= "<div class=rowfollow align=center width=1%><nobr>" . mkprettytime($now - $e["st"]) . "</nobr></div>\n";
		$s .= "<div class=rowfollow align=center width=1%><nobr>" . mkprettytime($now - $e["la"]) . "</nobr></div>\n";
		$s .= "<div class=rowfollow align=center width=1%><nobr>" . htmlspecialchars(get_agent($e["peer_id"],$e["agent"])) . "</nobr></div>\n";
		$s .= "</div>\n";
	}
	$s .= "</div>\n";
	return $s;
}

	$downloaders = array();
	$seeders = array();
	$subres = sql_query("SELECT seeder, finishedat, downloadoffset, uploadoffset, ip, port, uploaded, downloaded, to_go, UNIX_TIMESTAMP(started) AS st, connectable, agent, peer_id, UNIX_TIMESTAMP(last_action) AS la, userid FROM peers WHERE torrent = $id") or sqlerr();
	while ($subrow = mysql_fetch_array($subres)) {
	if ($subrow["seeder"] == "yes")
		$seeders[] = $subrow;
	else
		$downloaders[] = $subrow;
	}

	function leech_sort($a,$b) {
		$x = $a["to_go"];
		$y = $b["to_go"];
		if ($x == $y)
			return 0;
		if ($x < $y)
			return -1;
		return 1;
	}
	function seed_sort($a,$b) {
		$x = $a["uploaded"];
		$y = $b["uploaded"];
		if ($x == $y)
			return 0;
		if ($x < $y)
			return 1;
		return -1;
	}
	$res = sql_query("SELECT torrents.id, torrents.owner, torrents.size, torrents.anonymous FROM torrents WHERE torrents.id = $id LIMIT 1") or sqlerr();
	$row = mysql_fetch_array($res);
	usort($seeders, "seed_sort");
	usort($downloaders, "leech_sort");

	print(dltable($lang_viewpeerlist['text_seeders'], $seeders, $row));
	print(dltable($lang_viewpeerlist['text_leechers'], $downloaders, $row));
}
?>
