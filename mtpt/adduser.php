<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
require_once(get_langfile_path());
if (get_user_class() < UC_ADMINISTRATOR)
stderr("Error", "Access denied.");
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if ($_POST["username"] == "" || $_POST["password"] == "" || $_POST["email"] == "")
	stderr("Error", "Missing form data.");
	if ($_POST["password"] != $_POST["password2"])
	stderr("Error", "Passwords mismatch.");
	$email = htmlspecialchars(trim($_POST["email"]));
	$email = safe_email($email);
	if (!check_email($email))
	stderr("Error","Invalid email address!");
	
	$username = $_POST["username"];
	
	if (!validusername($username))
		stderr("Error","Invalid username.");
	$username = sqlesc($username);
	$res = sql_query("SELECT id FROM users WHERE username=$username");
	$arr = mysql_fetch_row($res);
	if ($arr)
		stderr("Error","Username already exists!");
	$password = $_POST["password"];
	$email = sqlesc($_POST["email"]);
	$res = sql_query("SELECT id FROM users WHERE email=$email");
	$arr = mysql_fetch_row($res);
	if ($arr)
		stderr("Error","The e-mail address is already in use.");
	$secret = mksecret();
	$passhash = sqlesc(md5($secret . $password . $secret));
	$secret = sqlesc($secret);

	sql_query("INSERT INTO users (added, last_access, secret, username, passhash, status, stylesheet, class,email) VALUES(NOW(), NOW(), $secret, $username, $passhash, 'confirmed', ".$defcss.",".$defaultclass_class.",$email)") or sqlerr(__FILE__, __LINE__);
	$res = sql_query("SELECT id FROM users WHERE username=$username");
	$arr = mysql_fetch_row($res);
	if (!$arr)
	stderr("Error", "Unable to create the account. The user name is possibly already taken.");
	header("Location: " . get_protocol_prefix() . "$BASEURL/userdetails.php?id=".htmlspecialchars($arr[0]));
	die;
}
stdhead("Add user");

?>
<h1><?php echo $lang_adduser['head_adduser']?></h1>
<form method=post action=adduser.php>
<div border=1 cellspacing=0 cellpadding=5>
<div><div class=rowhead><?php echo $lang_adduser['text_username']?></div><div><input type=text name=username size=40></div></div>
<div><div class=rowhead><?php echo $lang_adduser['text_passwd']?></div><div><input type=password name=password size=40></div></div>
<div><div class=rowhead><?php echo $lang_adduser['text_repasswd']?></div><div><input type=password name=password2 size=40></div></div>
<div><div class=rowhead><?php echo $lang_adduser['text_email']?></div><div><input type=text name=email size=40></div></div>
<div><div colspan=2 align=center><input type=submit value="<?php echo $lang_adduser['submit_add_user']?>" class=btn></div></div>
</div>
</form>
<?php stdfoot();
