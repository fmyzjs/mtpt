<?php
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();
if (get_user_class() < UC_ADMINISTRATOR) {
	stderr("Error","Only Administrators and above can modify the Rules, sorry.");
}

if ($_GET["act"] == "newsect")
{
	stdhead("Add section");
	//print("<div valign=top style=\"padding: 10px;\" colspan=2 align=center>");
	//begin_main_frame();
	print("<h1 align=center>Add Rules</h1>");
	print("<form method=\"post\" action=\"modrules.php?act=addsect\">");
	print("<div border=\"1\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\">\n");
	print("<div><div>Title:</div><div align=left><input style=\"width: 400px;\" type=\"text\" name=\"title\"/></div></div>\n");
	print("<div><div style=\"vertical-align: top;\">Rules:</div><div><textarea cols=90 rows=20 name=\"text\"></textarea></div></div>\n");
	$s = "<select name=language>";
	$langs = langlist("rule_lang");
	foreach ($langs as $row)
	{
		if($row["site_lang_folder"] == $deflang) $se = " selected"; else $se = "";
		$s .= "<option value=". $row["id"] . $se. ">" . htmlspecialchars($row["lang_name"]) . "</option>\n";
	}
	$s .= "</select>";
	print("<div><div>Language:</div><div align=\"center\">".$s."</div></div>\n");
	print("<div><div colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Add\" style=\"width: 60px;\"></div></div>\n");
	print("</div></form>");
	print("</div></div></div>");
	stdfoot();
}
elseif ($_GET["act"]=="addsect"){
	$title = $_POST["title"];
	$text = $_POST["text"];
	$language = $_POST["language"];
	sql_query("insert into rules (title, text, lang_id) values(".sqlesc($title).", ".sqlesc($text).", ".sqlesc($language).")") or sqlerr(__FILE__,__LINE__);
	header("Refresh: 0; url=modrules.php");
}
elseif ($_GET["act"] == "edit"){
	$id = $_GET["id"];
	$res = @mysql_fetch_array(@sql_query("select * from rules where id='$id'"));
	stdhead("Edit rules");
	//print("<div valign=top style=\"padding: 10px;\" colspan=2 align=center>");
	//begin_main_frame();
	print("<h1 align=center>Edit Rules</h1>");
	print("<form method=\"post\" action=\"modrules.php?act=edited\">");
	print("<div border=\"1\" cellspacing=\"0\" cellpadding=\"10\" align=\"center\">\n");
	print("<div><div>Title:</div><div align=left><input style=\"width: 400px;\" type=\"text\" name=\"title\" value=\"".htmlspecialchars($res[title])."\" /></div></div>\n");
	print("<div><div style=\"vertical-align: top;\">Rules:</div><div><textarea cols=90 rows=20 name=\"text\">$res[text]</textarea></div></div>\n");
	$s = "<select name=language>";
	$langs = langlist("site_lang");
	foreach ($langs as $row)
	{
		if ($row['id'] == $res['lang_id']) $se = " selected"; else $se = "";
		$s .= "<option value=". $row["id"] . $se. ">" . htmlspecialchars($row["lang_name"]) . "</option>\n";
	}
	$s .= "</select>";
	print("<div><div>Language:</div><div align=\"center\">".$s."</div></div>\n");
	print("<div><div colspan=\"2\" align=\"center\"><input type=hidden value=$res[id] name=id><input type=\"submit\" value=\"Save\" style=\"width: 60px;\"></div></div>\n");
	print("</div>");
	print("</div></div></div>");
	stdfoot();
}
elseif ($_GET["act"]=="edited"){
	$id = 0+$_POST["id"];
	$title = $_POST["title"];
	$text = $_POST["text"];
	$language = $_POST["language"];
	sql_query("update rules set title=".sqlesc($title).", text=".sqlesc($text).", lang_id = ".sqlesc($language)." where id=".sqlesc($id)) or sqlerr(__FILE__,__LINE__);
	header("Refresh: 0; url=modrules.php");
}
elseif ($_GET["act"]=="del"){
	$id = 0+$_GET["id"];
	$sure = 0+$_GET["sure"];
	if (!$sure)
	{
		stderr("Delete Rule","You are about to delete a rule. Click <a class=altlink href=?act=del&id=$id&sure=1>here</a> if you are sure.",false);
	}
	sql_query("DELETE FROM rules WHERE id=".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
	header("Refresh: 0; url=modrules.php");
}
else{
	$res = sql_query("select rules.*, lang_name from rules left join language on rules.lang_id = language.id order by lang_name, id");
	stdhead("Rules Manangement");
	//print("<div valign=top style=\"padding: 10px;\" colspan=2 align=center>");
	print("<h1 align=center>Rules Manangement</h1>");
	print("<br /><div width=940 border=0 cellspacing=0 cellpadding=5>");
	print("<div><div align=center><a href=modrules.php?act=newsect>Add Section</a></div></div></div>\n");
	while ($arr=mysql_fetch_assoc($res)){
		print("<br /><div width=940 border=1 cellspacing=0 cellpadding=5>");
		print("<div><div class=colhead>$arr[title] - $arr[lang_name]</div></div>\n");
		print("<div><div align=left>" . format_comment($arr["text"])."</div></div>");
		print("<div><div align=left><a href=?act=edit&id=$arr[id]>Edit</a>&nbsp;&nbsp;<a href=?act=del&id=$arr[id]>Delete</a></div></div></div>");
		//end_main_frame();
	}
	//print("");
	print("</div></div></div>");
	stdfoot();
}
