<?php
require_once("include/bittorrent.php");
dbconn();

$langid = 0 + $_GET['sitelanguage'];
if ($langid)
{
	$lang_folder = validlang($langid);
	if(get_langfolder_cookie() != $lang_folder)
	{
		set_langfolder_cookie($lang_folder);
		header("Location: " . $_SERVER['PHP_SELF']);
	}
}
require_once(get_langfile_path("", false, $CURLANGDIR));

failedloginscheck ();
cur_user_check () ;
stdhead($lang_login['head_login']);

$s = "<select name=\"sitelanguage\" onchange='submit()'>\n";

$langs = langlist("site_lang");

foreach ($langs as $row)
{
	if ($row["site_lang_folder"] == get_langfolder_cookie()) $se = "selected=\"selected\""; else $se = "";
	$s .= "<option value=\"". $row["id"] ."\" ". $se. ">" . htmlspecialchars($row["lang_name"]) . "</option>\n";
}
$s .= "\n</select>";
?>

<p></p>

<?php

unset($returnto);
if (!empty($_GET["returnto"])) {
	$returnto = $_GET["returnto"];
	if (!$_GET["nowarn"]) {
		print("<h1>" . $lang_login['h1_not_logged_in']. "</h1>\n");
		print("<p><b>" . $lang_login['p_error']. "</b> " . $lang_login['p_after_logged_in']. "</p>\n");
	}
}
?>
<form method="post" action="takelogin.php">

<div border="0" cellpadding="5">
<div><div class="rowhead"><?php echo $lang_login['rowhead_username']?></div><div class="rowfollow" align="left"><input type="text" name="username" style="width: 180px; border: 1px solid gray" /></div></div>
<div><div class="rowhead"><?php echo $lang_login['rowhead_password']?></div><div class="rowfollow" align="left"><input type="password" name="password" style="width: 180px; border: 1px solid gray"/></div></div>
<?php
show_image_code ();
if ($securelogin == "yes") 
	$sec = "checked=\"checked\" disabled=\"disabled\"";
elseif ($securelogin == "no")
	$sec = "disabled=\"disabled\"";
elseif ($securelogin == "op")
	$sec = "";

if ($securetracker == "yes") 
	$sectra = "checked=\"checked\" disabled=\"disabled\"";
elseif ($securetracker == "no")
	$sectra = "disabled=\"disabled\"";
elseif ($securetracker == "op")
	$sectra = "";
?>

<div><div class="rowhead"><?php echo $lang_login['text_auto_logout']?></div><div class="rowfollow" align="left"><input class="checkbox" type="checkbox" name="logout" value="yes"/><?php echo $lang_login['checkbox_auto_logout']?></div></div>

<div><div class="toolbox" colspan="2" align="center">
<input type="submit" value="<?php echo $lang_login['button_login']?>" class="btn" /> <input type="reset" value="<?php echo $lang_login['button_reset']?>" class="btn" />
<?php
if ($smtptype != 'none'){
?>
</div></div>
<div><div class="toolbox" colspan="2" align="center">
<p><?php echo $lang_login['p_forget_pass_recover']?> &nbsp&nbsp&nbsp<?php echo $lang_login['p_forget_pass_cardrecover']?> &nbsp  &nbsp <?php echo $lang_login['p_user_log']?></p>
<p><?php echo $lang_login['text_QQ']?></p>
</div></div>
</div>
<p></p>
<?php

if (isset($returnto))
	print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($returnto) . "\" />\n");

?>
</form>




<?php
}
if ($showhelpbox_main != 'no'){?>
<div width="700" class="main" border="0" cellspacing="0" cellpadding="0"><div><div class="embedded">
<h2><?php echo $lang_login['text_helpbox'] ?><font class="small"> - <?php echo $lang_login['text_helpbox_note'] ?><font id= "waittime" color="red"></font></h2>
<?php
print("<div width='100%' border='1' cellspacing='0' cellpadding='1'><div><div class=\"text\">\n");
	if ($Advertisement->enable_ad()){
		$shout_ad = $Advertisement->get_ad('shoutlogin');
		print("<div id=\"ad_shoutindex\">".$shout_ad[0]."</div>");
	}
?>
<script type="text/javascript">
	var shoutbox_value = 0;
	setInterval(check_shoutbox_new,2000);
        function check_shoutbox_new()
        {
                $.get("shoutbox_new.html",function(result){
			var value = parseInt(result);
			if((shoutbox_value < value && shoutbox_value > 0) || value == 0){
				$("[name=sbox]").attr("src",$("[name=sbox]").attr("src"));
			}
			shoutbox_value = value;
		});
        }
</script>
<?
print("<iframe src='" . get_protocol_prefix() . $BASEURL . "/shoutbox.php?type=helpbox' width='650' height='180' frameborder='0' name='sbox' marginwidth='0' marginheight='0'></iframe><br /><br />\n");
print("<form action='" . get_protocol_prefix() . $BASEURL . "/shoutbox.php' id='helpbox' method='get' target='sbox' name='shbox'>\n");
print($lang_login['text_message']."<input type='text' id=\"hbtext\" name='shbox_text' autocomplete='off' style='width: 500px; border: 1px solid gray' ><input type='submit' id='hbsubmit' class='btn' name='shout' value=\"".$lang_login['sumbit_shout']."\" /><input type='reset' class='btn' value=".$lang_login['submit_clear']." /> <input type='hidden' name='sent' value='yes'><input type='hidden' name='type' value='helpbox' />\n");
print("<div id=sbword style=\"display: none\">".$lang_login['sumbit_shout']."</div>");
print(smile_row("shbox","shbox_text"));
print("</div></div></div></form></div></div></div>");
}
stdfoot();
