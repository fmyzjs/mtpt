<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
require_once(get_langfile_path());
if (get_user_class() < UC_ADMINISTRATOR)
stderr("Error", "Access denied.");
$done = false;
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
$cachename = $_POST["cachename"];
if ($cachename == "")
stderr("Error", "You must fill in cache name.");
if ($_POST['multilang'] == 'yes')
$Cache->delete_value($cachename, true);
else 
$Cache->delete_value($cachename);
$done = true;
}
stdhead("Clear cache");
?>
<h1><?php echo $lang_clearcache['head_clearcache']?></h1>
<?php
if ($done)
print ("<p align=center><font class=striking>Cache cleared</font></p>");
?>
<form method=post action=clearcache.php>
<div border=1 cellspacing=0 cellpadding=5>
<div><div class=rowhead><?php echo $lang_clearcache['text_cachename']?></div><div><input type=text name=cachename size=40></div></div>
<div><div class=rowhead><?php echo $lang_clearcache['text_multilang']?></div><div><input type=checkbox name=multilang><?php echo $lang_clearcache['text_yes']?></div></div>
<div><div colspan=2 align=center><input type=submit value="<?php echo $lang_clearcache['submit_ok']?>" class=btn></div></div>
</div>
</form>
<?php stdfoot();
