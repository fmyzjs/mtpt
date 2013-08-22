<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
if (get_user_class() < UC_SYSOP)
stderr("Error", "Access denied.");
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
if ($_POST["username"] == "" || $_POST["donated"] == "")
stderr("Error", "Missing form data.");
$username = sqlesc($_POST["username"]);
$donated = sqlesc($_POST["donated"]);

sql_query("UPDATE users SET donated=$donated WHERE username=$username") or sqlerr(__FILE__, __LINE__);
$res = sql_query("SELECT id FROM users WHERE username=$username");
$arr = mysql_fetch_row($res);
if (!$arr)
stderr("Error", "Unable to update account.");
header("Location: " . get_protocol_prefix() . "$BASEURL/userdetails.php?id=$arr[0]");
die;
}
stdhead("Update Users Donated Amounts");
?>
<h1>Update Users Donated Amounts</h1>
<form method=post action=donated.php>
<div border=1 cellspacing=0 cellpadding=5>
<div><div class=rowhead>User name</div><div><input type=text name=username size=40></div></div>
<div><div class=rowhead>Donated</div><div><input type=uploaded name=donated size=5></div></div>
<div><div colspan=2 align=center><input type=submit value="Okay" class=btn></div></div>
</div>
</form>
<?php stdfoot();