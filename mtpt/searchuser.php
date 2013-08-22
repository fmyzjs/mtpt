<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
require_once(get_langfile_path());
if (get_user_class() < UC_SYSOP)
stderr("Sorry", "Access denied.");
stdhead("Add Upload", false);
?>
<div class=main width=737 border=0 cellspacing=0 cellpadding=0><div><div class=embedded>
<div align=center>
<h1><?php echo $lang_searchuser['head_search']?></a></h1>
<form method=post action=searchuser.php>
<?php

if ($_GET["returnto"] || $_SERVER["HTTP_REFERER"])
{
?>
<input type=hidden name=returnto value="<?php echo htmlspecialchars($_GET["returnto"]) ? htmlspecialchars($_GET["returnto"]) : htmlspecialchars($_SERVER["HTTP_REFERER"])?>">
<?php
}
?>
<div cellspacing=0 cellpadding=5>
<?php
if (isset($_POST["sear"]) && !empty($_POST["sear"])) {
$username = explode(";",$_POST["username"]);
$clases = $_POST["clases"];
for($i=0;$username[$i];$i++){
$res = sql_query("SELECT * FROM users WHERE username like '%".mysql_real_escape_string($username[$i])."%'");
while($a = mysql_fetch_assoc($res)){
$count++;
?>
<div><div colspan=2 class="text" width="300" align="left"><b><a href=userdetails.php?id=<?=$a["id"]?>><?=$a["username"]?></a></b></div></div>
<? }}
for($i=0;$clases[$i];$i++){
$res = sql_query("SELECT * FROM users WHERE class = '".mysql_real_escape_string($clases[$i])."'");
while($a = mysql_fetch_assoc($res)){
$count++;
?>
<div><div colspan=2 class="text" width="300" align="left"><b><a href=userdetails.php?id=<?=$a["id"]?>><?=$a["username"]?></a></b></div></div>
<? }}
if($count<=0){ ?>
<div><div colspan=2 class="text" width="300" align="left"><b><?php echo $lang_searchuser['text_noresult']?></b></div></div>
<? } ?>
<div><div><p align=center><a href=searchuser.php><?php echo $lang_searchuser['submit_return']?></a></p></div></div>
<div><div>
</div></div>
<? }else{ ?>
<div><div class="rowhead" valign="top"><?php echo $lang_searchuser['text_username']?></div><div class="rowfollow"><input type=text name=username size=20><?php echo $lang_searchuser['text_notice']?></div></div>
<div>
<div class="rowhead" valign="top"><?php echo $lang_searchuser['text_usergroup']?></div><div class="rowfollow">
  <div style="border: 0" width="100%" cellpadding="0" cellspacing="0">
    <div>
             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="1">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_peasant']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="2">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_user']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="3">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_power_user']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="4">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_elite_user']?></div>
      </div>
    <div>
             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="5">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_crazy_user']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="6">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_insane_user']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="7">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_veteran_user']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="8">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_extreme_user']?></div>
      </div>

    <div>
             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="9">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_ultimate_user']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="10">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_nexus_master']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="11">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_vip']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="12">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_uploader']?></div>
      </div>

    <div>
             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="13">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_moderators']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="14">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_administrators']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="15">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_sysops']?></div>

             <div style="border: 0" width="20"><input type="checkbox" name="clases[]" value="16">
             </div>
             <div style="border: 0"><?php echo $lang_functions['text_staff_leader']?></div>
	
       <div style="border: 0">&nbsp;</div>
       <div style="border: 0">&nbsp;</div>
      </div>
    </div>
  </div>
</div>
<div><div class="rowfollow" colspan=2 align=center>
<input name="sear" type="hidden" value="1" />
<input type=submit value="<?php echo $lang_searchuser['submit_search']?>" class=btn></div></div>
</div>
<? } ?>
</form>

 </div></div></div></div>
<br />
<?php
stdfoot();
