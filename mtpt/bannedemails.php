<?php
define("VERSION", "BAN EMAIL");
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
require_once(get_langfile_path());
if (get_user_class() < UC_SYSOP)
	stderr("Error", "Access denied.");

$action = isset($_POST['action']) ? htmlspecialchars($_POST['action']) : (isset($_GET['action']) ?  htmlspecialchars($_GET['action']) : 'showlist');

if ($action == 'showlist') {
	stdhead (VERSION." - Show List");
	print("<div border=1 cellspacing=0 cellpadding=5 width=737>\n");
	$sql = sql_query("SELECT * FROM bannedemails") or sqlerr(__FILE__, __LINE__);
	$list = mysql_fetch_array($sql);
?>
<form method=post action=bannedemails.php>
<input type=hidden name=action value=savelist>
<div><div><?php echo $lang_bannedemails['text_help1']?><br /><?php echo $lang_bannedemails['text_help2']?><br /><?php echo $lang_bannedemails['text_help3']?></div>
<div><textarea name="value" rows="5" cols="40"><?php echo $list[value]?></textarea>
<input type=submit value="<?php echo $lang_bannedemails['submit_save']?>"></form></div>
</div></div>
<?php
stdfoot () ;
}elseif ($action == 'savelist') {
	stdhead (VERSION." - Save List");
	$value = trim ( htmlspecialchars ( $_POST[value] ) ) ;
	sql_query("UPDATE bannedemails SET value = ".sqlesc($value)) or sqlerr(__FILE__, __LINE__);
	Print ("Saved.");
	stdfoot () ;
}
