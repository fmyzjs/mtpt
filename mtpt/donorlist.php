<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();

if (get_user_class() > UC_MODERATOR) {
	$res = sql_query("SELECT COUNT(*) FROM users WHERE donor='yes'");
	$row = mysql_fetch_array($res);
	$count = $row[0];

	list($pagertop, $pagerbottom, $limit) = pager(50, $count, "donorlist.php?");
	stdhead("Donorlist");
	if (mysql_num_rows($res) == 0)
	begin_main_frame();
	// ===================================
	$users = number_format(get_row_count("users", "WHERE donor='yes'"));
	begin_frame("Donor List ($users)", true);
	begin_table();
	echo $pagerbottom;
?>
<form method="post">
<div><div class="colhead">ID</div><div class="colhead" align="left">Username</div><div class="colhead" align="left">e-mail</div><div class="colhead" align="left">Joined</div><div class="colhead" align="left">How much?</div></div>
<?php

$res=sql_query("SELECT id,username,email,added,donated FROM users WHERE donor='yes' ORDER BY id DESC $limit") or print(mysql_error());
// ------------------
while ($arr = @mysql_fetch_assoc($res)) {
	echo "<div><div>" . $arr[id] . "</div><div align=\"left\">" . get_username($arr[id]) . "</div><div align=\"left\"><a href=mailto:" . $arr[email] . ">" . $arr[email] . "</a></div><div align=\"left\">" . $arr[added] . "</a></div><div align=\"left\">$" . $arr[donated] . "</div></div>";
}
?>

</form>
<?php
// ------------------
end_table();
end_frame();
// ===================================
end_main_frame();
stdfoot();
}
else {
	stderr("Sorry", "Access denied!");
}
