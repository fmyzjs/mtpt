<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
stderr("Sorry", "Access denied.");
$status = $_GET['status'];
	if ($status)
		int_check($status,true);
		
$res = sql_query("SELECT * FROM users WHERE status='pending' ORDER BY username" ) or sqlerr();
if( mysql_num_rows($res) != 0 )
{
	stdhead("Unconfirmed Users");
	begin_main_frame();
	begin_frame("");
print'<br><div width=100% border=1 cellspacing=0 cellpadding=5>';
if ($status)
	print '<div><div class=rowhead colspan=5><font color=red size=1>The User account has been updated!</font></div></div>';
print'<div>';
print'<div class=rowhead><center>Name</center></div>';
print'<div class=rowhead><center>eMail</center></div>';
print'<div class=rowhead><center>Added</center></div>';
print'<div class=rowhead><center>Set Status</center></div>';
print'<div class=rowhead><center>Confirm</center></div>';
print'</div>';
while( $row = mysql_fetch_assoc($res) )
{
$id = $row['id'];
print'<div><form method=post action=modtask.php>';
print'<input type=hidden name=\'action\' value=\'confirmuser\'>';
print("<input type=hidden name='userid' value='$id'>");
print'<a href="userdetails.php?id=' . $row['id'] . '"><div><center>' . $row['username'] . '</center></div></a>';
print'<div align=center>&nbsp;&nbsp;&nbsp;&nbsp;' . $row['email'] . '</div>';
print'<div align=center>&nbsp;&nbsp;&nbsp;&nbsp;' . $row['added'] . '</div>';
print'<div align=center><select name=confirm><option value=pending>pending</option><option value=confirmed>confirmed</option></select></div>';
print'<div align=center><input type=submit value="-Go-" style=\'height: 20px; width: 40px\'>';
print'</form></div>';
}
print '</div>';
end_frame();
end_main_frame();
}else{
	if ($status) {
		stderr("Updated!","The user account has been updated.");
	}
	else {
		stderr("Ups!","Nothing Found...");
	}
}

stdfoot();
